<?  
//session_start();
$val = $_GET['selected'];
	If ( $val > 0) 
	{
		define('DB_SERVER','localhost');
		define('DB_USERNAME','csci488_fall17');
		define('DB_PASSWORD','SeNSeMdb1');
		define('DB_DATABASE','csci488_fall17');

		mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die("Could not connect");
		mysql_select_db(DB_DATABASE) or die("Could not select database " . DB_DATABASE);
		$query = "SELECT * FROM heitkotter_test WHERE HIST_ID = $val";
		$result = mysql_query($query);
		$result = mysql_fetch_array($result);
		
		/*$visits_data = $_SESSION['visits_history'][$val];
		if ( $_GET['geo'] == 'yes' ) {
			$result = file_get_contents("https://tools.keycdn.com/geo.json?host=".$visits_data[1]); 
			$result = json_decode($result);
      
			//var_dump($result->data->geo); exit;
      
			$visits_data['geo'] = $result->data->geo;
		}*/
		
		echo json_encode($result);
		exit;
	}

?>