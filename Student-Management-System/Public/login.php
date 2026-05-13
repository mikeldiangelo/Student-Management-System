<?php
//INTIALIZE SESSION
session_start();

//CHECK IF THE USER IS ALREADY LOGGED IN
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: Student.php");
    exit;
}

require_once "../src/config/config.php";

//DEFINE VARIABLES AND INTIALIZE EMPTY VALUES
$email = $password = "";
$email_err = $password_err = $login_err = "";

//PROCESSING FORM DATA AN PASSING IT THROUGH VALIDATION
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM admins WHERE email = ?";

        if ($stmt = mysqli_prepare($connect, $sql)) {
            // Bind variables
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct → start new session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            // Redirect to dashboard
                            header("location: Student.php");
                            exit;
                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }
            } else {
                echo "Something went wrong. Please try again later.";
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
    <title>Admin Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            margin: 30px auto;
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
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-login {
            background: purple;
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #9c27b0;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
        }
        .test-credentials {
            font-size: 0.85rem;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header">
        <h3 class="mb-0">Admin Login</h3>
    </div>
    
    <div class="card-body">
        <?php if (!empty($login_err)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($login_err) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" 
                       class="form-control <?= !empty($email_err) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($email) ?>">
                <span class="text-danger"><?= $email_err ?></span>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" 
                       class="form-control <?= !empty($password_err) ? 'is-invalid' : '' ?>">
                <span class="text-danger"><?= $password_err ?></span>
            </div>

            <button type="submit" class="btn btn-login">Sign In</button>
        </form>

        <div class="text-center mt-4">
            Don't have an account? 
            <a href="register-admin.php" class="text-purple">Register here</a>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>