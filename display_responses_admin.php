<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "saved_surveys";
$database1 = "responses";

// Create a connection to the database
$conn = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

$conn1 = mysqli_connect($servername, $username, $password, $database1);
if (!$conn1) 
{
    die("Connection failed: " . mysqli_connect_error());
}

$name = $_GET['value'];
// Query to retrieve all rows from the table
$sql = "SELECT * FROM `$database1`.`$name`";
$result = mysqli_query($conn1, $sql);

$sql1 = "SELECT `question` FROM `$database`.`$name`";
$result1 = mysqli_query($conn, $sql1);

echo '<h3 align="center">' . substr($name,0,-9) . '</h3>';;
// Check if any rows are returned
if (mysqli_num_rows($result) > 0) 
{
    // Display the table headers
    echo "<table align='center' border='1' cellspacing='0' cellpadding='6'>";
    echo "<tr>";
	echo "<th>" . "Email" . "</th>";
	while ($row1 = mysqli_fetch_assoc($result1)) 
	{
		foreach ($row1 as $value) 
		{
			echo "<th>" . $value . "</th>";
		}
	}
    echo "</tr>";

    // Loop through each row of the result
    while ($row = mysqli_fetch_assoc($result)) 
	{
        // Display the data for each row
        echo "<tr>";
        
        // Loop through each column of the row
        foreach ($row as $value) 
		{
            echo "<td>" . $value . "</td>";
        }
        
        echo "</tr>";
    }

    // Close the table
    echo "</table>";
} 
else 
{
    // No rows found
    echo "No responses found";
}
// Close the connection
mysqli_close($conn);
?>
