<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

// Create a new database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the question and type from the submitted form
$question = $_POST['question'];
$type = $_POST['type'];

// Prepare the SQL query to delete the question from the database
$sql = "DELETE FROM publishable_surveys WHERE question = ? AND type = ?";
$stmt = mysqli_prepare($conn, $sql);

// Bind the parameters and execute the prepared statement
mysqli_stmt_bind_param($stmt, "ss", $question, $type);
if (mysqli_stmt_execute($stmt)) 
{
    // Redirect to survey.php if the deletion is successful
    header('Location: survey.php');
}
else 
{
    // Display an error message if the deletion fails
    echo "Error deleting the survey question.".mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
