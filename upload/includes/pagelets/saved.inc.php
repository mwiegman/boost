<?php //display user's saved classes

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

    $q = "SELECT COUNT(*) FROM saved WHERE user_id=$user_id";
    $r = mysqli_query ($dbc, $q);
    $row = mysqli_fetch_row ($r);
    $num_records = $row[0];

    if ($num_records == 0) {
      echo "<div class='text-center'><h2>You do not have any saved classes at this time.</h2></div>";
    }
    
    // Calculate the number of pages.
    if ($num_records > $display) { // More than 1 page.
        $num_pages = ceil ($num_records/$display);
    } else {
        $num_pages = 1;
    }    
   
} // End of np IF.
//make the query
$q = "SELECT c.class_id, c.loc_id, c.cat_id, c.class_name, c.class_desc, CONCAT('$', FORMAT(c.price, 2)) AS price, DATE_FORMAT(c.start_date, '%b %d, %Y') AS start_date, DATE_FORMAT(c.end_date, '%b %d, %Y') AS end_date, l.loc_name, k.cat_name 
    FROM classes AS c         
    INNER JOIN location as l  
    ON c.loc_id=l.loc_id 
    INNER JOIN category as k
    ON c.cat_id=k.cat_id
    INNER JOIN saved as s
    ON c.class_id= s.class_id
    WHERE c.active='1' AND s.user_id='$user_id'
    ORDER BY c.class_name ASC
    LIMIT $start, $display";
$r = mysqli_query ($dbc, $q); // Run the query.

// Display records.
while ($row = mysqli_fetch_assoc($r)) {
  $class_id= $row['class_id'];
  $cat_id= $row['cat_id'];
echo 
'<div class="row class-item">
  <div class="col-sm-12">
  <div class="row">    
    <div class="col-sm-12">
      <h4 class="text-uppercase">' . $row['start_date'] . ' - ' . $row['end_date'] .'</h4>
      <h3><strong>' . $row['class_name'] . '</strong></h3>
      <p>' . $row['class_desc'] . '</p>
    </div>
  </div>

  <div class="row class-info">
    <div class="col-sm-4 col-xs-6">
      <h4>Price: ' . $row['price'] . '</h4>
    </div>
    <div class="col-sm-6 col-xs-6 border-left">
      <h4>' . $row['loc_name'] . '</h4>
    </div>
    <div class="col-sm-1 col-xs-6 icon-link border-left text-center">
      <a href="index.php?pagelet=deleteclass&class=' . $row['class_id'] . '&saved_confirm=true" data-toggle="tooltip" title="Remove" class="text-danger"><span class="entypo-trash" ></span></a>
    </div>
    <div class="col-sm-1 col-xs-6 icon-link border-left text-center">
      <a href="index.php?pagelet=register&class=' . $row['class_id'] .'" data-toggle="tooltip" title="Register" class="plus"><span class="entypo-plus-squared"></span></a>
    </div>
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
      //previous link for default view
        echo '<li>
                  <a href="index.php?pagelet=saved&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>';
      
    }
    
    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
          //pagination links
            echo '<li><a href="index.php?pagelet=saved&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
                        
        } else {
             echo '<li class="active"><a href="#">' .$i . '</a></li>';
         }
    }
    
    // If it's not the last page, make a Next button.
    if ($current_page != $num_pages) {
      //next link  
          echo '<li>
                <a href="index.php?pagelet=saved&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
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
<!-- bootstrap tooltip -->
<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>