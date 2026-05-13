<?php
//LOG OUT OF THE DASHBOARD
//START SESSION
session_start();

//UNSET ALL SESSION DATA
session_unset();

//DESTROY ALL SESSION DATA
session_destroy();

//REDIRECT BACK TO LOGIN PAGE
header("Location: login.php");
exit;

?>