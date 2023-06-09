<?php
//Creating a table for each survey to store user responses 
$servername = "localhost";
$username = "root";
$password = "";
$database = "saved_surveys"; 

// Connect to the existing database
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve all table names from the existing database
$sqlTableNames = "SHOW TABLES";
$result = mysqli_query($conn, $sqlTableNames);

// Connect to the new database where you want to create the dynamic tables
$newDatabase = "responses";
$connNew = mysqli_connect($servername, $username, $password, $newDatabase);
if (!$connNew) 
{
    die("Connection failed: " . mysqli_connect_error());
}

// Loop through each table and create a new table for each one
while ($row = mysqli_fetch_row($result)) 
{
    $existingTableName = $row[0];
    $newTableName = $existingTableName; // Replace with the desired naming scheme for the new tables

    // Retrieve the number of rows from the existing table
    $sqlRowCount = "SELECT COUNT(*) FROM `$existingTableName`";
    $resultRowCount = mysqli_query($conn, $sqlRowCount);
    $rowCount = mysqli_fetch_row($resultRowCount)[0];
	
	$sql1 = "SELECT COUNT(*) AS tableCount FROM information_schema.tables WHERE table_schema = '$newDatabase' AND table_name = '$existingTableName'";
    $result1 = mysqli_query($connNew, $sql1);
    if($result1)
    {
        $row1 = mysqli_fetch_assoc($result1);
        $tableCount = $row1['tableCount'];
        if($tableCount == 0)
        {
			// Generate the dynamic SQL query to create the new table with the desired number of columns
			$sqlCreateTable = "CREATE TABLE `$newDatabase`.`$existingTableName` ( ";
			$sqlCreateTable .= "email VARCHAR(200), ";
			for ($i = 1; $i <= $rowCount; $i++) 
			{
			   $columnName = "Question" . $i;
			   $sqlCreateTable .= "$columnName VARCHAR(200)";
				if ($i < $rowCount) 
				{
					$sqlCreateTable .= ", ";
				}
			}
			$sqlCreateTable .= " )";
			mysqli_query($connNew, $sqlCreateTable);
		}
	}
}
// Close the connections
mysqli_close($conn);
mysqli_close($connNew);
?>
