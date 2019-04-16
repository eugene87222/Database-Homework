<?php
	session_start();
	require_once "connect.php";
	
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$username=$_SESSION["username"];
		$stmt=$connect->prepare("SELECT userID,username,name,email,identity FROM user WHERE username=:username");
		$stmt->execute(array("username"=>$username));
		$row=$stmt->fetch();
		$userID=$row[0];
		$name=$row[2];
		$email=$row[3];
		$identity=$row[4];
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
			$bookID=$_POST["ID"];
			$del=$connect->prepare("DELETE FROM booking WHERE ID=:bookID");
			$del->execute(array("bookID"=>$bookID));
		}
		elseif($_POST["submit"]=="edit"){
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
	<div class="title">Booking Management</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="home_page.php"><input type="submit" value="首頁"></form></li>
				<li><form action="logout.php"><input type="submit" value="Log out"></form></li>
			</ul>
		</nav>
			<?php
				$sql="SELECT booking.ID AS ID,house.name AS housename,price,location,user.name AS owner,check_in,check_out FROM booking LEFT JOIN house ON house.ID=booking.houseID LEFT JOIN user ON user.userID=house.ownerID LEFT JOIN location ON locationID=location.ID WHERE booking.visitorID=$userID ORDER BY house.ID";
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
				<th class="begin">ID</th>
				<th class="middle">Name</th>
				<th class="middle">Price</th>
				<th class="middle">Location</th>
				<th class="middle">Owner</th>
				<th class="middle">Check in</th>
				<th class="middle">Check out</th>
				<th class="end">Option</th>
			</tr>
				<?php
						while($booking=$getdata->fetch()){
	       ?>
	        		<tr class="data">
	          		<th><?php echo $booking['ID'];?></th>
	          		<th class="middle"><?php echo $booking['housename'];?></th>
	          		<th class="middle"><?php echo $booking['price'];?></th>
	          		<th class="middle">
	          			<?php
	          				if($booking['location']=='')
	          					echo 'unknown';
	          				else
	          					echo $booking['location'];?></th>
	          		<th class="middle"><?php echo $booking['owner'];?></th>
	          		<th class="middle"><?php echo $booking['check_in'];?></th>
	          		<th class="middle"><?php echo $booking['check_out'];?></th>
	          		<th class="end">
	          			<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
	          				<input onclick="return confirm('Are you sure you want to delete this order ?')" type="submit" name="submit" value="delete" id="delete">
	          				<input type="hidden" name="ID" value="<?php echo $booking['ID'];?>">
	          			</form>
	          			<form class="modify" action="edit_order.php" method="post">
	          				<input type="submit" value="edit" id="edit" style="margin-top: 2px;">
	          				<input type="hidden" name="ID" value="<?php echo $booking['ID'];?>">
	          			</form>
	          		</th>
	          	</tr>
	        <?php
	          	}
        	?>
		</table>
		<?php
				for($page=1;$page<=$number_of_page;$page++){
					echo '<a href="booking_page.php?page='.$page.'">'.$page.'</a>&nbsp;&nbsp;';
				}
	   	}
    ?>
	</div>
	<br>
</body>
</html>
