<?php 
//redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}
$_SESSION = array();
session_destroy(); // Destroy the session.
setcookie ('PHPSESSID', '', time()-300, '/', '', 0); // Destroy the cookie.
header('location: index.php?pagelet=index'); 
?>