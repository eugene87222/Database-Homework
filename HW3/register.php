<?php
	$usernameErr=$passwordErr=$confirmErr=$nameErr=$emailErr="";
	$username=$name=$email="";
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
	}
?>
<?php
	if(empty($usernameErr)&&empty($passwordErr)&&empty($confirmErr)&&empty($nameErr)&&empty($emailErr)&&$_SERVER["REQUEST_METHOD"]=="POST"){
		$stmt=$connect->prepare("SELECT username FROM user WHERE username=:username");
		$hash=password_hash($_POST["password"],PASSWORD_BCRYPT);
		$stmt->execute(array("username"=>$_POST["username"]));
		if($stmt->rowCount()==0){
			$insert=$connect->prepare("INSERT INTO user(username,password,name,email) VALUES(?,?,?,?)");
			$insert->execute(array($username,$hash,$name,$email));
			echo<<<EOT
				<!DOCTYPE>
				<html>
				<body>
				<script>
					alert("Register successfully!!");
					window.location.replace("index.php");
				</script>
				</body>
				</html>
EOT;
		}
		else{
			echo<<<EOT
				<!DOCTYPE>
				<html>
				<body>
				<script>
					alert("This username is already in use!");
					window.location.replace("register.php");
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
	<div class="title">Register</div>
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
			</table>
			<input type="submit" value="Sign Up" class="general_button">
		</form>
		<p><a href="index.php">or Sign in</a></p>
	</div>
</body>
</html>