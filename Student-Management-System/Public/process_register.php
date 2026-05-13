<?php
// IF USER IS NOT LOGGED IN SEDN THEM BACK TO LOGIN PAGE
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once "../src/config/config.php";
//DEFINE VARIABLES AND VALIDATE THE FORM
$name = $email = $course = "";
$nameError = $emailError = $courseError = $file_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");

    if (empty($name)) {
        $nameError = "Name is required";
        // CHECK IF NAME CONTAINS ONLY LETTERS AND NUMBERS
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$name)){
        $nameError = "Only alphabets and white spaces are allowed";
    }
    
    if (empty($email)) {
        $emailError = "Email is required";
        //CHECK IF EMAIL ADDRESS IS WELL FORMATTED
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailError = "Invalid email format*";
    } 

    if (empty($course)) {
        $courseError = "Kindly fill in your specified course";
    }

//RECIEVE IMAGE AND UPLOAD TO STORAGE FILE
$file_target = "../Storage/Uploads/";
$move_file = $file_target . basename($_FILES["Uploadfile"] ["name"] ?? "");
$Upload = 1;
$Imagetype = strtolower(pathinfo($move_file,PATHINFO_EXTENSION));

//CHECK IF IMAGE FILE IS AN ACTUAL IMAGE
if(!isset($_FILES["Uploadfile"]) || $_FILES["Uploadfile"]["error"] != 0 || empty($_FILES["Uploadfile"] ["name"])) {
    $file_error = "Please select an image.";
    $Upload = 0;
} else {
    //CHECK IF IMAGE IS AN ACTUAL IMAGE
    $check = getimagesize($_FILES["Uploadfile"] ["tmp_name"]);
    if($check === false) {
        $file_error = "File is not a valid image.";
        $Upload = 0;
    }

    //CHECK IF FILE EXISTS
    if(file_exists($move_file)) {
        $file_error = "Sorry, file already exists.";
        $Upload = 0;
    }

    //SET A SPECIFIC IMAGE SIZE
    if($_FILES["Uploadfile"] ["size"] > 20971520) {
        $file_error = "File is too large (max 20MB).";
        $Upload = 0;
    }

    //ALLOW CERTAIN FILE FORMATS
    if($Imagetype !== "jpg" && $Imagetype !== "png" && $Imagetype !== "jpeg") {
        $file_error = "Only JPG, JPEG, & PNG files are allowed.";
        $Upload = 0;
    }
}
    
//IF THERE ARE ERRORS IN THE FORM THEN IT SHOULD RETURN THE ERRORS ASSIGNED
if (!empty($nameError) || !empty($emailError) || !empty($courseError) || !empty($file_error)) {
    header("Location: Register.php?error=1" .  "&name=" . urlencode($name) .
    "&email=" . urlencode($email) .
    "&course=" . urlencode($course) .
    "&nameError=" . urlencode($nameError) .
    "&emailError=" . urlencode($emailError) .
    "&courseError=" . urlencode($courseError) .
    "&file_error=" . urlencode($file_error));
    exit();
}  

//IF NO ERRORS OCCURED DURING FILE UPLOAD UPLOAD FILE AND INSERT INTO DATABASE
if(move_uploaded_file($_FILES["Uploadfile"] ["tmp_name"], $move_file)) {
    //REDIRECTS USER AFTER SUCCESS IF NO ERRORS IS FOUND
    if (empty($nameError) && empty($emailError) && empty($courseError) && empty($file_error)) {           
            $admin_id = $_SESSION["id"];
            $sql3 = "INSERT INTO students (name, email, course, admin_id) VALUES (?, ?, ?, ?)";
            $stmt = $connect->prepare($sql3);
            $stmt->bind_param("sssi", $name, $email, $course, $admin_id);

            //CHECK IF INSERTING INTO DATABASE IS CORRECT AND REDIRECT AFTER SUCCESS
            if($stmt->execute()){
            //LOGGING STUDENT INTO TEXT FILE
            $filename = fopen("../Storage/Logs/registrations.txt", "a");
            $enterStudent = $name . " " ."registered on" . " " . date("d-m-Y H:i:s") . "\n";
            fwrite($filename, $enterStudent);
            fclose($filename);

            header("Location: Register.php?success=1");
            exit();
        } else {
            echo "Inserting into database error: " . $connect->error;
        }
    } else {
        $file_error = "Sorry, there was an error uploading your file.";
        header("Location: Register.php?error=1&file_error=" . urlencode($file_error));
        exit();
    }
}

}
?>