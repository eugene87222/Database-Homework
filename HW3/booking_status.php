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
	<div class="title">Booking Status</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="home_page.php"><input type="submit" value="首頁"></form></li>
				<li><form action="logout.php"><input type="submit" value="Log out"></form></li>
			</ul>
		</nav>
		<?php
			$sql="SELECT house.name AS housename,check_in,check_out,user.name AS username FROM booking LEFT JOIN user ON user.userID=booking.visitorID LEFT JOIN house ON house.ID=booking.houseID ORDER BY house.ID";
			$getdata=$connect->prepare($sql);
			$getdata->execute();
			$number_of_result=$getdata->rowCount();
			$number_of_page=ceil($number_of_result/$result_per_page);
			$getdata=$connect->prepare($sql." LIMIT ".($page-1)*$result_per_page.",".$result_per_page);
			$getdata->execute();
			if($getdata->rowCount()<=0) echo "Nothing";
			else{
		?>
		<table id="booking_mgnt">
			<tr class="caption">
				<th class="begin">House</th>
				<th class="middle">Check in</th>
				<th class="middle">Check out</th>
				<th class="end">Visitor</th>
			</tr>
				<?php
					while($booking=$getdata->fetch()){
         		?>
          		<tr class="data">
              		<th><?php echo $booking['housename'];?></th>
              		<th class="middle"><?php echo $booking['check_in'];?></th>
              		<th class="middle"><?php echo $booking['check_out'];?></th>
              		<th class="end"><?php echo $booking['username'];?></th>
              	</tr>
            <?php
          		}
        	?>
		</table>
		<br>
		<?php
				for($page=1;$page<=$number_of_page;$page++){
					echo '<a href="booking_status.php?page='.$page.'">'.$page.'</a>&nbsp;&nbsp;';
				}
			}
		?>
	</div>
	<?php
			}
	?>
	<br>
</body>
</html>
