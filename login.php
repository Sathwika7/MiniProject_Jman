<?php
session_start();
$servername="localhost";
$username="root";
$password="";
$dbname="registration";
$conn=mysqli_connect($servername,$username,$password,$dbname);
"<body>";
if(!$conn)
{
	die("Conection failed: ".mysqli_connect_error());
}
error_reporting(E_ERROR | E_PARSE);
$email=$_POST["email"];
$pwd=$_POST["pwd"];
$flag = true;
$sql="select * from register where email='$email'";
$result=mysqli_query($conn,$sql);
if(!$result || mysqli_num_rows($result) == 0)
{
	echo '<script type="text/javascript">
			alert("You might have entered incorrect email-id or Your email-id is not registered...Please check!")
			window.location.href="newuser.html"
		</script>';
	$flag = false;
}
else
{
	$row=mysqli_fetch_assoc($result);
	if($row['email'] == "$email" and $row['pwd'] == "$pwd")
	{
		$_SESSION['variable1'] = $email;
		header('Location: display_surveys.php');
		exit;
	}
	else if($row['email'] == "$email" and $row['pwd'] != "$pwd")
	{
		echo '<script type="text/javascript">
			alert("You have entered wrong password")
			window.location.href="newuser.html"
			</script>';
		$flag = false;
	}
}
echo <<<END
<style type="text/css">
body { background-image: linear-gradient(to right, #a57283, #795560, #503940, #2a2023, #000000); }
a { color: pink; text-decoration: none; text-align:center; margin: 0; position: absolute; 
top: 48%; left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
a:hover { color: gray; text-decoration: underline; text-align: center; margin: 0;position: absolute; 
top: 48%; left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
h3 { color: #008080; text-align: center; font-family: Garamond; margin: 0; position: absolute; top: 40%; 
left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
</style>
END;
if($flag == false)
{
	echo '<center><a href="newuser.html"><b>Login again</b></a></center>';
}
else
{
	echo '<center><a href="Homepage.html"><b>Home page</b></a></center>';
}
"</body>";
?>
