<?php
	session_start();
	require_once "connect.php";

	if(isset($_SESSION['houseID'])) unset($_SESSION['houseID']);
	if(isset($_SESSION['housename'])) unset($_SESSION['housename']);
	if(isset($_SESSION['price'])) unset($_SESSION['price']);
	if(isset($_SESSION['location'])) unset($_SESSION['location']);
	if(isset($_SESSION['time'])) unset($_SESSION['time']);
	if(isset($_SESSION['owner'])) unset($_SESSION['owner']);
	if(isset($_SESSION['info_list'])) unset($_SESSION['info_list']);
	if(isset($_SESSION['info_array'])) unset($_SESSION['info_array']);

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
	if(isset($_POST['submit'])){
		if($_POST['submit']=='delete'){
			$houseID=$_POST["houseID"];
			$do=$connect->prepare("DELETE FROM house WHERE ownerID=:userID AND ID=:houseID");
			$do->execute(array("userID"=>"$userID","houseID"=>"$houseID"));
		}
	}
	$getdata=$connect->prepare("SELECT house.ID AS houseID,house.name AS housename,house.price,house.location,house.time,GROUP_CONCAT(information.information SEPARATOR '<br>') AS info,user.name FROM house 
		LEFT JOIN information ON house.ID=information.houseID
		LEFT JOIN user ON house.ownerID=user.userID WHERE house.ownerID=:userID
		GROUP BY information.houseID ORDER BY house.ID");
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="aft_login">
	<div class="title">House Management</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="home_page.php"><input type="submit" value="首頁"></form></li>
				<li><form action="logout.php"><input type="submit" value="Log out"></form></li>
			</ul>
		</nav>
		<nav id="mgnt">
			<ul>
				<li><form action="new_house.php"><input type="submit" value="新增"></form></li>
				<li><form action="update_house.php"><input type="submit" value="編輯"></form></li>
			</ul>
		</nav>
		<?php
			$getdata->execute(array("userID"=>"$userID"));
			if($getdata->rowCount()<=0) echo "您尚未擁有任何房子";
			else{
		?>
		<table id="house_table">
			<tr class="caption">
				<th class="begin">ID</th>
				<th class="middle">Name</th>
				<th class="middle sort">Price</th>
				<th class="middle">Location</th>
				<th class="middle sort">Time</th>
				<th class="middle">Owner</th>
				<th class="middle">Information</th>
				<th class="end">Option</th>
			</tr>
		<?php
				$prehouseID=-1;
				while($house=$getdata->fetch()){
					if($house['houseID']==$prehouseID) continue;
	  	?>
	    	<tr class="data">
	      		<th class="begin"><?php echo $house['houseID'];?></th>
		    	<th class="middle"><?php echo $house['housename'];?></th>
		    	<th class="middle"><?php echo $house['price'];?></th>
		    	<th class="middle"><?php echo $house['location'];?></th>
		    	<th class="middle"><?php echo $house['time'];?></th>
		    	<th class="middle"><?php echo $house['name'];?></th>
		    	<th class="middle"><?php echo $house['info'];?></th>
		    	<th class="end">
			    	<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
		       			<input onclick="return confirm('Are you sure you want to delete this house ?')" type="submit" name="submit" value="delete" id="delete_house_mgnt_page">
	      	 			<input type="hidden" name="houseID" value="<?php echo $house['houseID'];?>">
	        		</form>
		    	</th>
		    </tr>
		<?php
	  				//$prehouseID=$house['houseID'];
	    		}
	  	?>
    	</table>
	    <?php
     		}
    	?>
	</div>
	<br>
</body>
</html>
