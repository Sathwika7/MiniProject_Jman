<?php
$servername="localhost";
$username="root";
$password="";
$dbname="registration";
$conn=mysqli_connect($servername,$username,$password,$dbname);
if(!$conn)
{
	die("Conection failed: ".mysqli_connect_error());
}
error_reporting(E_ERROR | E_PARSE);
$uname=$_POST["uname"];
$pwd=$_POST["pwd"];
$flag = true;
if($uname == "")
{
	echo "<h3>Please enter user id</h3>";
	$flag = false;
}
elseif($pwd == "")
{
	echo "<h3>Please enter password</h3>";
	$flag = false;
}
if($flag == true)
{
	$sql="select * from admin where uname='$uname'";
	$result=mysqli_query($conn,$sql);
	if(!$result || mysqli_num_rows($result) == 0)
	{
		echo '<script type="text/javascript">
			alert("You entered invalid username")
			window.location.href="Admin.html"
		</script>';
	}
	else
	{
		$row=mysqli_fetch_assoc($result);
		if($row['uname'] == "$uname" and $row['pwd'] == "$pwd")
		{
			header('Location: divide.html');
			
		}
		else if($row['uname'] == "$uname" and $row['pwd'] != "$pwd")
		{
			echo '<script type="text/javascript">
			alert("You entered invalid password")
			window.location.href="Admin.html"
			</script>';
		}
		
	}
}
echo <<<END
<style type="text/css">
body { background-image: linear-gradient(to right, #a57283, #795560, #503940, #2a2023, #000000); }
a:link { color: #9DD2DB; text-decoration: none; text-align:center; margin: 0; position: absolute;
top: 48%; left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
a:hover { color: gray; text-decoration: underline; text-align: center; margin: 0;position: absolute;
top: 48%; left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
h3 { color: #008080; text-align: center; font-family: Garamond; margin: 0; position: absolute; top: 40%; 
left: 50%; margin-right: -50%; transform: translate(-50%, -50%);}
</style>
END;
echo '<center><a href="Homepage.html"><b>Go to home page</b></a></center>';
?>
