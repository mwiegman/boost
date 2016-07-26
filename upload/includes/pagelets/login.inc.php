<?php 
// login page
//redirect user if logged in
if (isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require_once ('../upload/mysqli_connect.php'); // Connect to the db.
	
	$message = NULL; // message variable.

  // Trim all the incoming data:
  $trimmed = array_map('trim', $_POST);

	// Validate the email address:
	if (!empty($_POST['username'])) {
		$u = mysqli_real_escape_string ($dbc, $trimmed['username']);
	} else {
		$u = FALSE;
		$message .= '<p>Please enter your username.</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['password'])) {
		$p = mysqli_real_escape_string ($dbc, $trimmed['password']);
	} else {
		$p = FALSE;
		$message .= '<p>Please enter your password.</p>';
	}
	
	if ($u && $p) { // If everything's OK.

		// Query the database:
		$q = "SELECT user_id, prof_id, username, email, admin FROM users WHERE (username='$u' AND password=SHA1('$p')) AND active IS NULL";		
		$r = mysqli_query ($dbc, $q);
		
		if (@mysqli_num_rows($r) == 1) { // A match was made.

			// Register the values:
			$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
			mysqli_free_result($r);
			mysqli_close($dbc);
			
      if (!isset($_SESSION['prof_id']) && $_SESSION['admin'] == 0) {//if profile is not complete, redirect to profile page	
			header('location: index.php?pagelet=profile');						
			} else {//redirect to index page
        header('location: index.php?pagelet=index');            
      }
				
		} else { // No match was made.			
			$message='<p class="error">Your username and/or password is incorrect or you have not yet activated your account.</p>'; 
      //show error modal           
      echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#goback').modal('show');
        });
        </script>
        <noscript class='text-center'>
        <h2 class='text-danger'>$message</h2></noscript>";
		}
		
	} else { // If everything wasn't OK.
		//show error modal           
    echo "<script type='text/javascript'>
      $(document).ready(function(){
      $('#goback').modal('show');
      });
      </script>
      <noscript class='text-center'>
      <h2 class='text-danger'>$message</h2></noscript>";          
	}
	
	mysqli_close($dbc);

} // End of SUBMIT conditional.
 
?>
<div class="container">

  <div class="row">

      <div class="col-md-4 col-md-offset-4 text-center">
        <h4>Log In</h4>
        <p>Don't have an account? <a href="index.php?pagelet=signup">Sign Up</a>
        </p>
      </div>

  </div>

  <div class="row">

    <form class="col-md-4 col-md-offset-4" action="#" method="post" id="login">

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" value="<?php if (isset($trimmed['username'])) echo $trimmed['username']; ?>">                      
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password">
      </div>   

     <div class="form-group">
      <button type="submit" class="btn btn-success center-block">Log In</button>
     </div>  

     <div class="text-right">
      <a href="index.php?pagelet=reset"><small>Forgot password?</small></a>
     </div>

    </form>

  </div>

 </div>

<!-- goback modal -->
<div id="goback" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Login Error</h4>        
      </div>
      <div class="modal-body">      
      <div class="text-center text-danger"><?php echo "$message"; ?></div>
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="index.php?pagelet=login">Go Back</a>
      </div>
    </div>
  </div>
</div>