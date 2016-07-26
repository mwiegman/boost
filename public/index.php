<?php
ob_start();
session_start();
//  Make sure that the pagelet variable is set
$pagelet = (isset($_GET['pagelet']) ? $_GET['pagelet'] : "index");

require ("../upload/includes/language.inc.php");

// Include the page header.
include ("../upload/includes/header.inc.php");

//Begin page content
include ("../upload/includes/pagelets/$pagelet.inc.php");

// End page content
include ("../upload/includes/footer.inc.php");  //  Include the HTML footer

// Flush the buffered output to the Web browser.
ob_flush();
?>


