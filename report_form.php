<?php
 session_start();
 require_once('connection.php');
 $alertMessage = '';
 if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: index.php');
    exit;
 }
 if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("location:index.php");
 }



 function fetchEmployeeCheckinCheckoutData($conn, $isCompany,$registerId) {
    // Prepare the base query to fetch employee check-in and check-out data
    $query = "
    SELECT SQL_NO_CACHE
        eb.ebid,
        eb.Name AS full_name,
        DATE_FORMAT(ec.Punch_In, '%d-%m-%Y') AS Punch_In_date,  -- Group by date
        GROUP_CONCAT(DISTINCT DATE_FORMAT(ec.Punch_In, '%h:%i:%s %p')) AS Punch_In_times, 
        eb.location  -- Directly fetch the location without GROUP_CONCAT
    FROM 
        employees_biomatric eb
    INNER JOIN  
        employee_checkins ec ON eb.ebid = ec.ebid
        
    ";

    // Group by employee ID and check-in date, along with location
    if ($isCompany) {
        $query .= " GROUP BY eb.ebid, DATE(ec.Punch_In), eb.location "; 
        $query .= " ORDER BY DATE(ec.Punch_In) ASC;";  // Ensure punch-in dates are ordered ascending
    } else {
        // Optional filtering could be added here if needed for another case
    }

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Database prepare error: " . $conn->error);
        return []; // Return an empty array if prepare fails
    }

    // No binding needed since we're fetching all records
    $stmt->execute();
    $result = $stmt->get_result();

    $employees = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $employees[] = [
                'ebid' => htmlspecialchars($row['ebid']),
                'full_name' => htmlspecialchars($row['full_name']),
                'Punch_In_date' => htmlspecialchars($row['Punch_In_date']),
                'Punch_In_times' => htmlspecialchars($row['Punch_In_times']), // Collect all check-in times
                'location' => htmlspecialchars($row['location']), // Directly fetch location
            ];
        }
    } else {
        error_log("Database query error: " . $conn->error);
    }

    return $employees;
}



// Ensure the register_id is available from the session
if (isset($_SESSION['user_id'])) { // Assuming user_id corresponds to register_id
    $registerId = $_SESSION['user_id'];
    $isCompany =true;
    $employeesData = fetchEmployeeCheckinCheckoutData($conn, $isCompany,$registerId);
} else {
    // Handle case where register_id is not set in session
    $employeesData = [];
}

 if (isset($_GET['employee_id'])) {
    $employeeId = $_GET['employee_id'];
    $selectedDate = isset($_GET['date']) ? $_GET['date'] : date('d-m-Y');

     // Ensure the date is converted back to YYYY-MM-DD for SQL query
     $formattedDate = date('Y-m-d', strtotime($selectedDate));

    $query = "
    SELECT 
        DATE_FORMAT(ec.Punch_In, '%h:%i:%s %p') AS Punch_In_times,
        ec.location 
    FROM employee_checkins ec
    JOIN employees_biomatric eb ON ec.ebid = eb.ebid
    WHERE ec.ebid = ? AND DATE(ec.Punch_In) = ?
    ORDER BY ec.Punch_In ASC"; // Order by check-in for sequential processing
    

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        // Log the prepare error
        // error_log("Prepare error: " . $conn->error);
        echo json_encode(['error' => $conn->error]);
        exit;
    }
    
    $stmt->bind_param("is", $employeeId, $formattedDate);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result) {
       $data = [];
       while ($row = $result->fetch_assoc()) {
           $data[] = $row;
       }
 
       echo json_encode($data); // Return JSON response
    } else {
       // Log the execution error
       echo json_encode(['error' => $stmt->error]);
    }
 
    exit;
 }
 $conn->close(); 
?>
<!-- Include the header -->
<?php include('dashboard.php'); ?>
<!-- ---report form---- -->
<div class="container-fluid d-flex justify-content-center align-items-center">
    <div class="container mt-4">
        <div class="card shadow-sm" style="width: 100%; backdrop-filter: blur(15px); border: none;border: 1px solid rgba(0, 0, 0, 0.1); background: rgba(255, 255, 255, 0.7);">
            <div class="card-body">
                <h5 style="font-weight: 900; color: #03001C;" class="text-center mb-4">EMPLOYEE ATTENDANCE REPORT</h5>
                <table class="table table-striped table-hover" id="employeeTable">
                    <thead style="background-color: var(--secondary--color); color: white;">
                        <tr>
                            <th>Date</th>
                            <th>Full Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            // Check if data is available
                            if (isset($employeesData) && !empty($employeesData)) {
                                foreach ($employeesData as $employee) {
                                    echo '
                                        <tr>
                                            <td>' . htmlspecialchars($employee['Punch_In_date']) . '</td>
                                            <td>' . htmlspecialchars($employee['full_name']) . '</td>
                                            <td>
                                                <a href="#" 
                                                data-toggle="modal" 
                                                data-target="#exampleModal"
                                                data-employee-id="' . htmlspecialchars($employee['ebid']) . '"
                                                data-employee-name="' . htmlspecialchars($employee['full_name']) . '" 
                                                 data-check-in-date="' . htmlspecialchars($employee['Punch_In_date']) . '">
                                                Logs
                                                </a>
                                            </td>
                                        </tr>
                                    ';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center">No employee data found.</td></tr>';
                            }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- ---modal form---- -->
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #074560;">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white; font-weight: bold;"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <h6>Attendance Records:</h6> -->
                <ul id="attendance-list"></ul> 
                <!-- Use this list to display multiple records -->
            </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn" 
                        style="background-color: #074560; color: white; border: none;" 
                        data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>


<script>
     let intervalId = null;
      let isTimerEnabled = true;
      let isCheckedIn = false;
      checkoutSuccessful = false;
  
      $(document).ready(function() {
    console.log("All scripts loaded and DOM is ready.");

    $('#employeeTable').on('click', 'a[data-toggle="modal"]', function(e) {
        e.preventDefault(); // Prevent default link behavior
        
        // Get the employee ID and employee Name from the clicked link
        var employeeId = $(this).data('employee-id'); // Get the employee ID
        var employeeName = $(this).data('employee-name'); // Get the employee Name from the clicked link
        var selectedDate = $(this).data('check-in-date');

        console.log("Employee ID:", employeeId);
    console.log("Employee Name:", employeeName);
    console.log("Selected Date:", selectedDate);
        console.log("Making AJAX call to fetch employee details for ID:", employeeId); // Log the employee ID

        // Set the modal header with the employee name and ID only
        $('#exampleModalLabel').text(`${employeeName} (ID: ${employeeId})`);
      
        // AJAX call to fetch employee details
        console.log("AJAX parameters:", { employee_id: employeeId, date: selectedDate });
        $.ajax({
            url: 'report_form.php', 
            method: 'GET',
            data: { employee_id: employeeId,date: selectedDate },
            dataType: 'json',
            success: function(data) {
                console.log("Data received from server:", data);
                
                // Call function to display attendance records
                displayAttendanceRecords(data);

                $('#exampleModal').modal('show'); // Show the modal
            },
            error: function(xhr, status, error) {
                console.error("Error fetching employee details:", error); // Log any errors
            }
        });
    });
});


// Function to display attendance records
function displayAttendanceRecords(data) {
    // Clear previous data
    $('#attendance-list').empty(); // Clear any existing attendance records

    // Check if there are any records
    if (data.length > 0) {
        // Display the records in a proper format
        data.forEach(record => {
            // Display check-in time with the location
            if (record.Punch_In_times) {
                const outputString = createOutputString(record.location, record.Punch_In_times);
                $('#attendance-list').append(createTableRow(outputString));
            }

            // Display check-out time with the location
            // if (record.check_out_time) {
            //     const outputString = createOutputString(record.location, record.check_out_time);
            //     $('#attendance-list').append(createTableRow(outputString));
            // }
        });
    } else {
        $('#attendance-list').append('<li>No data available</li>');
    }
}
// Helper function to create output strings
function createOutputString(location, displayTime, status) {
    // Ensure displayTime is valid
    if (!displayTime) {
        console.warn(`Invalid display time:`, displayTime);
        return '';
    }

    // Use the raw display time directly
    // Assuming displayTime is already in the desired format (e.g., "06:19:54 PM")
    return `Location = ${location} - ${displayTime}`;
}
// Helper function to create table rows
function createTableRow(content) {
    return `
        <tr style="border-bottom: 1px solid #ccc;">
            <td style="padding: 10px;">
                ${content}
            </td>
        </tr>
    `;
}

</script>