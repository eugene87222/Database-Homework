<?php
	session_start();
	$usernameErr=$passwordErr=$confirmErr=$nameErr=$emailErr=$identityErr="";
	$username=$name=$email=$identity="";
	require_once "connect.php";
	if($_SERVER["REQUEST_METHOD"]=="POST"){
  		if(empty($_POST["username"])){
    		$usernameErr="Username is required";
  		}
  		else{
  			$stmt=$connect->prepare("SELECT username,password,identity FROM user WHERE username=:username");
			$stmt->execute(array("username"=>$_POST["username"]));
			if($stmt->rowCount()==1){
				$usernameErr="This username is already in use!";
			}
  			elseif(preg_match("/\s/",$_POST["username"])){
  				$usernameErr="Username cannot contain space";
  			}
  			else{
  				$usernameErr="";
  				$username=strip_tags($_POST["username"]);
  			}
  		}

  		if(empty($_POST["password"])){
    		$passwordErr="Password is required";
  		}
  		else{
  			$passwordErr="";
  		}

  		if(empty($_POST["confirm"])){
    		$confirmErr="Please confirm your password";
  		}
  		else{
  			$confirmErr="";
  			if(!empty($_POST["password"])&&($_POST["password"]!=$_POST["confirm"])){
  				$confirmErr="Please confirm your password correctly";
  			}
  		}

  		if(empty($_POST["name"])){
    		$nameErr="Name is required";
  		}
  		else{
  			$nameErr="";
  			$name=strip_tags($_POST["name"]);
  		}

  		if(empty($_POST["email"])){
    		$emailErr="Email is required";
  		}
  		else{
  			if(!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)){
      			$emailErr="Invalid email format"; 
    		}
    		else{
    			$emailErr="";
    			$email=$_POST["email"];
    		}
  		}

  		if($_POST["identity"]==""){
  			$identityErr="Please choose an identity";
  		}
  		else{
  			$identityErr="";
  			$identity=$_POST["identity"];
  		}
	}
	if(empty($usernameErr)&&empty($passwordErr)&&empty($confirmErr)&&empty($nameErr)&&empty($emailErr)&&empty($identityErr)&&$_SERVER["REQUEST_METHOD"]=="POST"){
		$stmt=$connect->prepare("SELECT username FROM user WHERE username=:username");
		$hash=password_hash($_POST["password"],PASSWORD_BCRYPT);
		$stmt->execute(array("username"=>$_POST["username"]));
		if($stmt->rowCount()==0){
			$insert=$connect->prepare("INSERT INTO user(username,password,name,email,identity) VALUES(?,?,?,?,?)");
			$insert->execute(array($username,$hash,$name,$email,$identity));
			echo<<<EOT
				<!DOCTYPE>
				<html>
				<body>
				<script>
					alert("Add a user successfully!!");
					window.location.replace('adminpage.php')
				</script>
				</body>
				</html>
EOT;		
			exit();
		}
		else{
			echo<<<EOT
				<!DOCTYPE>
				<html>
				<body>
				<script>
					alert("This username is already in use!");
				</script>
				</body>
				</html>
EOT;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="register">
	<?php
		if($_SESSION){
			if($_SESSION["identity"]=="admin"){
	?>	
	<div class="title">Add a user</div>
	<div class="content">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<table>
				<tr class="required"><th></th><th><span>*<?php echo $usernameErr;?></span></th></tr>
				<tr>
					<th>Username:</th>
					<th><input type="text" name="username" value="<?php echo $username;?>"></th>
				</tr>
				
				<tr class="required"><th></th><th><span>*<?php echo $passwordErr;?></span></th></tr>
				<tr>
					<th>Password:</th>
					<th><input type="password" name="password"></th>
				</tr>
				
				<tr class="required"><th></th><th><span>*<?php echo $confirmErr;?></span></th></tr>
				<tr>
					<th>Confirm Password:</th>
					<th><input type="password" name="confirm"></th>
				</tr>
				
				<tr class="required"><th></th><th><span>*<?php echo $nameErr;?></span></th></tr>
				<tr>
					<th>Name:</th>
					<th><input type="text" name="name" value="<?php echo $name;?>"></th>
				</tr>

				<tr class="required"><th></th><th><span>*<?php echo $emailErr;?></span></th></tr>
				<tr>
					<th>Email:</th>
					<th><input type="text" name="email" value="<?php echo $email;?>"></th>
				</tr>

				<tr class="required"><th></th><th><span>*<?php echo $identityErr;?></span></th></tr>
				<tr>
					<th>Identity:</th>
					<th>
						<select name="identity">
							<option value="" selected="selected">Choose identity</option>
   							<option <?php if ($identity=="admin"){?>selected="true" <?php }; ?>value="admin">Admin</option>
   							<option <?php if ($identity=="user"){?>selected="true" <?php }; ?>value="user">User</option>
						</select>
					</th>
				</tr>
			</table>
			<input type="submit" value="Add">
		</form>
		<input onclick="window.location.replace('adminpage.php');" type="submit" value="Cancel">
	</div>
	<?php
			}
			else{
				unset($_SESSION["username"]);
				session_destroy();
	?>
	<div class="title">Permission Denied</div>
	<meta http-equiv=REFRESH CONTENT=2;url=index.php>
	<?php
			}
		}
		else{
	?>
	<div class="title">Permission Denied</div>
	<meta http-equiv=REFRESH CONTENT=2;url=index.php>
	<?php
		}
	?>
</body>
</html>