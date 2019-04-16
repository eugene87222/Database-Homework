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
			$ID=$_POST["ID"];
			$del=$connect->prepare("DELETE FROM all_information WHERE ID=:ID");
			$del->execute(array("ID"=>$ID));
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
	<div class="title">Information Management</div>
	<div class="content">
		<nav>
			<ul>
				<li><form action="home_page.php"><input type="submit" value="首頁"></form></li>
				<li><form>
					<input type="submit" onClick="ShowPopup('new_information.php')" value="新增資訊">
					<script type="text/javascript">
        		var popup;
	        	function ShowPopup(url) {
            	popup = window.open(url, "Popup", "toolbar=no,scrollbars=no,location=no,statusbar=no,menubar=no,resizable=0,width=500,height=220");
            	popup.focus();
        		}
    			</script>
				</form></li>
			</ul>
		</nav>
		<table id="loca_info_mgnt">
			<tr class="caption">
				<th class="begin">ID</th>
				<th class="middle">Information</th>
				<th class="end">Modify</th>
			</tr>
				<?php
					$sql="SELECT ID,information FROM all_information ORDER BY ID";
					$getdata=$connect->prepare($sql);
					$getdata->execute();
					$number_of_result=$getdata->rowCount();
					$number_of_page=ceil($number_of_result/$result_per_page);
					$sql="SELECT ID,information FROM all_information ORDER BY ID LIMIT ".($page-1)*$result_per_page.",".$result_per_page;
					$getdata=$connect->prepare($sql);
					$getdata->execute();
					while($information=$getdata->fetch()){
         		?>
      		<tr class="data">
          		<th><?php echo $information['ID'];?></th>
          		<th class="middle"><?php echo $information['information'];?></th>
          		<th class="end">
          			<form class="modify" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
          				<input onclick="return confirm('Are you sure you want to delete this information ?')" type="submit" name="submit" value="delete" id="delete">
          				<input type="hidden" name="ID" value="<?php echo $information['ID'];?>">
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
				echo '<a href="info_management.php?page='.$page.'">'.$page.'</a>&nbsp;&nbsp;';
			}
		?>
	</div>
	<br>
</body>
</html>
