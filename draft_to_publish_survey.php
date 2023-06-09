<?php
//For handling drafted surveys i.e drafted surveys can be published or edited here.
$tablename = $_POST["name"];
$name = $_POST["p1"];

$sourceServer = "localhost";
$sourceUsername = "root";
$sourcePassword = "";
$sourceDatabase = "drafted_surveys";

$destinationServer = "localhost";
$destinationUsername = "root";
$destinationPassword = "";
$destinationDatabase = "registration";

$destinationServer1 = "localhost";
$destinationUsername1 = "root";
$destinationPassword1 = "";
$destinationDatabase1 = "saved_surveys";

// Create a connection to the source database
$sourceConn = mysqli_connect($sourceServer, $sourceUsername, $sourcePassword, $sourceDatabase);
if (!$sourceConn) 
{
    echo "Source connection error: " . mysqli_connect_error();
    exit;
}

// Create a connection to the destination database
$destinationConn = mysqli_connect($destinationServer, $destinationUsername, $destinationPassword, $destinationDatabase);
if (!$destinationConn) 
{
    echo "Destination connection error: " . mysqli_connect_error();
    exit;
}

$destinationConn1 = mysqli_connect($destinationServer1, $destinationUsername1, $destinationPassword1, $destinationDatabase1);
// Check destination connection
if (!$destinationConn1) 
{
    echo "Destination1 connection error: " . mysqli_connect_error();
    exit;
}

// Retrieve the table data from the source database;l
$sql = "SELECT * FROM `$sourceDatabase`.`$tablename`";
$result = mysqli_query($sourceConn,$sql);

// Copying into destination table based on clicked (submit/edit) buttons
if (!is_null($tablename) && $name == "publish") 
{
    $sql1 = "CREATE TABLE `$destinationDatabase1`.`$tablename` LIKE `$sourceDatabase`.`$tablename`";
    $result1 = mysqli_query($destinationConn1,$sql1);;
    if ($result1) 
	{
        if ($result->num_rows > 0) 
		{
            // Iterate through each row of the result
            while ($row = mysqli_fetch_assoc($result)) 
			{
                // Insert each row into the destination table
                $insertSql = "INSERT INTO `$destinationDatabase1`.`$tablename` (pid, question, type) VALUES (
                                " . $row["pid"] . ",
                                '" . $row["question"] . "',
                                '" . $row["type"] . "'
                                )";
                 mysqli_query($destinationConn1, $insertSql);
            }
            // Drop the source table
            $sql2 = "DROP TABLE `$sourceDatabase`.`$tablename`";
            if(mysqli_query($sourceConn,$sql2))
			{
                // Display toast message
				$message = "Survey published";
				// Output the toast message using PHP
				echo '<div class="toast-message">' . $message . '</div>';
				echo "<style>
				.toast-message 
				{
					position: fixed;
					top: 20px;
					left: 50%;
					transform: translateX(-50%);
					background-color: #333;
					color: #fff;
					padding: 10px 20px;
					border-radius: 5px;
					box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
					z-index: 9999;
				}
				</style>";
                header("refresh:5; url=display_submitted_drafted_survey_names.php");
                exit;
			}
        } 
		else 
		{
            echo "No data in source table";
        }
    } 
	else 
	{
        echo "Error creating table: " . mysqli_error($destinationConn1);
        exit;
    }
} 
elseif (!is_null($tablename) && $name == "edit") 
{
    $sql1 = "DELETE FROM `$destinationDatabase`.`publishable_surveys`";
    if(mysqli_query($destinationConn,$sql1))
	{
        if ($result->num_rows > 0) 
		{
            // Iterate through each row of the result
            while ($row = mysqli_fetch_assoc($result)) 
			{
                // Insert each row into the destination table
                $insertSql = "INSERT INTO `$destinationDatabase`.`publishable_surveys` (pid, question, type) VALUES (
                                " . $row["pid"] . ",
                                '" . $row["question"] . "',
                                '" . $row["type"] . "'
                                )";
                 mysqli_query($destinationConn1, $insertSql);
            }
            // Drop the source table
            $sql2 = "DROP TABLE `$sourceDatabase`.`$tablename`";
            if (mysqli_query($sourceConn, $sql2))
			{
                // Redirect to another page after a delay
                header("refresh:1; url=divide.html");
                exit;
            } 
			else 
			{
                echo "No data in source table";
            }
        }
    } 
	else 
	{
        echo '<script>alert("Error in editing the survey! Try creating a new survey");</script>';
        // Redirect to another page after a delay
        header("refresh:1; url=index.html");
        exit;
    }
}

// Close the connections
mysqli_close($sourceConn);
mysqli_close($destinationConn);
mysqli_close($destinationConn1);
?>
