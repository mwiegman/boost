<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo constant(strtoupper($pagelet) . '_DESC'); ?>">    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>
    	<?php echo "Boost | " . constant(strtoupper($pagelet) . '_TITLE'); ?> 
  	</title>    
    <link rel="icon" href="http://student065.webdev.seminolestate.edu/favicon.ico?v=2" type="image/x-icon" />    
    <link rel="stylesheet" href="css/animate.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>			
<?php 
if ($pagelet == 'addclass' || 'modifyclass') {//add tinymce script to pages with text areas
  echo "<script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
  <script>tinymce.init({ selector:'textarea', menubar: false, toolbar: 'undo redo bold italic alignleft aligncenter alignright bullist numlist outdent indent', content_css: 'css/styles.css' });</script>";
}
?>
</head>
<body> 
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">    
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand text-center" href="index.php?pagelet=index"><span class="boost">Boost</span> <br /><small>After School Enrichment</small></a>
    </div>    
    <div class="collapse navbar-collapse" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a href="index.php?pagelet=classes">Browse Classes</a></li>
      </ul>      
      <ul class="nav navbar-nav navbar-right">        
        

<?php //change site navigation depending on user. 

if (isset($_SESSION['user_id']) && ($pagelet != 'logout') && $_SESSION['admin'] == '0') {    
    echo '<li class="dropdown nav-border">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="entypo-user" aria-hidden="true"></span><span> ' .  $_SESSION['username'] . ' <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="index.php?pagelet=myclasses">My Classes</a></li>
            <li><a href="index.php?pagelet=saved">Saved Classes</a></li>
            <li><a href="index.php?pagelet=profile">Profile</a></li>
            <li><a href="index.php?pagelet=logout">Log Out</a></li>            
          </ul>
        </li>';
    
} elseif (isset($_SESSION['user_id']) && $pagelet != 'logout' && $_SESSION['admin'] == '1') {    
    echo '<li class="dropdown nav-border">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="entypo-user" aria-hidden="true"></span> ' .  $_SESSION['username'] . ' <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="index.php?pagelet=addclass">Add Class</a></li>            
            <li><a href="index.php?pagelet=classlist">Edit Classes</a></li>
            <li><a href="index.php?pagelet=registerlist">View Class Rosters</a></li>
            <li><a href="index.php?pagelet=logout">Log Out</a></li>            
          </ul>
        </li>
        ';    
} else  {
echo '<li class="nav-border"><a href="index.php?pagelet=signup">Sign Up</a></li> 
      <li class="nav-border"><a href="index.php?pagelet=login">Log In</a></li>';
}
?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="index.php?pagelet=contact">Contact</a></li>
              <li><a href="index.php?pagelet=locations">Locations</a></li>
              <li><a href="index.php?pagelet=faq">FAQ</a></li>            
            </ul>
          </li>
        </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
      