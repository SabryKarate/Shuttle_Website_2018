<?php
  require 'functions.php';
  // this page does not require authentication
  checkAuthentication(false);
  
  if($authenticated) {
    goToPage($homePage);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> 
<link rel="stylesheet" href ="style.css">
<title>Login</title>
</head>
<body>
<div class="sidenav">

<?php sidenavPrint(); ?>
<?php if(isset($_REQUEST['error'])){
	echo '<script language="javascript">';
	echo "alert('".$_REQUEST['error']."')";
	echo '</script>';
		}
?>

</div>
<div class="main">
<h1> Login </h1>
<h2><noscript>warning: Javascript is disabled, some functions may not work</noscript></h2>
<form action="login_validate.php" method="post">
	<input type="text" value="login" hidden="hidden" name="type" />

     	<p><label>Username:</label>
	<input type="email" maxlength="50" required="required" name="email" placeholder="email" />
	</p>
     	<p><label>Password:</label>
	<input type="password" maxlength="50" required="required" name="password" placeholder="password"/></p>
     	<p><input type="submit" value="Login" /></p>
</form>
<br>
<h1> New user? Register! </h1>

<form action="login_validate.php" method="post">
	<input type="text" value="register" hidden="hidden" name="type" />

     	<p><label>Username:</label>
	<input type="email" maxlength="50" required="required" name="email" placeholder="email" />
	</p>
     	<p><label>Password:</label>
	<input type="password" maxlength="50" required="required" name="password" pattern="(?=.*[a-z])((?=.*[A-Z])|(?=.*\d)).{1,}" placeholder="password" /></p>
     	<p><input type="submit" value="Register" /></p>
</form>


</div>
</body>
</html>

