<?php
// LOCAL (XAMPP)
// $server_name = "localhost";
// $username = "root";
// $password = "";
// $db_name = "student_db";

// LIVE (InfinityFree)
// $server_name = "sql105.infinityfree.com";
// $username = "if0_41882446";
// $password = "your_password_here";
// $db_name = "if0_41882446_mikel";

//CREATE CONNECTION TO DATABASE
$connect = new mysqli($server_name, $username, $password, $db_name);

//CHECK IF CONNECTION WAS SUCCESSFULL 
if ($connect->connect_error){
    die("Connection failed: " . $connect->connect_error);
}

// //CREATING TABLE IN THE DATABASE
// $sql = "CREATE TABLE students (
// id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// name VARCHAR(100) NOT NULL,
// email VARCHAR(75) NOT NULL UNIQUE,
// course VARCHAR(50) NOT NULL,
// created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// )";

// //CHECK CONNECTION TO TABLE
// if ($connect->query($sql) === TRUE) {
//     echo "Table created successfully";
// } else {
//     echo "Error creating table: " . $connect->error;
// }


// //CREATING ADMIN TABLE
// $sql2 = "CREATE TABLE admins (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(100) NOT NULL,
//     email VARCHAR(100) NOT NULL UNIQUE,
//     password VARCHAR(100) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// )";

// //CHECK CONNECTION TO TABLE
// if ($connect->query($sql2) === TRUE) {
//     echo "Admin table created successfully";
// } else {
//     echo "Error creating admin table: " . $connect->error;
// }

//DEFINE RELATIONSHIP 
//RELATIONSHIP IS DEFINED AS THE CONNECTION BETWEEN TWO TABLES USING A FOREIGN KEY I.E CONNECTING THE PRIMARY KEY OF ONE TABLE TO ANOTHER USING FOREIGN KEY AS A MEDIUM
?>