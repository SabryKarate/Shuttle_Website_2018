<?php
  require 'functions.php';
  // main page: not authenticated
  checkAuthentication(true);

  // Connect to database.
  $conn = connectToDb();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> 
<link rel="stylesheet" href ="style.css">
<title>Personal</title>
</head>
<body>
<div class="sidenav">

<?php sidenavPrint(); ?>

</div>
<div class="main">
<h1> Personal page </h1>
<h2><noscript>warning: Javascript is disabled, some functions may not work</noscript></h2>
<?php 
	if(isset($_SESSION['email']))
		echo '<h3>Welcome to your personal page,</h3><h2>'.$_SESSION['email'].'!</h2>';
 ?>
<?php if(isset($_REQUEST['error'])){
	echo '<script language="javascript">';
	echo "alert('".$_REQUEST['error']."')";
	echo '</script>';
		}
?>


<?php echo '<div class="centered">'; listTheAddresses($conn); ?> <br>

<?php if($authenticated==true){
	 
	if (isset($_REQUEST['success']))
		$success="true";
	else
		$success="false";
	echo'<p class="black"> -------  </p>';
	listTheSegmentsAuth($conn, $success); 
	echo '</div>';
	echo '<div class="centered">';
	 echo '<form action="booking_cancel.php" method="post">';
	 echo '<button type="submit" >Delete booking</button></form>';
	echo '</div>';
	}
else {
	echo'<p> -------  </p>';
	 listTheSegments($conn);
	 echo '</div>';
	}  ?>

</div>

</body>
</html>

