<html>
	<head>
		<title>KR MONITORING</title>
		<style>
		html, body{
			background-color: white;
			font-family: Arial;
			font-size: 1em;
		}
		table{
			border-spacing: 0;
			border-collapse: collapse;
			margin: 0 auto;
			width: 90%;
		}
		th{
			padding: 8px;
			background-color: #008000;
			border: 1px solid #CCC;
			color: white;
		}
		td{
			padding: 10px;
			background-color: #FFF;
			border: 1px solid #CCC;
		}
		
		p{
			margin: 0 0 1em 0;
		}
		
		div.contentcontainer{
			margin: 10px auto;
			width: 90%;
			background-color: #FFF;
			padding: 10px;
		}
		div.title{
			font-weight: bold;
			font-size: 1.5em;
			text-decoration: underline;

		}
		div.subtitle{
			font-weight: bold;
			font-size: 1.2em;
		}
		div.content{
			font-size: 1em;
		}
		</style>
	</head>
	<body>
	
</nav>
		<?php	
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
			
		
			// Get the most recent 25 entries.
			$result = mysqli_query($conn, "SELECT date, time, temp, hum, Co, No FROM sensor ORDER BY date DESC, time DESC  LIMIT 10");
			echo "<table><tr><th>Date</th><th>Time</th><th>Temperature</th><th>Humidity</th><th>Carbon</th><th>Nitrogen</th></tr>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr><td>";
				echo $row["date"];
				echo "</td><td>";
				echo $row["time"];
				echo "</td><td>";
				echo $row["temp"];
				echo "&deg;C</td><td>";
				echo $row["hum"];
				echo "%</td><td>";
				echo $row["Co"];
				echo " ppm</td><td>";
				echo $row["No"];
				echo " ppm</td></tr>";
			}
			echo "</tr></table>";

			// Get number of entries in the database.
			if ($result = mysqli_query($conn, "SELECT date FROM sensor")) {

				// Count number of rows on table.
				$row_cnt = mysqli_num_rows($result);

				// Print result.
				printf("The database has %d entries.", $row_cnt);
			}

			// Close connection.
			mysqli_close($conn);
		?>
	</body>
</html>