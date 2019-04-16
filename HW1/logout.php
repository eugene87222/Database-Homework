<?php
	session_start();
	$_SESSION['Authenticated']=false;
	unset($_SESSION["username"]);
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
		.content{
			font-size: 200%;
			margin-top: 40px;
		}
	</style>
</head>
<body>
	<div class="content">
		<?php echo "Log out......";?>
		<?php echo "<meta http-equiv=REFRESH CONTENT=1;url=index.php>";?>
	</div>
</body>
</html>