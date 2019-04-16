<?php
	session_start();
	session_unset();
	session_destroy();
	$_SESSION['Authenticated']=false;
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="bef_login">
	<div class="title">Sign In</div>
	<div class="content">
		<form action="login.php" method="post">
			<table>
				<tr>
					<th>Username:</th>		
					<th><input type="text" name="username"><br></th>
				</tr>
				<tr>
					<th>Password:
					<th><input type="password" name="password"><br></th>
				</tr>
			</table>
			<input type="submit" value="Sign In">
		</form>
		<p><a href="register.php">or Register</a></p>
	</div>
</body>
</html>