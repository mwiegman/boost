<?php //redirect user if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['admin'] == 0) {
  header('location: index.php?pagelet=index');
}
?>

<div class="jumbotron">
  <div class="container text-center">
    <div class="row">
      <h1 class="boost">Boost</h1>
      <h2>After School Enrichment</h2>
    </div>  
  </div>
</div>

<div class="container">

<?php
  require ('../upload/mysqli_connect.php'); // Connect to the db.

  //set class as inactive if admin delete
  if (isset($_GET['confirm'])) {

  $q = "UPDATE classes SET active='0' WHERE class_id='". $_GET['class'] . "'";
  $r = mysqli_query ($dbc, $q); // Run the query. 

  if (mysqli_affected_rows($dbc) == 1) {//display confirmation message
  echo "<div class='row text-center'>
          <div class='col-sm-12'>
            <h2 class='text-success'>This class has been deleted.</h2>
          </div>
        </div> 
        <div class='row text-center'> 
          <div class='col-sm-12'>
            <a class='btn btn-primary' href='index.php?pagelet=classlist'>Go Back</a> 
          </div>
        </div>";
  mysqli_close($dbc); //close the database connection
  } else {//show error if class status could not be changed

    echo "<div class='row text-center'>
            <h2 class='text-danger'>This class could not be deleted at this time.</h2>
          </div> 
          <div class='row text-center'>  
            <a class='btn btn-primary' href='index.php?pagelet=classlist'>Go Back</a>        
          </div>"; 
  }  
} 

//delete class if user deletes from saved class list
if (isset($_GET['saved_confirm'])) {

  $q = "DELETE FROM saved WHERE class_id='". $_GET['class'] . "' AND user_id='". $_SESSION['user_id'] . "'";
  $r = mysqli_query ($dbc, $q); // Run the query. 

  if (mysqli_affected_rows($dbc) == 1) {//display confirmation message

    header('location: index.php?pagelet=saved');
    mysqli_close($dbc); //close the database connection
  } else {//shower error if class status could not be changed

    echo "<div class='row text-center'>
            <h2 class='text-danger'>There was an error handling your request.</h2>
          </div> 
          <div class='row text-center'>  
            <a class='btn btn-primary' href='index.php?pagelet=saved'>Go Back</a>        
          </div>"; 
  }  
} 

?>
</div>