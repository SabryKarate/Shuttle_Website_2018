<?php
  require 'functions.php';
  // this page does not require authentication
  checkAuthentication(true);

  ?>

<script>
function myFunctionDep() {
	if ((document.getElementById("departure_select").value) == "other"){
    	document.getElementById("departure_text").removeAttribute('disabled');
	document.getElementById("departure_text").setAttribute('placeholder', "insert departure");
        }
    else {
	document.getElementById("departure_text").value="";
	document.getElementById("departure_text").setAttribute('placeholder', "other dep(disabled)");
    	document.getElementById("departure_text").setAttribute('disabled', true);
    	}
}

function myFunctionArr() {
	if ((document.getElementById("arrival_select").value) == "other"){
    	document.getElementById("arrival_text").removeAttribute('disabled');
	document.getElementById("arrival_text").setAttribute('placeholder', "insert arrival");

        }
    else {
	document.getElementById("arrival_text").value="";
	document.getElementById("arrival_text").setAttribute('placeholder', "other arr(disabled)");
    	document.getElementById("arrival_text").setAttribute('disabled', true);
    	}
}

</script>
<noscript>Javascript is disabled...</noscript>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> 
<link rel="stylesheet" href ="style.css">
<title>Booking</title>
</head>
<body>
<div class="sidenav">

<?php sidenavPrint(); ?>

</div>
<div class="main">
<h1> Booking </h1>
<h2><noscript>warning: Javascript is disabled, some functions may not work</noscript></h2>

<?php if(isset($_REQUEST['error'])){
	echo '<script language="javascript">';
	echo "alert('".$_REQUEST['error']."')";
	echo '</script>';
		}
?>
<form action="booking_submit.php" method="post">
     	<p><label>Departure:</label><br>
<select name="departure" id="departure_select" onchange="myFunctionDep()">
	<?php printOptions() ?> 
</select> 
<span class="black">--</span><input type="text" maxlength="50" required="required" id="departure_text" name="departure" pattern="[a-zA-Z]+" placeholder="other dep(disabled)" disabled="true" />
     	<p><label>Arrival:</label><br>
<select name="arrival" id="arrival_select" onchange="myFunctionArr()">
	<?php printOptions() ?>
</select>
<span class="black">--</span><input type="text" maxlength="50" required="required" id="arrival_text" name="arrival" pattern="[a-zA-Z]+" placeholder="other arr(disabled)" disabled="true" /></p>
     	<p><label>Number of people:</label><br>
<input type="text" maxlength="50" required="required" name="num_people" pattern="[0-9]+" placeholder="0" />

     	<p><input type="submit" value="Submit" /></p>
</form><br>
<p>Here you can select the departure and the arrival address from the addresses already present in the shuttle itinerary.</p>
<p>If you want to add a new departure address or a new arrival address, select "other" in the menu and then write the new address.</p>


</div>
</body>
</html>

