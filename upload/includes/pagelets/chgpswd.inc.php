<?php//Password reset page

// If $x and $y don't exist or aren't of the proper format, redirect the user:
if (isset($_GET['x'], $_GET['y']) && filter_var($_GET['x'], FILTER_VALIDATE_EMAIL) && (strlen($_GET['y']) == 32 )) { //check if reset expiration time is valid

  require_once ('../upload/mysqli_connect.php'); // Connect to the db.

  $q = "SELECT * FROM users WHERE email='" . mysqli_real_escape_string($dbc, $_GET['x']) . "' AND reset='" . mysqli_real_escape_string($dbc, $_GET['y']) . "' AND reset_expire > NOW()";
  $r = mysqli_query ($dbc, $q);

  if (mysqli_num_rows($r) == 1) {//reset not expired, show form
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
      <p>Your password has been reset. You may now <a href='index.php?pagelet=login'>Log In</a></p>
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="index.php?pagelet=index">Close</a>        
      </div>
    </div>
  </div>
</div>

<div class="jumbotron">
  <div class="container text-center">
    <div class="row">
      <h1>Boost</h1>
      <h2>After School Enrichment</h2>
    </div>  
  </div>
</div>

<div class="container">

  <div class="row">
      <div class="col-md-4 col-md-offset-4 text-center">
        <h4>Reset Password</h4>        
        <p>Enter a new password below.</p>
      </div>
  </div>

  <div class="row">

    <form class="col-md-4 col-md-offset-4" action="" method="post" id="chgpswd">
       
      <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" class="form-control" id="password" name="password"/>
      </div> 

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password"/>
      </div>           

      <div class="form-group">
        <button type="submit" class="btn btn-success center-block">Reset</button>
      </div> 

    </form>

  </div>

</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { //form submit
  // message variable. 
  $message = ''; 
  
  // Trim all the incoming data:
  $trimmed = array_map('trim', $_POST);

  // Assume invalid value:
  $p = FALSE;
  
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
  
  if ($p) {//update password
    $q = "UPDATE users SET password=SHA1('$p') WHERE email='" . mysqli_real_escape_string($dbc, $_GET['x']) . "' AND reset='" . mysqli_real_escape_string($dbc, $_GET['y']) . "' AND reset_expire > NOW()";
    $r = mysqli_query ($dbc, $q);

    if (mysqli_affected_rows($dbc) == 1) {//if update worked
      echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal({show: true, keyboard:false, backdrop: 'static'});
          });
          </script>
          <noscript class='text-center'>
          <div class='text-success'>Your password has been reset. You may now <a href='index.php?pagelet=login'><u>log in</u></a></div>";                 
        exit(); // Stop the page.

    } else {//show system error
      $message = '<p>Your password could not be reset due to a system error. We apologize for any inconvenience.</p><p>' . mysqli_error($dbc) . '</p>';
      echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#goback').modal('show');
            });
            </script>
            <noscript class='text-center'>
            <div class='text-danger'>$message</div></noscript>";
    }
  }

}
  } else {//show expiry message
    echo "<div class='row text-center'>
            <div class='col-sm-12'>
              <h2 class='text-success'>Your password reset has expired.</h2>
            </div>
          </div> 
          <div class='row text-center'> 
            <div class='col-sm-12'>
            <p>To reset your password, please 
              <a href='index.php?pagelet=reset'>make a new request</a> 
            </div>
          </div>";
  }

} else {// Redirect.   
  header("Location: index.php?pagelet=index");
  exit(); // Quit the script.
}

?>

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