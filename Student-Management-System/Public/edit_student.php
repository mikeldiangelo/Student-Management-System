<?php
require_once "../src/config/config.php";
$name = $email = $course = "";
$errorMessage = "";
$successMessage = "";

//SAFETY PROCEDURE WHILE PICKING FROM DATABASE
// FETCHING ID FROM DATABASE USING THE URL
$id = $_GET['id'] ?? null;
//CHECK USING ARGUMENTS
if (!$id || !is_numeric($id)){
    die("Invalid student");
}

//FETCHING STUDENT RECORDS FROM DATABASE
$collect = $connect->prepare("SELECT * FROM students WHERE id = ?");
$collect->bind_param("i", $id);
$collect->execute();
$result = $collect->get_result();

//CHECKING IF ID RESULTS ZERO STUDENT IF NOT IT RUNS THROUGH
if ($result->num_rows == 0){
    die("Student not found");
}

$Student = $result->fetch_assoc();

//HANDLE FORM SUBMISSION TO UPDATE DATABASE
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $course = trim($_POST["course"]);

    //CHECKING FOR EMPTY INPUTS AND EMAIL VALIDATION
    if(empty($name) || empty($email) || empty($course)){
        $errorMessage = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMessage = "Input a valid email";
    } else {
        $updateInfo = $connect->prepare("UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?");
        //EXECUTE THE PREPARED STATEMENT AND CHECK IF SUCCESSFUL
        $updateInfo->bind_param("sssi", $name, $email, $course, $id);

        if($updateInfo->execute()){
            $successMessage = "Student Updated Successfully";

            //CHECK IF UPDATED INFORMATION IS VALID
            $collect->execute();
            $result = $collect->get_result();
            $Student = $result->fetch_assoc();
        } else {
            $errorMessage ="Update failed: " . $connect->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>UPDATING STUDENT INFORMATION</title>
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
        .btn-update {
            background: purple;
            color: white;
            border: none;
            padding: 14px 30px;
            font-weight: bold;
            border-radius: 10px;
            width: 48%;
        }
        .btn-update:hover {
            background: #9c27b0;
            transform: translateY(-2px);
        }
        .btn-return {
            background: #6c757d;
            color: white;
            border: none;
            padding: 14px 30px;
            font-weight: bold;
            border-radius: 10px;
            width: 48%;
        }
        .btn-return:hover {
            background: #5a6268;
        }
        .alert {
            border-radius: 12px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="edit-card">
    <div class="card-header">
        <h3 class="my-3 text-center">
            Edit Student Information
        </h3>
    </div>

    <div class="card-body">
        <!-- CHECKING FOR EMPTY SPACES IN THE FORM AND SHOW AN ALERT MESSAGE -->
        <?php if (!empty($successMessage)):?>
            <div class="container my-4" style="max-width: 550px; font-size: 16px;">
                <div class="alert alert-success alert-dismissible fade show text-center mx-3" role= "alert">
                    <?php echo $successMessage; ?>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="close"></button>
                </div>
            </div>
        <?php endif; ?>
    
        <?php if (!empty($errorMessage)):?>
            <div class="container my-4" style="max-width: 550px; font-size: 16px;">
                <div class="alert alert-danger alert-dismissible fade show text-center mx-3" role="alert">
                    <?php echo $errorMessage; ?>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="close"></button>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="mb-4">
                <label for="name" class= "form-label fw-bold">Name</label>
                <input type="text" name = "name" class="form-control" value = "<?php echo htmlspecialchars($Student['name']); ?>">
            </div>

            <div class="mb-4">
                <label for="email" class= "form-label fw-bold">Email</label>
                <input type="email" name = "email" class="form-control" value = "<?php echo htmlspecialchars($Student['email']); ?>">
            </div>

            <div class="mb-4">
                <label for="course" class= "form-label fw-bold">Course</label>
                <input type="text" name = "course" class="form-control" value = "<?php echo htmlspecialchars($Student['course']); ?>">
            </div>

            <!-- TWO BUTTONS SIDE BY SIDE USING FLEX -->
            <div class="d-flex gap-3 justify-content-center mt-4">
                <button type = "submit" class= "btn btn-sm  btn-update" name="submit">
                Update Student
                </button>

                <a href="Student.php" class= "btn btn-sm btn-return text-decoration-none text-center text-sm">
                    Return to Dashboard
                </a>
            </div>
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
        }, 2000); //2000ms = 2sec
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>

