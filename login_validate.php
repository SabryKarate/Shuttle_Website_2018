<?php
  session_start();
  // remove all session variables: new login is asked
  session_unset();
  require 'functions.php';
  if(!isset($_POST['type'])) {
    goToWithError('Incorrect request');
  }
  $conn = connectToDb();

if($_POST['type'] === 'login') {
    $email = getRequiredPostArgument($conn, 'email');
    // you should not escape the password, it may be weakened
    $password = sha1(getRequiredPostArgument($conn, 'password', false));
    
    login($conn, $email, $password);
  }

else if($_POST['type'] === 'register') {
// sanitize the input elements, to avoid code injection
    $email = getRequiredPostArgument($conn, 'email');
    $pass = getRequiredPostArgument($conn, 'password', false);
    // check the parameters
    if(strlen($email) > 50) {
      goToWithError('Email too long');
    }
    if(strlen($pass) > 50) {
      goToWithError('Password too long');
    }
    if(strlen($pass) < 2) {
      goToWithError('Password too short');
    }
    if(!((preg_match( '~[A-Z]~', $pass) | preg_match( '~\d~', $pass)) && preg_match( '~[a-z]~', $pass))){
	goToWithError('Password should contain at least one lowercase letter and an uppercase letter or a number');
    } 

   $password = sha1($pass);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      goToWithError('Invalid email');
    }

    // try to signup
    signup($conn, $email, $password);
  } 

else {
    goToWithError('Incorrect request');
  }

?>
