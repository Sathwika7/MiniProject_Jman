<?php
session_start(); // Start the session
$email = $_SESSION['variable1']; 
// Check if the user clicked the "Exit" link
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: Homepage.html"); // Redirect to the login page or any other desired page
    exit(); // Terminate the script
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
	a 
	{
		text-decoration:none;
		color:#C47482;
	}
	a:hover
	{
		text-decoration:underline;
	}
	body 
	{
	  margin:0;
	}
  </style>

</head>
<body>
  <!-- Rest of your PHP code goes here -->
	<?php
	
	//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	//ini_set('display_errors', 0);
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "saved_surveys";
	$db = "registration";
	$conn = mysqli_connect($servername, $username, $password, $database);
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	$conn1 = mysqli_connect($servername, $username, $password, $db);
	if(!$conn1)
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	// Get all table names from drafted_surveys database
	$sql = "SHOW TABLES";
	$result = mysqli_query($conn, $sql);

	$sql1 = "select * from register where email='$email'";
	$result1 = mysqli_query($conn1, $sql1);
	if (mysqli_num_rows($result1) == 1) 
	{
		$row1 = mysqli_fetch_array($result1);
		$profession = $row1['profession'];
	}

	// Display table names in a table format
	echo "<table border='1' align='center' width='50%' bordercolor='gray' cellpadding='1' cellspacing='1'>
			<tr>
				<th>Survey name</th>
				<th>Status</th>
			</tr>";
	if(mysqli_num_rows($result) > 0)
	{
		echo "<h3 style='text-align:center'>Published surveys</h3>";
		while ($row = mysqli_fetch_array($result)) 
		{
			echo '<tr style="text-align: center;">';
			echo '<form method="post" id="myForm" action="display_survey_questions.php">';
			echo '<input type="hidden" name="email" value="<?php echo $email; ?>">';
			echo '<input type="hidden" name="name" value="' . $row[0] . '">';
			//echo $row[0];
			if($profession == "student" && substr($row[0],-9) == "_students")
			{
				echo '<td>' . substr($row[0],0,-9) . '</td>';
				echo '<td><button type="submit" name="submit" value="submit">Take survey</button></td>';
			}
			else if($profession == "teacher" && substr($row[0],-9) == "_teachers")
			{
				echo '<td>' . substr($row[0],0,-9) . '</td>';
				echo '<td><button type="submit" name="submit" value="submit">Take survey</button></td>';
			}
			echo '</form>';
			echo '</tr>';
		}
	}
	else
	{
		echo "There are no published surveys<br>";
	}

	echo "</table>";
	echo '<br><br><center><a href="?logout=1"><b>Exit</b></a></center>';
	// Close the connection to saved_surveys database
	mysqli_close($conn);
	?>  
</body>
</html>
