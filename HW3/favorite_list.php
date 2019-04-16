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
	if(!isset($_GET['page'])){
		$page=1;
	}
	else{
		$page=$_GET['page'];
	}

	if(isset($_POST['submit'])){
		if($_POST['submit']=='delete'){
			$houseID=$_POST["houseID"];
			$do=$connect->prepare("DELETE FROM favorite WHERE favoriteID=:favoriteID AND userID=:userID");
			$do->execute(array("favoriteID"=>"$houseID","userID"=>"$userID"));
		}
	}
	$sql="SELECT house.ID AS houseID,house.name AS housename,house.price,locationID,house.time,user.name FROM house 
		LEFT JOIN user ON house.ownerID=user.userID 
		LEFT JOIN favorite ON favorite.favoriteID=house.ID WHERE favorite.userID=:userID
		ORDER BY house.ID";
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="aft_login">
	<div class="title">Favorite List</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="home_page.php"><input type="submit" value="首頁"></form></li>
				<li><form action="logout.php"><input type="submit" value="Log out"></form></li>
			</ul>
		</nav>
		<?php
			$getdata=$connect->prepare($sql);
			$getdata->execute(array("userID"=>"$userID"));
			$number_of_result=$getdata->rowCount();
			$number_of_page=ceil($number_of_result/$result_per_page);
			$getdata=$connect->prepare($sql." LIMIT ".($page-1)*$result_per_page.",".$result_per_page);
			$getdata->execute(array("userID"=>"$userID"));
			if($getdata->rowCount()<=0) echo "Nothing";
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
				while($house=$getdata->fetch()){
	  	?>
	    	<tr class="data">
	      	<th class="begin"><?php echo $house['houseID'];?></th>
		    	<th class="middle"><?php echo $house['housename'];?></th>
		    	<th class="middle"><?php echo $house['price'];?></th>
		    	<th class="middle">
		    	<?php
	    			if($house['locationID']==NULL) echo "unknown";
	    			else{
	    				$locationID=$house['locationID'];
	    				$get_location="SELECT location FROM location WHERE ID=$locationID";
	    				$location=$connect->query($get_location);
	    				$location=$location->fetchObject();
	    				echo $location->location;
	    			}
    			?></th>
		    	<th class="middle"><?php echo $house['time'];?></th>
		    	<th class="middle"><?php echo $house['name'];?></th>
		    	<th class="middle info">
	    		<?php
	    			$id=$house['houseID'];
	    			$get_info="SELECT all_information.information FROM information LEFT JOIN all_information ON information.informationID=all_information.ID WHERE information.houseID=$id";
						$info=$connect->query($get_info);
						while($item=$info->fetchObject()){
	  					echo $item->information."<br>";
						}
	    		?></th>
		    	<th class="end">
			    	<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
		       			<input onclick="return confirm('Are you sure you want to remove this house from your favorite ?')" type="submit" name="submit" value="delete" id="delete_house_mgnt_page">
	      	 			<input type="hidden" name="houseID" value="<?php echo $house['houseID'];?>">
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
						echo '<a href="favorite_list.php?page='.$page.'">'.$page.'</a>&nbsp;&nbsp;';
					}
     		}
    	?>
	</div>
	<br>
</body>
</html>
