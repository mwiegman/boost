<div class="jumbotron">
  <div class="container text-center">
    <div class="row">
      <h1>Boost</h1>
      <h2>After School Enrichment</h2>
    </div>  
  </div>
</div>
<div class="container">

<?php 
// This page activates the user's account.

// If $x and $y don't exist or aren't of the proper format, redirect the user:
if (isset($_GET['x'], $_GET['y']) 
	&& filter_var($_GET['x'], FILTER_VALIDATE_EMAIL)
	&& (strlen($_GET['y']) == 32 )
	) {

	// Update the database...
	require_once ('../upload/mysqli_connect.php'); // Connect to the db.

	$q = "UPDATE users SET active=NULL WHERE (email='" . mysqli_real_escape_string($dbc, $_GET['x']) . "' AND active='" . mysqli_real_escape_string($dbc, $_GET['y']) . "') LIMIT 1";
	$r = mysqli_query ($dbc, $q);
	
	// Print a customized message:
	if (mysqli_affected_rows($dbc) == 1) {
		echo "<div class='row text-center'>
	          <div class='col-sm-12'>
	            <h2 class='text-success'>Your account is now active</h2>
	          </div>
        	</div> 
	        <div class='row text-center'> 
	          <div class='col-sm-12'>
	            <a class='btn btn-primary' href='index.php?pagelet=login'>Log In</a> 
	          </div>
	        </div>";
		$_SESSION['new_signup'] = "true";		
		
	} else {
		echo '<p class="error">Your account could not be activated. Please re-check the link or contact the system administrator.</p>'; 
	}

	mysqli_close($dbc);

} else { // Redirect.		
	header("Location: index.php?pagelet=index");
	exit(); // Quit the script.

} // End of main IF-ELSE.

?>
</div>