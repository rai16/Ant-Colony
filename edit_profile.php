<?php
	$flag = 0;
	function crypto_rand_secure($min, $max)
	{
		$range = $max - $min;
		if ($range < 1) return $min; // not so random...
		$log = ceil(log($range, 2));
		$bytes = (int) ($log / 8) + 1; // length in bytes	
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}
	function getToken($length)
	{
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet) - 1;
		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[crypto_rand_secure(0, $max)];
		}
		return $token;
	}
	session_set_cookie_params(3600);
	session_start();
	$connec = oci_connect('antcolony','whatidodefinesme','localhost');
	if (!$connec) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	function docheck()
	{
		if (isset($_COOKIE['antcolonylogin'])==true)
		{
			if (isset($_SESSION[$_COOKIE['antcolonylogin']])&&$_SESSION[$_COOKIE['antcolonylogin']]==1)
			{
				return true;
			}
			else
			{
				session_unset();
				setcookie('antcolonylogin',"",time()-100);
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	if (docheck()==false)
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
	$handle = oci_parse($connec,"select * from profile where id = '".$_COOKIE['antcolonylogin']."'");
	oci_execute($handle);
	$dat = oci_fetch_array($handle,OCI_BOTH);
	$valid_images = array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG);
	if (isset($_FILES['dpupload']['name'])&&$_FILES['dpupload']['name']!="")
	{
		if (in_array(exif_imagetype($_FILES['dpupload']['tmp_name']),$valid_images))
		{
			$uploaddir = "./images/";
			$str = getToken(40);
			$fileadd = $uploaddir.$str.".".substr($_FILES['dpupload']['type'],stripos(image_type_to_mime_type(exif_imagetype($_FILES['dpupload']['tmp_name'])),"/")+1);
			if (move_uploaded_file($_FILES['dpupload']['tmp_name'], $fileadd)) 
			{
				if (isset($dat['DPNAME']))
				{
					unlink($dat['DPNAME']);
				}
				$handle = oci_parse($connec,"update profile set dpname = '".$fileadd."' where id = '".$dat['ID']."'");
				oci_execute($handle);
				$handle = oci_parse($connec,"select * from profile where id = '".$_COOKIE['antcolonylogin']."'");
				oci_execute($handle);
				$dat = oci_fetch_array($handle,OCI_BOTH);
			}
		}
		else
		{
			$flag = 1;
		}
	}
	else if (isset($_FILES['dpupload']['name'])&&$_FILES['dpupload']['name']=="")
	{
		$flag = 2;
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ANTColony</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
  </head>
  
  <style>
  
  
	html { 
	background: url("profile_background.jpg") no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	}
  
	#head_edit_profile
	{
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
		margin-left: 80px;
		margin-top: 40px;
		font-size: 3.7em;
		font-weight: bold;
		
	}
  
  </style>
  
  
  <body>
		
	<div class="container-fluid">
		
		<div class="col-md-12 container" style= "border-bottom-style: solid; ">
			<img src="anticon.png" class="img-circle" alt="Cinque Terre" width="100" height="100" style="float: left; margin: 30px;">
			<h1 id="head_edit_profile"> EDIT PROFILE </h1>
			<ul class="nav navbar-nav navbar-right">
					<li><a href="./index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="#" onclick="logmeout()"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
				</ul>
		</div>
		
		<div class="col-md-4" style="height: 400px; float:left;">
		<?php
			if (!isset($dat['DPNAME']))
			{
				if (isset($dat['GENDER']))
				{
					if ($dat['GENDER']==0)
					{
						$x = "./gravatar_male.png";
					}
					else
					{
						$x = "./gravatar_female.png";
					}
				}
				else
				{
					$x = "./gravatar_male.png";
				}
			}
			else
			{
				$x = $dat['DPNAME'];
			}
			echo '<img src="'.$x.'" id="gravatar_icon" class="img-circle" alt="Cinque Terre" width="200" height="200" style="margin: 20px; border: 3px solid grey;">';
			?>
			<form class="form-horizontal" role="form" method = "POST" action="" enctype="multipart/form-data">
				<div class="form-group">
					<label class="control-label col-sm-4" for="upload_img">Upload image:</label>
					<div class="col-sm-8">
						<input type="file" class="form-control" id="uppload_img" style="padding: 0px;" name="dpupload" onclick="formathide()">
						<br>
						<?php if ($flag==1)
						{
							echo '<p style="color:red" id="formatw">Only jpeg,png and gif format are allowded</p>';
						}
							else if ($flag==2)
							{
								echo '<p style="color:red" id="formatw">Input a file</p>';
							} ?>
						<button type="submit" class="btn btn-primary btn-lg">Upload</button>
					</div>
				</div>
			</form>
			
		</div>
		
		<div class="col-md-8" style="height: 600px;">
			<h2> Tell us about yourself : </h2>
			
			<form class="form-horizontal" role="form">
							
				<div class="form-group">
					<label class="control-label col-sm-3" for="fname">First Name:</label>
					<div class="col-sm-9">
					<?php
						if (!isset($dat['FNAME']))
						{
							$fx = "";
						}
						else
						{
							$fx = $dat['FNAME'];
						}
						echo '<input type="text" class="form-control" id="fname" placeholder="Enter first name" value="'.$fx.'">';
					?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="lname">Last Name:</label>
					<div class="col-sm-9">
					<?php
						if (!isset($dat['LNAME']))
						{
							$fx = "";
						}
						else
						{
							$fx = $dat['LNAME'];
						}
						echo '<input type="text" class="form-control" id="lname" placeholder="Enter last name" value="'.$fx.'">';
					?>
					</div>
				</div>
								
				<div class="form-group">
					<label class="control-label col-sm-3" for="dob">Date of Birth:</label>
					<div class="col-sm-9">
					<?php
						if (!isset($dat['DOB']))
						{
							$fx = "";
						}
						else
						{
							$handle = oci_parse($connec,"select to_char(dob,'dd/mm/yyyy') as Daob from profile where id = '".$dat['ID']."'");
							oci_execute($handle);
							$ret2 = oci_fetch_array($handle,OCI_BOTH);
							$fx = $ret2['DAOB'];
						}
						echo '<input type="date" class="form-control" id="dob" placeholder="dd/mm/yyyy" value="'.$fx.'">';
					?>	
					</div>
				</div>
								
				<div class="form-group">
					<label class="control-label col-sm-3" for="uname">Phone:</label>
					<div class="col-sm-9">
					<?php
						if (!isset($dat['PHONE']))
						{
							$fx = "";
						}
						else
						{
							$fx = $dat['PHONE'];
						}	
						echo '<input type="text" class="form-control" id="phone" placeholder="Enter your mobile number" value="'.$fx.'">';
					?>
					</div>
			    </div>
								
				<div class="form-group">
					<label class="control-label col-sm-3" for="city">City:</label>
					<div class="col-sm-9">
					<?php
						if (!isset($dat['CITY']))
						{
							$fx = "";
						}
						else
						{
							$fx = $dat['CITY'];
						}
						echo '<input type="text" class="form-control" id="city" placeholder="Where do you live?" value="'.$fx.'">';
					?>
					</div>
				</div>
								
				<div class="form-group">
					<label class="control-label col-sm-3" for="country">Country:</label>
					<div class="col-sm-9">
					<?php
						if (!isset($dat['COUNTRY']))
						{
							$fx = "";
						}
						else
						{
							$fx = $dat['COUNTRY'];
						}
						echo '<input type="text" class="form-control" id="country" placeholder="Where are you from?" value="'.$fx.'">';
					?>
					</div>
				</div>
				<div class="form-group">
				<label class="control-label col-sm-3" for="gender" style="margin-top: 8px;"><b>Gender:</b></label>
				
				<div class="radio col-sm-9" id="gender">
					<?php
						if (!isset($dat['GENDER']))
						{
							echo'<label class="radio-inline"><input type="radio" name="gender_radio" value="male"/>Male </label>
							<label class="radio-inline"><input type="radio" name="gender_radio" value="female"/>Female </label>'; 
						}
						else
						{
							if ($dat['GENDER'] == 0)
							{
								echo '<label class="radio-inline"><input type="radio" name="gender_radio" value="male" checked="true"/>Male </label>';
								echo '<label class="radio-inline"><input type="radio" name="gender_radio" value="female"/>Female </label>';
							}
							else
							{
								echo '<label class="radio-inline"><input type="radio" name="gender_radio" value="male"/>Male </label>';
								echo '<label class="radio-inline"><input type="radio" name="gender_radio" value="female" checked="true"/>Female </label>';
							}
						}
					?>
				</div>
				</div>
				<div class="form-group">
				<label class="control-label col-sm-3" for="fpublic" style="margin-top: 8px;"><b>Visibility:</b></label>
				<div class="radio col-sm-9" id="fpublic">
				<?php
					if (!isset($dat['FPUBLIC']))
					{
						echo'<label class="radio-inline"><input type="radio" name="fpublic_radio" value="public"/>Public </label>
						<label class="radio-inline"><input type="radio" name="fpublic_radio" value="private"/>Private </label>'; 
					}
					else
					{
						if ($dat['FPUBLIC']==1)
						{
							echo '<label class="radio-inline"><input type="radio" name="fpublic_radio" value="public" checked="true"/>Public </label>
							<label class="radio-inline"><input type="radio" name="fpublic_radio" value="private"/>Private </label>';
						}
						else 
						{
							echo '<label class="radio-inline"><input type="radio" name="fpublic_radio" value="public"/>Public </label>
							<label class="radio-inline"><input type="radio" name="fpublic_radio" value="private" checked="true"/>Private </label>'; 
						}
					}
				?>
				</div>
				</div>
			</form>
			<p style="color:green; margin-left:40px; visibility:hidden" id="saved"><b>Changes saved</b></p>
			<button class="btn btn-success btn-lg" style="margin: 19px;" onclick="updateme()">Save Changes</button>
		</div>
		
	</div>
			
		
	<!-- Javascript starts here -->
	<script>
		var cphone = 0,ccity = 0,ccountry = 0;
		var cfname=0, clname=0;
		var cdob = 0;
		function logmeout()
		{
			document.cookie = "antcolonylogin=; expires="+(Date().getMillisceonds-3600);
			window.location = "http://localhost";
		}
			
		$("#gender").change(function(){
			if (($("#gravatar_icon").attr("src")=="gravatar_male.png")||($("#gravatar_icon").attr("src")=="./gravatar_male.png")||($("#gravatar_icon").attr("src")=="gravatar_female.png")||($("#gravatar_icon").attr("src")=="./gravatar_female.png"))
			{
				var selected = $("input[name='gender_radio']:checked").val();
				if( selected=="male")
				{
					$("#gravatar_icon").attr("src", "gravatar_male.png");
				}
				else if(selected=="female")
				{
					$("#gravatar_icon").attr("src", "gravatar_female.png");
				}
			}
		});
		function formathide()
		{
			$("#formatw").hide();
		}
		function isLetter(inputtxt)  
		{  
			var letters = /^[A-Za-z]+$/;
			return letters.test(inputtxt);
	    }
		function isDate(stri)
		{
			var regex = /(^(((0[1-9]|1[0-9]|2[0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)/;
			return regex.test(stri);
		}
		function isPhone(stri)
		{
			var regex = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/;
			return regex.test(stri);
		}
		{
			var fname=  $("#fname").val();
			if(!isLetter(fname) && fname!=""){
		
				$("#fname").css("border","2px solid red");
				$("#fname").val("");
				cfname = 0;
			}
			else if(isLetter(fname) && fname!=""){
				$("#fname").css("border","2px solid green");
				cfname=1;
			}
			/***************************************/
			var lname=  $("#lname").val();
			if(!isLetter(lname) && lname!=""){
		
				$("#lname").css("border","2px solid red");
				$("#lname").val("");
				clname = 0;
			}
			else if(isLetter(lname) && lname!=""){
				$("#lname").css("border","2px solid green");
				clname =1;
			}
			/********************************/
			var dob=  $("#dob").val();
			if(!isDate(dob) && dob!=""){
		
				$("#dob").css("border","2px solid red");
				$("#dob").val("");
			}
			else if(isDate(dob) && dob!=""){
				$("#dob").css("border","2px solid green");
				cdob =1;
			}
			else if (dob=="")
			{
				cdob = 1;
			}
			/************************************/
			var phone=  $("#phone").val();
			if(!isPhone(phone) && phone!=""){
		
				$("#phone").css("border","2px solid red");
				$("#phone").val("");
			}
			else if(isPhone(phone) && phone!=""){
				$("#phone").css("border","2px solid green");
				cphone =1;
			}
			else if (phone=="")
			{
				cphone = 1;
			}
			/***************************************/
			var city=  $("#city").val();
			if(!isLetter(city) && city!=""){
		
				$("#city").css("border","2px solid red");
				$("#city").val("");
			}
			else if(isLetter(city) && city!=""){
				$("#city").css("border","2px solid green");
				ccity =1;
			}
			else if (city=="")
			{
				ccity = 1;
			}
			/***********************************************/
			var country=  $("#country").val();
			if(!isLetter(country) && country!=""){
		
				$("#country").css("border","2px solid red");
				$("#country").val("");
			}
			else if(isLetter(country) && country!=""){
				$("#country").css("border","2px solid green");
				ccountry =1;
			}
			else if (country=="")
			{
				ccountry = 1;
			}
		}
			
		$("#fname").blur(function(){
	
			var fname=  $("#fname").val();
			if(!isLetter(fname) && fname!=""){
		
				$("#fname").css("border","2px solid red");
				$("#fname").val("");
				cfname = 0;
			}
			else if(isLetter(fname) && fname!=""){
				$("#fname").css("border","2px solid green");
				cfname=1;
			}
		});
		
		$("#lname").blur(function(){
	
			var lname=  $("#lname").val();
			if(!isLetter(lname) && lname!=""){
		
				$("#lname").css("border","2px solid red");
				$("#lname").val("");
				clname = 0;
			}
			else if(isLetter(lname) && lname!=""){
				$("#lname").css("border","2px solid green");
				clname =1;
			}
		});
		$("#dob").blur(function(){
			var dob=  $("#dob").val();
			if(!isDate(dob) && dob!=""){
		
				$("#dob").css("border","2px solid red");
				$("#dob").val("");
			}
			else if(isDate(dob) && dob!=""){
				$("#dob").css("border","2px solid green");
				cdob =1;
			}
			else if (dob=="")
			{
				cdob = 1;
			}
		});
		$("#phone").blur(function(){
			var phone=  $("#phone").val();
			if(!isPhone(phone) && phone!=""){
		
				$("#phone").css("border","2px solid red");
				$("#phone").val("");
			}
			else if(isPhone(phone) && phone!=""){
				$("#phone").css("border","2px solid green");
				cphone =1;
			}
			else if (phone=="")
			{
				cphone = 1;
			}
		});
		$("#city").blur(function(){
			var city=  $("#city").val();
			if(!isLetter(city) && city!=""){
		
				$("#city").css("border","2px solid red");
				$("#city").val("");
			}
			else if(isLetter(city) && city!=""){
				$("#city").css("border","2px solid green");
				ccity =1;
			}
			else if (city=="")
			{
				ccity = 1;
			}
		});
		$("#country").blur(function(){
			var country=  $("#country").val();
			if(!isLetter(country) && country!=""){
		
				$("#country").css("border","2px solid red");
				$("#country").val("");
			}
			else if(isLetter(country) && country!=""){
				$("#country").css("border","2px solid green");
				ccountry =1;
			}
			else if (country=="")
			{
				ccountry = 1;
			}
		});
		function updateme()
		{
			if (cphone == 1 && ccity == 1 && ccountry == 1 && cfname == 1 && clname == 1 && cdob == 1)
			{
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
				if (xhttp.readyState == 4 && xhttp.status == 200)
				{
					var res = xhttp.responseText;
					if (res != "YES")
					{
						window.alert("Profile Update Unsuccessful");
					}
					else
					{
						$("#saved").attr("style","color:green; margin-left:40px;");
						$("#saved").hide(2000);
					}
				}
				};
				var fname = $("#fname").val();
				var lname = $("#lname").val();
				var dob = $("#dob").val();
				var phone = $("#phone").val();
				var city = $("#city").val();
				var country = $("#country").val();
				var gender = $("input[name='gender_radio']:checked").val();
				var fpublic = $("input[name='fpublic_radio']:checked").val();
				xhttp.open("POST","./update.php",true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("fname="+fname+"&lname="+lname+"&dob="+dob+"&phone="+phone+"&city="+city+"&country="+country+"&gender="+gender+"&fpublic="+fpublic);
			}
			else
			{
				window.alert("Fill all fields correctly.");
			}
		}
	</script>
  </body>
</html>