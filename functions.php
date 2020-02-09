<?php
require 'config.php';

$authenticated = false;


//--------------- AUTHENTICATION CHECKING ----------------
function checkAuthentication($redirect) {
  global $maxInactiveTime, $loginPage, $authenticated;
  session_start();

  // check value of timeout
  if(!isset($_SESSION['timeout']) || $_SESSION['timeout'] + $maxInactiveTime < time()) {
    // expired or new session
    if(!isset($_SESSION['timeout'])) {
      $message = 'You must be authenticated to see this page!';
    } else {
      $message = 'Your session has expired. Please, log in again!';
    }
    
    session_unset();
    session_destroy();

    if($redirect) {
      // go to login
      goToPage("$loginPage?error=$message");
      die();
    }
  } else {
    // valid session, update the timeout
    $_SESSION['timeout'] = time();
    $authenticated = true;
  }
}


//--------------- CONNECT TO A DATABASE AND DISABLE AUTOCOMMIT ----------------
function connectToDb() {
  global $host, $user, $pwd, $db;
  $conn = @new mysqli($host, $user, $pwd, $db);
  if($conn->connect_error) {
    die('<div style="color:#fff;background-color:#f44336"><h1>Connection with database failed!</h1><h3>Please, contact the system admin</h3></div>');
  }
  if(!$conn) {
    die('Impossible to connect to database!');
  }
  // Unsetting the db credentials, for security
  unset($host);
  unset($user);
  unset($pwd);
  unset($db);

// it allows to perform transactions, you will need to explicitly commit
  $conn->autocommit(false);
  return $conn;
}



//--------------- SHOW THE ITINERARY ----------------
function listTheAddresses($conn) {
  $result = $conn->query('SELECT address FROM address_table ORDER BY address');
  if(!$result) {
    die('Impossible to list the address table entries!');
  }
  if($result->num_rows == 0) {
    echo '<h3>There are no values in the address table</h3>';
  } else {
    echo '<table style="float: left">';
    echo '<tr><th> Shuttle Itinerary </th></tr>';
    while($row = $result->fetch_object()) {
      echo '<tr><td>'.$row->address.'</td></tr>'; 
    }
    $result->close();
    echo '</table>';
  }
}


//--------------- SHOW THE SEGMENTS, TOTAL AND USERS WHEN LOGGED IN ----------------
function listTheSegmentsAuth($conn, $success) {
  $result = $conn->query('SELECT address, total FROM address_table ORDER BY address');
  if(!$result) {
    die('Impossible to list the address table entries');
  }
  if($result->num_rows == 0) {
    echo '<h3>There are no values in the address table</h3>';
  } else {

// Select departure and arrival of the user
	$arrival = "NULL";
	$departure = "NULL";
if ($success=="true"){
	$result0 = $conn->query("SELECT departure, arrival FROM departure_arrival WHERE email ='".$_SESSION['email']."'");
  		if(!$result0) {
    			die('Impossible to select new departure and arrival of the user');
 			}
  				if($result0->num_rows == 1) {
					$row0 = $result0->fetch_object(); 
					$arrival = $row0->arrival;
					$departure = $row0->departure;
					}
				mysqli_free_result($result0);
}


// print table with segments, total and users (color the new inserted booking)
    echo '<table style="float: right">';
    echo '<tr><th>   Departure   </th><th>   Arrival   </th><th>   Total   </th><th>   Users    </th></tr>';
    if ($row1= $result->fetch_object()){
    		while($row = $result->fetch_object()) {
			if ($row1->total != 0){
				// color in red the new inserted departure and arrival
      			if ($success=="true" && $row1->address == $departure && $row->address == $arrival)		
					echo '<tr><td><span style="color:red">'.$row1->address.'</span></td><td><span style="color:red">'.$row->address.'</span></td><td>'.$row1->total.'</td><td>'; 	
				else if ($success=="true" && $row1->address == $departure && $row->address != $arrival)
					echo '<tr><td><span style="color:red">'.$row1->address.'</span></td><td>'.$row->address.'</td><td>'.$row1->total.'</td><td>'; 
				else if ($success=="true" && $row1->address != $departure && $row->address == $arrival)
					echo '<tr><td>'.$row1->address.'</td><td><span style="color:red">'.$row->address.'</span></td><td>'.$row1->total.'</td><td>'; 
				else
      				echo '<tr><td>'.$row1->address.'</td><td>'.$row->address.'</td><td>'.$row1->total.'</td><td>'; 
 
				$resultU = $conn->query("SELECT email, num_people FROM departure_arrival WHERE departure<='".$row1->address."' AND arrival>='".$row->address."'");
  				if(!$resultU) {
    					die('Impossible to list the users');
 					 }
  				if($resultU->num_rows == 0) {
   				 echo ' ';
  				} else {
    				while($rowU = $resultU->fetch_object()) {
					if ($success=="true" && $rowU->email == $_SESSION['email'])
						echo "<span style='color:red'>".$rowU->email.": ".$rowU->num_people." people; </span>";
					else
      					echo $rowU->email.": ".$rowU->num_people." people; ";
    					}
    				$resultU->close();
  				}
				echo '</td></tr>';
			}
// if total people booked for the segment is 0, show that it is empty
			else 
				echo '<tr><td>'.$row1->address.'</td><td>'.$row->address.'</td><td>  0  </td><td> empty </td></tr>'; 
			$row1=$row;
    }

	}
    $result->close();
    echo '</table>';
  }
}

//--------------- SHOW THE SEGMENTS AND TOTAL WHEN NOT LOGGED IN ----------------
function listTheSegments($conn) {
  $result = $conn->query('SELECT address, total FROM address_table ORDER BY address');
  if(!$result) {
    die('Impossible to list the address table entries');
  }
  if($result->num_rows == 0) {
    echo '<h3>There are no values in the address table</h3>';
  } else {
    echo '<table style="float: right">';
    echo '<tr><th>   Departure   </th><th>   Arrival   </th><th>   Total   </th></tr>';
    if ($row1= $result->fetch_object()){
    		while($row = $result->fetch_object()) {
			if ($row1->total != 0){
      			echo '<tr><td>'.$row1->address.'</td><td>'.$row->address.'</td><td>'.$row1->total.'</td></tr>'; 
			}
			else {
				echo '<tr><td>'.$row1->address.'</td><td>'.$row->address.'</td><td> empty </td></tr>'; 
			}
			$row1=$row;
    }

	}
    $result->close();
    echo '</table>';
  }
}



//--------------- PRINT THE LINKS IN THE SIDEBAR ----------------
function sidenavPrint() {
  global $authenticated, $loginPage;

  echo '<a href="index.php"> Home </a>';
  // if the user is logged in, show Personal, Booking and Logout link
  if($authenticated) {
    // authenticated user
    echo '<a href="personal.php"> Personal </a>';
    echo '<a href="booking.php"> Booking </a>';
    echo '<a href="logout.php"> Logout</a>';
  } else {
    // otherwise, show Login/Register link
    echo "<a href='login.php'> Login </a>";
  }
}


// retrieve current file
function getCurrentScriptFileName() {
  return basename($_SERVER['SCRIPT_FILENAME']);
}


// where to go if success
function getRedirectionPageSuccess() {
  global $redirections;
  return $redirections[getCurrentScriptFileName()]['success'];
}


// where to go if error
function getRedirectionPageError() {
  global $redirections;
  return $redirections[getCurrentScriptFileName()]['error'];
}


//--------------- SANITIZE STRINGS FROM HTML-PHP-SQL-..., TO PREVENT CODE INJECTION ----------------
function getRequiredPostArgument($conn, $name, $escape = true) {
  // check if POST is present
  if(!isset($_POST[$name]) || $_POST[$name] === '') {
    goToWithError("missing required data: $name");
    die();
  }
  // if escape is set to true, also sanitize with htmlentities
  if ($escape) {
    $result = $conn->real_escape_string(htmlentities(trim($_POST[$name])));
  } else {
     $result = $conn->real_escape_string($_POST[$name]);
  }
  return $result;
}


// redirect on corresponding error page
function goToWithError($error) {
  header('Location: '.getRedirectionPageError()."?error=$error");
  die();
}


// redirect on corresponding success page
function goToDestination() {
  header('Location: '.getRedirectionPageSuccess());
  die();
}


// go to a custom destination page
function goToPage($destination) {
  header("Location: $destination");
  die();
}


//--------------- LOG IN ----------------
function login($conn, $email, $password) {
  $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
  if(!$result) {
    goToWithError('Error: Could not create the query for log in');
  }
  if($result->num_rows == 0) {
    // both if password wrong or if non-existing account
    goToWithError('Error: Wrong credentials');
  }
  if(!($row = $result->fetch_object())) {
    goToWithError('Error fetching the result');
  }
  $result->close();
 // save into SESSION array useful data
  $_SESSION['timeout'] = time();
  $_SESSION['email'] = $email;
  $_SESSION['password'] = $password;
  $_SESSION['user_id'] = $row->id;
  // and go to the right place
  goToDestination();
}


//--------------- REGISTER ----------------
function signup($conn, $email, $password) {
  $result = $conn->query("INSERT INTO users(email, password) VALUES('$email', '$password')");
  if(!$result) {
    goToWithError('Impossible to create the account. Maybe the email was already used');
  }
  // the id of the last inserted value
  $id = $conn->insert_id;
  if(!$conn->commit()) {
    goToWithError('Impossible to commit. Please, try again');
  }
  // save into SESSION array useful data
  $_SESSION['timeout'] = time();
  $_SESSION['email'] = $email;
  $_SESSION['password'] = $password;
  $_SESSION['user_id'] = $id;
  // and go to the right place
  goToDestination();
}


//--------------- INSERT NEW BOOKING ----------------
function insert_booking($conn, $email, $departure, $arrival, $num_people){
global $max_capacity;
if ($num_people <= 0)
    goToWithError('Error: You can not book for less than 1 person!');

if ($departure>=$arrival)
    goToWithError('Error: Departure can not be equal or greater than arrival!');


// check if User already booked the shuttle once
$result0 = $conn->query("SELECT * FROM `departure_arrival` WHERE email = '".$email."'");
  if(!$result0) {
    goToWithError('Error: Could not create the query');
  }
  if($result0->num_rows != 0) {
	mysqli_free_result($result0);
	$conn->rollback();
	goToWithError('Error: user already has booked the shuttle');
	}
mysqli_free_result($result0);

// insert DEPARTURE, ARRIVAL and NUM_PEOPLE in departure_arrival table
	$result1 = $conn->query("INSERT INTO `departure_arrival`(`email`, `num_people`, `departure`, `arrival`) VALUES ('".$email."','".$num_people."','".$departure."', '".$arrival."')" );

  	if(!$result1) {
		$conn->rollback();
    		goToWithError('impossible to insert data in departure_arrival');  
  	}
mysqli_free_result($result1);
// check if DEPARTURE is present in table address_table: if not present, insert
$result2 = $conn->query("SELECT * FROM address_table WHERE address = '$departure'");
  if(!$result2) {
	$conn->rollback();
    goToWithError('Error: Could not create the query');
  }
  if($result2->num_rows == 0) {
mysqli_free_result($result2);

$result2b = $conn->query("SELECT total FROM address_table WHERE address < '$departure' ORDER BY address DESC LIMIT 1");
if(!$result2b) {
		$conn->rollback();
    		goToWithError('impossible to select data from address_table');  
  	}

if($result2b->num_rows == 0) 
	$prev_tot = 0;
else {
	$row2b = $result2b->fetch_object();
	$prev_tot = $row2b->total;
	}
mysqli_free_result($result2b);


    // insert new DEPARTURE address
    $result3 = $conn->query("INSERT INTO `address_table`(`address`, `total`) VALUES ('".$departure."','".$prev_tot."')" );
  	if(!$result3) {
		$conn->rollback();
    		goToWithError('impossible to insert data in departure_arrival');  
  	}
mysqli_free_result($result3);
  }

// check if ARRIVAL is present in table address_table: if not present, insert
$result4 = $conn->query("SELECT * FROM address_table WHERE address = '$arrival'");
  if(!$result4) {
	$conn->rollback();
    goToWithError('Error: Could not create the query');
  }
  if($result4->num_rows == 0) {
mysqli_free_result($result4);

//-----------------------------
$result4b = $conn->query("SELECT total FROM address_table WHERE address < '$arrival' ORDER BY address DESC LIMIT 1");
  if(!$result4b) {
    goToWithError('Error: Could not create the query');
  }
  if($result4b->num_rows == 0) {
	$new_total = 0;	
	mysqli_free_result($result4b);
	}
   else {
	$row4b= $result4b->fetch_object();
	$new_total = $row4b->total;
	mysqli_free_result($result4b);
 	}

//-----------------------------

    // insert new ARRIVAL address
    $result5 = $conn->query("INSERT INTO `address_table`(`address`, `total`) VALUES ('".$arrival."','".$new_total."')" );

  	if(!$result5) {
		$conn->rollback();
    		goToWithError('impossible to insert data in departure_arrival');  
  	}
mysqli_free_result($result5);

  }

// UPDATE ADDRESS_TABLE, lines where address >= $departure and address <= $arrival
$result6 = $conn->query("UPDATE `address_table` SET `total`=total+'".$num_people."' WHERE address>='".$departure."' AND address<'".$arrival."'");
  if(!$result6) {
	$conn->rollback();
    goToWithError('impossible to update address_table');
  }
mysqli_free_result($result6);

// check if at least one total people is greater than max_capacity: if so, rollback; otherwise, commit
$result7 = $conn->query("SELECT * FROM address_table WHERE total > '".$max_capacity."'");
  if(!$result7) {
	$conn->rollback();
    goToWithError('Error: Could not create the query');
  }
  if($result7->num_rows != 0) {
mysqli_free_result($result7);
	$conn->rollback();
	goToWithError('Error: Too many people');
	}
mysqli_free_result($result7); 	

// commit the changes all together
	$conn->commit();

  	goToPage("personal.php?success='true'");
					
}


//--------------- CANCEL A BOOKING ----------------
function cancel_booking($conn, $email){
// check if User already booked the shuttle once
$result0 = $conn->query("SELECT * FROM `departure_arrival` WHERE email = '".$email."'");
  if(!$result0) {
    goToWithError('Error: Could not create the query');
  }
  if($result0->num_rows == 0) {
	mysqli_free_result($result0);
	$conn->rollback();
	goToWithError('Error: user has not booked the shuttle yet!');
	}
$row0= $result0->fetch_object();
$num_people = $row0->num_people;
$departure = $row0->departure;
$arrival = $row0->arrival;
mysqli_free_result($result0);
// UPDATE ADDRESS_TABLE, lines where address >= $departure and address <= $arrival
$result1 = $conn->query("UPDATE `address_table` SET `total`=total-'".$num_people."' WHERE address>='".$departure."' AND address<'".$arrival."'");
  if(!$result1) {
	$conn->rollback();
    goToWithError('impossible to update address_table');
  }


$result2 = $conn->query("DELETE FROM `departure_arrival` WHERE email = '".$email."'");
if(!$result2) {
	$conn->rollback();
    goToWithError('impossible to update departure_arrival');
  }


//------------------------- remove arrival if useless
$result6 = $conn->query("SELECT * FROM departure_arrival WHERE departure = '".$arrival."' OR arrival= '".$arrival."'");

if(!$result6) {
		$conn->rollback();
    		goToWithError('impossible to select data from address_table');  
  	}

if($result6->num_rows == 0) {

	$result6b = $conn->query("DELETE FROM `address_table` WHERE address = '".$arrival."'");
	if(!$result6b) {
		$conn->rollback();
    		goToWithError('impossible to update departure_arrival');
  		}

	}
mysqli_free_result($result6);

//------------------------- remove departure if useless
$result7 = $conn->query("SELECT * FROM departure_arrival WHERE departure = '".$departure."' OR arrival= '".$departure."'");
if(!$result7) {
		$conn->rollback();
    		goToWithError('impossible to select data from address_table');  
  	}

if($result7->num_rows == 0) {

	$result7b = $conn->query("DELETE FROM `address_table` WHERE address = '".$departure."'");
	if(!$result7b) {
		$conn->rollback();
    		goToWithError('impossible to update departure_arrival');
  		}

	}
mysqli_free_result($result7);


$conn->commit();

goToPage("personal.php?success='true'");
}



// this function allows to print the options of the dropdown menu
function printOptions(){
$conn = connectToDb();

$result = $conn->query("SELECT * FROM address_table ORDER BY address");
  if(!$result) {
	$conn->rollback();
    goToWithError('Error: Could not create the query');
  }
  if($result->num_rows != 0){
  while ($row = $result->fetch_object()){
  		echo"<option value='".$row->address."'>".$row->address."</option>";
	}

	}
	else{
		echo"<option value='other'></option>";
	}
	echo"<option value='other'>other</option>";


   mysqli_free_result($result); 

}
