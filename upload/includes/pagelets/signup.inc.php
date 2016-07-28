<?php // sign up page

//redirect user if logged in
if (isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}
?>

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <a href="index.php?pagelet=index"><button type="button" class="close"  aria-label="Close"><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title text-center" id="myModalLabel">Success!</h4>        
      </div>
      <div class="modal-body">
      <p>Thank you for registering with Boost Afterschool Enrichment! To activate your account, please check your email.</p>
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="index.php?pagelet=index">Close</a>        
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-11 title">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
  </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	require_once ('../upload/mysqli_connect.php'); // Connect to the db.

	// message variable. 
	$message = ''; 
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$u = $e = $p = FALSE;
	
	// Check for a user name:
	if (preg_match ('/^[A-Z]{4,20}$/i', $trimmed['username'])) {
		$u = mysqli_real_escape_string ($dbc, $trimmed['username']);
	} else {
		$message .= '<p>Please enter a username.</p>';
	}
	
	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	} else {
		$message .= '<p>Please enter your email address.</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{8,20}$/', $trimmed['password']) ) {
		if ($trimmed['password'] == $trimmed['confirm_password']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password']);
		} else {
			$message .= '<p>Passwords must match.</p>';
		}
	} else {
		$message .= '<p>Please enter a password.</p>';
	}
	
	if ($u && $e && $p) { // validation passes

		// Make sure the email address and username is not in use
		$q = "SELECT user_id FROM users WHERE email='$e' || username='$u'";
		$r = mysqli_query ($dbc, $q);
		
		if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));

			// Add the user to the database:
			$q = "INSERT INTO users (username, email, password, active, reg_date) VALUES ('$u', '$e', SHA1('$p'), '$a', NOW() )";
			$r = mysqli_query ($dbc, $q);

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				//Send the email:
				$body = "Thank you for registering at Boost. To activate your account, please click on this link:\n\n";
				$body .= 'http://student065.webdev.seminolestate.edu/index.php?pagelet=activate&x=' . urlencode($e) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
				
				// Show confirmation:				
				echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal({show: true, keyboard:false, backdrop: 'static'});
          });
          </script>
          <noscript class='text-center'>
          <div class='text-success'>Thank you for registering! A confirmation email has been sent to your address. Please click on the link in that email to activate your account.</div>";          				
				exit(); // Stop the page.
				
			} else { // Insert failed
        //show error message
				$message = '<p>You could not be registered due to a system error. We apologize for any inconvenience.</p><p>' . mysqli_error($dbc) . '</p>';
				echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#goback').modal('show');
            });
            </script>
            <noscript class='text-center'>
            <div class='text-danger'>$message</div></noscript>";
			}
			
		} else { // Email and/or username is in use.
      
      //check for email      
      $q = "SELECT user_id FROM users WHERE email='$e'";
      $r = mysqli_query ($dbc, $q);
      if (mysqli_num_rows($r) == 1) {
        $message .= 'That email address has already been registered. ';      
          } 
      //check for username
      $query = "SELECT user_id FROM users WHERE username ='$u'";
      $result = mysqli_query ($dbc, $query); 
      if (mysqli_num_rows($result) == 1) {
        $message .= 'That username has already been registered. ';
      }

      //direct user to log in
			$message .= '<p>If you have an account, <a href="index.php?pagelet=login"><u>log in</u></a>.</p>';
			echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#goback').modal('show');
            });
            </script>
            <noscript class='text-center'>
            <div class='text-danger'>$message</div></noscript>";
		}
		
	} else { // validation fails			
		echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#goback').modal('show');
          });
          </script>
          <noscript class='text-center'>
          <div class='text-danger'>$message</div></noscript>";
	}

	mysqli_close($dbc);

} // End of the main submit conditional.
?>
	
<div class="container">

  <div class="row">
      <div class="col-md-4 col-md-offset-4 text-center">
        <h4>Sign Up</h4>
        <p>Already have an account? <a href="index.php?pagelet=login">Log In</a>
        </p>
      </div>
  </div>

  <div class="row">

    <form class="col-md-4 col-md-offset-4" action="" method="post" id="signup">

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" value="<?php if (isset($trimmed['username'])) echo $trimmed['username']; ?>"/>                      
      </div>

      <div class="form-group">
        <label for="username">Email</label>
        <input type="text" class="form-control" name="email" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>"/>                      
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" />
      </div> 

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" />
      </div>           

      <div class="form-group">
        <button type="submit" class="btn btn-success center-block">Sign Up</button>
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
        <h4 class="modal-title text-center" id="myModalLabel">Error!</h4>        
      </div>
      <div class="modal-body">
      <p>There was an error creating your account.</p>
      <p class="text-danger text-center"><?php echo "$message";?></p>
      </div>
      <div class="modal-footer">  
          <button type="submit" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Go Back</button>
      </div>
    </div>
  </div>
</div>

