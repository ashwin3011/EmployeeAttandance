<?php
require_once('connection.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("location:index.php");
}
// Example user role; you should set this based on your authentication logic

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>PDHAMECHA - Biometric Dashboard</title>

    <!-- Bootstrap and FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <link rel="icon" type="image/x-icon" href="assets/logo.png">
    <style>
   
        .background-image {
         background-image: url('assets/image/biomatric.png');
         background-size: cover; /* Adjusts the image to cover the entire area */
         background-position: center; /* Centers the image */
         background-repeat: no-repeat; /* Prevents the image from repeating */
         min-height: 100vh; /* Ensures the body takes up at least the full height of the viewport */
         overflow-x: hidden;
      }
      .swal2-confirm {
        background-color: #074560; /* Custom button color */
        color: white; /* Button text color */
        }
    </style>

</head>

<body class="background-image">
   <!-- top-bar-section-->
  <!-- Sticky Header Wrapper -->
<div class="sticky-top-bar" style="position: sticky; top: 0; z-index: 1000;">

<!-- Navbar Section -->
<div class="w-100 float-left" style="background-color:#ebeaec; border:solid 1px gray">
   <div class="overlay-img">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg text-white">
         <div class="pt-1 pb-1 d-flex justify-content-between align-items-center">
            <a class="navbar-brand ml-3" href="http://pdhamecha.com/" target="_blank">
               <h4 style="font-weight: 900; color: var(--secondary--color);">PDHAMECHA-BIOMATRIC</h4>
               <h5 class="font-weight-bold text-center" style="font-weight: 700;color:#03001C">
                  Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                  <?php if (isset($_SESSION['location'])): ?>
                     , Location: <?php echo htmlspecialchars($_SESSION['location']); ?>
                  <?php endif; ?>
               </h5>
            </a>
         </div>

         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>

         <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                  <a class="btn outline-light d-flex align-items-center px-3 py-2" href="search_form.php"
                     style="border: 2px solid #424242; background: transparent;">
                     <i class="fas fa-search mr-2"></i> Search
                  </a>
               </li>

               <li class="nav-item" style="display: <?php echo ($_SESSION['location'] === 'company') ? 'block' : 'none'; ?>;">
                  <a class="btn outline-light d-flex align-items-center px-3 py-2" href="report_form.php"
                     style="border: 2px solid #424242; background: transparent;">
                     <i class="fas fa-calendar-check mr-2"></i> Report
                  </a>
               </li>

               <li class="nav-item">
                  <a class="btn outline-light d-flex align-items-center px-3 py-2" href="register_employee.php"
                     style="border: 2px solid #424242; background: transparent;">
                     <i class="fas fa-user-plus mr-2"></i> Add Employee
                  </a>
               </li>

               <li class="nav-item">
                  <a href="?logout" class="btn outline-light d-flex align-items-center px-3 py-2"
                     style="border: 2px solid #424242; background: transparent;">
                     <i class="fas fa-sign-out mr-2"></i> Logout
                  </a>
               </li>
            </ul>
         </div>
      </nav>
   </div>
</div>

</div>
        <?php echo $alertMessage; ?>
    </div>


</body>
</html>
