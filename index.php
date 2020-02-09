<?php
  require 'functions.php';
  // main page: not authenticated
  checkAuthentication(false);

  // Connect to database.
  $conn = connectToDb();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> 
<link rel="stylesheet" href ="style.css">
<title>Home</title>
</head>
<body>
<div class="sidenav">

<?php sidenavPrint(); ?>

</div>
<div class="main">
<h1> Home </h1>
<h2><noscript>warning: Javascript is disabled, some functions may not work</noscript></h2>
<h2>Welcome to the<br>shuttle home page</h2>

<div class="centered">
<?php listTheAddresses($conn); ?> <br>
<p class="black">-------</p>
<?php listTheSegments($conn); ?>
</div>
</div>

</body>
</html>

