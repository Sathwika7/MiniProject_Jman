<!--For displaying drafted and published surveys-->
<?php 
include 'create_table_for_storing_responses.php';
session_start(); // Start the session
//$email = $_SESSION['variable1']; 
// Check if the user clicked the "Exit" link
if (isset($_GET['logout'])) 
{
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
		text-decoration: none;
		color:#C47482;
	}
	#button1
	{
		color: #5784BA;
	}
	body 
	{
	  margin: 0;
	  font-family: Times New Roman;
	}
	.topnav 
	{
	  overflow: hidden;
	  background-color: #333;
	}
	.topnav a 
	{
	  float: left;
	  color: #f2f2f2;
	  text-align: center;
	  padding: 14px 16px;
	  text-decoration: none;
	  font-size: 17px;
	}
	.topnav a:hover 
	{
	  background-color: #ddd;
	  color: black;
	}
	.topnav a.active 
	{
	  background-color: #04AA6D;
	  color: white;
	}
	.topnav-right 
	{
	  float: right;
	}	
	
  </style>

</head>
<body>
  <div class="topnav">
  <a href="divide.html">Create a survey</a>
  <a href="display_submitted_drafted_survey_names.php">Created surveys</a>
  </div>

  <div>
	<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "saved_surveys";

	// Create a connection to the drafted_surveys database
	$conn = mysqli_connect($servername, $username, $password, $database);

	// Check connection
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	// Get all table names from drafted_surveys database
	$sql = "SHOW TABLES";
	$result = mysqli_query($conn, $sql);

	// Display table names in a table format
	echo "<table border='1' align='center' width='50%' bordercolor='gray' cellpadding='1' cellspacing='1'>
			<tr>
				<th>Survey name</th>
				<th>Targeted Category</th>
				<th>Status</th>
				<th>Responses</th>
			</tr>";
	if(mysqli_num_rows($result) > 0)
	{
		echo "<h3 style='text-align:center'>Published surveys</h3>";
		while ($row = mysqli_fetch_array($result)) 
		{
			echo '<tr style="text-align: center;">';
			echo '<td>';
			echo '<form action="display_submitted_survey_questions.php" target="_blank" method="post">';
			echo '<input type="hidden" name="name" value="' . $row[0] . '">';
			echo '<button id="button1" type="submit" style="border: none; background: none; padding: 0; text-decoration: none;">' . substr($row[0], 0, -9) . '</button>';
			echo '</form>';
			echo '</td>';
			$name1 = substr($row[0],-8);
			echo '<td>';
			echo ucfirst($name1);
			echo '</td>';
			echo '<td><button type="button" disabled>Published</button></td>';
			echo '<td><a href="display_responses_admin.php?value=' . $row[0] . '" target="_blank" rel="noopener noreferrer">Show</a></td>';
			echo '</tr>';
		}
	}
	else
	{
		echo "There are no published surveys<br>";
	}

	echo "</table>";

	// Close the connection to drafted_surveys database
	mysqli_close($conn);
	echo "</div><div>";
	$database1 = "drafted_surveys";

	// Create a connection to the drafted_surveys database
	$conn = mysqli_connect($servername, $username, $password, $database1);

	// Check connection
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	// Get all table names from drafted_surveys database
	$sql = "SHOW TABLES";
	$result = mysqli_query($conn, $sql);

	// Display table names in a table format
	echo "<table border='1' align='center' width='50%' cellpadding='1' cellspacing='1'>
			<tr>
				<th>Survey name</th>
				<th>Targeted Category</th>
				<th>Action</th>
			</tr>";
	echo "<br><h3 style='text-align:center'>Drafted surveys</h3>";
	if(mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result)) 
		{
			echo "<form method='post' action='draft_to_publish_survey.php'>";
			echo '<tr style="text-align: center;">';
			echo '<td>' . substr($row[0],0,-9) . '</td>';
			echo '<input type="hidden" name="name" value="'.$row[0].'">';
			echo '<td>';
			echo substr($row[0],-8);
			echo '</td>';
			echo '<td colspan="2"><button name="p1" class="button" type="submit" value="publish">Publish</button>&nbsp;&nbsp;';
			echo '<button name="p1" class="button" type="submit" value="edit">Edit</button></td>';
			echo '</form>';
			echo '</tr>';
		}
	}
	echo "</table>";

	// Close the connection to drafted_surveys database
	echo '<br><br><center><a href="?logout=1"><b>Exit</b></a></center>';
	mysqli_close($conn);
	?>  
	</div>
</body>
</html>
