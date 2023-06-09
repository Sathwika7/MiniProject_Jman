<?php
header("refresh: 4;");

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'registration';
echo "<h3>Selected survey questions:</h3>";
$mysqli = new mysqli($servername, $username, $password, $database);
// Checking for connections
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// SQL query to select data from database
$sql = "SELECT * FROM publishable_surveys";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) 
{
    while ($row = $result->fetch_assoc()) {
        // Display HTML form with database values pre-populated
        echo "<form method='post' action='delete_question.php'>";
        echo "<div style='display: flex; align-items: center; justify-content: space-between;'>";
        echo "<input type='hidden' name='question' value='" . $row['question'] . "'>";
        echo $row['question'];
        echo "<input type='hidden' name='type' value='" . $row['type'] . "'>";
        echo "<button class='button1' type='submit'>Delete</button>";
        echo "</div>";
        echo "</form>";
    }
}

$mysqli->close();
?>
