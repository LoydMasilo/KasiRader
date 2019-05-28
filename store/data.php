<?php
	// Current date and time.
	$curdate = date('Y-M-D');
	$curtime = date('H:i:s');
	
	// Get values.
	$password = $_GET['code'];
	$temp = $_GET['t'];
	$hum = $_GET['h'];
	$Co = $_GET['c'];
	$No = $_GET['n'];
	
	// Set password. Must be consistent with PASSCODE in Arduino code.
	$passcode = "loyd";
	
	// Check if password is right.
	if(isset($password) && ($password == $passcode)){
		// If all three values are present, insert it into the MySQL database.
		if(isset($temp)&&isset($hum)&&isset($Co)){
			// Database credentials.
			$servername = "localhost";
			$username = "root";
			$dbname = "krem";
			$password = "";

			// Create connection.
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}

			  
			// Insert values into table.
			$sql = "INSERT INTO sensor (date, time, temp, hum, Co, No)
			VALUES ('$curdate', '$curtime', $temp, $hum, $Co, $No)";

			if (mysqli_query($conn, $sql)) {
				echo "OK";
			} else {
				echo "Fail: " . $sql . "<br>" . mysqli_error($conn);
			}
			
			// Close connection.
			mysqli_close($conn);
		}
	}
?>