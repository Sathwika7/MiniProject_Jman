<?php
//Submit or Draft survey based on the option selected by the user 
$destination_table1 = $_POST["f1"];
$destination_table = $destination_table1;
$name = $_POST["target"];
$name2 = $_POST["s1"];
if($name == "teachers")
{
	$destination_table .= "_teachers";
}
else if($name == "students")
{
	$destination_table .= "_students";
}
$sourceServer = "localhost";
$sourceUsername = "root";
$sourcePassword = "";
$sourceDatabase = "registration";

$destinationServer = "localhost";
$destinationUsername = "root";
$destinationPassword = "";
$destinationDatabase = "saved_surveys";


$destinationServer1 = "localhost";
$destinationUsername1 = "root";
$destinationPassword1 = "";
$destinationDatabase1 = "drafted_surveys";


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

// Retrieve the table data from the source database
$sql = "SELECT * FROM publishable_surveys";
$result = mysqli_query($sourceConn, $sql);

//Copying into destination table based on clicked (draft/submit) buttons
if (!is_null($destination_table) && $name2 === "submit") 
{
    $sql1 = "SELECT COUNT(*) AS tableCount FROM information_schema.tables WHERE table_schema = 'saved_surveys' AND table_name = '$destination_table'";
    $result1 = mysqli_query($destinationConn, $sql1);
	$sql2 = "SELECT COUNT(*) AS tableCount FROM information_schema.tables WHERE table_schema = 'drafted_surveys' AND table_name = '$destination_table'";
	$result2 = mysqli_query($destinationConn1, $sql2);
    if ($result1)
    {
        $row = mysqli_fetch_assoc($result1);
        $tableCount = $row['tableCount'];
		$tableCount1 = 0;
		if($result2)
		{
			$row1 = mysqli_fetch_assoc($result2);
			$tableCount1 = $row1['tableCount'];
		}
        if ($tableCount > 0 or $tableCount1 > 0) 
        {
            echo '<script type="text/javascript">
                alert("Given survey name already exists...Submit the survey with a new name")
                window.location.href="index.html"
                </script>';
        } 
        else 
        {
			$sql3 = "CREATE TABLE `saved_surveys`.`$destination_table` LIKE `registration`.`publishable_surveys`";
			$result3 = mysqli_query($destinationConn, $sql3);
            if ($result3 === false) 
            {
                // An error occurred during table creation
                echo "Error creating table: " . mysqli_error($destinationConn);
                exit;
            } 
            else 
            {
                if ($result->num_rows > 0) 
                {
                    //Creating destination table dynamically based on the value given by the user while submitting created survey.
                    // Iterate through each row of the result
                    while ($row = mysqli_fetch_assoc($result)) 
                    {
                        // Insert each row into the destination table
                        $insertSql = "INSERT INTO `$destination_table` (pid, question, type) VALUES (
                            " . $row["pid"] . ",
                            '" . $row["question"] . "',
                            '" . $row["type"] . "'
                        )";
                        mysqli_query($destinationConn, $insertSql);
                    }
                    //Delete all rows from source table
                    $sql4 = "DELETE FROM publishable_surveys";
                    if(mysqli_query($sourceConn, $sql4)) 
                    {
						$message = "Survey published";
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
						header("refresh:5; url=index.html");
                        exit;
                    }
                } 
                else 
                {
					$sql5 = "DROP TABLE `saved_surveys`.`$destination_table`";
					if(mysqli_query($destinationConn, $sql5))
					{
						echo '<script type="text/javascript">
						alert("No questions selected")
						window.location.href="index.html"
						</script>';
						exit;
					}
                }
            }
        }
    } 
    else 
    {
        echo "Error executing SQL query: " . mysqli_error($destinationConn);
        exit;
    }
} 

if (!is_null($destination_table) && $name2 === "draft") 
{
    $sql1 = "SELECT COUNT(*) AS tableCount FROM information_schema.tables WHERE table_schema = 'drafted_surveys' AND table_name = '$destination_table'";
    $result1 = mysqli_query($destinationConn1, $sql1);
	$sql2 = "SELECT COUNT(*) AS tableCount FROM information_schema.tables WHERE table_schema = 'saved_surveys' AND table_name = '$destination_table'";
	$result2 = mysqli_query($destinationConn, $sql2);
    if ($result1) 
    {
        $row = mysqli_fetch_assoc($result1);
        $tableCount = $row['tableCount'];
		$tableCount1 = 0;
		if($result2)
		{
			$row1 = mysqli_fetch_assoc($result2);
			$tableCount1 = $row1['tableCount'];
		}
        if ($tableCount > 0 or $tableCount1 > 0) 
        {
            echo '<script type="text/javascript">
                alert("Given survey name already exists...Draft the survey with a new name")
                window.location.href="index.html"
                </script>';
        } 
        else 
        {
            $sql3 = "CREATE TABLE `drafted_surveys`.`$destination_table` LIKE `registration`.`publishable_surveys`";
            $result3 = mysqli_query($destinationConn1, $sql3);
            if ($result3 === false) 
            {
                // An error occurred during table creation
                echo "Error creating table: " . mysqli_error($destinationConn1);
                exit;
            } 
            else 
            {
                if ($result->num_rows > 0) 
                {
                    //Creating destination table dynamically based on the value given by the user while submitting created survey.
                    // Iterate through each row of the result
                    while ($row = mysqli_fetch_assoc($result)) 
                    {
                        // Insert each row into the destination table
                        $insertSql = "INSERT INTO `$destination_table` (pid, question, type) VALUES (
                            " . $row["pid"] . ",
                            '" . $row["question"] . "',
                            '" . $row["type"] . "'
                        )";

                        mysqli_query($destinationConn1, $insertSql);
                    }
                    //Delete all rows from source table
                    $sql4 = "DELETE FROM publishable_surveys";
                    if (mysqli_query($sourceConn, $sql4)) 
                    {
                        echo '<script>alert("Survey drafted");</script>';
                        // Redirect to another page after a delay
                        header("refresh:1; url=index.html");
                        exit;
                    }
                } 
                else 
                {
					$sql5 = "DROP TABLE `drafted_surveys`.`$destination_table`";
					if(mysqli_query($destinationConn, $sql5))
					{
						echo '<script type="text/javascript">
						alert("No questions selected")
						window.location.href="index.html"
						</script>';
						exit;
					}
                }
            }
        }
    } 
    else 
    {
        echo "Error executing SQL query: " . mysqli_error($destinationConn1);
        exit;
    }
}

// Close the connections
mysqli_close($sourceConn);
mysqli_close($destinationConn);
mysqli_close($destinationConn1);
?>
