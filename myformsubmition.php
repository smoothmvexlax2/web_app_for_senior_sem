<?
// $_GET and $_POST are built-in PHP SuperGlobals that handle those kind of form submissions, respectively.
//The line below merges both into one array so that this script will handle both GET and POST form submissions.

$data = array_merge($_GET,$_POST);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Echo of HTML Form Submission</title>
  <style type="text/css">
  	table{
         border: 1px dashed ;
    }
	tr{
		text-align: center;
		padding: 10px;
	}
	td{
		border-right: 1px dashed;
	}
  </style>
</head>
<body>
This script echos back the data in all <tt>values</tt> submitted by any HTML form.
<hr />
<br />


<table width="" cellspacing="" cellpadding="3">
    <tr>
    <? foreach ($data as $key=>$value) 
	{ 
		if (is_array($value))
		{?>
				<td>
				<?
				foreach ($value as $goal=>$gotit)
				{?>	
					<?=$gotit?><br>
			<?  } ?>
				</td>
			<?	
		}

		else
		{	
			if ($key== "exercise03" && $value== "on")
			{
				?>
				<td><?="exercises 0-3"?></td>
			<?}
			elseif ($key== "exercise45" && $value== "on")
			{
				?>
				<td><?="exercises 4-5"?></td>
			<?}
			elseif ($key== "exercise67" && $value== "on")
			{
				?>
				<td><?="exercises 6-7"?></td>
			<?}
			else 
			{?>
				<td><?=$value?></td>
		<? } 			
		}
	}?>
	</tr>
</table>
  
<a href="http://csci.lakeforest.edu/~heitkotterbj/csci488/htmlformspage">Return to Form</a>
</body>
</html>