<?php //user profile page

//redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}

//prompt to complete profile if no profile exists (and not admin)
if (!isset($_SESSION['prof_id']) && $_SESSION['admin'] == 0 ) {
  
  echo "<script type='text/javascript'>
  $(document).ready(function(){
  $('#profile-modal').modal('show');
  });
  </script>
  <noscript class='text-center'>
    <h2 class='text-success'>Please complete your registration by updating your profile.</h2>
  </noscript>";
}

require ('../upload/mysqli_connect.php'); // Connect to the db.

$user_id = $_SESSION['user_id']; //store user_id in session variable

//check if profile exists
if (isset($_SESSION['prof_id'])) {
//select user email from users table and profile info from profile table
$q="SELECT p.prof_id, p.first_name, p.last_name, u.email, p.street, p.city, p.state, p.zip FROM profiles AS p INNER JOIN users as u ON p.user_id=u.user_id AND u.user_id = '$user_id'";  
$r = mysqli_query ($dbc, $q); 

if (mysqli_num_rows($r) == 1) {//if query successful
  //set session variables with profile values
  $row = mysqli_fetch_row ($r);   
  $_SESSION['prof_id'] = $row[0]; 
  $_SESSION['first_name'] = $row[1];
  $_SESSION['last_name'] = $row[2];
  $_SESSION['email'] = $row[3];
  $_SESSION['street'] = $row[4];
  $_SESSION['city'] = $row[5];
  $_SESSION['state'] = $row[6];
  $_SESSION['zip'] = $row[7];   
  }
  mysqli_free_result($r);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//if form is submitted

 // message variable.
$message = NULL;

//trim incoming data
$trimmed = array_map('trim', $_POST);

// Check for a first name.
if (empty($_POST['firstname'])) {
    $f = FALSE;
    $message .= '<p>Please enter your first name.</p>';
} else {
    $f = mysqli_real_escape_string ($dbc, $trimmed['firstname']);
}

// Check for a last name.
if (empty($_POST['lastname'])) {
    $l = FALSE;
    $message .= '<p>Please enter your last name.</p>';
} else {
    $l = mysqli_real_escape_string ($dbc, $trimmed['lastname']);
}

// Check for a email.
if (empty($_POST['email'])) {
    $e = FALSE;
    $message .= '<p>Please enter your email.</p>';
} else {
    $e = mysqli_real_escape_string ($dbc, $trimmed['email']);
}

// Check for a street.
if (empty($_POST['street'])) {
    $s = FALSE;
    $message .= '<p>Please enter your street.</p>';
} else {
    $s = mysqli_real_escape_string ($dbc, $trimmed['street']);
}

// Check for a city.
if (empty($_POST['city'])) {
    $c = FALSE;
    $message .= '<p>Please enter your city.</p>';
} else {        
  $c = mysqli_real_escape_string ($dbc, $trimmed['city']);        
}   

 // Check for a state.
if (empty($_POST['state'])) {
    $st = FALSE;
    $message .= '<p>Please select your state.</p>';
} else {        
  $st = mysqli_real_escape_string ($dbc, $trimmed['state']);        
}  

 // Check for a zip.
if (empty($_POST['zip'])) {
    $z = FALSE;
    $message .= '<p>Please enter your zip code.</p>';
} else {        
  $z = mysqli_real_escape_string ($dbc, $trimmed['zip']);        
}  

if ($f && $l && $e && $s && $c && $st && $z) {//validation passes

if (!($_SESSION['prof_id'])) {//if no profile exists
  //insert new values in profiles table
  $q = "INSERT INTO profiles (user_id, first_name, last_name, street, city, state, zip) VALUES ('$user_id', '$f', '$l', '$s', '$c', '$st', '$z')";
     
  $r = mysqli_query ($dbc, $q); 

  if (mysqli_affected_rows($dbc) == 1) {//if insert successful  

  //update prof_id in users table
  $q = "UPDATE users SET prof_id=LAST_INSERT_ID() WHERE user_id='$user_id'";
  $r = mysqli_query($dbc, $q);  

  //set session variables   
  $q = "SELECT p.prof_id, p.first_name, p.last_name, u.email, p.street, p.city, p.state, p.zip FROM profiles AS p INNER JOIN users as u ON p.user_id=u.user_id AND u.user_id = '$user_id'";       
  $r = mysqli_query ($dbc, $q);  

  $row = mysqli_fetch_row ($r);   
  $_SESSION['prof_id'] = $row[0]; 
  $_SESSION['first_name'] = $row[1];
  $_SESSION['last_name'] = $row[2];
  $_SESSION['email'] = $row[3];
  $_SESSION['street'] = $row[4];
  $_SESSION['city'] = $row[5];
  $_SESSION['state'] = $row[6];
  $_SESSION['zip'] = $row[7]; 
  
  mysqli_free_result($r);
  mysqli_close($dbc); 

  //show confirm modal   
  echo "<script type='text/javascript'>
  $(document).ready(function(){
  $('#confirm-modal').modal('show');
  });
  </script>
  <noscript class='text-center'>
    <h2 class='text-success'>You have successfully updated your profile.</h2>
  </noscript>";  
  } else {// if insert was not successful 
  //create error message
  $message="<p>Your profile could not be created at this time.</p>";
  //show error modal           
  echo "<script type='text/javascript'>
    $(document).ready(function(){
    $('#goback').modal('show');
    });
    </script>
    <noscript class='text-center'>
    <h2 class='text-danger'>$message</h2>
    </noscript>";
  }//end of insert conditional

} elseif (isset($_SESSION['prof_id'])) {//if profile exists
  //update profile on submit 
  $q = "UPDATE users AS u, profiles AS p SET p.first_name='$f', p.last_name='$l', p.street='$s', p.city='$c', p.state='$st', p.zip='$z', u.email='$e' WHERE p.user_id='$user_id' AND u.user_id='$user_id'"; 
  $r = mysqli_query ($dbc, $q); 

  if (mysqli_affected_rows($dbc) == 1) {
    //update session variables
    $q = "SELECT p.prof_id, p.first_name, p.last_name, u.email, p.street, p.city, p.state, p.zip FROM profiles AS p INNER JOIN users as u ON p.user_id=u.user_id AND u.user_id = '$user_id'";
    $r = mysqli_query ($dbc, $q);  

    $row = mysqli_fetch_row ($r);   
    $_SESSION['prof_id'] = $row[0]; 
    $_SESSION['first_name'] = $row[1];
    $_SESSION['last_name'] = $row[2];
    $_SESSION['email'] = $row[3];
    $_SESSION['street'] = $row[4];
    $_SESSION['city'] = $row[5];
    $_SESSION['state'] = $row[6];
    $_SESSION['zip'] = $row[7]; 
   
    mysqli_free_result($r);
    mysqli_close($dbc);

    //show confirm modal
    echo "<script type='text/javascript'>
    $(document).ready(function(){
    $('#confirm-modal').modal('show');
    });
    </script>
    <noscript class='text-center'>
      <h2 class='text-success'>You have successfully updated your profile.</h2>
    </noscript>";  
  } else {//update was not successful
    //create error message
    $message="<p>Your profile could not be updated at this time.</p>";
    //show error modal           
    echo "<script type='text/javascript'>
      $(document).ready(function(){
      $('#goback').modal('show');
      });
      </script>
      <noscript class='text-center'>
      <h2 class='text-danger'>$message</h2>
      </noscript>";
  } //end of update conditional
} //end of profile match conditional
} else {//validation fails
  echo "<script type='text/javascript'>
      $(document).ready(function(){
      $('#goback').modal('show');
      });
      </script>
      <noscript class='text-center'>
      <h2 class='text-danger'>$message</h2>
      </noscript>";  
}
} //end of submit conditional
?>

<div class="row">
  <div class="col-xs-11 title">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
  </div>
</div>

<div class="container">

  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h4><u>Your Profile Information</u></h4>
    </div>
  </div>

  <div class="row">

    <form class="col-md-8 col-md-offset-2" action="" method="post" id="profile">
      <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" class="form-control" name="firstname" value="<?php if (isset ($_SESSION['first_name'])) echo $_SESSION['first_name']; ?>">                      
      </div>

      <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" class="form-control" name="lastname" value="<?php if (isset ($_SESSION['last_name'])) echo $_SESSION['last_name']; ?>">                      
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" value="<?php if (isset ($_SESSION['email'])) echo $_SESSION['email']; ?>">
      </div> 

      <div class="form-group">
        <label for="street">Street Address</label>
        <input type="text" class="form-control" name="street" value="<?php if (isset ($_SESSION['street'])) echo $_SESSION['street']; ?>">
      </div>   

      <div class="form-group">
        <label for="city">City</label>
        <input type="text" class="form-control" name="city" value="<?php if (isset ($_SESSION['city'])) echo $_SESSION['city']; ?>">
      </div>

      <div class="form-group">
        <label for="state">State</label>
         <select name="state">
            <option disabled selected value>--select an option--</option>            
            <option value="AL" <?php if (isset($_SESSION['state']) && ($_SESSION['state'] == 'AL')) echo 'selected="selected"'; ?>>Alabama</option>
            <option value="AK" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'AK')) echo 'selected="selected"'; ?>>Alaska</option>
            <option value="AZ" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'AZ')) echo 'selected="selected"'; ?>>Arizona</option>
            <option value="AR" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'AZ')) echo 'selected="selected"'; ?>>Arkansas</option>
            <option value="CA" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'CA')) echo 'selected="selected"'; ?>>California</option>
            <option value="CO" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'CO')) echo 'selected="selected"'; ?>>Colorado</option>
            <option value="CT" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'CT')) echo 'selected="selected"'; ?>>Connecticut</option>
            <option value="DE" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'DE')) echo 'selected="selected"'; ?>>Delaware</option>
            <option value="DC" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'DC')) echo 'selected="selected"'; ?>>District Of Columbia</option>
            <option value="FL" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'FL')) echo 'selected="selected"'; ?>>Florida</option>
            <option value="GA" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'GA')) echo 'selected="selected"'; ?>>Georgia</option>
            <option value="HI" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'HI')) echo 'selected="selected"'; ?>>Hawaii</option>
            <option value="ID" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'ID')) echo 'selected="selected"'; ?>>Idaho</option>
            <option value="IL" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'IL')) echo 'selected="selected"'; ?>>Illinois</option>
            <option value="IN" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'IN')) echo 'selected="selected"'; ?>>Indiana</option>
            <option value="IA" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'IA')) echo 'selected="selected"'; ?>>Iowa</option>
            <option value="KS" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'KS')) echo 'selected="selected"'; ?>>Kansas</option>
            <option value="KY" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'KY'))echo 'selected="selected"'; ?>>Kentucky</option>
            <option value="LA" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'LA'))echo 'selected="selected"'; ?>>Louisiana</option>
            <option value="ME" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'ME'))echo 'selected="selected"'; ?>>Maine</option>
            <option value="MD" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MD'))echo 'selected="selected"'; ?>>Maryland</option>
            <option value="MA" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MA'))echo 'selected="selected"'; ?>>Massachusetts</option>
            <option value="MI" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MI'))echo 'selected="selected"'; ?>>Michigan</option>
            <option value="MN" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MN'))echo 'selected="selected"'; ?>>Minnesota</option>
            <option value="MS" <?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MS'))echo 'selected="selected"'; ?>>Mississippi</option>
            <option value="MO"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MO'))echo 'selected="selected"'; ?>>Missouri</option>
            <option value="MT"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'MT'))echo 'selected="selected"'; ?>>Montana</option>
            <option value="NE"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NE'))echo 'selected="selected"'; ?>>Nebraska</option>
            <option value="NV"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NV'))echo 'selected="selected"'; ?>>Nevada</option>
            <option value="NH"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NH'))echo 'selected="selected"'; ?>>New Hampshire</option>
            <option value="NJ"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NJ'))echo 'selected="selected"'; ?>>New Jersey</option>
            <option value="NM"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NM')) echo 'selected="selected"'; ?>>New Mexico</option>
            <option value="NY"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NY')) echo 'selected="selected"'; ?>>New York</option>
            <option value="NC"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'NC')) echo 'selected="selected"'; ?>>North Carolina</option>
            <option value="ND"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'ND')) echo 'selected="selected"'; ?>>North Dakota</option>
            <option value="OH"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'OH')) echo 'selected="selected"'; ?>>Ohio</option>
            <option value="OK"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'OK')) echo 'selected="selected"'; ?>>Oklahoma</option>
            <option value="OR"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'OR')) echo 'selected="selected"'; ?>>Oregon</option>
            <option value="PA"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'PA')) echo 'selected="selected"'; ?>>Pennsylvania</option>
            <option value="RI"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'RI')) echo 'selected="selected"'; ?>>Rhode Island</option>
            <option value="SC"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'SC')) echo 'selected="selected"'; ?>>South Carolina</option>
            <option value="SD"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'SD')) echo 'selected="selected"'; ?>>South Dakota</option>
            <option value="TN"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'TN')) echo 'selected="selected"'; ?>>Tennessee</option>
            <option value="TX"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'TX')) echo 'selected="selected"'; ?>>Texas</option>
            <option value="UT"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'UT')) echo 'selected="selected"'; ?>>Utah</option>
            <option value="VT"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'VT')) echo 'selected="selected"'; ?>>Vermont</option>
            <option value="VA"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'VA')) echo 'selected="selected"'; ?>>Virginia</option>
            <option value="WA"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'WA')) echo 'selected="selected"'; ?>>Washington</option>
            <option value="WV"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'WV')) echo 'selected="selected"'; ?>>West Virginia</option>
            <option value="WI"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'WI')) echo 'selected="selected"'; ?>>Wisconsin</option>
            <option value="WY"<?php if (isset ($_SESSION['state']) && ($_SESSION['state'] == 'WY')) echo 'selected="selected"'; ?>>Wyoming</option>
          </select>
      </div>   

      <div class="form-group">
        <label for="zip">Zipcode</label>
        <input type="text" class="form-control" name="zip" value="<?php if (isset ($_SESSION['zip'])) echo $_SESSION['zip']; ?>">
      </div>

      <div class="form-group pull-right">
        <button type="submit" class="btn btn-success">Save</button>
      </div> 

    </form>

  </div>

</div>

<!-- complete profile modal -->
<div id="profile-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="profileLabel">Complete Registration</h4>       
      </div>
      <div class="modal-body">
      <p>Please complete your registration by updating your profile.</p>
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="#" data-dismiss="modal">Close</a>        
      </div>
    </div>
  </div>
</div>

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="confirm-modalLabel">Success!</h4>        
      </div>
      <div class="modal-body">
      <p>Profile has been updated.</p>
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="#" data-dismiss="modal">Close</a>        
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
        <h4 class="modal-title text-center" id="gobackLabel">Error!</h4>        
      </div>
      <div class="modal-body">
      <p>There was an error updating your account.<?=$message?></p>
      </div>
      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="#" data-dismiss="modal">Go Back</a>
      </div>
    </div>
  </div>
</div>