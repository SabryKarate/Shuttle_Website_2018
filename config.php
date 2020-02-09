<?php
// show warnings and errors
ini_set('display_errors', 0);

// force HTTPS
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
  header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TRUE, 301);
  die();
}

// check the cookies
function checkCookies() {
  // set a test cookie
  setcookie('test', 1);
  // if i am not in the test page, redirect to it
  if(!isset($_GET['cookies'])){
    if (sizeof($_GET)) {
      // add a new argument
      header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&cookies', TRUE, 301);
    } else {
      // this is the only argument
      header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?cookies', TRUE, 301);
    }
    die();
  }
  if(count($_COOKIE) > 0){
    // ok
  } else {
    die('<h1 style="color:#fff;background-color:#f44336">you must enable cookies to view this site</h1>');
  }

}
// cookie check must be done only if there are no cookies
if (!isset($_COOKIE['test'])) {
  checkCookies();
}

// useful pages
$loginPage = 'login.php';
$homePage = 'index.php';

// max time of inactivity, timeout
$maxInactiveTime = 120;
// global variable, max capacity of shuttle
$max_capacity = 4;


$redirections = array(
  'login_validate.php' => array(
    'success' => 'personal.php',
    'error' => 'login.php'
  ),
  'booking_submit.php' => array(
    'success' => 'personal.php', 
    'error' => 'booking.php'
  ),
  'booking_cancel.php' => array(
    'success' => 'personal.php', 
    'error' => 'personal.php'
  )) ;



// redirect navigation 
switch (basename($_SERVER['SCRIPT_FILENAME'])) {
  case 'config.php':
    header("Location: $homePage");
    break;
  default:
    // nothing
    break;
}

// database information
  $host = 'localhost';
  $user = 'root';
  $pwd = '';
  $db = 's233148';  // name of database
?>