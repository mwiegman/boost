<?php // password reset page

//redirect user if logged in
if (isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}
?>

<div class="col-sm-12">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
</div>

<?php

//handle form 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//check user input
// Need the database connection
  require_once ('../upload/mysqli_connect.php'); // Connect to the db.

  // message variable. 
  $message = ''; 
  
  // Trim all the incoming data:
  $trimmed = array_map('trim', $_POST);

  // Assume invalid values:
  $u = $e = FALSE;
  
  // Check for a user name:
  if (preg_match ('/^[A-Z]{4,20}$/i', $trimmed['username'])) {
    $u = mysqli_real_escape_string ($dbc, $trimmed['username']);
  } else {
    $message .= '<p>Please enter your username.</p>';
  }
  
  // Check for an email address:
  if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
    $e = mysqli_real_escape_string ($dbc, $trimmed['email']);
  } else {
    $message .= '<p>Please enter your email address.</p>';
  }

//if form validates
  if ($u && $e) {
    //confirm username and email
    $q = "SELECT user_id FROM users WHERE email='$e' || username='$u'";
    $r = mysqli_query ($dbc, $q);

    if (mysqli_num_rows($r) == 1) {//confirmed
      //create reset token
      $reset = md5(uniqid(rand(), true));

      //add reset token and expiration to database
      $q = "INSERT INTO users (reset, reset_expire) VALUES ('$reset', DATE_ADD(NOW(), INTERVAL 4 HOUR) )";
      $r = mysqli_query ($dbc, $q);

      if (mysqli_affected_rows($dbc) == 1) {//insert worked
      //send email
      // $body = "Did you forget your Boost password? It happens. To reset your password, please click on this link:\n\n";
      // $body .= 'http://student065.webdev.seminolestate.edu/index.php?pagelet=chgpswd&x=' . urlencode($e) . "&y=$reset";
      // mail($trimmed['email'], 'Forget your password?', $body, 'From: admin@boost.com');

      //show confirmation message
      echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal({show: true, keyboard:false, backdrop: 'static'});
          });
          </script>
          <noscript class='text-center'>
          <div class='text-success'>Your request has been processed. A password reset email has been sent to your address. Please click on the link in that email to reset your password.</div>";                 
        exit(); // Stop the page.

      } else {//insert failed
        $message = '<p>Your password could not be reset due to a system error. We apologize for any inconvenience.</p><p>' . mysqli_error($dbc) . '</p>';
        echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#goback').modal('show');
            });
            </script>
            <noscript class='text-center'>
            <div class='text-danger'>$message</div></noscript>";
      }      
    } else {//username/email don't match
    //show error message
    $message = '<p>There is no account associated with the username and/or email you entered.</p>';
    echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#goback').modal('show');
        });
        </script>
        <noscript class='text-center'>
        <div class='text-danger'>$message</div></noscript>";
    }
  } else {//show validation error
    echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#goback').modal('show');
          });
          </script>
          <noscript class='text-center'>
          <div class='text-danger'>$message</div></noscript>";
  }
 mysqli_close($dbc);
} //show form
?>
  
<div class="container">

  <div class="row">
      <div class="col-md-4 col-md-offset-4 text-center">
        <h4>Reset Your Password</h4>
        <p>Enter your username and email address and we'll send you a link to reset your password
        </p>
      </div>
  </div>

  <div class="row">

    <form class="col-md-4 col-md-offset-4" action="" method="post" id="reset">

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" value="<?php if (isset($trimmed['username'])) echo $trimmed['username']; ?>"/>                      
      </div>

      <div class="form-group">
        <label for="username">Email</label>
        <input type="text" class="form-control" name="email" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>"/>                      
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-success center-block">Reset Password</button>
      </div> 

      <a href="index.php?pagelet=login">Return to Log In</a>

    </form>

  </div>

</div>


<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <a href="index.php?pagelet=index"><button type="button" class="close"  aria-label="Close"><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title text-center" id="myModalLabel">Success!</h4>        
      </div>
      <div class="modal-body">
      <p>If the username you entered exists, a password reset link has been sent. To reset your password, please check your email.</p>
      <a href="index.php?pagelet=chgpswd&x="<?php echo urlencode($e) . "&y=$reset";?>></a>
      <!-- <p>Thank you for registering with Boost Afterschool Enrichment! To activate your account, please check your email.</p> -->
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="index.php?pagelet=index">Close</a>        
      </div>
    </div>
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
      <p>There was an error resetting your password.</p>
      <p class="text-danger text-center"><?php echo "$message";?></p>
      </div>
      <div class="modal-footer">  
          <button type="submit" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Go Back</button>
      </div>
    </div>
  </div>
</div>