<?php //This page allows the admin to edit class details

//redirect user if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['admin'] == 0) {
  header('location: index.php?pagelet=index');
}

if (isset($_SESSION['mod_class'])) {
  echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal('show');
          });
          </script>
          <noscript class='text-center'>
            <h2>This class has been edited.</h2>
            <br>
              <div class='text-center'>
              <a class='btn btn-primary' href='index.php?pagelet=classes'>Go to class list</a>  
              <a  class='btn btn-primary' href='index.php?pagelet=addclass'>Add another class</a>
              </div>        
          </noscript>";

  unset($_SESSION['mod_class']);
}

if (isset($_SESSION['delete_class'])) {
  echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal('show');
          });
          </script>
          <noscript class='text-center'>
            <h2>This class has been deleted</h2>
            <br>
              <div class='text-center'>
              <a class='btn btn-primary' href='index.php?pagelet=classes'>Go to class list</a>  
              <a  class='btn btn-primary' href='index.php?pagelet=addclass'>Add another class</a>
              </div>        
          </noscript>";

  unset($_SESSION['delete_class']);
}

//from view classes page
$class_id = $_GET['class'];//get class id from GET variable

require ('../upload/mysqli_connect.php'); // Connect to the db.

//check if class id is set
if (isset($class_id)) {
//select class values  
$q = "SELECT c.class_id, c.loc_id, c.cat_id, c.class_name, c.class_desc, CONCAT(FORMAT(c.price, 0)) AS price, DATE_FORMAT(c.start_date, '%Y-%m-%d') AS start_date, DATE_FORMAT(c.end_date, '%Y-%m-%d') AS end_date, l.loc_name, k.cat_name 
  FROM classes AS c         
  INNER JOIN location as l  
  ON c.loc_id=l.loc_id 
  INNER JOIN category as k
  ON c.cat_id=k.cat_id
  WHERE c.class_id='$class_id'";
$r = mysqli_query ($dbc, $q); 

if (mysqli_num_rows($r) == 1) { // A match was made.
  //set session variables with class values
  $row = mysqli_fetch_assoc ($r);        
  $_SESSION['class_name'] = $row['class_name'];   
  $_SESSION['class_desc'] = $row['class_desc'];
  $_SESSION['price'] = $row['price'];
  $_SESSION['start_date'] = $row['start_date'];
  $_SESSION['end_date'] = $row['end_date'];
  $_SESSION['location'] = $row['loc_id'];
  $_SESSION['category'] = $row['cat_id']; 
  }
  mysqli_free_result($r);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form. 

$message = NULL; // message variable.    

//trim incoming data
$trimmed = array_map('trim', $_POST);

// Check for a class name.
if (empty($_POST['classname'])) {
    $c = FALSE;
    $message .= '<p>You did not enter a class title.</p>';
} else {
    $c = mysqli_real_escape_string ($dbc, $trimmed['classname']);
}

// Check for a start date.
if (empty($_POST['startdate'])) {
    $s = FALSE;
    $message .= '<p>You did not enter a start date.</p>';
} else {
    $s = mysqli_real_escape_string ($dbc, $trimmed['startdate']);
}  

// Check for an end date.
if (empty($_POST['enddate'])) {
    $e = FALSE;
    $message .= '<p>You did not enter an end date.</p>';
} else {
    $e = mysqli_real_escape_string ($dbc, $trimmed['enddate']);
} 

// Check for a price.
if (empty($_POST['price'])) {
    $p = FALSE;
    $message .= '<p>You did not enter a price.</p>';
} else {
    $p = mysqli_real_escape_string ($dbc, $trimmed['price']);
}

// Check for a location.
if (empty($_POST['location'])) {
    $l = FALSE;
    $message .= '<p>You did not enter a location.</p>';
} else {
    $l = mysqli_real_escape_string ($dbc, $trimmed['location']);
} 

// Check for a category.
if (empty($_POST['category'])) {
    $l = FALSE;
    $message .= '<p>You did not enter a location.</p>';
} else {
    $cat = mysqli_real_escape_string ($dbc, $trimmed['category']);
} 

// Check for a description.
if (empty($_POST['desc'])) {
    $d = FALSE;
    $message .= '<p>You did not enter a class description.</p>';
} else {
    $d = mysqli_real_escape_string ($dbc, $trimmed['desc']);
} 

if ($c && $s && $e && $p && $l && $cat && $d )   {//validation passes   
  //update record   
  $q = "UPDATE classes SET loc_id='$l', cat_id='$cat', class_name='$c', class_desc='$d', start_date='$s', end_date='$e', price='$p' WHERE class_id = '$class_id'";
  $r = mysqli_query ($dbc, $q);
  
  if (mysqli_affected_rows($dbc) == 1) {
  //reset session variables  
  $q = "SELECT c.class_id, c.loc_id, c.cat_id, c.class_name, c.class_desc, CONCAT(FORMAT(c.price, 0)) AS price, DATE_FORMAT(c.start_date, '%Y-%m-%d') AS start_date, DATE_FORMAT(c.end_date, '%Y-%m-%d') AS end_date, l.loc_name, k.cat_name 
  FROM classes AS c         
  INNER JOIN location as l  
  ON c.loc_id=l.loc_id 
  INNER JOIN category as k
  ON c.cat_id=k.cat_id
  WHERE c.class_id='$class_id'";
  $r = mysqli_query ($dbc, $q); 

  $row = mysqli_fetch_assoc ($r);  
  $_SESSION['class_id'] = $row['class_id'];      
  $_SESSION['class_name'] = $row['class_name'];   
  $_SESSION['class_desc'] = $row['class_desc'];
  $_SESSION['price'] = $row['price'];
  $_SESSION['start_date'] = $row['start_date'];
  $_SESSION['end_date'] = $row['end_date'];
  $_SESSION['location'] = $row['loc_id'];
  $_SESSION['category'] = $row['cat_id'];

  mysqli_free_result($r);
  mysqli_close($dbc);

  //show confirmation message
  echo "<script type='text/javascript'>
    $(document).ready(function(){
    $('#confirm-modal').modal('show');
    });
    </script>    
    <noscript class='text-center'>
      <h2>You have successfully modified this class.</h2>
      <br>
        <div class='text-center'>
        <a class='btn btn-primary' href='index.php?pagelet=classes'>Go to class list</a>  
        <a class='btn btn-primary' href='index.php?pagelet=modifyclass'>Edit another class</a>
        </div>        
    </noscript>"; 
    } else {//update was unsuccessful. 
      //create error message
      $message="<p>This class could not be updated at this time." . mysqli_error($dbc);
      //show error modal
      echo "<script type='text/javascript'>
      $(document).ready(function(){
      $('#goback').modal('show');
      });
      </script>
      <noscript class='text-center'>
      <h2>There was an error modifying this class</h2>
      <div class='text-danger'>" .$message. "</div>
      </noscript>"; 
    }//end of update conditional
  } else { //validation fails     
    //show error modal           
    echo "<script type='text/javascript'>
      $(document).ready(function(){
      $('#goback').modal('show');
      });
      </script>
      <noscript class='text-center'>
      <h2>$message</h2>
      <div class='text-danger'>" .$message. "</div>
      </noscript>";          
  }//end of validation conditional           
} // End of post conditional. Always display the form.
?>
<div class="row">
  <div class="col-xs-11 title">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
  </div>
</div>

<div class="container">
  <div class="row">
    <form class="col-md-6 col-md-offset-3" action="" method="post" id="modifyclass">
      <div class="form-group">
        <label for="classname">Class Title</label>
        <input type="text" class="form-control" name="classname" value="<?php if (isset ($_SESSION['class_name'])) echo $_SESSION['class_name']; ?>">                      
      </div>
      <div class="form-group">
        <label for="startdate">Start Date <small>(Date format is YYYY-MM-DD)</small></label>
        <input type="date" class="form-control" name="startdate" value="<?php if (isset ($_SESSION['start_date'])) echo $_SESSION['start_date']; ?>">                      
      </div>
      <div class="form-group">
        <label for="enddate">End Date <small>(Date format is YYYY-MM-DD)</small></label>
        <input type="date" class="form-control" name="enddate" value="<?php if (isset ($_SESSION['end_date'])) echo $_SESSION['end_date']; ?>">
      </div> 
      <div class="form-group">
        <label class="sr-only" for="price">Amount (in dollars)</label>
        <div class="input-group">
          <div class="input-group-addon">$</div>
          <input type="text" class="form-control" name="price" placeholder="Amount" value="<?php if (isset ($_SESSION['price'])) echo $_SESSION['price']; ?>">
          <div class="input-group-addon">.00</div>
        </div>
      </div>            
     <div class="form-group">
      <label for="location">Location </label>
       <select name="location">
          <option disabled selected value>--select an option--</option>            
          <option value="1" <?php if (isset($_SESSION['location']) && ($_SESSION['location'] == '1')) echo 'selected="selected"'; ?>>Cypress Springs Elementary</option>
          <option value="2" <?php if (isset ($_SESSION['location']) && ($_SESSION['location'] == '2')) echo 'selected="selected"'; ?>>Arbor Ridge School</option>  
          <option value="3" <?php if (isset ($_SESSION['location']) && ($_SESSION['location'] == '3')) echo 'selected="selected"'; ?>>Andover Elementary</option>            
        </select>
     </div>
     <div class="form-group">
      <label for="category">Category </label>
       <select name="category">
          <option disabled selected value>--select an option--</option>            
          <option value="1" <?php if (isset($_SESSION['category']) && ($_SESSION['category'] == '1')) echo 'selected="selected"';?> >Sports</option>
          <option value="2" <?php if (isset ($_SESSION['category']) && ($_SESSION['category'] == '2')) echo 'selected="selected"';?> >Music</option>            
          <option value="3" <?php if (isset ($_SESSION['category']) && ($_SESSION['category'] == '3')) echo 'selected="selected"';?> >Art</option>  
        </select>
     </div>   
     <div class="form-group">
        <label for="desc">Class Description</label>
        <textarea rows="10" cols="50" class="form-control" name="desc"><?php if (isset ($_SESSION['class_desc'])) echo $_SESSION['class_desc']; ?></textarea>
      </div>
      <div class="form-group pull-right">
      <!-- <button type="submit" class="btn btn-primary">Delete</button> -->
      <button type="submit" class="btn btn-success">Update</button>
     </div> 
    </form>        
  </div>
</div>

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Success!</h4>        
      </div>
      <div class="modal-body text-center">
      <p>This class has been modified.</p>
      </div>
      <div class="modal-footer">  
          <a class="btn btn-primary" href="index.php?pagelet=classlist">Go to class list</a>
      </div>
    </div>
  </div>
</div>

<!-- goback modal -->
<div id="goback" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Error!</h4>        
      </div>
      <div class="modal-body">
      <p>There was an error modifying this class.</p>      
      </div>
      <div class="modal-footer">  
          <a class="btn btn-primary" data-dismiss="modal">Go Back</a>
      </div>
    </div>
  </div>
</div>