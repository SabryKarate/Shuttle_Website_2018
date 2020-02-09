<?php
  session_start();
  require 'functions.php';
    $conn = connectToDb();

// sanitize strings to avoid code injection
    $departure = getRequiredPostArgument($conn, 'departure');
    $arrival = getRequiredPostArgument($conn, 'arrival');
    $num_people = getRequiredPostArgument($conn, 'num_people');

	// check the parameters
    if(strlen($departure) > 50) {
      goToWithError('Departure too long');
    }
    if(strlen($arrival) > 50) {
      goToWithError('Arrival too long');
    }
    if(strlen($departure) < 1) {
      goToWithError('Departure too short');
    }
    if(strlen($arrival) < 1) {
      goToWithError('Arrival too short');
    }
    if(!is_numeric($num_people)) {
      goToWithError('Number of people not a number');
    }
    if (strcmp($departure, $arrival) >= 0){
      goToWithError('Departure can not be equal or greater than arrival');
    }


	if(isset($_SESSION['email']))
		$email = $_SESSION['email'];
	else 
		goToWithError('Email is not set in SESSION');

    // try to insert booking
    insert_booking($conn, $email, $departure, $arrival, $num_people);
  
?>


