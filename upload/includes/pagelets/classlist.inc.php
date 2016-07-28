<?php//Admin edit/delete classlist page

//redirect user if not logged in as admin
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

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <?php echo "<h1>" . constant(strtoupper($pagelet) . '_TITLE') . "</h1>";?>
    </div>
  </div>
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
                                    <a href="index.php?pagelet=classlist&filter=cse">Cypress Springs Elementary</a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classlist&filter=ars">Arbor Ridge K-8</a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classlist&filter=ae">Andover Elementary</a>
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
                                    <a href="index.php?pagelet=classlist&filter=sports">Athletics</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classlist&filter=music">Music</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="index.php?pagelet=classlist&filter=art">Art</a>
                                </td>
                            </tr>

                        </table>

                    </div><!-- panel-body -->

                </div><!-- collapseTwo -->

            </div><!-- panel --> 

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a href="index.php?pagelet=classlist">
                        Show All</a>
                    </h4>
                </div>
            </div>

        </div><!-- panel-group -->
    </div>

    <div class="col-sm-8 col-md-9">

<?php 
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
        ORDER BY c.class_name ASC
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
      echo '<span class="entypo-palette"></span><h5>' . $row['cat_name'] . '</h5>';
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
    </div>
    <div class="col-xs-1 icon-link class-details">
      <a href="index.php?pagelet=classlist&delete=true&class=' . $row['class_id'] .'" data-toggle="tooltip" title="Delete" class="text-danger"><span class="entypo-trash" ></span></a>
    </div>
    <div class="col-xs-1 icon-link ">
      <a href="index.php?pagelet=modifyclass&class=' . $row['class_id'] .'" data-toggle="tooltip" title="Edit"><span class="entypo-pencil" class="text-primary"></span></a>
    </div>
  </div>

</div>';
}   

echo '</div>
</div>';

// Make the links to other pages, if necessary.
if ($num_pages > 1) {
    
    echo '<nav class="text-center">
            <ul class="pagination">';

    // Determine what page the script is on.    
    $current_page = ($start/$display) + 1;
    
    // If it's not the first page, make a Previous button.
    if ($current_page != 1) {                    
      if(isset($filter)) {//previous link for filtered view
        echo '<li>
              <a href="index.php?pagelet=classlist&filter='. $filter .'&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>';
      } else {//previous link for default view
        echo '<li>
              <a href="index.php?pagelet=classlist&s=' . ($start - $display) . '&np=' . $num_pages . '" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>';
      }
    }
    
    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
          if(isset($filter)) {//pagination links for filtered view
            echo '<li><a href="index.php?pagelet=classlist&filter='. $filter .'&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
          } elseif (!isset($filter)) {//pagination links for default view
            echo '<li><a href="index.php?pagelet=classlist&s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a></li>';
            }            
        } else {
             echo '<li class="active"><a href="#">' .$i . '</a></li>';
         }
    }
    
    // If it's not the last page, make a Next button.
    if ($current_page != $num_pages) {
      if (isset($filter)) {//next link for filtered view
        echo '<li>
                <a href="index.php?pagelet=classlist&filter='. $filter .'&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>'; 

      } else {//next link for default view 
          echo '<li>
                <a href="index.php?pagelet=classlist&s=' . ($start + $display) . '&np=' . $num_pages . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>'; 
      }               
    }
    
    echo '</ul>
    </nav>';    
} // End of links section.

//set class to inactive and remove from class lists
if (isset($_GET['delete'])) {
  echo "<script type='text/javascript'>
      $(document).ready(function(){
      $('#delete').modal('show');
      });
      </script>
      <noscript class='text-center'>
      <h2 class='text-danger'>Are you sure you want to delete this class?</h2>      
      <div>  
        <a class='btn btn-danger'href='index.php?pagelet=deleteclass&class=" . $_GET['class'] . "&confirm=true'>Yes</a>
        <a class='btn btn-primary'href='index.php?pagelet=classlist'>No</a>
      </div>
      </noscript>

      </div> <!-- col-sm-9 -->
  </div><!-- row -->
</div>

<!-- delete modal -->
<div id='delete' class='modal fade'>
  <div class='modal-dialog modal-sm'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
        <h4 class='modal-title text-center' id='myModalLabel'>Confirm Class Deletion</h4>        
      </div>
      <div class='modal-body'>
      <p>Are you sure you want to delete this class?</p>      
      </div>
      <div class='modal-footer'>  
        <a class='btn btn-primary' href='index.php?pagelet=deleteclass&class=" . $_GET['class'] . "&confirm=true'>Yes</a>
        <a class='btn btn-primary' data-dismiss='modal'>No</a>
      </div>
    </div>
  </div>
</div>";
}

mysqli_close($dbc); //close the database connection

?>
    </div> <!--col-sm-9 -->
  </div><!-- row -->
</div><!--container -->
<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>