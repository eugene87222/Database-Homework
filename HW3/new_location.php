<?php
	session_start();
	require_once "connect.php";
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$username=$_SESSION['username'];
		$userID=$_SESSION['userID'];
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
	$locationErr="";
	$location="";
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(empty($_POST['location'])||strip_tags(trim($_POST['location']))==''){
  		$locationErr='Location is required';
		}
		else{
			$location=strip_tags(trim($_POST['location']));
			$stmt=$connect->prepare("SELECT location FROM location WHERE location=:location");
			$stmt->execute(array("location"=>$location));
			if($stmt->rowCount()==1){
				$locationErr="This location already exists!";
			}
			else{
				$locationErr='';
			}
		}
	}
	$done=false;
	if(empty($locationErr)&&$_SERVER["REQUEST_METHOD"]=="POST"){
		$do=$connect->prepare("INSERT INTO location(location) VALUES(?)");
		$do->execute(array($location));
		$done=true;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="add_house"  onunload="unLoad();">
	<div class="content">
		<?php if($done){ ?>
		<script type="text/javascript">
			function unLoad(){		
					window.opener.location.href = window.opener.location.href;
			}
			window.close();
		</script>
		<?php } ?>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<table>
				<tr class="required"><th></th><th><span>*<?php echo $locationErr;?></span></th></tr>
				<tr>
					<th>Location:</th>
					<th><input type="text" name="location" value="<?php echo $location;?>"></th>
				</tr>
			</table>
			<input type="submit" value="Add" class="general_button">
		</form>
		<form action="location_management.php"><input type="submit" value="Cancel" class="general_button"></form>
	</div>
	<br>
</body>
</html>
