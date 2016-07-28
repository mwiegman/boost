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

  
<?php //contact form page

if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $human = intval($_POST['human']);
    $from = $email; 
    $to = 'email@example.com'; //change address where form will be sent
    $subject = 'Message from Contact Form ';
    
    $body = "From: $name\n E-Mail: $email\n Message:\n $message";

    $err='';
 
    // Check if name has been entered
    if (!$_POST['name']) {
      $err = 'Please enter your name. ';
    }
    
    // Check if email has been entered and is valid
    if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $err .= 'Please enter a valid email address. ';
    }
    
    //Check if message has been entered
    if (!$_POST['message']) {
      $err .= 'Please enter your message. ';
    }
    //Check if simple anti-bot test is correct
    if ($human !== 5) {
      $err .= 'Your anti-spam is incorrect. ';
    }    
 

echo '
<div class="row">
  <div class="col-sm-10 col-offset-2">';

//If there are no errors, send the email
if (!$err) {
  if (mail ($to, $subject, $body, $from)) {
    echo '<div class="alert alert-success">Thank You! Your message has been sent</div>
     <div class="text-center">
     <a href="index.php?pagelet=contact" class="btn btn-primary btn-lg">Go Back</a>
     </div>';
  } else {
    echo "<div class='alert alert-danger'>There was an error sending your message. $err</div>
    <div class='text-center'>
    <a href='index.php?pagelet=contact' class='btn btn-primary btn-lg'>Go Back</a></div>";
  }
}
echo '</div>
</div>';
} else {//show contact form
?>
  <div class="row">
    <div class="col-sm-12 col-lg-8 col-lg-offset-2">

    <form class="form-horizontal" role="form" method="post" action="" id="contactForm"> 

      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value="<?php if (isset($_POST['name'])) {echo htmlspecialchars($_POST['name']);} ?>">
        </div>        
      </div>

      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php if (isset($_POST['email'])) {echo htmlspecialchars($_POST['email']);} ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="message" class="col-sm-2 control-label">Message</label>
        <div class="col-sm-10">
          <textarea class="form-control" rows="4" name="message"><?php if (isset($_POST['message'])) {echo htmlspecialchars($_POST['message']);} ?></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="human" class="col-sm-2 control-label">2 + 3 = ?</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="human" name="human" placeholder="Your Answer">
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
          <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
        </div>
      </div> 
           
    </form>

    </div>
  </div>
<?php
}
?>

</div>

<!-- confirmation modal -->
<div id="confirm-modal" class="modal fade" tabindex="-1">

  <div class="modal-dialog modal-sm">

    <div class="modal-content">

      <div class="modal-header">
        <a href="index.php?pagelet=index"><button type="button" class="close"  aria-label="Close"><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title text-center" id="myModalLabel">Success!</h4>        
      </div>

      <div class="modal-body">
        <?php if (isset($Body)) {echo $Body;} ?>
      </div>

      <div class="modal-footer">  
          <a type="submit" class="btn btn-primary" href="index.php?pagelet=index">Close</a>        
      </div>

    </div>

  </div>

</div>
