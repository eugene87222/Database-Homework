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
	if(isset($_POST['ID']))
		$_SESSION['bookingID']=$_POST['ID'];
	$bookingID=$_SESSION['bookingID'];
	$Errmsg='';
	$checkErr=false;
	if(isset($_POST["submit"])){
		if($_POST['submit']=='OK'){
			$check_in=$_POST['check_in'];
			$check_out=$_POST['check_out'];
			if($check_in==''||$check_out==''){
				$checkErr=true;
				$Errmsg='Please choose check in/out time.';
			}
			elseif($check_in>=$check_out){
				$checkErr=true;
				$Errmsg='You cannot check out before check in.';
			}
			elseif($check_in<date("Y-m-d")){
				$checkErr=true;
				$Errmsg='You cannot go back to the past.';
			}
			else{
				$room_booked=$connect->prepare("SELECT ID,check_in AS checkin,check_out AS checkout FROM booking WHERE houseID=:houseID");
				$room_booked->execute(array("houseID"=>$_POST['houseID']));
				while($booked=$room_booked->fetch()){
					if(!($check_in>=$booked['checkout']||$check_out<=$booked['checkin'])){
						if($booked['ID']!=$_SESSION['bookingID'])
							$checkErr=true;
					}
				}
				if($checkErr) $Errmsg='The room has been reserved. Please choose another check in/out time.';
			}
			if(!$checkErr){
				$do=$connect->prepare("UPDATE booking SET check_in=:check_in,check_out=:check_out WHERE ID=:bookingID");
				$do->execute(array("check_in"=>$check_in,"check_out"=>$check_out,"bookingID"=>$bookingID));
				echo<<<EOT
					<!DOCTYPE>
					<html>
					<body>
					<script>
						window.location.replace('booking_page.php');
					</script>
					</body?
					</html>
EOT;
			}
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
				<li><form action="booking_page.php"><input type="submit" value="取消"></form></li>
			</ul>
		</nav>
		<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
		<table id="booking_mgnt">
			<tr class="caption">
				<th class="begin">ID</th>
				<th class="middle">Name</th>
				<th class="middle">Price</th>
				<th class="middle">Location</th>
				<th class="middle">Owner</th>
				<th class="middle">Check in</th>
				<th class="end">Check out</th>
			</tr>
				<?php
					$getdata=$connect->prepare("SELECT booking.ID AS ID,house.ID AS houseID,house.name AS housename,price,location,user.name AS owner,check_in,check_out FROM booking LEFT JOIN house ON house.ID=booking.houseID LEFT JOIN user ON user.userID=house.ownerID LEFT JOIN location ON locationID=location.ID WHERE booking.ID=:bookingID ORDER BY house.ID");
					$getdata->execute(array("bookingID"=>$bookingID));
					$booking=$getdata->fetch();
         		?>
    		<tr class="data">
      		<th><?php echo $booking['ID'];?></th>
      		<th class="middle"><?php echo $booking['housename'];?></th>
      		<th class="middle"><?php echo $booking['price'];?></th>
      		<th class="middle"><?php echo $booking['location'];?></th>
      		<th class="middle"><?php echo $booking['owner'];?></th>
      		<th class="middle" style="font-size: 150%;">
      			<input type="date" name="check_in" value="<?php if(!isset($check_in)) echo $booking['check_in']; else echo $check_in; ?>">
      		</th>
      		<th class="end" style="font-size: 150%;">
      			<input type="date" name="check_out" value="<?php if(!isset($check_out)) echo $booking['check_out']; else echo $check_out; ?>">
      		</th>
      	</tr>
			</table>
			<span style="color: red;">
				<?php if($checkErr) echo $Errmsg."<br>"; else echo " "."<br>"; ?></span>
			<br>
			<input type="hidden" name="houseID" value="<?php echo $booking['houseID']; ?>">
			<input type="submit" name="submit" value="OK">
		</form>
	</div>
	<br>
</body>
</html>
