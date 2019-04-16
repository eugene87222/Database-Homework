<?php
	session_start();
	require_once "connect.php";
	if(isset($_SESSION['Authenticated'])&&$_SESSION['Authenticated']==true){
		$username=$_SESSION['username'];
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
			</body>
			</html>
EOT;
	}
	if(!isset($_GET['page'])){
		$page=1;
	}
	else{
		$page=$_GET['page'];
	}

	if(!isset($_SESSION['houseID'])) $_SESSION['houseID']="";
	if(!isset($_SESSION['housename'])) $_SESSION['housename']="";
	if(!isset($_SESSION['price'])) $_SESSION['price']="";
	if(!isset($_SESSION['loca_num'])) $_SESSION['loca_num']=0;
	if(!isset($_SESSION['loca_list'])) $_SESSION['loca_list']="";
	if(!isset($_SESSION['loca_array'])) $_SESSION['loca_array']=array("");
	if(!isset($_SESSION['checkin'])) $_SESSION['checkin']='';
	if(!isset($_SESSION['checkout'])) $_SESSION['checkout']='';
	if(!isset($_SESSION['owner'])) $_SESSION['owner']="";
	if(!isset($_SESSION['info_num'])) $_SESSION['info_num']=0;
	if(!isset($_SESSION['info_list'])) $_SESSION['info_list']="";
	if(!isset($_SESSION['info_array'])) $_SESSION['info_array']=array("");
	if(!isset($_SESSION['orderby'])) $_SESSION['orderby']="house.ID";
	if(!isset($_SESSION['sort_method'])) $_SESSION['sort_method']="ASC";
	$houseID=$housename=$price=$loca_list=$time=$owner=$info_list="";
	$checkErr=false;
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
			if($_POST['checkin']==''||$_POST['checkout']==''){
				$checkErr=true;
			}
			elseif($_POST['checkin']>=$_POST['checkout']){
				$checkErr=true;
			}
			else{
				$checkErr=false;
			}
			$_SESSION['checkin']=$_POST['checkin'];
			$_SESSION['checkout']=$_POST['checkout'];
			if(!$checkErr){
				$_SESSION['houseID']=trim($_POST['houseID']);
				$_SESSION['housename']=trim($_POST['housename']);
				$_SESSION['price']=$_POST['price'];
				if(isset($_POST['location'])){
					$_SESSION['loca_num']=count($_POST['location']);
					$_SESSION['loca_array']=$_POST['location'];
					$_SESSION['loca_list']=join(' , ',$_SESSION['loca_array']);
				}
				else{
					$_SESSION['loca_list']="";
				}
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
		}
		elseif($_POST["submit"]=="▲"){
			if(isset($_POST["price"])) $_SESSION['orderby']="house.price";
			if(isset($_POST["time"]))  $_SESSION['orderby']="house.time";
			$_SESSION['sort_method']="ASC";
		}
		elseif($_POST["submit"]=="▼"){
			if(isset($_POST["price"])) $_SESSION['orderby']="house.price";
			if(isset($_POST["time"]))  $_SESSION['orderby']="house.time";
			$_SESSION['sort_method']="DESC";
		}
		elseif($_POST["submit"]=="Price"||$_POST["submit"]=="Time"){
			$_SESSION['orderby']="house.ID";
			$_SESSION['sort_method']="ASC";
		}
		elseif($_POST['submit']=='book'){
			if($_SESSION['checkin']==''||$_SESSION['checkout']==''){
				$checkErr=true;
			}
			elseif($_SESSION['checkin']>=$_SESSION['checkout']){
				$checkErr=true;
			}
			elseif($_SESSION['checkin']<date("Y-m-d")){
				$checkErr=true;
			}
			else{
				$book=$connect->prepare("INSERT INTO booking(houseID,visitorID,check_in,check_out) VALUES(?,?,?,?)");
				$book->execute(array($_POST['houseID'],$_POST['userID'],$_SESSION['checkin'],$_SESSION['checkout']));
			}
		}
	}
	$orderby=$_SESSION['orderby'];
	$sort_method=$_SESSION['sort_method'];
	$sql="SELECT house.ID AS houseID,house.name AS housename,house.price,house.locationID,house.time,user.name FROM house 
		LEFT JOIN information ON house.ID=information.houseID
		LEFT JOIN user ON house.ownerID=user.userID WHERE 
		(CASE WHEN :houseID='ID' THEN 1 WHEN :houseID='' THEN 1 ELSE house.ID=:houseID END) 
		AND (CASE WHEN :housename='keywords' THEN 1 WHEN :housename='' THEN 1 ELSE LOCATE(:housename,house.name)>0 END) 
		AND (CASE WHEN :owner='keywords' THEN 1 WHEN :owner='' THEN 1 ELSE LOCATE(:owner,user.name)>0 END) 
		AND (CASE WHEN :loca_list='' THEN 1 ELSE LOCATE(house.locationID,:loca_list)>0 END)
		AND (CASE WHEN :price='interval' THEN 1 WHEN :price='' THEN 1 
			WHEN :price='0~3000' THEN house.price between 0 and 3000 
			WHEN :price='3000~6000' THEN house.price between 3000 and 6000
			WHEN :price='6000~12000' THEN house.price between 6000 and 12000
			WHEN :price='12000~' THEN house.price>=12000 END)
		AND (CASE WHEN :info_list='' THEN 1 ELSE LOCATE(information.informationID,:info_list)>0 END)
		GROUP BY house.ID ORDER BY $orderby $sort_method";
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
				<li><form action="booking_page.php"><input type="submit" value="訂房管理"></form></li>
				<?php if($_SESSION['identity']=='admin'){ ?>
					<li><form action="booking_status.php"><input type="submit" value="訂房現況" class="for_admin"></form></li>
					<li><form action="info_management.php"><input type="submit" value="資訊管理" class="for_admin"></form></li>
					<li><form action="location_management.php"><input type="submit" value="地點管理" class="for_admin"></form></li>
					<li><form action="member_management.php"><input type="submit" value="會員管理" class="for_admin"></form></li>
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
				<th>
					<li>filter
						<ul>
							<?php
								$get_all_loca=$connect->prepare("SELECT ID,location FROM location");
								$get_all_loca->execute();
								while($loca=$get_all_loca->fetch()){
							?>
									<li><input type="checkbox" name="location[]" value="<?php echo $loca['ID'];?>"<?php if(strpos($_SESSION['loca_list'],$loca['ID'])!==false) echo "checked='checked'"; ?> ><?php echo $loca['location'];?></li>
							<?php
								}
							?>
						</ul>
					</li>
				</th>
				<th class="<?php if($checkErr) echo 'error'?>">
					<li style="background: transparent; border: none;">From</li>
					<li><input type="date" name="checkin" style="font-size: 125%; width: 140px;" value="<?php echo $_SESSION['checkin']; ?>"></li>
					<li style="background: transparent; border: none;">To</li>
					<li><input type="date" name="checkout" style="font-size: 125%; width: 140px;" value="<?php echo $_SESSION['checkout']; ?>"></li>
				</th>
				<th><input type="text" name="owner" value="<?php if($_SESSION['owner']!="") echo $_SESSION['owner']; else echo "keywords"; ?>" onfocus="if(this.value=='keywords') this.value=''"></th>
				<th>
					<li>filter
						<ul>
							<?php
								$get_all_info=$connect->prepare("SELECT ID,information FROM all_information");
								$get_all_info->execute();
								while($info=$get_all_info->fetch()){
							?>
									<li><input type="checkbox" name="information[]" value="<?php echo $info['ID'];?>"<?php if(strpos($_SESSION['info_list'],$info['ID'])!==false) echo "checked='checked'"; ?> ><?php echo $info['information'];?></li>
							<?php
								}
							?>
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
						<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
							<input type="submit" name="submit" value="▲">
							<input type="hidden" name="price" value="price">
							<input type="submit" name="submit" value="Price" style="font-size: 100%; width: 50px;">
							<input type="submit" name="submit" value="▼">
							<input type="hidden" name="price" value="price">
						</form>
					</th>
					<th class="middle">Location</th>
					<th class="middle sort">
						<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
							<input type="submit" name="submit" value="▲">
							<input type="hidden" name="time" value="time">
							<input type="submit" name="submit" value="Time" style="font-size: 100%; width: 50px;">
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
				$getdata=$connect->prepare($sql);
				$getdata->execute(array("houseID"=>$_SESSION['houseID'],"housename"=>$_SESSION['housename'],"price"=>$_SESSION['price'],"loca_list"=>$_SESSION['loca_list'],"owner"=>$_SESSION['owner'],"info_list"=>$_SESSION['info_list']));
				$cnt=0;
				while($house=$getdata->fetch()){
					if(isset($_POST['information'])||$_SESSION['info_list']!=''){
						$checkinfo=$connect->prepare("SELECT GROUP_CONCAT(information.informationID SEPARATOR ',<br>') AS info FROM house LEFT JOIN information ON information.houseID=:houseID
							WHERE (CASE WHEN :info_list='' THEN 1 ELSE LOCATE(information.informationID,:info_list)>0 END)
							GROUP BY house.ID");
						$checkinfo->execute(array("houseID"=>$house['houseID'],"info_list"=>$_SESSION['info_list']));
		    		$checkinfo_num=$checkinfo->fetch();
						if(substr_count($checkinfo_num['info'],',')+1<$_SESSION['info_num']) continue;
					}
					if(($_SESSION['checkin']!=''&&$_SESSION['checkout']!='')&&($_SESSION['checkin']<$_SESSION['checkout'])){
						$skip=false;
						$room_booked=$connect->prepare("SELECT check_in AS checkin,check_out AS checkout FROM booking WHERE houseID=:houseID");
						$room_booked->execute(array("houseID"=>$house['houseID']));
						while($booked=$room_booked->fetch()){
							if(!($_SESSION['checkin']>=$booked['checkout']||$_SESSION['checkout']<=$booked['checkin']))
								$skip=true;
						}
						if($skip) continue;
					}
					$house_list[$cnt]=$house;
					$cnt++;
				}
				$number_of_result=$cnt;
				$number_of_page=ceil($number_of_result/$result_per_page);
				for($show=($page-1)*$result_per_page,$i=0;$i<$result_per_page&&$show<$cnt;$show++,$i++){
	    ?>
    	<tr class="data">
        <th class="begin"><?php echo $house_list[$show]['houseID'];?></th>
    		<th class="middle"><?php echo $house_list[$show]['housename'];?></th>
    		<th class="middle"><?php echo $house_list[$show]['price'];?></th>
    		<th class="middle">
    		<?php
    			if($house_list[$show]['locationID']==NULL) echo "unknown";
    			else{
    				$locationID=$house_list[$show]['locationID'];
    				$get_location="SELECT location FROM location WHERE ID=$locationID";
    				$location=$connect->query($get_location);
    				$location=$location->fetchObject();
    				echo $location->location;
    			}
    		?></th>
    		<th class="middle"><?php echo $house_list[$show]['time'];?></th>
    		<th class="middle"><?php echo $house_list[$show]['name'];?></th>
    		<th class="middle info">
    		<?php
    			$id=$house_list[$show]['houseID'];
    			$get_info="SELECT all_information.information FROM information LEFT JOIN all_information ON information.informationID=all_information.ID WHERE information.houseID=$id";
					$info=$connect->query($get_info);
					while($item=$info->fetchObject()){
  					echo $item->information."<br>";
					}
    		?></th>
    		<th class="end">
    		<?php
    			$in_favorite=$connect->prepare("SELECT * FROM favorite WHERE userID=:userID AND favoriteID=:houseID");
    			$in_favorite->execute(array("userID"=>$userID,"houseID"=>$house_list[$show]['houseID']));
    		?>
    			<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
    				<input id="book" type="submit" name="submit" value="book" 
    				onclick="<?php
    					if($_SESSION['checkin']==''||$_SESSION['checkout']==''){ ?>
    						window.alert('Please choose check in/out time.')<?php }
    					elseif($_SESSION['checkin']>=$_SESSION['checkout']){ ?>
    						window.alert('You cannot check out before check in.')<?php }
    					elseif($_SESSION['checkin']<date("Y-m-d")){ ?>
    						window.alert('You cannot go back to the past.')<?php }
    					else{ ?>
    						window.confirm('Are you sure you want to book <?php echo $house['housename']; ?> from <?php echo $_SESSION['checkin']; ?> to <?php echo $_SESSION['checkout']; ?> ?')
    				<?php } ?>">
    				<input type="hidden" name="userID" value="<?php echo $userID;?>">
    				<input type="hidden" name="houseID" value="<?php echo $house_list[$show]['houseID'];?>">
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
          <input type="hidden" name="houseID" value="<?php echo $house_list[$show]['houseID'];?>">
    			<?php
    			}
    			else{
    				if($in_favorite->rowCount()) echo "已加到最愛";
    					else{
    			?>
          		<input type="submit" name="submit" value="favorite" id="add_to_favorite">
          		<input type="hidden" name="userID" value="<?php echo $userID;?>">
          		<input type="hidden" name="houseID" value="<?php echo $house_list[$show]['houseID'];?>">
    			<?php
      	 			}
      			}
    			?>
        	</form>
    		</th>
    	</tr>
    <?php
        }
    ?>
  	</table>
  	<br>
  	<?php
  		for($page=1;$page<=$number_of_page;$page++){
				echo '<a href="home_page.php?page='.$page.'">'.$page.'</a>&nbsp;&nbsp;';
			}
  	?>
	</div>
	<br>
</body>
</html>