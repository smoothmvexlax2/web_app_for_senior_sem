<?
require 'init.php'; // database connection, etc

$sql = "SELECT * FROM " . FORM_TABLE . " WHERE 1 ORDER BY EMAIL";
$result = lib::db_query($sql);

$num_rows = $result->num_rows;
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Non-Fancy Listing of Table</title>
		<script>
			function confirm_delete(formdata_id, email, pword) {
				var choice = confirm("Are you sure you want to delete " + email + " with password " + pword + "?");
        
				if ( choice == true ) {
					window.location.href = "exercise_form.php?task=delete&formdata_id="+formdata_id;
				}
			}
		</script>
	</head>
	<body>
	   <a href="exercise_form.php">Back To Form</a>
	   <br><br>
	   <? if ( isset($_REQUEST['deleted_message']) ) { ?>
          <b>The DB record was sucessfully deleted.</b> 
          <br><br>
     <? } ?>
	
	   <? if ($num_rows == 0) { ?>
	      <b>No records were found in the database.</b>
      <? } else { ?>
        
        <b>Listing of Database Records:</b>
        
        <table width="" border="1" cellspacing="0" cellpadding="5">
	      <tr  valign="top">
            <td>Email</td>
            <td>Password</td>
            <td>Age</td>
            <td>Gender</td>
			<td>Calories</td>
			<td>Exercise</td>
			<td>Diet</td>
			<td>Details</td>
			<td>Work out style</td>
			<td>Goals</td>
			<td>Date</td>
         </tr>
         <? while ( $row = $result->fetch_assoc() ) { ?>
            <tr  valign="top">
               <td><?=$row['EMAIL']?></td>
               <td><?=$row['P_WORD']?></td>
               <td><?=$row['AGE']?></td>
			   <td><?=$row['GENDER']?></td>
               <td><?=$row['CALORIES']?></td>
			   <td><?=$row['EXERCISE']?></td>
			   <td><?=$row['DIET']?></td>
			   <td><?=lib::textarea_to_html($row['DETAILS'])?></td>
			   <td><?=$row['WORKOUT_STYLE']?></td>
			   <td><? $arr = json_decode($row['GOALS']); foreach($arr as $g){ echo $g."<br>";}?></td>
			   <td><?=date("Y-m-d | h:i:sa", $row['DATE'])?></td>
			   <td>
                  <a href="exercise_form.php?task=edit&formdata_id=<?=$row['FORMDATA_ID']?>">Edit</a>
                  &nbsp;&nbsp;|&nbsp;&nbsp;
                  <a href="#null" onclick="confirm_delete(<?=$row['FORMDATA_ID']?> , '<?=$row['EMAIL']?>' , '<?=$row['P_WORD']?>')">Delete</a>
               </td>
            </tr>
	  <? }} ?>
         </table>
	</body>
</html>