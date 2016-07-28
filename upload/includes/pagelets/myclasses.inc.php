<?php //shows user's registered classes

//redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
  header('location: index.php?pagelet=index');
}

//redirect user if profile has not been completed
if (!$_SESSION['prof_id'] && $_SESSION['admin'] == 0) {
  header('location: index.php?pagelet=profile');
}
?>

<div class="row">
  <div class="col-xs-11 title">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
  </div>
</div>

<?php
if (isset($_GET['registered'])) {
  
  echo "<script type='text/javascript'>
  $(document).ready(function(){
  $('#confirm-modal').modal('show');
  });
  </script>
  <noscript class='text-center'>
    <h2 class='text-success'>The student is now registered.</h2>
  </noscript>";
}
?>

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

    $q = "SELECT COUNT(*) FROM participants WHERE user_id=$user_id";
    $r = mysqli_query ($dbc, $q);
    $row = mysqli_fetch_row ($r);
    $num_records = $row[0];

    if ($num_records == 0) {
      echo "<div class='text-center'><h2>You do not have any students registered at this time.</h2></div>";
    }
    
    // Calculate the number of pages.
    if ($num_records > $display) { // More than 1 page.
        $num_pages = ceil ($num_records/$display);
    } else {
        $num_pages = 1;
    }    
   
} // End of np IF.

$q = "SELECT c.class_id, c.loc_id, c.cat_id, c.class_name, c.class_desc, CONCAT('$', FORMAT(c.price, 2)) AS price, DATE_FORMAT(c.start_date, '%b %d, %Y') AS start_date, DATE_FORMAT(c.end_date, '%b %d, %Y') AS end_date, l.loc_name, k.cat_name, CONCAT(p.first_name, ' ', p.last_name) AS name, p.age, p.grade 
    FROM classes AS c         
    INNER JOIN location as l  
    ON c.loc_id=l.loc_id 
    INNER JOIN category as k
    ON c.cat_id=k.cat_id
    INNER JOIN participants as p
    ON c.class_id= p.class_id
    WHERE c.active='1' AND p.user_id='$user_id'
    ORDER BY c.class_name ASC
    LIMIT $start, $display";
$r = mysqli_query ($dbc, $q); // Run the query.

// Display records.
while ($row = mysqli_fetch_assoc($r)) {  
echo 
'<div class="row class-item">

  <div class="row">    
    <div class="col-sm-6 class-details">
      <h3><strong>' . $row['class_name'] . '</strong></h3>
      <h4 class="text-uppercase">' . $row['start_date'] . ' - ' . $row['end_date'] .'</h4>
      <h4>' . $row['loc_name'] . '</h4>
    </div>
    <div class="col-sm-6">
      <h4><u>Student Information</u></h4>
      <h4>' . $row['name'] . '</h4>
      <h4>Age ' . $row['age'] . '</h4>
      <h4>Grade ' . $row['grade'] . '</h4>
    </div>
  </div>

  <div class="row class-info">

    <div class="col-xs-12 text-right">
      <h4><strong>Amount Due: ' . $row['price'] . '</strong></h4>
    </div>    
    
  </div>

</div>';
}   

// Make the links to other pages, if necessary.
if ($num_pages > 1) {
    
    echo '<nav class="text-center">
            <ul class="pagination">';

    // Determine what page the script is on.    
    $current_page = ($start/$display) + 1;
    
    // If it's not the first page, make a Previous button.
    if ($current_page != 1) {                    
      //previous link 
        echo '<li>
                  <a href="index.php?pagelet=myclasses&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>';
      
    }
    
    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
          //pagination links
            echo '<li><a href="index.php?pagelet=myclasses&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
                        
        } else {
             echo '<li class="active"><a href="#">' .$i . '</a></li>';
         }
    }
    
    // If it's not the last page, make a Next button.
    if ($current_page != $num_pages) {
      //next link  
          echo '<li>
                <a href="index.php?pagelet=myclasses&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>'; 
                     
    }
    
    echo '</ul>
    </nav>';    
} // End of links section.

mysqli_close($dbc); //close the database connection
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