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
	$getdata=$connect->prepare("SELECT house.ID AS houseID,house.name AS housename,house.price,house.location,house.time,information.information,user.name FROM house 
		LEFT JOIN information ON house.ID=information.houseID
		LEFT JOIN user ON house.ownerID=user.userID WHERE house.ownerID=:userID;
		ORDER BY house.ID");
	$housenameErr=$priceErr=$locationErr=$timeErr="";
	$housename=$price=$location=$YYYY=$MM=$DD=$info_list="";
	if($_SERVER['REQUEST_METHOD']=='POST'){
  		if(empty($_POST['housename'])||strip_tags(trim($_POST["housename"]))==''){
    		$housenameErr='Housename is required';
  		}
  		else{
  			$housename=strip_tags(trim($_POST["housename"]));
			$housenameErr="";
  		}

  		if($_POST['price']<0){
 			$priceErr='Price cannot be negative';
 		}
 		else{
 			if(empty($_POST['price']))
 				$price=0;
 			else
 				$price=$_POST['price'];
 			$priceErr='';
 		}

  		if(empty($_POST['location'])||strip_tags(trim($_POST['location']))==''){
    		$locationErr='Location is required';
  		}
  		else{
  			$location=strip_tags(trim($_POST['location']));
  			$locationErr='';
  		}

  		if(strip_tags(trim($_POST['YYYY']))==''||strip_tags(trim($_POST['MM']))==''||strip_tags(trim($_POST['DD']))==''){
  			$timeErr='Wrong time format';
  		}
  		else{
  			$YYYY=intval(strip_tags(trim($_POST['YYYY'])));
  			$MM=intval(strip_tags(trim($_POST['MM'])));
  			$DD=intval(strip_tags(trim($_POST['DD'])));
  			if(checkdate($MM,$DD,$YYYY)){
  				$timeErr='';
  			}
  			else{
  				$timeErr='Wrong time format';
  			}
  		}

  		if(isset($_POST['information'])){
				$info_list=join(' , ',$_POST['information']);
  		}
	}
	if(empty($housenameErr)&&empty($priceErr)&&empty($locationErr)&&empty($timeErr)&&$_SERVER["REQUEST_METHOD"]=="POST"){
		$do=$connect->prepare("INSERT INTO house(name,price,location,time,ownerID) VALUES(?,?,?,?,?)");
		$do->execute(array($housename,$price,$location,$YYYY.'/'.$MM.'/'.$DD,$userID));
		$do=$connect->prepare("SELECT LAST_INSERT_ID() FROM house");
		$do->execute();
		$latest_id=$do->fetch();
		$do=$connect->prepare("INSERT INTO information(information,houseID) VALUES(?,?)");
		if(isset($_POST['information'])){
			foreach($_POST['information'] as $info){
				$do->execute(array($info,$latest_id[0]));
			}
		}
		echo<<<EOT
				<!DOCTYPE>
				<html>
				<body>
				<script>
					alert("Add a house successfully!!");
					window.location.replace('house_management.php')
				</script>
				</body>
				</html>
EOT;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="add_house">
	<div class="title">Add a new house</div>
	<div class="content">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<table>
				<tr class="required"><th></th><th><span>*<?php echo $housenameErr;?></span></th></tr>
				<tr>
					<th>Housename:</th>
					<th><input type="text" name="housename" value="<?php echo $housename;?>"></th>
				</tr>
				
				<tr class="required"><th></th><th><span>*<?php echo $priceErr;?></span></th></tr>
				<tr>
					<th>Price:</th>
					<th><input type="number" name="price" value="<?php echo $price;?>"></th>
				</tr>
				
				<tr class="required"><th></th><th><span>*<?php echo $locationErr;?></span></th></tr>
				<tr>
					<th>Location:</th>
					<th><input type="text" name="location" value="<?php echo $location;?>"></th>
				</tr>
				
				<tr class="required"><th></th><th><span>*<?php echo $timeErr;?></span></th></tr>
				<tr>
					<th>Time:</th>
					<th class="time" style="width: 227px;">
						<input style="width: 25%;" type="text" name="YYYY" 
						value="<?php if($YYYY!='') echo $YYYY; else echo 'YYYY';?>" 
						onfocus="if(this.value=='YYYY') this.value=''"></li>
						<span>/</span></li>
						<input style="width: 14%;" type="text" name="MM" 
						value="<?php if($MM!='') echo $MM; else echo 'MM';?>"
						onfocus="if(this.value=='MM') this.value=''">
						<span>/</span></li>
						<input style="width: 14%;" type="text" name="DD" 
						value="<?php if($DD!='') echo $DD; else echo 'DD';?>"
						onfocus="if(this.value=='DD') this.value=''">
					</th>
				</tr>
				<tr>
					<th>Information:</th>
					<th>
						<li>Please Select
						<ul id="new_house">
							<li><input type="checkbox" name="information[]" value="laundry facilities"
								<?php if(strpos($info_list,"laundry facilities")!==false) echo "checked='checked'"; ?> >laundry facilities</li>

							<li><input type="checkbox" name="information[]" value="wifi"
								<?php if(strpos($info_list,"wifi")!==false) echo "checked='checked'"; ?> >wifi</li>

							<li><input type="checkbox" name="information[]" value="lockers"
								<?php if(strpos($info_list,"lockers")!==false) echo "checked='checked'"; ?> >lockers</li>

							<li><input type="checkbox" name="information[]" value="kitchen"
								<?php if(strpos($info_list,"kitchen")!==false) echo "checked='checked'"; ?> >kitchen</li>

							<li><input type="checkbox" name="information[]" value="elevator"
								<?php if(strpos($info_list,"elevator")!==false) echo "checked='checked'"; ?> >elevator</li>

							<li><input type="checkbox" name="information[]" value="no smoking"
								<?php if(strpos($info_list,"no smoking")!==false) echo "checked='checked'"; ?> >no smoking</li>

							<li><input type="checkbox" name="information[]" value="television"
								<?php if(strpos($info_list,"television")!==false) echo "checked='checked'"; ?> >television</li>

							<li><input type="checkbox" name="information[]" value="breakfast"
								<?php if(strpos($info_list,"breakfast")!==false) echo "checked='checked'"; ?> >breakfast</li>

							<li><input type="checkbox" name="information[]" value="toiletries provided"
								<?php if(strpos($info_list,"toiletries provided")!==false) echo "checked='checked'"; ?> >toiletries provided</li>

							<li><input type="checkbox" name="information[]" value="shuttle service"
								<?php if(strpos($info_list,"shuttle service")!==false) echo "checked='checked'"; ?> >shuttle service</li>
						</ul>
				</tr>
			</table>
			<br>
			<input type="submit" value="Add" class="general_button">
		</form>
		<form action="house_management.php"><input type="submit" value="Cancel" class="general_button"></form>
	</div>
	<br>
</body>
</html>
