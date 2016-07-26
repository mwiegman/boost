<?php //redirect user if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['admin'] == 0) {
  header('location: index.php?pagelet=index');
}

if (isset($_SESSION['add_class'])) {//confirmation when returning to page after successful form submit
  echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal('show');
          });
          </script>
          <noscript class='text-center'>
            <h2>You have successfully added a class.</h2>
            <br>
              <div class='text-center'>
              <a class='btn btn-primary' href='index.php?pagelet=classes'>Go to class list</a>  
              <a  class='btn btn-primary' href='index.php?pagelet=addclass'>Add another class</a>
              </div>        
          </noscript>";

  unset($_SESSION['add_class']);
}
?>

<div class="col-sm-12">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
</div>

<div class="container">

  <div class="row"/>
<?php     
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.
   
    require_once ('../upload/mysqli_connect.php'); // Connect to the db.

    //validate form fields
    $message = NULL; // message variable.    

    // Trim all the incoming data:
    $trimmed = array_map('trim', $_POST);

    // Assume invalid values:
    $c = $s = $e = $p = $l = $cat = $d = FALSE;
    
    // Check for a class name.
    if (!empty($_POST['classname'])) {
      $c = mysqli_real_escape_string ($dbc, $trimmed['classname']);
    } else {
        $message .= '<p>Please enter a class name.</p>';
    }

    // Check for a start date.
    if (!empty($_POST['startdate'])) {
      $s = mysqli_real_escape_string ($dbc, $trimmed['startdate']);
    } else {
        $message .= '<p>Please enter a start date.</p>';
    }
    
    // Check for an end date.
    if (!empty($_POST['enddate'])) {
      $e = mysqli_real_escape_string ($dbc, $trimmed['enddate']);
    } else {
        $message .= '<p>Please enter an end date.</p>';
    }
    
    // Check for a price.
    if (!empty($_POST['price'])) {
      $p = mysqli_real_escape_string ($dbc, $trimmed['price']);
    } else {
        $message .= '<p>Please enter a price.</p>';
    }
    
    // Check for a location.
    if (!empty($_POST['location'])) {
      $l = mysqli_real_escape_string ($dbc, $trimmed['location']);
    } else {
        $message .= '<p>Please enter a location.</p>';
    }

    // Check for a category.
    if (!empty($_POST['location'])) {
      $cat = mysqli_real_escape_string ($dbc, $trimmed['location']);
    } else {
        $message .= '<p>Please enter a location.</p>';
    }
    
    // Check for a description.
    if (!empty($_POST['desc'])) {
      $d = mysqli_real_escape_string ($dbc, $trimmed['desc']);
    } else {
        $message .= '<p>Please enter a description.</p>';
    }

    if ($c && $s && $e && $p && $l && $cat && $d) {//everything's ok 

    echo $s . ' ' . $e;

    //insert record
    $q = "INSERT INTO classes (loc_id, cat_id, class_name, class_desc, start_date, end_date, price) VALUES ('$l', '$cat', '$c', '$d', '$s', '$e', '$p')";
    $r = mysqli_query ($dbc, $q);       

    if (mysqli_affected_rows($dbc) == 1) {//insert successful
      //show confirmation message
      echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#confirm-modal').modal('show');
        });
        </script>
        <noscript class='text-center'>
          <h2>You have successfully added a class.</h2>
          <br>
            <div class='text-center'>
            <a class='btn btn-primary' href='index.php?pagelet=classes'>Go to class list</a>  
            <a  class='btn btn-primary' href='index.php?pagelet=addclass'>Add another class</a>
            </div>        
        </noscript>"; 

      header('location: index.php?pagelet=addclass');
      $_SESSION['add_class'] = TRUE;

      //close database connection
      mysqli_close($dbc); 

      } else {//insert failed
        //show error message
        echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#goback').modal('show');
        });
        </script>
        <noscript class='text-center'>
        <h2>There was an error creating this class. Please try again." . mysqli_error($dbc) .  "</h2>
        <div class='text-danger'>$message </div></noscript>";
      }

    } else {//missing form fields
      //display error message
      echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#goback').modal('show');
        });
        </script>
        <noscript class='text-center'>          
        <div class='text-danger'>$message</div>
        <div class='text-center'><a class='btn btn-primary' href='index.php?pagelet=addclass'>Go Back</a></div></noscript>";
    }

} else {//always show form   
?>
    <form class="col-md-6 col-md-offset-3" action="" method="post" id="addclass">

      <div class="form-group">
        <label for="classname">Class Title</label>
        <input type="text" class="form-control" name="classname" value="<?php if (isset ($_POST['classname'])) echo stripslashes($_POST['classname']); ?>">                      
      </div>
      <div class="form-group">
        <label for="startdate">Start Date <small>(Date format is YYYY-MM-DD)</small></label>
        <input type="date" class="form-control" name="startdate">                      
      </div>
      <div class="form-group">
        <label for="enddate">End Date <small>(Date format is YYYY-MM-DD)</small></label>
        <input type="date" class="form-control" name="enddate" >
      </div> 
      <div class="form-group">
        <label class="sr-only" for="price">Amount (in dollars)</label>
        <div class="input-group">
          <div class="input-group-addon">$</div>
          <input type="text" class="form-control" name="price" placeholder="Amount" value="<?php if (isset ($_POST['price'])) echo $_POST['price']; ?>"> 
          <div class="input-group-addon">.00</div>         
        </div>
      </div>                  
     <div class="form-group">
      <label for="location">Location </label>
       <select name="location">
          <option disabled selected value>--select an option--</option>            
          <option value="1" <?php if (isset($_POST['location']) && ($_POST['location'] == '1')) echo 'selected="selected"';?> >Cypress Springs Elementary</option>
          <option value="2" <?php if (isset ($_POST['location']) && ($_POST['location'] == '2')) echo 'selected="selected"';?> >Arbor Ridge K-8</option>
          <option value="3" <?php if (isset ($_POST['location']) && ($_POST['location'] == '3')) echo 'selected="selected"';?> >Andover Elementary</option>
        </select>
     </div>   
     <div class="form-group">
      <label for="category">Category </label>
       <select name="category">
          <option disabled selected value>--select an option--</option>            
          <option value="1" <?php if (isset($_POST['category']) && ($_POST['category'] == '1')) echo 'selected="selected"';?> >Sports</option>
          <option value="2" <?php if (isset ($_POST['category']) && ($_POST['category'] == '2')) echo 'selected="selected"';?> >Music</option>            
          <option value="3" <?php if (isset ($_POST['category']) && ($_POST['category'] == '3')) echo 'selected="selected"';?> >Art</option>  
        </select>
     </div>   
     <div class="form-group">
        <label for="desc">Class Description</label>
        <textarea rows="10" cols="50" class="form-control" name="desc"><?php if (isset ($_POST['desc'])) echo stripslashes($_POST['desc']); ?></textarea>
      </div>
     <div class="form-group pull-right">
      <button type="submit" class="btn btn-success">Save</button>
     </div> 
    </form>
<?php 
}
?>
  </div>
</div>

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Success!</h4>        
      </div>

      <div class="modal-body">
        <p>You have successfully added a class.</p>
      </div>

      <div class="modal-footer">  
          <a class="btn btn-primary" href="index.php?pagelet=classlist">Go to class list</a>  
          <a class="btn btn-primary" href="index.php?pagelet=addclass">Add another class</a>      
      </div>
    </div>

  </div>
</div>

<!-- goback modal -->
<div id="goback" class="modal fade" tabindex="-1">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Error!</h4>        
      </div>

      <div class="modal-body">
      <p>There was an error adding this class.</p>
      </div>

      <div class="modal-footer">  
          <a class="btn btn-primary" href="index.php?pagelet=addclass">Go Back</a>
      </div>
    </div>

  </div>
</div>