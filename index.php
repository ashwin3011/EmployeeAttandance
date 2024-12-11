<?php
session_start(); // Start the session
include 'connection.php';

$alertMessage = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // Registration logic
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $location = $_POST['location'];
        $password = $_POST['password']; // No password hashing

        // Check if the email already exists
        $checkEmailSql = "SELECT * FROM register WHERE email = '$email'";
        $checkEmailResult = $conn->query($checkEmailSql);

        if ($checkEmailResult->num_rows > 0) {
            $alertMessage = "<script>Swal.fire('Error', 'Email already exists!', 'error');</script>";
            
        } else {
            $sql = "INSERT INTO register (name, email, location, password) VALUES ('$fullname', '$email', '$location', '$password')";

            if ($conn->query($sql) === TRUE) {
                // Get the user ID of the newly created account
                $userId = $conn->insert_id;

                // Set session variables
                $_SESSION['user_id'] = $userId; // Assuming 'id' is the primary key in your table
                $_SESSION['username'] = $fullname; // Store the user's name
                $_SESSION['location'] = $location;  // Assuming 'location' is a column in your database
                $_SESSION['user_email'] = $email;
              

                // Redirect to dashboard after successful registration using PHP
                header('Location: search_form.php');
                exit; // Important to prevent further execution
            } else {
                $alertMessage = "<script>Swal.fire('Error', 'Error: " . $conn->error . "', 'error');</script>";
            }
        }
    }

    if (isset($_POST['login'])) {
        // Login logic
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM register WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Direct password comparison
            if ($password === $user['password']) {
                // Set session variables
                $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the primary key in your table
                $_SESSION['username'] = $user['name']; // Store the user's name
                $_SESSION['location'] = $user['location']; // Store location in session // Assuming 'location' is a column in your database
                $_SESSION['user_email'] = $user['email'];

                $alertMessage = "<script>
                    Swal.fire({
                        title: 'Success',
                        text: 'Login successful!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'swal2-confirm' // Use your custom class here
                        }
                    }).then(() => {
                        // Redirect to dashboard
                        window.location = 'search_form.php';
                    });
                </script>";
            } else {
                $alertMessage = "<script>Swal.fire('Error', 'Incorrect password!', 'error');</script>";
            }
        } else {
            $alertMessage = "<script>Swal.fire('Error', 'No user found with that email!', 'error');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to My Website</title>
    <link href="assets/bootstarp/bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <style>
        .swal2-confirm {
        background-color:   #074560;/* Custom button color */
        color: white; /* Button text color */
	
    }
    </style>
    <script>
        // Function to toggle between login and registration forms
        function toggleForm(formType) {
            if (formType === 'register') {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('registerForm').style.display = 'block';
            } else {
                document.getElementById('loginForm').style.display = 'block';
                document.getElementById('registerForm').style.display = 'none';
            }
        }
    </script>
</head>
<body style="background-color: hsl(0, 0%, 96%); height: 100vh;">
    <!-- Section: Design Block -->
    <section class="d-flex align-items-center justify-content-center h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4"> <!-- Adjust width here -->
                    <!-- Login Form (Initially Visible) -->
                    <div class="card rounded-lg" style="background-color: rgba(255, 255, 255, 0.5); border: none;" id="loginForm">
                        <div class="card-body py-5 px-md-5">
                            <form method="post">
                                <div class="row">
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0"><img src="assets/image/biometric-security.png" alt="" style="height: 100px;"></span>
                                    </div>
                                    <h5 class="fw-700 mb-3 pb-3" style="letter-spacing: 1px;">SIGN INTO YOUR ACCOUNT</h5>
                                    <div class="col-md-12 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <label class="form-label" for="form3Example3">Email address</label>
                                            <input type="email" name="email" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <label class="form-label" for="form3Example4">Password</label>
                                            <input type="password" name="password" class="form-control" required/>
                                        </div>
                                    </div>
                                    <!-- Submit button -->
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" name="login" style="background-color: #074560; border: none;">Sign in</button>
                                    <div class="col-md-12 mb-4">
                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#" onclick="toggleForm('register')" style="color: #393f81;">Register here</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Registration Form (Initially Hidden) -->
                    <div class="card rounded-lg" style="background-color: rgba(255, 255, 255, 0.5); border: none; display: none;" id="registerForm">
                        <div class="card-body py-5 px-md-5">
                            <form method="post">
                                <div class="row">
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0"><img src="assets/image/biometric-security.png" alt="" style="height: 85px;"></span>
                                    </div>
                                    <h5 class="fw-700 mb-3 pb-3" style="letter-spacing: 1px;">SIGN UP YOUR ACCOUNT</h5>
                                    <div class="col-md-12 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <label class="form-label" for="form3Example3">Full name</label>
                                            <input type="text" name="fullname" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <label class="form-label" for="form3Example3">Email address</label>
                                            <input type="email" name="email" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <label class="form-label" for="form3Example4">Password</label>
                                            <input type="password" name="password" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <label class="form-label" for="form3Example5">Location</label>
                                            <select id="form3Example5" class="form-control" name="location">
                                                <option value="" disabled selected>Select your location</option>
                                                <option value="company">Company</option>
                                                <option value="client">Client1</option>
                                                <option value="client2">Client2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Submit button -->
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" name="register" style="background-color: #074560; border: 2px solid #424242;">Sign Up</button>
                                    <div class="col-md-12 mb-4">
                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Already registered? <a href="#" onclick="toggleForm('login')" style="color: #393f81;">Login here</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- SweetAlert Trigger -->
    <?php echo $alertMessage; ?>
    <script src="assets/js/bootstrap5.min.js"></script>
</body>

</html>
