<?php
	session_start();
	require_once "connect.php";
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$_SESSION["identity"]="user";
		$username=$_SESSION["username"];
		if(isset($_GET['page']))
			$page=$_GET['page'];
		else
			$page=1;
		$postperpage=2;

		$stmt=$connect->prepare("SELECT username,name,email,identity FROM user WHERE username=:username");
		$stmt->execute(array("username"=>$username));
		$row=$stmt->fetch();
		$name=$row[1];
		$email=$row[2];
		$identity=$row[3];
	}
	else{
		echo<<<EOT
			<!DOCTYPE>
			<html>
			<body>
			<script>
				alert("Please login first.");
				window.location.replace('index.php');
			</script>
			</body?
			</html>
EOT;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="aft_login">
	<div class="title">User Page</div>
	<div class="content">
		<table>
			<tr class="caption">
				<th class="begin">Username</th>
				<th class="middle">Name</th>
				<th class="end">E-mail</th>
			</tr>
			<tr class="data">
				<th><?php echo $username?></th>
				<th class="middle"><?php echo $name?></th>
				<th class="end"><?php echo $email?></th>
			</tr>
		</table>
		<form action="logout.php">
			<input type="submit" value="Log out">
		</form>
	</div>
</body>
</html>
