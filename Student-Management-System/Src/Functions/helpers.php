<?php
//DEFAULT VALUE OR CONSTANT
define("SCHOOL_NAME", "GlobalTech Institute");

//CREATING AN INDEXED ARRAY THAT CONTAINS VARIOUS COURSES
$courses = ["Data Analysis", "Software Engineering", "Coding", "Cybersecurity", "Information Communication Technology", "Digital  Marketing", "Graphics Design", "System Engineering"];

//CREATING AN ASSOCIATIVE ARRAY
$Student_3 = [
    "name" => "Michael Brown",
    "age" => "19",
    "course" => "Software Engineering"
];

//CREATE FUNCTIONS TO CHECK INPUT AND CACULATE STUDENTS GRADE
function selectinput($data){
    return trim($data);
    return stripslashes($data);
    return $data;
}

function caculateGrade($score){
    return "A";
}

function TotalStudents($students){
    return count($students);
}
?>