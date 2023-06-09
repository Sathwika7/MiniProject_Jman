<?php
session_start();

$email = $_POST['email'];
$values = array();

foreach ($_POST as $key => $value) {
    // Exclude non-question fields
    if (strpos($key, 'question_') === 0) {
        // Check if the value is an array
        if (is_array($value)) {
            // Join the array values into a comma-separated string
            $value = implode(', ', $value);
        }

        $values[] = $value;
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "responses";
$tableName = $_POST['name'];

// Create a connection to the database
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Generate the placeholders for the prepared statement
$placeholders = implode(', ', array_fill(0, count($values), '?'));

// Generate and execute the INSERT statement
$sql = "INSERT INTO `$tableName` VALUES (?, $placeholders)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Generate the type definition string for bind_param
    $typeString = 's' . str_repeat('s', count($values));

    // Create an array with the email value followed by the other values
    $bindParams = array_merge([$typeString], [$email], $values);

    // Bind the values to the prepared statement
    mysqli_stmt_bind_param($stmt, ...$bindParams);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Check if the insert was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Survey completed');</script>";
        // Redirect to another page after a delay
        header("refresh:1; url=display_surveys.php");
        exit;
    } else {
        echo "No values inserted.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>
