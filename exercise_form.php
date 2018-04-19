<?
require 'init.php'; // database connection, etc

$task = $_REQUEST['task'];

switch ($task)  {

    case 'save':
		 if ( isset($_REQUEST['formdata_id']) && $_REQUEST['formdata_id'] > 0 ) {
            $formdata_id = $_REQUEST['formdata_id'];  // need to update existing person DB record
         }
         else {
            $formdata_id = 0;                    // need to create new person DB record  
         }
        // Create a database record From Form Submission
         $email = addslashes(trim($_REQUEST['mail']));
         $pword = addslashes(hash('sha256',trim($_REQUEST['pw'])));
         $age = addslashes(trim($_REQUEST['age']));
         $gender = addslashes(trim($_REQUEST['gender']));
		 $calories = addslashes(trim($_REQUEST['arange']));
		 $exercise ="";
		 if( $_REQUEST['exercise03']){
			$exercise = $exercise."0-3 ";
		 }
		 if( $_REQUEST['exercise45']){
			$exercise = $exercise."4-5 ";
		 }
		 if( $_REQUEST['exercise67']){
			$exercise = $exercise."6-7";
		 }
		 $exercise = addslashes($exercise);
		 $diet = addslashes(trim($_REQUEST['diet']));
		 $details = addslashes(trim($_REQUEST['details']));
		 $wo_style = addslashes(trim($_REQUEST['wostyle']));
		 $goals = json_encode($_REQUEST['goals']);
		 $date = $_SERVER[REQUEST_TIME];
		
		
        // Build the INSERT statement
		if($formdata_id==0){
			$sql = "INSERT INTO " . FORM_TABLE . " VALUES ('','$email','$pword','$age','$gender', '$calories', '$exercise', '$diet', '$details', '$wo_style', '$goals', '$date') ";
		}
		else{
			$sql = "UPDATE ". FORM_TABLE ." SET EMAIL='$email', P_WORD= '$pword', AGE='$age', GENDER='$gender', CALORIES='$calories', EXERCISE='$exercise', DIET='$diet', DETAILS='$details', WORKOUT_STYLE= '$wo_style', GOALS= '$goals' WHERE FORMDATA_ID='$formdata_id' ";
		}
        
        // Execute the INSERT statement 
        lib::db_query($sql);
        
        // Used db_query() wrapper for $mysqli->query($query) from class_lib.php
              
        // Transfer to the listing page -- not good to let the browser sitting on the post or get data transaction
        // The PHP header function adds the Location directive to the HTTP response header, which then causes the browser to do the transfer.
        header ("Location: formdata_listing.php");
        exit;
        break;
        /////////////////////////////
        // End Save Case
        /////////////////////////////
        
    case 'delete':
		if ( isset($_REQUEST['formdata_id']) && $_REQUEST['formdata_id'] > 0 ) {
          $formdata_id = $_REQUEST['formdata_id'];                    
        }
       
		$sql = "DELETE FROM " . FORM_TABLE . " WHERE FORMDATA_ID = $formdata_id ";
		lib::db_query($sql);
       
		header ("Location: formdata_listing.php?deleted_message=yes");
		exit;
		break;  
      
      /////////////////////////////
      // End delete Case
      /////////////////////////////
    
    case 'edit':
		if ( ! isset($_REQUEST['formdata_id']) || $_REQUEST['formdata_id'] <= 0 ) {
        // if no incoming ppl_id, just give blank form
			break; 
        }
      
        $formdata_id = $_REQUEST['formdata_id'];
		$sql = "SELECT * FROM " . FORM_TABLE . " WHERE  FORMDATA_ID=$formdata_id ";
		$result = lib::db_query($sql);
		$row = $result->fetch_assoc();  // will only be one row
		$row['GOALS'] = json_decode($row['GOALS']);
		foreach ($row as $key => $value) {
			if ($row[$key]==$row['GOALS']){
				continue;
			}
			$row[$key] = htmlspecialchars($value);
        // certain characters like " and < and > are reserved in HTML.
        // This converts them all into HTML character entities like &quot;
		}
		break;
      
      /////////////////////////////
      // End edit Case
      /////////////////////////////
    default: 
	
}

?>
	
<!DOCTYPE html>
<html>
	<head>
		<title>formpage</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			function getv(obj){
				var cal = obj.value;
				document.getElementById('returnvalue').innerHTML = "" + cal;
			}
		</script>
	</head>
	<body>
		<body>
		<a href="http://csci.lakeforest.edu/~heitkotterbj/csci488/formdata_listing.php">Listing page</a><br><br>
		<form name="stuff1" id="stuff1" action="exercise_form.php" method="POST" novalidate>
			<input type="hidden" name="task" value="save">
			<input type="hidden" name="formdata_id" value="<?=$formdata_id?>">
			<div class ="container">
			<div class="form-group">
			<div class="row">
				<div class="col-md-4">
					<label for="mail">Please Enter your Email:</label> 
					<input id="mail" type="email" name="mail" class="form-control" placeholder="duecebigalo@mailgigilo.com" value="<?=$row['EMAIL']?>"/>
    
					<br /><br />
					<label for="pw">Password:</label> 
					<input id="pw" type="password" name="pw" class="form-control" value="<?=$row['P_WORD']?>"/> 
				</div>	
			</div>
				<div class="row">
					<div class="col-md-3">
						<label for="age">Select age:</label>
						<input id="age" type="number" name="age" value="<?=$row['AGE']?>" min ="14" max ="110"/> 
					</div>
					<div class="col-md-3">
						<label for="gender"> Select Gender</label>
							<div class="radio">
								<label><input id="female" type="radio" name="gender" value="female" <?if($row['GENDER']=="female"){echo 'checked="yes"';}?>> Female</label>
							</div>
							<div class="radio">
								<label><input id="male" type="radio" name="gender" value="male" <?if($row['GENDER']=="male"){echo 'checked="yes"';}?>> Male</label>
							</div>
							<div class="radio">
								<label><input id="transgender" type="radio" name="gender" value="transgender" <?if($row['GENDER']=="transgender"){echo 'checked="yes"';}?>> Transgender</label>
							</div>
							<div id="gendererror"></div>
					</div>
					<div class="col-md-6">
						<label for="arange">caloric intake per day:</label><span class="label label-primary" id="returnvalue"><?=$row['CALORIES']?></span><br>
						<input id= "arange" type="range" name="arange" min="500" max="6000" onchange="getv(this)" onload="getv(this)" value="<?=$row['CALORIES']?>"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<? $exer =$row['EXERCISE']; $exarr = str_split($exer);?>
						<div class="checkbox">
							<label><input id="exercise03" name="exercise03" type="checkbox" <? if(in_array(0,$exarr)){echo 'checked="yes"';} ?>/>exercise 0-3 times a week</label>
						</div>
						<div class="checkbox">
							<label><input id="exercise45" name="exercise45" type="checkbox" <? if(in_array(4,$exarr)){echo 'checked="yes"';} ?>/>exercise 4-5 times a week</label>
						</div>
						<div class="checkbox">
							<label><input id="exercise67" name="exercise67" type="checkbox" <? if(in_array(6,$exarr)){echo 'checked="yes"';} ?>/>exercise 6-7 times a week</label>
						</div>
						<div id="excerciseerror"></div>
					</div>
					<div class="col-md-6">
						<label for="yah">Pick a diet</label>
						<ul id="yah">
							<label class="radio-inline"><input type="radio" name="diet" value="nodiet" <?if($row['DIET']=="nodiet"){echo 'checked="yes"';}?>> No structure</label>
							<label class="radio-inline"><input type="radio" name="diet" value="vegetarian" <?if($row['DIET']=="vegetarian"){echo 'checked="yes"';}?>> vegetarian</label>
							<label class="radio-inline"><input type="radio" name="diet" value="vegan" <?if($row['DIET']=="vegan"){echo 'checked="yes"';}?>> vegan</label>
							<label class="radio-inline"><input type="radio" name="diet" value="macro" <?if($row['DIET']=="macro"){echo 'checked="yes"';}?>> macro counting</label>
							<label class="radio-inline"><input type="radio" name="diet" value="paleo" <?if($row['DIET']=="paleo"){echo 'checked="yes"';}?>> paleo</label>
						</ul>
						<div id="dieterror"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label for="details">Details:</label>
							<textarea id="details" name="details" class="form-control" rows=10 cols=50><?=$row['DETAILS']?></textarea>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-3">
						<label for="wostyle">pick a work out style</label>
						<select id= "wostyle" name="wostyle" class="form-control">
  								<option value="Powerlift" <? if($row['WORKOUT_STYLE']=="Powerlift"){echo 'selected';}?>>powerlift</option>
  								<option value="Endurance" <? if($row['WORKOUT_STYLE']=="Endurance"){echo 'selected="yes"';} ?>>endurance</option>
  								<option value="Strongman" <? if($row['WORKOUT_STYLE']=="Strongman"){echo 'selected="yes"';} ?>>strongman</option>
  								<option value="BodyBuilding" <? if($row['WORKOUT_STYLE']=="BodyBuilding"){echo 'selected="yes"';}?>>bodybuilding</option>
  								<option value="CrossFit" <? if($row['WORKOUT_STYLE']=="CrossFit"){echo 'selected="yes"';}?> >crossfit</option>
								<option value="Yoga" <? if($row['WORKOUT_STYLE']=="Yoga"){echo 'selected="yes"';} ?> >yoga</option>
								<option value="Anything" <? if($row['WORKOUT_STYLE']=="Anything"){echo 'selected="yes"';}?> >anything</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="goals">pick your goals</label>
						<select id="goals" name="goals[]" class="form-control" multiple>
							<option value="gain muscle" <? if(in_array('gain muscle',$row['GOALS'])){echo 'selected="yes"';}?>>gain muscle</option>
  							<option value="lose weight" <? if(in_array('lose weight',$row['GOALS'])){echo 'selected="yes"';}?>>lose weight</option>
  							<option value="better mobility" <? if(in_array('better mobility',$row['GOALS'])){echo 'selected="yes"';}?>>better mobility</option>
  							<option value="increase stamina"<? if(in_array('increase stamina',$row['GOALS'])){echo 'selected="yes"';}?>>increase stamina</option>
						</select>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<input class="form-control" type="submit"/><input class="form-control" type="reset"/>
					</div>
				</div>
			</div>
			</div>
		</form>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js"></script>
	<script type="text/javascript">
	
	$().ready(function() {
		jQuery.validator.addMethod("nowhite", function(value, element) 
		{
         // allow any non-whitespace characters as the host part
			return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]/.test( value );
		}, 'nice try, but please try again');
		
		$("#stuff1").validate({
				
			rules: 
			{
				mail: {
					required: true,
					email:true
				},
				
				pw: {
					required: true,
					minlength: 5,
					nowhite: true
				},
				
				age:{ 
					required: true,
					digits: true
				},
					
				arange: "required",
				
				exercise03: {
					required:{
						depends: function(element) {
							return ($('#exercise45').is(':unchecked') && $('#exercise67').is(':unchecked'))
						}
					
					}
				},
				
				
				diet: "required",
				gender: "required",
	
				details: {
					required: true,
					minlength: 1,
					maxlength: 140,
					nowhite: true
				},
				"goals[]": {
					required: true,
				},
				
				wostyle: {
					required: true,
				},
				
			},
			messages: 
			{
				mail: 
				{
					required: "please enter a valid email",
					email: "the email you entered was not valid",
				},
				pw: {
					required: "please enter a password",
					minlength: "the password must be at least 5 characters long", 
				},
				age: "Please enter your age",
				arange: "Please enter your caloric intake",
				exercise03: "Please check a box",
				wostyle: "please select a work out style",
				diet: "please pick a diet",
				details: {
					required: "please add details",
					minlength: "please add something",
					maxlength: "no more than 140 characters",
					nowhite: "nice try, but please add details"
				},
				gender: "Please pick a gender",
				"goals[]": "Please pick at least 1 goal"
			},
			errorPlacement : function(error, element) {
				if($(element).prop("name") === "diet") {
					$("#dieterror").append(error);
				}
				else if($(element).prop("name") === "gender") {
					$("#gendererror").append(error);
				}
				else if($(element).prop("name") === "exercise03") {
					$("#exerciseerror").append(error);
				}
				else {
					error.insertAfter(element); // default error placement.
				}
			}
		
		});

	});
	
		</script>	
	</body>
</html>

	

	
	