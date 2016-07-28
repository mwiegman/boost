<?php //logout page

//redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}

$_SESSION = array(); //Destroy session variables.
session_destroy(); // Destroy the session.
setcookie ('PHPSESSID', '', time()-300, '/', '', 0); // Destroy the cookie.
header('location: index.php?pagelet=index');//redirect to home page. 
?>