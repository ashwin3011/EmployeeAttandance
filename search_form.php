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
// Handle employee search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['IsoTemplate']) && !isset($_POST['Name'])) {
    $isoTemplate = $_POST['IsoTemplate'];
 
    // Log the received IsoTemplate
    error_log("Received IsoTemplate: " . print_r($isoTemplate, true));
 
    $stmt = $conn->prepare("SELECT * FROM employees_biomatric WHERE IsoTemplate = ?");
    $stmt->bind_param("s", $isoTemplate);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
 
       // Return employee details as JSON
        echo json_encode([
            'status' => 'success',
            'id' => $employee['ebid'],
            'name' => $employee['Name'],
            'email' => $employee['email'],
            'password' => $employee['password'],
            'created_at' => $employee['created_at'],
            'location' => $employee['location'],  // Add location here
            'message' => 'Employee found: ' . $employee['Name'],
            //'check_in' => $checkInTime // Include check-in time here if needed
        ]);
    } else {
       error_log("No employee found for IsoTemplate: " . $isoTemplate); 
       echo json_encode(['status' => 'error', 'message' => 'No employee found!']);
    }
 
    $stmt->close();
    exit; // Important to stop further execution
 }
 
 // Handle Punch-in
 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['PunchIn'])) {
  
    $checkInId = $_POST['PunchIn'];
    $location = $_SESSION['location'];
    // Prepare the check-in statement
    $stmt = $conn->prepare("INSERT INTO employee_checkins (ebid, Punch_In,location) VALUES (?, NOW(),?)");
    $stmt->bind_param("is", $checkInId,$location); // Only bind the employee ID
 
    // Check if the statement executes successfully
    if ($stmt->execute()) {
        // Perform a join to get employee details
        $query = "SELECT e.name, e.email, d.Punch_In,d.location
                  FROM employees_biomatric AS e
                  JOIN employee_checkins AS d ON e.ebid = d.ebid
                  WHERE e.ebid = ?  AND DATE(d.Punch_In) = CURDATE() ";
        $joinStmt = $conn->prepare($query);
        $joinStmt->bind_param("i", $checkInId);
        $joinStmt->execute();
        $joinResult = $joinStmt->get_result();
 
        // Check if employee data exists
        if ($joinResult->num_rows > 0) {
            $employeeData = $joinResult->fetch_assoc();
            header('Content-Type: application/json'); // Set content type for JSON response
            echo json_encode([
                'status' => 'success',
                'message' => 'Punch-In time recorded.',
                'employee' => $employeeData // Include employee details in response
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'No employee data found.']);
        }
    } else {
        // Handle execution failure
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Failed to record check-in time.']);
    }
 
    $stmt->close();
    exit; // Stop further execution
 }
 
 
 
?>
<!-- Include the header -->
<?php include('dashboard.php'); ?>
<!-- --search form--- -->
<div class="container-fluid d-flex justify-content-center align-items-center">
    <div class="container mt-4">
<form class="form w-100" id="searchemp" autocomplete="off" method="post" onsubmit="return searchEmployee(event)">
    <div class="container-fluid d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center align-items-center"> <!-- Adjusted column width for medium size -->
                <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px; backdrop-filter: blur(15px); border: none;background: rgba(255, 255, 255, 0.7);">
                <center>    
                    <h6 id="checkoutTimer" style="font-weight: 700; color: #03001C;">
                        YOU CAN PUNCH IN <span id="timeRemaining">00:10</span>
                    </h6>
                </center>
                    <figure class="overlay-img text-center p-4">
                        <div class="col-md-12">     
                            <img src="assets/finger.png" id="srthumbprint" alt="Schedule-icon" class="img-fluid" style="max-width: 70%; margin-bottom: 10px;">
                            <label id="srcaptureproper" class="text-danger" style="display: none;">Capture finger properly.</label>
                        </div>
                        <div class="col-md-12 d-flex flex-column align-items-center">
                            <input type="text" id="isoTemplate" placeholder="Enter ISO Template Data" class="form-control mb-3 w-75" required />
                            <button type="submit" id="searchBtn" class="btn outline-light text-white form-submit-btn mb-3 p-2 w-75" style="border: 2px solid #424242; background-color: var(--secondary--color);">Punch In</button>
                            <div style="margin-bottom: 15px;"></div>
                            <label id="errorres" style="font-size: 20px; display: none; color: red; background-color: transparent;">No Employee Found</label>
                        </div>
                    </figure>
                    <!-- <hr style="border: 1px solid #E4E0E1; margin: 20px 0;">  -->
                    <div class="col-lg-12 d-flex justify-content-center align-items-center" id="printableArea" style="display: none;">
                        <div>
                            <h5 style="font-weight: 700; color: #03001C;" class="mt-3">EMPLOYEE INFORMATION</h5>
                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <span class="service-title"><strong>EID : &nbsp;&nbsp;</strong><span id="srEid" class="badge badge-success text-white"></span></span><br>
                                        <span class="service-title"><strong>Employee : &nbsp;&nbsp;</strong><span id="srName"></span></span><br>
                                        <span class="service-title"><strong>Contact : &nbsp;&nbsp;</strong><span id="srContact"></span></span><br>
                                        <span class="service-title"><strong>Password : &nbsp;&nbsp;</strong><span id="srPassword"></span></span><br>
                                        <span class="service-title"><strong>Registration Date : &nbsp;&nbsp;</strong><span id="srCreateDate"></span></span><br>
                                        <span class="service-title"><strong>Location : &nbsp;&nbsp;</strong><span id="srLocation"></span></span><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
    </div>






<script>
      let intervalId = null;
      let isTimerEnabled = true;
      let isCheckedIn = false;
      checkoutSuccessful = false;
      function showIsoTemplateInput() {
         // Show the ISO template input field
         document.getElementById("isoTemplate").style.display = "block";
         document.getElementById("searchBtn").style.display = "block"; // Show the search button
         document.getElementById("srcapturebtn").style.display = "none"; // Hide the Capture button
      }

       // Function to handle check-in (2 minutes after checkout)
       function PunchIn(employeeId) {
         console.log("checkIn() function called with employeeId:", employeeId); // Check if function is triggered
    
         const searchBtn = document.getElementById("searchBtn");
         
         if (!searchBtn) {
            console.error("searchBtn not found in the DOM");
            return; // Exit if searchBtn is not found
         }
         console.log("Check-In button element:", searchBtn);
         fetch('search_form.php', {
            method: 'POST',
            headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'PunchIn=' + encodeURIComponent(employeeId) // Send employee ID
         })
         .then(response => response.json())
         .then(data => {
            if (data.status === 'success') {
                  Swal.fire('Punch In Successfully', data.message, 'success');
                   
                          // Disable the button immediately after check-in
                      // Disable the button immediately after check-in
                      searchBtn.disabled = true; // Disable the button after check-in

                      console.log("Button disabled after check-in");
               // Clear the previous timer if it exists
               // if (intervalId) {
               //    clearInterval(intervalId);
               // }

                  
                  console.log("Punch-In successful, setting timer for Check OUT button.");
                   // Reload the page after the SweetAlert is confirmed
                  
                  // Call the function to allow Check-Out button action after 2 minutes
                  //showCheckOutButtonAfterDelay();
            } else {
                  Swal.fire('Check In Failed', data.message, 'error');
            }
         })
         .catch(error => {
            console.error('Error during check-in:', error);
            Swal.fire('Error', 'An error occurred during check-in.', 'error');
         });
      }


      
      // Function to display Check-Out button after a delay (e.g., 2 minutes) with a countdown
      function startTime() {
         const timerDisplay = document.getElementById("checkoutTimer");
         const timeRemainingDisplay = document.getElementById("timeRemaining");
         const searchBtn = document.getElementById("searchBtn");
         let totalTime = 10; // Total time in seconds (30 seconds)

         timerDisplay.style.display = "block"; // Show the timer display
         searchBtn.disabled = true; // Disable the button initially

         // Clear any existing interval to avoid multiple timers running
         if (intervalId) {
            clearInterval(intervalId);
         }

         // Start the countdown
               intervalId = setInterval(() => {
                  const minutes = Math.floor(totalTime / 60);
                  const seconds = totalTime % 60;

                  timeRemainingDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`; // Update timer display

                  totalTime--; // Decrease total time by 1 second

                  if (totalTime < 0) {
                        clearInterval(intervalId); // Stop the timer
                        searchBtn.disabled= false; // Enable the button after the countdown finishes
                        location.reload();
                        //searchBtn.textContent = 'checkOut'; // Change the button text to allow checkout
                        //console.log("Button enabled for check-out after 2 minutes");
               
                  }
               }, 1000); // Update every second (1000 milliseconds)
         }
            
     
      // Function to handle search, check-in, and check-out
      function searchEmployee(event) {
         event.preventDefault(); // Prevent the default form 
         console.log('Search employee function called.'); 
         const searchBtn = document.getElementById("searchBtn");

         
            //document.getElementById("searchBtn").text = 'checkIn';
            startTime();
            const isoTemplate = document.getElementById("isoTemplate").value;

         if (!isoTemplate) {
            alert('Please enter the ISO Template Data.');
            return;
         }
         // Fetch employee data based on the ISO Template
         fetch('search_form.php', {
            method: 'POST',
            headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'IsoTemplate=' + encodeURIComponent(isoTemplate) // Send ISO template data
         })
         .then(response => response.json())
         .then(data => {
            if (data.status === 'success') {
                  // Populate employee details dynamically
                  document.getElementById("srEid").textContent = data.id;
                  document.getElementById("srName").textContent = data.name;
                  document.getElementById("srContact").textContent = data.email;
                  document.getElementById("srPassword").textContent = data.password;
                  document.getElementById("srCreateDate").textContent = data.created_at;
                   // Define and populate the location
                    document.getElementById("srLocation").textContent = data.location;
                  document.getElementById("printableArea").style.display = "block"; // Show employee details
                  document.getElementById("errorres").style.display = "none"; // Hide error message

                  //searchBtn.text = 'checkOut';
                  PunchIn(data.id);
                  // setTimeout(() => {
                  //    alert('Session expired. Please check out.');
                  //    searchBtn.text = 'checkOut'; 
                  //    checkOut(data.id);
                  
                  // }, 3000); 
                 // searchBtn.textContent  = 'checkOut'; // Change the button text to allow checkout
                console.log("Button set to Check-Out after successful check-in");
                  // Update button text based on check-in status
                  
                  // if (isCheckedIn) {
                  //    button.textContent = "Check OUT";
                  //    button.onclick = () => {
                  //       checkOut(data.id); // Call check-out function
                  //    };
                  // } else {
                  //    button.textContent = "Check IN";
                  //    button.onclick = () => {
                       
                  //       checkIn(data.id); // Call check-in function
                  //    };
                  // }

            } else {
                  // If employee details not found
                  Swal.fire('Employee Details Not Found', data.message, 'error');
                  document.getElementById("errorres").textContent = data.message;
                  document.getElementById("errorres").style.display = "block"; // Show error message
            }
                 
         })
         .catch(error => {
            console.error('Error fetching employee details:', error);
            document.getElementById("errorres").textContent = 'Error fetching employee details.';
            document.getElementById("errorres").style.display = "block"; // Show error message
         });
        }
      
</script>