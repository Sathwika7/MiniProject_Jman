<?php
	//To display questions in a form
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "saved_surveys";
	$name = $_POST['name'];
	$num = 0;
	// Create a connection to the drafted_surveys database
	$conn = mysqli_connect($servername, $username, $password, $database);
	// Check connection
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	$sql = "SELECT * FROM `$name`";
	$result = mysqli_query($conn, $sql);
	$name1 = substr($name,0,-9);
	echo "<h2 align='center'>$name1</h2>";
	echo '<form action="submitted_surveys.php" method="post">';
	echo '<input type="hidden" name="email" value="$email">';
	// Display the questions dynamically
	while ($row = mysqli_fetch_assoc($result)) 
	{
		$num++;
		$questionId = $row['pid'];
		$questionText = $row['question'];
		$questionType = $row['type'];

		echo '<p>'. $num . '. ' . $questionText . '</p>';

		if ($questionType === 'radio') 
		{
			// Display radio question
			echo '<input type="radio" name="question_' . $questionId . '" value="Yes" required>Yes<br>';
			echo '<input type="radio" name="question_' . $questionId . '" value="No" required>No<br>';
		} 
		elseif ($questionType === 'checkbox') 
		{
			// Display checkbox question
			echo '<input type="checkbox" name="question_' . $questionId . '[]" value="Good" checked>Good<br>';
			echo '<input type="checkbox" name="question_' . $questionId . '[]" value="Very Good">Very Good<br>';
			echo '<input type="checkbox" name="question_' . $questionId . '[]" value="Not Good">Not Good<br>';
			
		}
		elseif ($questionType === 'textarea') 
		{
			// Display textarea question
			echo '<textarea name="question_' . $questionId . '"required></textarea><br>';
		} 
		elseif ($questionType === 'number') 
		{
			// Display number question
			echo '<input type="number" id="number" min="1" max="5" name="question_' . $questionId . '"required> (Rate from 1 to 5)<br>';
		} 
		else 
		{
			// Display other types of questions
			echo '<input type="text" name="question_' . $questionId . '"required><br>';
		}
	}
	echo '<button type="submit">Submit</button>
	echo '</form>';
	?>
		
		