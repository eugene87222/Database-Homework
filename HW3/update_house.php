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
	$housenameErr=$priceErr=$timeErr=0;
	$housenameErr_list=$priceErr_list=$timeErr_list=array();
	$housename_list=$price_list=$location_list=$info_list=$YYYY_list=$MM_list=$DD_list=array();
	if(isset($_POST['submit'])){
		if($_POST['submit']=='OK'){
			for($i=0;$i<$_POST['rowCount'];$i++){
				$housename_list[$i]=strip_tags(trim($_POST['housename'][$i]));
				$price_list[$i]=$_POST['price'][$i];
				$location_list[$i]=$_POST['location'][$i];
				if(isset($_POST['information'][$i])){
	  			$info_list[$i]=join(' , ',$_POST['information'][$i]);
  			}	
				$YYYY_list[$i]=strip_tags(trim($_POST['YYYY'][$i]));
				$MM_list[$i]=strip_tags(trim($_POST['MM'][$i]));
				$DD_list[$i]=strip_tags(trim($_POST['DD'][$i]));
			}
			for($i=0;$i<$_POST['rowCount'];$i++){
				if(empty($_POST['housename'][$i])||$housename_list[$i]==''){
					$housenameErr_list[$i]=1;
					$housenameErr=1;
				}
				else $housenameErr_list[$i]=0;
				
				if($_POST['price'][$i]<0){
					$priceErr_list[$i]=1;
					$priceErr=1;
				}
				else $priceErr_list[$i]=0;

				if($YYYY_list[$i]==''||$MM_list[$i]==''||$DD_list[$i]==''){
					$timeErr_list[$i]=1;
  				$timeErr=1;
  			}
  			else{
					$YYYY=intval($YYYY_list[$i]);
	  			$MM=intval($MM_list[$i]);
	  			$DD=intval($DD_list[$i]);
	  			if(checkdate($MM,$DD,$YYYY)){
	  				$timeErr_list[$i]=0;
	  			}
	  			else{
	  				$timeErr_list[$i]=1;
	  				$timeErr=1;
	  			}
  			}
			}
			if(!$housenameErr&&!$priceErr&&!$timeErr){
				for($i=0;$i<$_POST['rowCount'];$i++){
					$houseID=$_POST['ID'][$i];
					$housename=$housename_list[$i];
					$price=$price_list[$i];
					$locationID=$location_list[$i];
					$time=$YYYY_list[$i].'/'.$MM_list[$i].'/'.$DD_list[$i];
					if($locationID==''){
						$do=$connect->prepare("UPDATE house SET name=:housename,price=:price,time=:time WHERE ID=:houseID");
						$do->execute(array("housename"=>"$housename","price"=>"$price","time"=>"$time","houseID"=>"$houseID"));
					}
					else{
						$do=$connect->prepare("UPDATE house SET name=:housename,price=:price,locationID=:locationID,time=:time WHERE ID=:houseID");
						$do->execute(array("housename"=>"$housename","price"=>"$price","locationID"=>"$locationID","time"=>"$time","houseID"=>"$houseID"));
					}
					$del_info=$connect->prepare("DELETE FROM information WHERE houseID=:houseID");
					$del_info->execute(array("houseID"=>$_POST['ID'][$i]));
					$add_info=$connect->prepare("INSERT INTO information(informationID,houseID) VALUES(?,?)");
					if(isset($_POST['information'][$i])){
						foreach($_POST['information'][$i] as $information){
							$add_info->execute(array($information,$_POST['ID'][$i]));
						}
					}
				}
				echo<<<EOT
					<!DOCTYPE>
					<html>
					<body>
					<script>
						window.location.replace('house_management.php');
					</script>
					</body?
					</html>
EOT;
			}
		}
	}
	$getdata=$connect->prepare("SELECT house.ID AS houseID,house.name AS housename,house.price,house.locationID,location,house.time,information.informationID,user.name FROM house 
		LEFT JOIN information ON house.ID=information.houseID
		LEFT JOIN location ON house.locationID=location.ID
		LEFT JOIN user ON house.ownerID=user.userID WHERE house.ownerID=:userID
		ORDER BY house.ID");
	$getinfo=$connect->prepare("SELECT informationID FROM information WHERE houseID=:houseID");
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
				<li><form action="house_management.php"><input type="submit" value="取消"></form></li>
			</ul>
		</nav>
		<?php
			$getdata->execute(array("userID"=>"$userID"));
			if($getdata->rowCount()<=0) echo "您尚未擁有任何房子";
			else{
		?>
		<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> method="post">
		<table id="update_house">
			<tr class="caption">
				<th class="begin">ID</th>
				<th class="middle <?php if($housenameErr) echo "error_title"; ?>">Name</th>
				<th class="middle <?php if($priceErr) echo "error_title"; ?>">Price</th>
				<th class="middle">Location</th>
				<th class="middle <?php if($timeErr) echo "error_title"; ?>">Time</th>
				<th class="middle">Information</th>
				<th class="end">Owner</th>
			</tr>

		<?php
				$prehouseID=-1;
				$row=0;
				$ID=array();
				$house_list=$getdata->fetchAll();
				$getdata->closeCursor();
				foreach($house_list as $house){
					if($house['houseID']==$prehouseID) continue;
					$getinfo->execute(array("houseID"=>$house['houseID']));
					$i=0;
					$info="";
					while($info_part=$getinfo->fetch()){
						if($i) $info=$info.','.$info_part['informationID'];
						else $info=$info.$info_part['informationID'];
						$i++;
					}
					$row++;
	  ?>
	   	<tr class="data">
		   	<th class="begin"><input type="hidden" name="ID[]" value="<?php echo $house['houseID'];?>"><?php echo $house['houseID'];?></th>
		   	
		   	<th class="middle <?php if(isset($housenameErr_list[$row-1])&&$housenameErr_list[$row-1]==1) echo "error"; ?>"><input type="text" name="housename[]" value="<?php if(!isset($housename_list[$row-1])) echo $house['housename']; else echo $housename_list[$row-1]; ?>"></th>
		   	
		   	<th class="middle <?php if(isset($priceErr_list[$row-1])&&$priceErr_list[$row-1]==1) echo "error"; ?>"><input type="number" name="price[]" value="<?php if(!isset($price_list[$row-1])) echo $house['price']; else echo $price_list[$row-1]; ?>"></th>
		   	
		   	<th class="middle">
		   		<select name="location[]">
							<option value="">Choose one</option>
							<?php
								$get_all_loca=$connect->prepare("SELECT ID,location FROM location");
								$get_all_loca->execute();
								while($loca=$get_all_loca->fetch()){
							?>
									<option <?php 
										if(isset($location_list[$row-1])){
											if($location_list[$row-1]==$loca['ID']){ ?>selected="true" <?php }
										}
										elseif($house['locationID']==$loca['ID']){ ?>selected="true" <?php } ?>
											value="<?php echo $loca['ID'];?>" ><?php echo $loca['location'];?></option>
							<?php
								}
							?>
						</select></th>

		  	<th class="middle time <?php if(isset($timeErr_list[$row-1])&&$timeErr_list[$row-1]==1) echo "error"; ?>" style="width: 170px;">
		  		<input style="width: 22%;" type="text" name="YYYY[]" value="<?php if(!isset($YYYY_list[$row-1])) echo date("Y",strtotime($house['time'])); else echo $YYYY_list[$row-1]; ?>"><span>/</span>
		  		<input style="width: 11%;" type="text" name="MM[]" value="<?php if(!isset($MM_list[$row-1])) echo date("m",strtotime($house['time'])); else echo $MM_list[$row-1]; ?>"><span>/</span>
		  		<input style="width: 11%;" type="text" name="DD[]" value="<?php if(!isset($DD_list[$row-1])) echo date("d",strtotime($house['time'])); else echo $DD_list[$row-1]; ?>">
		  	</th>

		   	<th class="middle">
		   		<li>Please Select
		   			<ul id="alter_house">
							<?php
								$get_all_info=$connect->prepare("SELECT ID,information FROM all_information");
								$get_all_info->execute();
								while($all_info=$get_all_info->fetch()){
							?>
									<li><input type="checkbox" name="information[<?php echo $row-1; ?>][]" value="<?php echo $all_info['ID'];?>"
										<?php if(!isset($info_list[$row-1])){if(strpos($info,$all_info['ID'])!==false) echo "checked='checked'";} else{if(strpos($info_list[$row-1],$all_info['ID'])!==false) echo "checked='checked'";} ?> ><?php echo $all_info['information']; ?></li>
							<?php
								}
							?>
						</ul>
		   	</th>

		   	<th class="end"><?php echo $house['name'];?></th>
		  </tr>
		<?php
	  				$prehouseID=$house['houseID'];
	    		}
	  ?>
    	</table>
    	<br>
	   	<input type="submit" name="submit" value="OK">
	   	<input type="hidden" name="rowCount" value="<?php echo $row; ?>">
			</form>
	  <?php
     		}
    ?>
	</div>
	<br>
</body>
</html>
