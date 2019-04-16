<?php
	session_start();
	$_SESSION["Authenticated"]=false;
	if(isset($_POST["username"])&&isset($_POST["password"])){
		$username=$_POST["username"];
		$password=$_POST["password"];
		try{
			require_once "connect.php";
			$stmt=$connect->prepare("SELECT username,password,identity FROM user WHERE username=:username");
			$stmt->execute(array("username"=>$username));
			if($stmt->rowCount()==1){
				$row=$stmt->fetch();
				$username_chk=$row[0];
				$password_chk=$row[1];
				$identity=$row[2];
				if(!strcmp($username_chk,$username)&&password_verify($password,$password_chk)){
					$_SESSION['Authenticated']=true;
					$_SESSION['username']=$row[0];
					$_SESSION['identity']=$identity;
					header("Location: home_page.php?page=1");
					exit();
				}
				else{
				session_unset();
				session_destroy();
				echo<<<EOT
					<!DOCTYPE>
					<html>
					<body>
					<script>
						alert("Login failed.");
						window.location.replace('index.php');
					</script>
					</body?
					</html>
EOT;
				}
				exit();
			}
			else{
				session_unset();
				session_destroy();
				echo<<<EOT
					<!DOCTYPE>
					<html>
					<body>
					<script>
						alert("Login failed.");
						window.location.replace('index.php');
					</script>
					</body?
					</html>
EOT;
			}
		}
		catch(PDOException $e){
			$msg=$e->getMessage();
			session_unset();
			session_destroy();
			echo <<<EOT
				<!DOCTYPE>
					<html>
					<body>
					<script>
						alert("Internal error.");
						window.location.replace('index.php');
					</script>
					</body?
					</html>
EOT;
		}
	}
?>