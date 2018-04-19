<? 
	require 'init.php';
	if(!isset($_COOKIE['logged'])){
		header ("Location: login.php?cookie=error");
        exit;
	}
	$id = $_GET['id'];
	$cookie = $_COOKIE['logged'];
	
	$sql = "SELECT * FROM " . LOGIN_TABLE . " WHERE login_usr_id='$id' ORDER BY login_ts DESC";
	$result = lib::db_query($sql);
	$row = $result->fetch_assoc();
	if(hash('sha256', $cookie.SECRET)!= $row['login_cookie']){
		header ("Location: login.php?log=error");
        exit;
	}
	else{
		?>
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset="UTF-8">
		<meta name="author" content="Ben Heitkotter">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		</head>
		<div class="jumbotron text-center">
			<h1> Welcome, this page is secured</h1>
		</div>
		</html> 
	<?}
?>