<div class="jumbotron">
  <div class="container text-center">
    <div class="row">
      <h1 class="boost">Boost</h1>
      <h2>After School Enrichment</h2>
    </div>  
  </div>
</div>

<div class="col-sm-12">
    <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm-4 col-md-3">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">

                <div class="panel-heading">

                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Filter by Location <span class="caret"></span></a>
                    </h4>

                </div><!-- panel-heading -->

                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">

                        <table class="table">

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&filter=cse">Cypress Springs Elementary</a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&filter=ars">Arbor Ridge K-8</a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&filter=ae">Andover Elementary</a>
                                </td>
                            </tr>   

                        </table>

                    </div><!-- panel-body -->

                </div><!-- collpase-one -->

            </div><!-- panel -->
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Filter by Category <span class="caret"></span></a>
                    </h4>
                </div><!-- panel-heading -->

                <div id="collapseTwo" class="panel-collapse collapse">

                    <div class="panel-body">

                        <table class="table">

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&filter=sports">Athletics</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&filter=music">Music</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&filter=art">Art</a>
                                </td>
                            </tr>

                        </table>

                    </div><!-- panel-body -->

                </div><!-- collapseTwo -->

            </div><!-- panel --> 
             <div class="panel panel-default">

                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Sort <span class="caret"></span></a>
                    </h4>
                </div><!-- panel-heading -->

                <div id="collapseThree" class="panel-collapse collapse">

                    <div class="panel-body">

                        <table class="table">

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&sort=sd">Starting Date</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&sort=pl">Price (Low to High)</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classes&sort=ph">Price(High to Low)</a>
                                </td>
                            </tr>

                        </table>

                    </div><!-- panel-body -->

                </div><!-- collapseTwo -->

            </div><!-- panel --> 

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a href="index.php?pagelet=classes">
                        Show All</a>
                    </h4>
                </div>
            </div>

        </div><!-- panel-group -->
    </div>

    <div class="col-sm-8 col-md-9">

<?php 
//show confirmation for adding class to saved list
if (isset($_GET['saved'])) {
   echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#confirm-modal').modal('show');
          });
          </script>
          <noscript class='text-center'>
          <h2 class='text-success'>This class has been saved. Go to <a href='index.php?pagelet=saved'>Saved Classes</a> to view.</h2></noscript>";
}

// display classes

require ('../upload/mysqli_connect.php'); // Connect to the db.

// Determine which records to show
if (isset($_GET['filter']))  {

switch ($_GET['filter'])  {//display class records according to selected filter
    case 'cse';
      $and="AND l.loc_id='1'";   
      $where = "AND loc_id='1'";        
      break;        
    case 'ars';
        $and = "AND l.loc_id='2'";
        $where = "AND loc_id='2'";            
        break;
    case 'ae';
        $and = "AND l.loc_id='3'";
        $where = "AND loc_id='3'";
        break;
    case 'sports';
        $and = "AND k.cat_id='1'";
        $where = "AND cat_id='1'";            
        break;
    case 'music';
       $and = "AND k.cat_id='2'";
       $where = "AND cat_id='2'";
       break;
    case 'art';
        $and = "AND k.cat_id='3'";
        $where = "AND cat_id='3'";
        break;
    default;
        $and = "";
        $where = "";
        break;
}

    //  Append $filter to the pagination links
    $filter = $_GET['filter'];

} else {  // show all classes by default
    $and = "";
    $where = "";
}

if (isset($GET['sort'])) {
  switch($GET['sort']) {
    case 'sd';
      $sort = "ORDER BY start_date ASC";
      break;
    case 'ph';
      $sort = "ORDER BY price DESC";
      break;
    case 'pl';
      $sort = "ORDER BY price ASC";
      break;
    default;
      $sort = "ORDER BY c.class_name ASC";
      break;    
  }
} else {
  $sort = "ORDER BY c.class_name ASC";
}

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

    $q = "SELECT COUNT(*) FROM classes WHERE active='1' $where ORDER BY class_name ASC";
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

// Make the query.
$q = "SELECT c.class_id, c.loc_id, c.cat_id, c.class_name, c.class_desc, CONCAT('$', FORMAT(c.price, 2)) AS price, DATE_FORMAT(c.start_date, '%b %d, %Y') AS start_date, DATE_FORMAT(c.end_date, '%b %d, %Y') AS end_date, l.loc_name, k.cat_name 
        FROM classes AS c         
        INNER JOIN location as l  
        ON c.loc_id=l.loc_id 
        INNER JOIN category as k
        ON c.cat_id=k.cat_id
        $and
        WHERE c.active='1'
        $sort
        LIMIT $start, $display";        
$r = mysqli_query ($dbc, $q); // Run the query.

// Display records.
while ($row = mysqli_fetch_assoc($r)) {
  $class_id= $row['class_id'];
  $cat_id= $row['cat_id'];
echo 
'<div class="row class-item">

  <div class="row">
    <div class="col-sm-4 class-icon">';
if ($row['cat_id'] == 1) {
      echo '<span class="entypo-trophy"></span><h5>' . $row['cat_name'] . '</h5>';
    }
    elseif ($row['cat_id'] == 2) {
      echo '<span class="entypo-note-beamed"></span><h5>' . $row['cat_name'] . '</h5>';
    } 
    else{
      echo '<span class="maki-art-gallery"></span><h5>' . $row['cat_name'] . '</h5>';
    }     
echo '
    </div>
    <div class="col-sm-8">
      <h4 class="text-uppercase">' . $row['start_date'] . ' - ' . $row['end_date'] .'</h4>
      <h3><strong>' . $row['class_name'] . '</strong></h3>
      <p>' . $row['class_desc'] . '</p>
    </div>
  </div>

  <div class="row class-info">
    <div class="col-xs-4 class-details">
      <h4>Price: ' . $row['price'] . '</h4>
    </div>
    <div class="col-xs-6 class-details">
      <h4>' . $row['loc_name'] . '</h4>
    </div>';
         //show save and register buttons only for logged in users
        if (isset($_SESSION['user_id']) && $_SESSION['admin'] == 0) {
        //show save button only if record is not associated with user_id in saved table
        $query = "SELECT * FROM saved WHERE user_id = '" . $_SESSION['user_id'] ."' AND class_id = '$class_id'";
        $result = mysqli_query ($dbc, $query);
        $row = mysqli_fetch_row ($result);
        $num_records = $row[0];

        if ($num_records == 0) {
        echo '
          <div class="col-xs-1 icon-link class-details">
            <a href="index.php?pagelet=classes&class=' . $class_id .'" data-toggle="tooltip"  title="Save" class="heart"><span class="entypo-heart-empty"></span>          
            </a> 
          </div>
          <div class="col-xs-1 icon-link">
            <a href="index.php?pagelet=register&class=' . $class_id .'" data-toggle="tooltip" title="Register" class="plus"><span class="entypo-plus-squared"></span>
            </a>
          </div>';
        } else {//always show register button
        echo '
          <div class="col-xs-1 icon-link class-details">
            <a href="" data-toggle="tooltip"  title="Saved" class="heart"><span class="entypo-heart"></span>          
            </a> 
          </div>
          <div class="col-xs-1 icon-link">
            <a href="index.php?pagelet=register&class=' . $class_id .'" data-toggle="tooltip" title="Register" class="plus"><span class="entypo-plus-squared" ></span>
            </a>
          </div>';
        }
        
      }
      echo ' 
  </div>

</div>';
}   

echo '</div>
</div>';

// Make the links to other pages, if necessary.
if ($num_pages > 1) {
    
    echo '<nav class="text-center row">
            <ul class="pagination">';

    // Determine what page the script is on.    
    $current_page = ($start/$display) + 1;
    
    // If it's not the first page, make a Previous button.
    if ($current_page != 1) {                    
      if(isset($filter)) {//previous link for filtered view
        echo '<li>
                  <a href="index.php?pagelet=classes&filter='. $filter .'&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>';
      } else {//previous link for default view
        echo '<li>
                  <a href="index.php?pagelet=classes&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>';
      }
    }
    
    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
          if(isset($filter)) {//pagination links for filtered view
            echo '<li><a href="index.php?pagelet=classes&filter='. $filter .'&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
          } elseif (!isset($filter)) {//pagination links for default view
            echo '<li><a href="index.php?pagelet=classes&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
            }            
        } else {
             echo '<li class="active"><a href="#">' .$i . '</a></li>';
         }
    }
    
    // If it's not the last page, make a Next button.
    if ($current_page != $num_pages) {
      if (isset($filter)) {//next link for filtered view
        echo '<li>
                <a href="index.php?pagelet=classes&filter='. $filter .'&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>'; 

      } else {//next link for default view 
          echo '<li>
                <a href="index.php?pagelet=classes&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>'; 
      }               
    }
    
    echo '</ul>
    </nav>';    
} // End of links section.

//save classes

//check for class to save
if (isset($_GET['class'])) {
    $class_id=$_GET['class'];
    $user_id=$_SESSION['user_id'];
//insert class into saved classes
$q="INSERT INTO saved (class_id, user_id) VALUES ('$class_id', '$user_id')";
$r = mysqli_query ($dbc, $q);

    //check to see if insert worked 
    if (mysqli_affected_rows($dbc) == 1) {
        //redirect and show confirmation message on page reload
      header ('location: index.php?pagelet=classes&saved=true');   
    } else {//show error message
        $message = "This class could not be saved at this time. Please try again";
        echo "<script type='text/javascript'>
          $(document).ready(function(){
          $('#goback').modal('show');
          });
          </script>
          <noscript class='text-center'>
          <h2 class='text-danger'><?php echo '$message'?></h2></noscript>";  
    }

}

mysqli_close($dbc); //close the database connection
?>

  </div><!-- row -->
</div><!-- container -->

<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Success!</h4>        
      </div>
      <div class="modal-body text-center">
      <p>This class has been saved.</p>
      </div>
      <div class="modal-footer">  
          <a class="btn btn-primary" href="index.php?pagelet=saved">Go to Saved Classes</a>
      </div>
    </div>
  </div>
</div>

<!-- go back modal -->
<div id="goback" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Error</h4>        
      </div>

      <div class="modal-body">      
        <div class="text-center text-danger"><?php echo "$message"; ?></div>
      </div>

      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="index.php?pagelet=classes">Go Back</a>
      </div>

    </div>
  </div>
</div>