<?php
// IF USER IS NOT LOGGED IN SEDN THEM BACK TO LOGIN PAGE
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "../src/config/config.php";

//CREATING AN ARRAY OF STUDENTS THAT PRINTS THEIR NAME AND SERIAL NO
// $student = [
//     ["name" => "Mikel", "age" => 27, "course" => "ICT"],
//     ["name" => "John", "age" => 32, "course" => "Data Analysis"],
//     ["name" => "Samson", "age" => 22, "course" => "Data Science"],
//     ["name" => "Jane", "age" => 28, "course" => "Computer Science"],
//     ["name" => "Declan", "age" => 25, "course" => "Cybersecurity"]
// ];

// echo "<h3> No of Students</h3>";
// $serial_number = 1;
// foreach ($student as $students){
//     echo $serial_number . " . " . $students["name"] . "<br>";
//     $serial_number ++;
// }

// SETTING UP THE PAGINATION
$limit = 5; //student data per page
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$start_from = ($page - 1) * $limit;

//TOTAL NUMBER OF RECORDS IN DATABASE USED TO CACULATE TOTAL PAGES TO BE DISPLAYED
$sql = "SELECT COUNT(*) AS total FROM students";
$total_result = $connect->query($sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

//MAIN QUERY TO PRINT STUDENTS FROM DATABASE
$sql5 = "SELECT * FROM students ORDER BY id DESC LIMIT $start_from, $limit";
$result= $connect->query($sql5);

//HANDLE INVALID PAGE NUMBERS
if($page > $total_pages && $total_pages >= 1) {
    header("Location: ?page=" . $total_pages);
    exit();
}

//SETTING VARIABLES FOR SUCCESS AND ERROR MESSAGES
$successMessage = "";

// SHOW SUCCESS AND ERROR MESSAGE
if(isset($_GET['deleted'])) {
    $successMessage = "Student deleted successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Student Dashboard</title>
</head>
<body class="container mt-5">

    <!-- ADDING A WELCOME MESSAGE WHEN LOGGED INTO THE DASHBOARD -->
    <div class="alert alert-info text-center">
        <strong>Welcome Admin!</strong> 
        <?= htmlspecialchars($_SESSION['email'] ?? '') ?>
    </div>

    <h2 class="my-4 text-center">Student Management System</h2>

    <?php if(!empty ($successMessage)):?>
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

    <table class="table table-bordered table-striped my-3 p-2 table-responsive">
        <thead class="table-dark">
            <tr>
                <th>Serial No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Creation Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["course"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";

                    echo "<td>";

                    echo "<a class='btn btn-primary btn-sm me-2 mx-2 my-2'
                    href='edit_student.php?id=" . $row['id'] . "'>
                    Edit
                    </a>";

                    echo "<a class= 'btn btn-danger btn-sm me-2 mx-2 my-2' href='delete_student.php?id=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this student ?');\">
                    Delete
                    </a>";

                    echo "</td>";

                    echo "</tr>";
            
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6' class='text-center'>No Students Found </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- ADDING PAGINATION TO PAGE -->
    <nav aria-label= "Student pagination">
        <ul class="pagination justify-content-center">
            <!-- PREVIOUS PAGE -->
             <?php if($page > 1):?>
                <li class="page-item">
                    <a href="?page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                <?php endif; ?>

                <!-- PAGE INFO -->
                <li class="page-item disabled">
                    <span class="page-link">
                        Page <?= $page ?> of <?= $total_pages ?>
                        (Total: <?= $total_records ?> students)
                    </span>
                </li>

                <!-- NEXT PAGE -->
                <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a href="?page=<?= $page + 1 ?>" class="page-link">Next</a>
                    </li>

                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                <?php endif; ?>
        </ul>
    </nav>

    <a href="Register.php" class= "btn btn-primary p-2 mx-2 mt-3 btn-sm mb-4">
        Register New Student
    </a>

    <a href="logout.php" class="btn btn-danger p-2 mx-2 mt-3 mb-4 btn-md" 
        onclick="return confirm('Are you sure you want to logout?');">
        Logout
    </a>

    <a href="Dashboard.php" class="btn btn-secondary p-2 mx-2 mt-3 btn-md mb-4">
        Return to Admin Dashboard
    </a>

    
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
      }, 2000); 
    //   4000ms = 4sec
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>