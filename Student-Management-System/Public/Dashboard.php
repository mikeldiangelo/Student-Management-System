<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "../src/config/config.php";

// COUNT TOTAL STUDENTS IN THE DATABASE
$countTotal = "SELECT COUNT(*) AS total_students FROM students";
$result_of_count = $connect->query($countTotal);

$count = $result_of_count->fetch_assoc();
$total_student = $count['total_students'];

// FETCH AT LEAST 5 STUDENTS FROM DATABASE IF AVLIABLE
$select = "SELECT * FROM students ORDER BY created_at DESC LIMIT 5";
$fetch_student = $connect->query($select);


// ADDING SEARCH FUNCTION TO ADMIN DASHBOARD USING GET METHOD
$search = $_GET['search'] ?? "";

//USING JOIN QUERY TO CONNECT STUDENT AND ADMIN DATABASE TOGETHER
if(!empty($search)) {
    $join = "SELECT students.*,admins.name AS admin_name FROM students JOIN admins ON students.admin_id = admins.id WHERE students.name LIKE ? ORDER BY students.created_at DESC";

    $stmt =$connect->prepare($join);
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $fetch_student = $stmt->get_result();
} else {
    $join = "SELECT students.*,admins.name AS admin_name FROM students JOIN admins ON students.admin_id = admins.id ORDER BY students.created_at DESC LIMIT 5";

   $fetch_student = $connect->query($join);
}

//SHOW ERROR IF STUDENTS AND ADMIN TABLE COULDN'T JOIN AND DISPLAY
if(!$fetch_student) {
    die("Error loading students: " . $connect->error);
}

// READ REGISTRATION TEXT FILE AND DISPLAY ON DASHBOARD
$filename = "../Storage/Logs/registrations.txt";

$show = [];

if (file_exists($filename)) {
    $show = file($filename);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Admin Dashboard</title>
    <style>
        body {
            background: #f8f9fa;
        }

        .dashboard-header {
            background: purple;
            color: #fff;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .total-card {
            transition: transform 0.3s;
        }

        .total-card:hover {
            transform: translateY(-5px);
        }

        table tbody tr:hover {
            background: #f0f1ff;
        }

        .logs-card {
            max-height: 400px;
            overflow-y: auto;
        }

        .search-input {
            border-radius: 45px 0 0 45px;
            padding: 12px 20px;
        }

        .search-btn {
            border-radius: 0 45px 45px 0;
            padding: 12px 25px;
        }
    </style>
</head>
<body>
    <!-- PRUPLE HEADER WITH A WELCOME ALERT -->
<div class="dashboard-header">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="my-2 text-center mb-0">Admin Dashboard</h2>
        <div class="d-flex align-items-center gap-3">
            <div class="alert alert-light py-2 px-3 mb-0">
                <strong>Welcome Admin</strong>  <?php echo htmlspecialchars($_SESSION["email"] ?? ""); ?>
                <a href="logout.php" class="btn btn-danger btn-sm p-2 mx-3" onclick= "return confirm('Are you sure you want logout?');">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="container p-3">
        <!-- TOTAL STUDENTS CARD -->
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card text-center total-card shadow border-0 my-3" style = "max-width: 400px; margin: auto;">
                <div class= "card-body py-3">
                    <h4>Total Registered Students</h4>
                    <h2 class="display-4 fw-bold text-purple">
                    <?php echo $total_student; ?>
                    </h2>
                    <small class="text-success">↑ All time</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ADDING SEARCH BAR TO THE STUDENT DASHBOARD -->
    <div class="my-5">
        <form method="get">
            <div class="input-group">
                <input type="text" name = "search" class = "form-control search-input shadow-sm" placeholder = "Search student" value = "<?php echo htmlspecialchars($search); ?>">

                <button class="btn btn-dark text-white search-btn btn-sm">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- DISPLAYING LATEST STUDENT TABLE -->
    <h4 class="my-2 text-center">Registered Students</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-stripped shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Admin</th>
                    <th>Registered</th>
                </tr>
            </thead>

            <tbody>
            <?php
                if($fetch_student->num_rows > 0) {
                    while ($row = $fetch_student->fetch_assoc()) {
                        echo "<tr>";

                        echo "<td>" . $row["id"] . "</td>";

                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";

                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";

                        echo "<td>" . htmlspecialchars($row["course"]) . "</td>";

                        echo "<td>" . htmlspecialchars($row["admin_name"]) . "</td>";
                            
                        echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";

                        echo "</tr>";
                        }
                    } else {
                        echo "<tr>";

                        echo "<td colspan='6' class='text-center'>No students found</td>";
                        
                        echo "</tr>";
                    }
                    ?>
            </tbody>
        </table>
    </div>

    <!-- REGISTRATION LOGS -->
    <h4 class="mt-5 mb-3 text-center">Registration Logs</h4>
    <div class="card shadow-sm logs-card p-3">
        <div class="card-body">
            <?php
                if (!empty($show)) {
                    foreach ($show as $shows) {
                        echo "<p class = 'mb-2 border-bottom pb-2'>" . htmlspecialchars($shows) . "</p>";
                    }
                } else {
                    echo "<p class='text-muted text-center py-3'>No registration logs found yet.</p>";
                }
            ?>
        </div>
    </div>
</div>

    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>