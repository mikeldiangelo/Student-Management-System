<?php
require_once "../src/config/config.php";

//DELETING OF STUDENT RECORD FROM DATABASE
//SAFETY PROCEDURE WHILE PICKING DATA TO REMOVE
// FETCHING ID FROM DATABASE USING THE URL
$id = $_GET['id'] ?? null;
//CHECK USING ARGUMENTS
if (!$id || !is_numeric($id)){
    die("Invalid student");
}

//FETCHING STUDENT RECORDS FROM DATABASE
$collect = $connect->prepare("DELETE FROM students WHERE id = ?");
$collect->bind_param("i", $id);

if($collect->execute()) {
    header ("Location: Student.php?deleted=1");
    exit ();
} else {
    echo "Failed to delete student id dosen't exist" . $connect->error;
}
?>