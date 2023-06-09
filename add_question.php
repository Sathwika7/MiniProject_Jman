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

// Prepare the SQL query with placeholders
$sql = "INSERT INTO publishable_surveys (question, type) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// Bind the parameters and execute the prepared statement
mysqli_stmt_bind_param($stmt, "ss", $question, $type);
if (mysqli_stmt_execute($stmt)) 
{
    header('Location: index.html');
} 
else 
{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
