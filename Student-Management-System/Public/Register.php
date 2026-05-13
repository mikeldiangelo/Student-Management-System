<?php
// IF USER IS NOT LOGGED IN SEDN THEM BACK TO LOGIN PAGE
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// VARIABLES TO KEEP FORM INTACT
$name = $_GET['name'] ?? "";
$email = $_GET['email'] ?? "";
$course = $_GET['course'] ?? "";

//DISPLAY ERROR MESSAGES USING THIS VARIABLES
$nameError = $_GET['nameError'] ?? "";
$emailError = $_GET['emailError'] ?? "";
$courseError = $_GET['courseError'] ?? "";
$file_error = $_GET['file_error'] ?? "";

//DISPLAY A SUCCESS OR ERROR MESSAGE AFTER FORM SUBMISSION
$successMessage = "";
$errorMessage = "";

if (isset($_GET['success'])){
    $successMessage = "Student registered successfully";
}

// if (isset($_GET['error'])){
//     $errorMessage = "Kindly review your information for errors";
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Registration Page</title>
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e4e9f2 100%);
            min-height: 100vh;
        }
        .edit-card {
            max-width: 520px;
            margin: 60px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background: purple;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .card-body {
            padding: 40px 35px;
        }
        .form-control {
            border-radius: 10px;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: purple;
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 128, 0.25);
            outline: none;
        }
        .btn-submit {
            background: purple;
            color: white;
            border: none;
            padding: 14px 30px;
            font-weight: bold;
            border-radius: 10px;
            width: 100%;
        }
        .btn-submit:hover {
            background: #9c27b0;
            transform: translateY(-2px);
        }
    
        .error{
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="edit-card">
    <div class="card-header">
        <h3 class="mb-0 text-center">Student Registration</h3>
    </div>

    <div class="card-body">
        <?php if (!empty($successMessage)):?>
            <div class="container my-4" style="max-width: 500px; font-size: 13px;">
                <div class="alert alert-success alert-dismissible fade show text-center mx-3" role= "alert">
                    <?php echo $successMessage; ?>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="close"></button>
                </div>
            </div>
        <?php endif; ?>
    
        <?php if (!empty($errorMessage)):?>
            <div class="container my-4" style="max-width: 500px; font-size: 13px;">
                <div class="alert alert-danger alert-dismissible fade show text-center mx-3" role="alert">
                    <?php echo $errorMessage; ?>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="close"></button>
                </div>
            </div>
        <?php endif; ?>

        <form action="process_register.php" method="post" enctype="multipart/form-data">
            <div class="mb-1">
                <label for="name" class="form-label fw-bold">Full Name:</label> 
                <input type="text" id= "name" name="name" class="form-control" placeholder="John Doe" value="<?php echo $name; ?>"> <span class="error"><?php echo $nameError;?></span><br><br>
            </div>

            <div class="mb-1">
                <label for="email" class="form-label fw-bold">Email:</label>
                <input type="email" id= "email"  name="email" class="form-control" placeholder="john@example.com" value ="<?php echo $email;?>"> <span class="error"><?php echo $emailError;?></span><br><br>
            </div>
            <div class="mb-1">
                <label for="course" class="form-label fw-bold"> Course:</label>
                <input type="text" id= "course"  name="course" class="form-control" placeholder="cybersecurity" value="<?php echo $course;?>"> <span class="error"><?php echo $courseError;?></span><br><br>
            </div>

            <div class="mb-1">
                <label for="picture" class="form-label fw-bold d-flex">Upload an image</label>
                <input type="file" name="Uploadfile" id="picture" accept= "image/jpeg,image/png">
                <span class = "error"><?php echo $file_error; ?></span><br><br>
            </div>

            <button type="submit" name="submit" class="btn btn-submit">
                Submit Registration
            </button>
        </form>
    </div>
</div>
<!-- SETTING A BOOTSTRAP ALERT TO CANCEL AFTER A FEW SECONDS OF DISPLAY -->
    <script>
   setTimeout(function(){
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert){
        alert.style.transition = "opacity 0.5s ease";
        alert.style.opacity = "0";
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
   }, 3000); //3000ms = 3sec
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>