<?php //class registration page

//redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}

//redirect user if profile has not been completed
if (!isset($_SESSION['prof_id']) && $_SESSION['admin'] == 0) {
  header('location: index.php?pagelet=profile');
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">  
    <div class="col-sm-6">      
<?php
//set user and class variables
$user_id = $_SESSION['user_id'];
$class_id= $_GET['class'];

require_once ('../upload/mysqli_connect.php'); // Connect to the db.
//make the query
$q = "SELECT c.class_id, c.loc_id,c.class_name, c.class_desc, CONCAT('$', FORMAT(c.price, 2)) AS price, DATE_FORMAT(c.start_date, '%b %d, %Y') AS start_date, DATE_FORMAT(c.end_date, '%b %d, %Y')AS end_date, l.loc_name
  FROM classes AS c         
  INNER JOIN location as l  
  ON c.loc_id=l.loc_id         
  WHERE c.class_id='$class_id'";
  $r = mysqli_query ($dbc, $q); // Run the query.

//Display class information
while ($row = mysqli_fetch_assoc($r)) {
echo '<h4><u>Class Information</u></h4>
        <ul class="list-unstyled">
          <li><h4 class="class-title">' . $row['class_name'] . '</h4><li>
          <li class="text-uppercase">' . $row['start_date'] . ' - ' . $row['end_date'] .'</li>
          <li>' . $row['loc_name'] . '</li>
          <li>Price: ' . $row['price'] . '</li>            
        </ul> ';
}
?>
</div>
    <div class='col-sm-6'>
<?php
//display profile information
$q="SELECT p.prof_id, CONCAT_WS (' ', p.first_name, p.last_name) AS name, u.email, p.street, p.city, p.state, p.zip FROM profiles AS p INNER JOIN users as u ON p.user_id=u.user_id AND u.user_id = '$user_id'";  
$r = mysqli_query ($dbc, $q); 

while ($row = mysqli_fetch_assoc($r)) {
echo "<h4><u>Your Profile Information</u></h4>
        <address>
          <strong>Name</strong><br>". $row['name'] . "<br>
          <strong>Email</strong><br>". $row['email'] . "<br><strong>Address</strong><br>". $row['street'] . "<br>". $row['city'] . ", ". $row['state'] ." " .  $row['zip'] . "</address>
      ";
}
?>
    </div>    
  </div><!-- row -->
  <div class="row">
    <?php 
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {//if form is submitted

      //validate form fields
      $message = NULL; // message variable.

      //trim incoming data
      $trimmed = array_map('trim', $_POST);

      // Check for a first name.
      if (empty($_POST['firstname'])) {
          $f = FALSE;
          $message .= '<p>You did not enter a first name.</p>';
      } else {
          $f = mysqli_real_escape_string ($dbc, $trimmed['firstname']);
      }

      // Check for a last name.
      if (empty($_POST['lastname'])) {
          $l = FALSE;
          $message .= '<p>You did not enter a last name.</p>';
      } else {
          $l = mysqli_real_escape_string ($dbc, $trimmed['lastname']);
      }  

      // Check for an age.
      if (empty($_POST['age'])) {
          $a = FALSE;
          $message .= '<p>You did not enter an age.</p>';
      } else {
          $a = mysqli_real_escape_string ($dbc, $trimmed['age']);
      } 

      // Check for a grade.
      if (empty($_POST['grade'])) {
          $g = FALSE;
          $message .= '<p>You did not enter a grade.</p>';
      } else {
          $g = mysqli_real_escape_string ($dbc, $trimmed['grade']);
      }
    
      if ($f && $l && $a && $g){//validation passes
        //check for student in database
        $q= "SELECT * FROM participants WHERE class_id='$class_id' AND user_id='$user_id' AND first_name='$f' AND last_name='$l'";
        $r = mysqli_query ($dbc, $q);          

        if (mysqli_num_rows($r) == 0) {//register student if not found
        $q = "INSERT INTO participants (user_id, first_name, last_name, age, grade, class_id) VALUES ('$user_id', '$f', '$l', '$a', '$g', '$class_id')";
        $r = mysqli_query ($dbc, $q);

          if (mysqli_affected_rows($dbc) == 1) {
            //redirect to myclasses page
            header ('location: index.php?pagelet=myclasses&registered=true');              
          } else {
            //show registration error
            $message="This student could not be registered at this time due to a system error.".  mysqli_error($dbc) ." We apologize for the inconvenience." ;
            echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#goback').modal('show');
            });
            </script>
            <noscript class='text-center'>
            <h2 class='text-danger'>$message</h2></noscript>";
          }      
        } else {//student already registered for class
          //show error message
        $message="This student is already registered for the selected class. Please register another student.";
        echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#goback').modal('show');
        });
        </script>
        <noscript class='text-center'>
        <h2 class='text-danger'>$message</h2></noscript>";        
        }
      } else {//validation fails
        //show error message 
        echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#goback').modal('show');
            });
            </script>
            <noscript class='text-center'>
            <h2 class='text-danger'>$message</h2></noscript>";
      }
    } 
  ?>
  
    <div class="col-xs-12 col-md-7 col-md-offset-2">
      <h4><u>Student Information</u></h4>
        <form action="" method="post" id="participant">   

        <div class="student-form">

          <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" class="form-control" name="firstname">                      
          </div>

          <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" class="form-control" name="lastname">                      
          </div>

          <div class="form-group">
            <label for="age">Age</label>
            <select name="age">
              <option disabled selected value>--select an option--</option> 
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option> 
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>           
            </select>
          </div> 

          <div class="form-group">
            <label for="grade">Grade</label>
            <select name="grade">
              <option disabled selected value>--select an option--</option>            
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>            
            </select>
          </div>  

          <div class="form-group row text-center">
            <br />
            <button type="submit" class="btn btn-success btn-lg"> + Register</button>          
          </div>

        </div><!-- student form -->       

      </form>

    </div><!-- col -->
  </div><!-- row -->
</div><!-- container -->
  
<!-- goback modal -->
<div id="goback" class="modal fade">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Error!</h4>        
      </div>

      <div class="modal-body">        
        <p class="text-danger"><?php echo "$message"?></p>
      </div>

      <div class="modal-footer">  
          <a class="btn btn-primary" data-dismiss="modal">Go Back</a>
      </div>

    </div>

  </div>
</div>