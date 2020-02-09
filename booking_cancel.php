<?php

  require 'functions.php';
  // main page: not authenticated
  checkAuthentication(true);

  // Connect to database.
  $conn = connectToDb();

    	if(isset($_SESSION['email']))
		$email = $_SESSION['email'];
	else 
		echo 'ERROR!!!';

    // try to cancel
    cancel_booking($conn, $email);

?>


