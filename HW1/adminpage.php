<?php
	session_start();
	require_once "connect.php";
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$_SESSION["identity"]="admin";
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
	if(isset($_POST["submit"])){
		if($_POST["submit"]=="delete"){
			$userID=$_POST["userID"];
			$del=$connect->prepare("DELETE FROM user WHERE userID=:userID");
			$del->execute(array("userID"=>$userID));
		}
		elseif($_POST["submit"]=="promote"){
			$userID=$_POST["userID"];
			$pro=$connect->prepare("UPDATE user SET identity='admin' WHERE userID=:userID");
			$pro->execute(array("userID"=>$userID));
		}
		elseif($_POST["submit"]=="demote"){
			$userID=$_POST["userID"];
			$pro=$connect->prepare("UPDATE user SET identity='user' WHERE userID=:userID");
			$pro->execute(array("userID"=>$userID));
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="aft_login">
	<div class="title">Admin Page</div>
	<div class="content">
		<table>
			<tr class="caption">
				<th class="begin">Username</th>
				<th class="middle">Name</th>
				<th class="middle">E-mail</th>
				<th class="middle">Identity</th>
				<th class="end">Modify</th>
			</tr>
				<?php
					$getdata="SELECT userID,username,name,email,identity FROM user ORDER BY userID";
					$data=$connect->query($getdata);
					while($person=$data->fetchObject()){        	
            			if(!strcmp($person->username,$username)){
         		?>
            		<tr class="admin">
                		<th><?php echo $person->username;?></th>
                		<th class="middle"><?php echo $person->name;?></th>
                		<th class="middle"><?php echo $person->email;?></th>
                		<th class="middle"><?php echo $person->identity;?></th>
                		<th class="end"></th>
                	</tr>
              	<?php
                		}
            			else{
            	?>
            		<tr class="data">
                		<th><?php echo $person->username;?></th>
                		<th class="middle"><?php echo $person->name;?></th>
                		<th class="middle"><?php echo $person->email;?></th>
                		<th class="middle"><?php echo $person->identity;?></th>
                		<th class="end">
                			<form class="modify" action="adminpage.php" method="post">
                			<?php
                				if($person->identity=="user"){
                			?>
                			<input onClick="return confirm('Are you sure you want to premote this user ?')" type="submit" name="submit" value="promote" id="promote">
                			<input type="hidden" name="userID" value="<?php echo $person->userID;?>">
                			<?php
                				}
                				elseif($person->identity=="admin"){
                			?>
                			<input onclick="return confirm('Are you sure you want to demote this user ?')" type="submit" name="submit" value="demote" id="demote">
                			<input type="hidden" name="userID" value="<?php echo $person->userID;?>">
                			<?php
                				}
                			?>
                			<input onclick="return confirm('Are you sure you want to delete this user ?')" type="submit" name="submit" value="delete" id="delete">
                			<input type="hidden" name="userID" value="<?php echo $person->userID;?>">
                			</form>
                		</th>
                	</tr>
                <?php
                		}
              		}
            	?>
		</table>
		<form action="new_user.php">
			<input type="submit" value="New user">
		</form>
		<form action="logout.php">
			<input type="submit" value="Log out">
		</form>
	</div>
</body>
</html>
