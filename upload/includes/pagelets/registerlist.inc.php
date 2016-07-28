<?php //admin class roster page

//redirect user if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['admin'] == 0) {
  header('location: index.php?pagelet=index');
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
    <div class="col-sm-12 col-md-10 col-md-offset-1">   

<?php
$user_id=$_SESSION['user_id'];

require ('../upload/mysqli_connect.php'); // Connect to the db.

// Number of records to show per page:
$display = 5;

// Determine where in the database to start returning results.
if (isset($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.

    $num_pages = $_GET['np'];

} else { // Need to determine.

    $q = "SELECT COUNT(*) FROM classes WHERE active='1' ORDER BY class_name ASC";
    $r = mysqli_query ($dbc, $q);
    $row = mysqli_fetch_row ($r);
    $num_records = $row[0];
    
    // Calculate the number of pages.
    if ($num_records > $display) { // More than 1 page.
        $num_pages = ceil ($num_records/$display);
    } else {
        $num_pages = 1;
    }    
   
} // End of np IF.
//make query
$q = "SELECT c.class_id, c.class_name, DATE_FORMAT(c.start_date, '%b %d, %Y') AS start_date, DATE_FORMAT(c.end_date, '%b %d, %Y') AS end_date, l.loc_name 
        FROM classes AS c         
        INNER JOIN location as l  
        ON c.loc_id=l.loc_id 
        WHERE c.active='1'
        ORDER BY c.class_name ASC
        LIMIT $start, $display";
$r = mysqli_query ($dbc, $q); // Run the query.

// Display class records.
while ($row = mysqli_fetch_assoc($r)) {
echo '<div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <ul class="list-group">
            <li class="list-group-item text-center">
              <h4 class="list-group-item-heading">' . $row['class_name'] . '</h4>
              <p class="list-group-item-heading text-uppercase">' . $row['start_date'] . ' - ' . $row['end_date'] .'<br />' . $row['loc_name'] . '</p>
            </li>';

      //echo student names for each class
      $query = "SELECT CONCAT(first_name, ' ', last_name) AS name, age, grade FROM participants WHERE       class_id='" . $row['class_id'] ."'";
      $result = mysqli_query ($dbc, $query);
      if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
      echo '<li class="list-group-item">
              ' . $row['name'] . '
                <br />Age ' . $row['age'] . ' 
                <br />Grade ' . $row['grade'] . '
              
            </li>';
      }   
    } else {
      echo '<li class="list-group-item">There are no students registered for this class.</li>';
    }        
    echo '</ul>
      </div>
    </div>';
}   

mysqli_free_result($r);
mysqli_close($dbc); //close the database connection

// Make the links to other pages, if necessary.
if ($num_pages > 1) {
    
    echo '<div class="row">
    <nav class="text-center">
            <ul class="pagination">';

    // Determine what page the script is on.    
    $current_page = ($start/$display) + 1;
    
    // If it's not the first page, make a Previous button.
    if ($current_page != 1) {                    
      //previous link for default view
        echo '<li>
                  <a href="index.php?pagelet=registerlist&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>';
      
    }
    
    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
          //pagination links
            echo '<li><a href="index.php?pagelet=registerlist&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
                        
        } else {
             echo '<li class="active"><a href="#">' .$i . '</a></li>';
         }
    }
    
    // If it's not the last page, make a Next button.
    if ($current_page != $num_pages) {
      //next link  
          echo '<li>
                <a href="index.php?pagelet=registerlist&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>'; 
                     
    }
    
    echo '</ul>
    </nav>
  </div>';    
} // End of links section.
?>
      
    </div><!-- col-sm-12 -->
  </div><!-- row -->
</div><!-- container -->

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Success!</h4>        
      </div>
      <div class="modal-body text-center">
      <p>The student has been registered.</p>
      </div>
      <div class="modal-footer">  
          <a class="btn btn-primary" href="index.php?pagelet=myclasses">Go to class list</a>
      </div>
    </div>
  </div>
</div>