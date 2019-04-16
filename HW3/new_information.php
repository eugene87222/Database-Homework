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
	$infoErr="";
	$information="";
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(empty($_POST['information'])||strip_tags(trim($_POST['information']))==''){
  			$infoErr='Information is required';
		}
		else{
			$information=strip_tags(trim($_POST['information']));
			$stmt=$connect->prepare("SELECT information FROM all_information WHERE information=:information");
			$stmt->execute(array("information"=>$information));
			if($stmt->rowCount()==1){
				$infoErr="This information already exists!";
			}
			else{
				$infoErr='';
			}
		}
	}
	$done=false;
	if(empty($infoErr)&&$_SERVER["REQUEST_METHOD"]=="POST"){
		$do=$connect->prepare("INSERT INTO all_information(information) VALUES(?)");
		$do->execute(array($information));
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
				<tr class="required"><th></th><th><span>*<?php echo $infoErr;?></span></th></tr>
				<tr>
					<th>Information:</th>
					<th><input type="text" name="information" value="<?php echo $information;?>"></th>
				</tr>
			</table>
			<input type="submit" value="Add" class="general_button">
		</form>
		<form action="info_management.php"><input type="submit" value="Cancel" class="general_button"></form>
	</div>
	<br>
</body>
</html>
