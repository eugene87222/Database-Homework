<?php
	session_start();
	require_once "connect.php";
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$username=$_SESSION['username'];
		if(isset($_GET['page']))
			$page=$_GET['page'];
		else
			$page=1;
		$postperpage=2;

		$stmt=$connect->prepare("SELECT userID,username,name,email,identity FROM user WHERE username=:username");
		$stmt->execute(array("username"=>$username));
		$row=$stmt->fetch();
		$userID=$row[0];
		$_SESSION['userID']=$userID;
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
	$orderby="house.ID";
	$sort_method="ASC";
	if(!isset($_SESSION['houseID'])) $_SESSION['houseID']="";
	if(!isset($_SESSION['housename'])) $_SESSION['housename']="";
	if(!isset($_SESSION['price'])) $_SESSION['price']="";
	if(!isset($_SESSION['location'])) $_SESSION['location']="";
	if(!isset($_SESSION['time'])) $_SESSION['time']="";
	if(!isset($_SESSION['owner'])) $_SESSION['owner']="";
	if(!isset($_SESSION['info_num'])) $_SESSION['info_num']=0;
	if(!isset($_SESSION['info_list'])) $_SESSION['info_list']="";
	if(!isset($_SESSION['info_array'])) $_SESSION['info_array']=array("");
	$houseID=$housename=$price=$location=$time=$owner=$info_list="";
	$info_array=array();
	if(isset($_POST['submit'])){
		if($_POST['submit']=="favorite"){
			$userID=$_POST['userID'];
			$houseID=$_POST['houseID'];
			$do=$connect->prepare("INSERT INTO favorite(userID,favoriteID) VALUES(?,?)");
			$do->execute(array($userID,$houseID));
		}
		elseif($_POST['submit']=="X"){
			$houseID=$_POST['houseID'];
			$do=$connect->prepare("DELETE FROM house WHERE ID=:houseID");
			$do->execute(array("houseID"=>$houseID));
		}
		elseif($_POST['submit']=="search"){
			$_SESSION['houseID']=trim($_POST['houseID']);
			$_SESSION['housename']=trim($_POST['housename']);
			$_SESSION['price']=$_POST['price'];
			$_SESSION['location']=trim($_POST['location']);
			$_SESSION['time']=trim($_POST['time']);
			$_SESSION['owner']=trim($_POST['owner']);
			if(isset($_POST['information'])){
				$_SESSION['info_num']=count($_POST['information']);
				$_SESSION['info_array']=$_POST['information'];
				$_SESSION['info_list']=join(' , ',$_SESSION['info_array']);
			}
			else{
				$_SESSION['info_list']="";
			}
		}
		elseif($_POST["submit"]=="▲"){
			if(isset($_POST["price"])) $orderby="house.price";
			if(isset($_POST["time"]))  $orderby="house.time";
			$sort_method="ASC";
		}
		elseif($_POST["submit"]=="▼"){
			if(isset($_POST["price"])) $orderby="house.price";
			if(isset($_POST["time"]))  $orderby="house.time";
			$sort_method="DESC";
		}
	}
	$getdata=$connect->prepare("SELECT house.ID AS houseID,house.name AS housename,house.price,house.location,house.time,GROUP_CONCAT(information.information SEPARATOR ',<br>') AS info,user.name FROM house 
		LEFT JOIN information ON house.ID=information.houseID
		LEFT JOIN user ON house.ownerID=user.userID WHERE 
		(CASE WHEN :houseID='ID' THEN 1 WHEN :houseID='' THEN 1 ELSE house.ID=:houseID END) 
		AND (CASE WHEN :housename='keywords' THEN 1 WHEN :housename='' THEN 1 ELSE LOCATE(:housename,house.name)>0 END) 
		AND (CASE WHEN :location='keywords' THEN 1 WHEN :location='' THEN 1 ELSE LOCATE(:location,house.location)>0 END) 
		AND (CASE WHEN :time='keywords' THEN 1 WHEN :time='' THEN 1 ELSE LOCATE(:time,house.time)>0 END) 
		AND (CASE WHEN :owner='keywords' THEN 1 WHEN :owner='' THEN 1 ELSE LOCATE(:owner,user.name)>0 END) 
		AND (CASE WHEN :price='interval' THEN 1 WHEN :price='' THEN 1 
			WHEN :price='0~3000' THEN house.price between 0 and 3000 
			WHEN :price='3000~6000' THEN house.price between 3000 and 6000
			WHEN :price='6000~12000' THEN house.price between 6000 and 12000
			WHEN :price='12000~' THEN house.price>=12000 END)
		AND (CASE WHEN :info_list='' THEN 1 ELSE LOCATE(information.information,:info_list)>0 END)
		GROUP BY information.houseID ORDER BY $orderby $sort_method");
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="aft_login">
	<div class="title">Home Page</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="house_management.php"><input type="submit" value="房屋管理"></form></li>
				<li><form action="favorite_list.php"><input type="submit" value="我的最愛"></form></li>
				<?php if($_SESSION['identity']=='admin'){ ?>
					<li><form action="member_management.php"><input type="submit" value="會員管理"></form></li>
				<?php } ?>
				<li><form action="logout.php"><input type="submit" value="Log out"></form></li>
			</ul>
		</nav>
		<table id="house_table">
			<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
			<tr class="search_bar">
				<th><input type="text" name="houseID" value="<?php if($_SESSION['houseID']!="") echo $_SESSION['houseID']; else echo "ID"; ?>" onfocus="if(this.value=='ID') this.value=''" class="begin"></th>
				<th><input type="text" name="housename" value="<?php if($_SESSION['housename']!="") echo $_SESSION['housename']; else echo "keywords"; ?>" onfocus="if(this.value=='keywords') this.value=''"></th>
				<th><select name="price">
					<option value="">interval</option>
   					<option <?php if ($_SESSION['price']=="0~3000"){?>selected="true" <?php };?> value="0~3000">0~3000</option>
   					<option <?php if ($_SESSION['price']=="3000~6000"){?>selected="true" <?php };?> value="3000~6000">3000~6000</option>
   					<option <?php if ($_SESSION['price']=="6000~12000"){?>selected="true" <?php };?> value="6000~12000">6000~12000</option>
   					<option <?php if ($_SESSION['price']=="12000~"){?>selected="true" <?php };?> value="12000~">12000~</option>
				</select></th>
				<th><input type="text" name="location" value="<?php if($_SESSION['location']!="") echo $_SESSION['location']; else echo "keywords"; ?>" onfocus="if(this.value=='keywords') this.value=''"></th>
				<th><input type="text" name="time" value="<?php if($_SESSION['time']!="") echo $_SESSION['time']; else echo "keywords"; ?>" onfocus="if(this.value=='keywords') this.value=''"></th>
				<th><input type="text" name="owner" value="<?php if($_SESSION['owner']!="") echo $_SESSION['owner']; else echo "keywords"; ?>" onfocus="if(this.value=='keywords') this.value=''"></th>
				<!-- <th><input type="text" name="information" value="<?php if($information!="") echo $information; else echo "keywords"; ?>" onfocus="if(this.value=='keywords') this.value=''"></th> -->
				<th>
					<li>filter
						<ul>
							<li><input type="checkbox" name="information[]" value="laundry facilities"
								<?php if(strpos($_SESSION['info_list'],"laundry facilities")!==false) echo "checked='checked'"; ?> >laundry facilities</li>

							<li><input type="checkbox" name="information[]" value="wifi"
								<?php if(strpos($_SESSION['info_list'],"wifi")!==false) echo "checked='checked'"; ?> >wifi</li>

							<li><input type="checkbox" name="information[]" value="lockers"
								<?php if(strpos($_SESSION['info_list'],"lockers")!==false) echo "checked='checked'"; ?> >lockers</li>

							<li><input type="checkbox" name="information[]" value="kitchen"
								<?php if(strpos($_SESSION['info_list'],"kitchen")!==false) echo "checked='checked'"; ?> >kitchen</li>

							<li><input type="checkbox" name="information[]" value="elevator"
								<?php if(strpos($_SESSION['info_list'],"elevator")!==false) echo "checked='checked'"; ?> >elevator</li>

							<li><input type="checkbox" name="information[]" value="no smoking"
								<?php if(strpos($_SESSION['info_list'],"no smoking")!==false) echo "checked='checked'"; ?> >no smoking</li>

							<li><input type="checkbox" name="information[]" value="television"
								<?php if(strpos($_SESSION['info_list'],"television")!==false) echo "checked='checked'"; ?> >television</li>

							<li><input type="checkbox" name="information[]" value="breakfast"
								<?php if(strpos($_SESSION['info_list'],"breakfast")!==false) echo "checked='checked'"; ?> >breakfast</li>

							<li><input type="checkbox" name="information[]" value="toiletries provided"
								<?php if(strpos($_SESSION['info_list'],"toiletries provided")!==false) echo "checked='checked'"; ?> >toiletries provided</li>

							<li><input type="checkbox" name="information[]" value="shuttle service"
								<?php if(strpos($_SESSION['info_list'],"shuttle service")!==false) echo "checked='checked'"; ?> >shuttle service</li>
						</ul>
					</li>
				</th>
				<th><input type="submit" name="submit" value="search"></th>
			</tr>
			</form>
			<tr class="caption">
					<th class="begin">ID</th>
					<th class="middle">Name</th>
					<th class="middle sort">
						<form  action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
							<input type="submit" name="submit" value="▲">
							<input type="hidden" name="price" value="price">
							Price
							<input type="submit" name="submit" value="▼">
							<input type="hidden" name="price" value="price">
						</form>
					</th>
					<th class="middle">Location</th>
					<th class="middle sort">
						<form  action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
							<input type="submit" name="submit" value="▲">
							<input type="hidden" name="time" value="time">
							Time
							<input type="submit" name="submit" value="▼">
							<input type="hidden" name="time" value="time">
						</form>
					</th>
					<th class="middle">Owner</th>
					<th class="middle">Information</th>
					<th class="end">Option</th>
				</form>
			</tr>
			<?php
				$prehouseID=-1;
				$getdata->execute(array("houseID"=>$_SESSION['houseID'],"housename"=>$_SESSION['housename'],"price"=>$_SESSION['price'],"location"=>$_SESSION['location'],"time"=>$_SESSION['time'],"owner"=>$_SESSION['owner'],"info_list"=>$_SESSION['info_list']));
				while($house=$getdata->fetch()){
					if($house['houseID']==$prehouseID) continue;
					if(isset($_POST['information'])||$_SESSION['info_list']!=''){
						if(substr_count($house['info'],',')+1<$_SESSION['info_num']) continue;
					}
	        ?>
	        	<tr class="data">
	            <th class="begin"><?php echo $house['houseID'];?></th>
	        		<th class="middle"><?php echo $house['housename'];?></th>
	        		<th class="middle"><?php echo $house['price'];?></th>
	        		<th class="middle"><?php echo $house['location'];?></th>
	        		<th class="middle"><?php echo $house['time'];?></th>
	        		<th class="middle"><?php echo $house['name'];?></th>
	        		<th class="middle info">
	        		<?php
	        			$id=$house['houseID'];
	        			$get_info="SELECT * FROM information WHERE houseID=$id";
      					$info=$connect->query($get_info);
      					while($item=$info->fetchObject()){
        					echo $item->information."<br>";
      					}
	        		?></th>
	        		<th class="end">
	        <?php
	        		$in_favorite=$connect->prepare("SELECT * FROM favorite WHERE userID=:userID AND favoriteID=:houseID");
	        		$in_favorite->execute(array("userID"=>$userID,"houseID"=>$house['houseID']));
	        ?>
	        			<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
	        <?php
	        		if($_SESSION['identity']=='admin'){
		       			if($in_favorite->rowCount()) echo '<span style="font-size: 80%;">已加到最愛</span>';
	        			else{
		    	?>
	              	<input type="submit" name="submit" value="favorite" id="add_to_favorite">
	              	<input type="hidden" name="userID" value="<?php echo $userID;?>">
	        <?php
	         			}
	        ?>
	              <input onclick="return confirm('Are you sure you want to delete this house ?')" type="submit" name="submit" value="X" id="delete_house">
	              <input type="hidden" name="houseID" value="<?php echo $house['houseID'];?>">
	        <?php
	        		}
	        		else{
	        			if($in_favorite->rowCount()) echo "已加到最愛";
	        			else{
	        ?>
                		<input type="submit" name="submit" value="favorite" id="add_to_favorite">
                		<input type="hidden" name="userID" value="<?php echo $userID;?>">
                		<input type="hidden" name="houseID" value="<?php echo $house['houseID'];?>">
          <?php
          	 		}
            	}
          ?>
              	</form>
	        		</th>
	        	</tr>
	        <?php
	        		//$prehouseID=$house['houseID'];
	            }
	        ?>
        </table>
	</div>
	<br>
</body>
</html>