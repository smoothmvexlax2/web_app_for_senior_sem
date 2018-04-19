<?
	require 'init.php';

	$usr_name = addslashes(trim($_REQUEST['usr_name']));
	$pw = addslashes(trim(hash('sha256', $_REQUEST['pw'])));
	$sql = "SELECT * FROM " . ACCT_TABLE . " WHERE usr_name='$usr_name' and usr_pw = '$pw' ";
	$result = lib::db_query($sql);
	$num_rows = $result->num_rows;
	if($num_rows==0){
		$log = 'Invalid Username or Password';
	}
	if($num_rows==1){
		$row = $result->fetch_assoc();
		$mail = $row['usr_email'];
		$id = $row['usr_id'];
		if($row['usr_verified']==0){
			
			$sql = "UPDATE ". ACCT_TABLE ." SET usr_name='$mail', usr_verified=1";
			lib::db_query($sql);
		}
		$date = $_SERVER[REQUEST_TIME];
		$token = uniqid();
		$cookie = addslashes(trim(hash('sha256', $token.SECRET)));
		setcookie('logged', $token , time() + (60*60) );
		$sql = "INSERT INTO " . LOGIN_TABLE . " VALUES ('', '$date', '$cookie', '$id') "; 
		lib::db_query($sql);
		header ('Location: securehomepage.php?id='.$id.'');
        exit;
    }	
	else{
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="cs488 project">
  <meta name="keywords" content="HTML, CS488, Server">
  <meta name="author" content="Ben Heitkotter">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
		#invalidpw{
			color: red;
		}
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript">
	window.onload = function heading()
	{
		var w = screen.width;
		var h = screen.height;
		var pixels = w*h;
		var div1 = document.getElementById("divider1");
		var div2 = document.getElementById("divider2");
		var div3 = document.getElementById("divider3");
		if(pixels < 768){
			div1.className= "col-xs-4";
			div2.className= "col-xs-4";
			div3.className= "col-xs-2";
		}
		else if( 768<= pixels < 992){
			div1.className= "col-sm-4";
			div2.className= "col-sm-4";
			div3.className= "col-sm-2";
		}
		else if( 992<= pixels < 1200){
			div1.className= "col-md-4";
			div2.className= "col-md-4";
			div3.className= "col-md-2";
		}
		else{
			div1.className= "col-lg-4";
			div2.className= "col-lg-4";
			div3.className= "col-lg-2";
		}
	};
	
	</script> 
</head>
<body>
	<form name="login" id="login" method="POST" action="login.php" novalidate>
	<div class="container">
		<div class="row">
			<div id="divider1">
				<label for="usr_name">Please Enter Your Username:</label> 
				<input id="usr_name" type="text" name="usr_name" class="form-control" placeholder="my@email.com"/>
				<a href="http://csci.lakeforest.edu/~heitkotterbj/csci488/newperson">create account</a>
			</div>
			<div id="divider2">
				<label for="pw">Password:</label>
				<input id="pw" type="password" name="pw" class="form-control" placeholder="*****"/> 
			</div>
			<div id="divider3">
				<label for="sub"></label> 
				<input id="sub" class="form-control" type="submit"/>
				<span id="invalidpw"><?if(isset($log)){echo $log;}?></span>
			</div>
		</div>
	</div>
	</form>
</body>
</html>
	<?}?>