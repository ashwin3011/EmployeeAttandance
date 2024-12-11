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
// Handle employee registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Name'], $_POST['email'], $_POST['password'], $_POST['IsoTemplate'], $_POST['AnsiTemplate'])) {
    // Retrieve form data
    $name = $_POST['Name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isoTemplate = $_POST['IsoTemplate'];
    $ansiTemplate = $_POST['AnsiTemplate'];
 
    // Get the location flag from the session
    $flag = $_SESSION['location'] ?? 'default'; // Use 'default' if not set
    $reg_id = $_SESSION['user_id'];
  
    // Prepare an SQL statement to insert into employees_biomatric
    $stmt = $conn->prepare("
        INSERT INTO employees_biomatric (Name, email, password, IsoTemplate, AnsiTemplate, location,register_id) 
        VALUES (?, ?, ?, ?, ?, ?,?)
    ");
 
    // Bind parameters
    $stmt->bind_param("sssssss", $name, $email, $password, $isoTemplate, $ansiTemplate, $flag, $reg_id);
 
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error); // Log the error
        exit("An error occurred while processing your request.");
    }
 
     // Assuming $stmt is your prepared statement for inserting the user
     if ($stmt->execute()) {
             $alertMessage = "<script>
             Swal.fire({
                title: 'Success!',
                text: 'The employee has been registered.',
                icon: 'success',
                confirmButtonText: 'Okay',
                customClass: {
                    confirmButton: 'swal2-confirm' // Add custom class for button
                }
             });
          </script>";
 
   } else {
    
       $alertMessage = "<script>
                     Swal.fire({
                         title: 'Error',
                         text: 'An error occurred during registration!',
                         icon: 'error'
                     });
                  </script>";
   }
   // Elsewhere in your HTML, echo the $alertMessage
 
    // Close the statement
    $stmt->close();
 }
 // Output the alert message here, ensure it is after the SweetAlert script is included

 $conn->close();
?>
<!-- Include the header -->
<?php include('dashboard.php'); ?>

        <!-- -----register employee--- -->
<!-- ----- Register Employee --- -->
<?php if (isset($alertMessage)) {
    echo $alertMessage;
 }?>
<div class="container-fluid d-flex justify-content-center align-items-center">
<div class="container mt-4">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <form class="form w-100" id="regemp" autocomplete="off" method="post">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-12 col-md-12 col-sm-12"> <!-- Adjusted column width for a medium size -->
                    <div class="card shadow-sm p-4 mb-4 rounded" style="width: 100%; backdrop-filter: blur(15px); border: none; background: rgba(255, 255, 255, 0.7);">

                        <div class="row">
                            <div class="col-md-5 text-center"> <!-- Left side for biometric image and button -->
                                <figure class="mb-4">
                                    <img src="assets/finger.png" id="thumbprint" alt="Schedule-icon" class="img-fluid" style="max-width: 80%; margin-bottom: 10px;">
                                    <label id="captureproper" class="text-danger" style="display: none;">Capture fingerprint properly.</label>
                                    <button type="button" id="capturebtn" class="btn outline-light text-white form-submit-btn mb-3 p-2 w-75" style="border: 2px solid #424242; background-color: var(--secondary--color);" onclick="return Capture()">Capture</button>
                                </figure>
                            </div>
                            <div class="col-md-7"> <!-- Right side for the form -->
                                <h5 style="font-weight: 700; color: #03001C;" class="mt-3">EMPLOYEE REGISTRATION FORM</h5>
                                <div class="form-row w-100">
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="d-block">Full Name:</label>
                                            <input type="text" name="Name" id="Name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="d-block">Email:</label>
                                            <input type="email" name="email" id="email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="d-block">Password:</label>
                                            <input type="password" name="password" id="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="d-block">ISO Template:</label>
                                            <input type="text" name="IsoTemplate" id="IsoTemplate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="d-block">ANSI Template:</label>
                                            <input type="text" name="AnsiTemplate" id="AnsiTemplate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <button type="submit" id="rgstrsbmt" class="btn outline-light d-flex align-items-center px-3 py-2" 
                                            style="border: 2px solid #424242; background-color: var(--secondary--color); color: white;">
                                                Register Employee
                                            </button>
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

</div>



    







