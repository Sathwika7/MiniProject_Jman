<?php
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
$uname=$_POST["uname"];
$pwd=$_POST["pwd"];
$email=$_POST["email"];
$profession=$_POST["profession"];
$sql="select * from register where email='$email'";
$result=mysqli_query($conn,$sql);
if(!$result || mysqli_num_rows($result) == 0)
{
	if(mysqli_query($conn,$sql))
	{
		if(substr($profession,0,1) == "t" || substr($profession,0,1) == "T")
		{
			$profession = "teacher";
		}
		else if(substr($profession,0,1) == "s" || substr($profession,0,1) == "S")
		{
			$profession = "student";
		}
		$sql1="insert into register values('$uname', '$pwd', '$email', '$profession')";
		if(mysqli_query($conn,$sql1))
		{
			echo '<script type="text/javascript">
			alert("Registered successfully!")
			window.location.href="newuser.html"
		  </script>';
		}
	}
	else
	{
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
	mysqli_close($conn);
}
else
{
	echo '<script type="text/javascript">
			alert("You have already registered! Login here!!")
			window.location.href="newuser.html"
		  </script>';
}
echo <<<END
<style type="text/css">
body { background-image: linear-gradient(to right, #a57283, #795560, #503940, #2a2023, #000000); }
a:link { color: #008080; text-decoration: none; text-align:center; margin: 0; position: absolute;
top: 48%; left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
a:hover { color: gray; text-decoration: underline; text-align: center; margin: 0;position: absolute;
top: 48%; left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
h3 { color: #008080; text-align: center; font-family: Garamond; margin: 0; position: absolute; top: 40%; 
left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
</style>
END;
echo '<center><a href="newuser.html"><b>Login here</b></a></center>';
"</body>";
?>
