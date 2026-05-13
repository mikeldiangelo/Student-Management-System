<?php
session_start();

// IF ALREADY LOGGED IN, GO TO DASHBAOARD
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Student.php");
    exit;
}

require_once "../src/config/config.php";

$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = $register_err = $success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //EMAIL VALIDATION
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    //PASSWORD VALIDATION
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must be at least 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    //CONFIRM PASSWORD
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && $password !== $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    //IF NO ERRORS, INSERT INTO USERS TABLE
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        //PREPARE SELECT STATEMENT AND BIND BEFORE SUBMITTING INTO DATABASE
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($connect, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $register_err = "This email is already registered.";   // DISPLAY A BOOTSTRAP ALERT
                } else {
                    //INSERT INTO USERS TABLE
                    $insert_sql = "INSERT INTO admins (email, password) VALUES (?, ?)";
                    
                    if ($insert_stmt = mysqli_prepare($connect, $insert_sql)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($insert_stmt, "ss", $param_email, $hashed_password);
                        $param_email = $email;

                        if (mysqli_stmt_execute($insert_stmt)) {
                            $success_msg = "Account created successfully! Redirecting to login page.";
                        } else {
                            $register_err = "Something went wrong. Please try again.";
                        }
                        mysqli_stmt_close($insert_stmt);
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Admin Registration</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .register-card {
            max-width: 420px;
            width: 100%;
            margin: 30px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
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
        }
        .form-control:focus {
            border-color: purple;
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 128, 0.25);
        }
        .btn-register {
            background: purple;
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 10px;
            width: 100%;
        }
        .btn-register:hover {
            background: #9c27b0;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="card-header">
        <h3 class="mb-0">Register Admin</h3>
    </div>
    
    <div class="card-body">
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
            <script>
                setTimeout(() => {
                    window.location.href = 'login.php'; // redirects to login page after success alert
                }, 3000); // 3ms = 3Sec
            </script>
        <?php endif; ?>

        <?php if (!empty($register_err)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($register_err) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control <?= !empty($email_err) ? 'is-invalid' : '' ?>" 
                value="<?= htmlspecialchars($email) ?>">
                <span class="text-danger"><?= $email_err ?></span>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control <?= !empty($password_err) ? 'is-invalid' : '' ?>">
                <span class="text-danger"><?= $password_err ?></span>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?= !empty($confirm_password_err) ? 'is-invalid' : '' ?>">
                <span class="text-danger"><?= $confirm_password_err ?></span>
            </div>

            <button type="submit" class="btn btn-register">Register Admin Account</button>
        </form>

        <div class="text-center mt-4">
            Already have an account? 
            <a href="login.php" class="text-purple">Login here</a>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>