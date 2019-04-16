<?php
	session_start();
	require_once "connect.php";
	
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$username=$_SESSION["username"];
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
	if(!isset($_GET['page'])){
		$page=1;
	}
	else{
		$page=$_GET['page'];
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
	<?php
		if($_SESSION['identity']=='user'){
					unset($_SESSION['username']);
					session_destroy();
	?>
			<div class="title">Permission Denied</div>
			<meta http-equiv=REFRESH CONTENT=2;url=index.php>
	<?php
		}
		else{
	?>
	<div class="title">Member Management</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="home_page.php"><input type="submit" value="首頁"></form></li>
				<li><form action="new_user.php"><input type="submit" value="新增使用者"></form></li>
				<li><form action="logout.php"><input type="submit" value="Log out"></form></li>
			</ul>
		</nav>
		<table id="member_mgnt">
			<tr class="caption">
				<th class="begin">Username</th>
				<th class="middle">Name</th>
				<th class="middle">E-mail</th>
				<th class="middle">Identity</th>
				<th class="end">Option</th>
			</tr>
				<?php
					$sql="SELECT userID,username,name,email,identity FROM user ORDER BY userID";
					$getdata=$connect->prepare($sql);
					$getdata->execute();
					$number_of_result=$getdata->rowCount();
					$number_of_page=ceil($number_of_result/$result_per_page);
					$getdata=$connect->prepare($sql." LIMIT ".($page-1)*$result_per_page.",".$result_per_page);
					$getdata->execute();
					while($person=$getdata->fetch()){
            			if(!strcmp($person['username'],$username)){
         		?>
            		<tr class="admin">
                		<th><?php echo $person['username'];?></th>
                		<th class="middle"><?php echo $person['name'];?></th>
                		<th class="middle"><?php echo $person['email'];?></th>
                		<th class="middle"><?php echo $person['identity'];?></th>
                		<th class="end"></th>
                	</tr>
              	<?php
                		}
            			else{
            	?>
            		<tr class="data">
                		<th><?php echo $person['username'];?></th>
                		<th class="middle"><?php echo $person['name'];?></th>
                		<th class="middle"><?php echo $person['email'];?></th>
                		<th class="middle"><?php echo $person['identity'];?></th>
                		<th class="end">
                			<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
                			<?php
                				if($person['identity']=="user"){
                			?>
                			<input onClick="return confirm('Are you sure you want to premote this user ?')" type="submit" name="submit" value="promote" id="promote">
                			<?php
                				}
                				elseif($person['identity']=="admin"){
                			?>
                			<input onclick="return confirm('Are you sure you want to demote this user ?')" type="submit" name="submit" value="demote" id="demote">
                			<?php
                				}
                			?>
                			<input onclick="return confirm('Are you sure you want to delete this user ?')" type="submit" name="submit" value="delete" id="delete">
                			<input type="hidden" name="userID" value="<?php echo $person['userID'];?>">
                			</form>
                		</th>
                	</tr>
                <?php
                		}
              		}
            	?>
		</table>
		<br>
		<?php
			for($page=1;$page<=$number_of_page;$page++){
				echo '<a href="member_management.php?page='.$page.'">'.$page.'</a>&nbsp;&nbsp;';
			}
		?>
	</div>
	<?php
			}
	?>
	<br>
</body>
</html>
