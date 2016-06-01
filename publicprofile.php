<?php
	$handle = oci_parse($connec,"select * from profile where id = '".$_REQUEST['user']."'");
	oci_execute($handle);
	$dat = oci_fetch_array($handle,OCI_BOTH);
	if ($dat==false)
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
	$handle = oci_parse($connec,"select email from users where id = '".$_REQUEST['user']."'");
	oci_execute($handle);
	$dat1 = oci_fetch_array($handle,OCI_BOTH);
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
  
  
	h4{
		color: white;
		font-size: 1.6em;
		font-weight: bold;
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
	}
	
	h5{
		color: #16A085;
		font-size: 1.4em;
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
		margin-left: 15px;
	}
  

  </style>
  <body>
	
		 <nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<img src="anticon.png" class="img-circle" alt="Cinque Terre" width="40" height="40" style="float: left; margin-top: 5px;">
					<a class="navbar-brand" href="#"><h3 style="font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif; margin-top: 0px; margin-left: 5px;"><b>ANT</b> Colony</h3></a>
				</div>
				
		
				<ul class="nav navbar-nav navbar-right">
					<li><a href="./index.php"><span class="glyphicon glyphicon-log-in"></span> Log in</a></li>
				</ul>
			</div>
		</nav>
		
		<div class="col-md-4" style="height: 400px; float: left; margin-top: 70px;">
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
			echo '<img src="'.$x.'" class="img-thumbnail" width="300" height="380" style="margin: 70px; margin-top: 30px;">';
?>
			  <div class="list-group" style="width: 300px; margin-left: 70px;">
				<a href="./globaluserlist.php" class="list-group-item">Global User List</a>
			  </div>
			  </div>
			
		</div>
		
		<div class="col-md-8"style="margin-top: 70px;">
		
			<div class="container-fluid" style="background-color: #ECF0F1; border-radius: 10px; margin-top: 30px; padding-left: 0px;">
<?php
				echo '<div style="background-color: #16A085; text-align: center;  margin: 0px; color: white; width: 150px; float: left; border-radius: 5px;">
					<h4>Name:</h4>
				</div>
				
				<div style="padding: 4px; margin-left: 4px;float: left; border-radius: 5px;">';
					echo '<h5>'.$dat['FNAME'].' '.$dat['LNAME'].'</h5>
				</div>
				
			</div>
			
			<div class="container-fluid" style="background-color: #ECF0F1; border-radius: 10px; margin-top: 30px; padding-left: 0px;">
			
				<div style="background-color: #16A085; text-align: center; margin: 0px; color: white; width: 150px; float: left; border-radius: 5px;">
					<h4>Email:</h4>
				</div>
				
				<div style="padding: 4px; margin-left: 4px;float: left; border-radius: 5px;">';
					echo '<h5>'.$dat1['EMAIL'].'</h5>
				</div>
				
			</div>
			
			<div class="container-fluid" style="background-color: #ECF0F1; border-radius: 10px; margin-top: 30px; padding-left: 0px;">
			
				<div style="background-color: #16A085; text-align: center; margin: 0px; width: 150px; color: white; float: left; border-radius: 5px;">
					<h4>Address:</h4>
				</div>
				
				<div style="padding: 4px; margin-left: 4px;float: left; border-radius: 5px;">';
					if (isset($dat['CITY']))
					{
						$x = $dat['CITY'];
					}
					else
					{
						$x = "N.A";
					}
					if (isset($dat['COUNTRY']))
					{
						$y = $dat['COUNTRY'];
					}
					else
					{
						$y = "N.A";
					}
					echo '<h5>'.$x.','.$y.'</h5>
				</div>
				
			</div>
			
			<div class="container-fluid" style="background-color: #ECF0F1; border-radius: 10px; margin-top: 30px; padding-left: 0px;">
			
				<div style="background-color: #16A085; text-align: center; margin: 0px; width: 150px; color: white; float: left; border-radius: 5px;">
					<h4>Date of Birth:</h4>
				</div>
				
				<div style="padding: 4px; margin-left: 4px;float: left; border-radius: 5px;">';
					if (isset($dat['DOB']))
					{
						$x = $dat['DOB'];
					}
					else
					{
						$x = "N.A";
					}
					echo '<h5>'.$x.'</h5>
				</div>
				
			</div>
			
			<div class="container-fluid" style="background-color: #ECF0F1; border-radius: 10px; margin-top: 30px; padding-left: 0px;">
			
				<div style="background-color: #16A085; text-align: center; margin: 0px; width:150px; color: white; float: left; border-radius: 5px; ">
					<h4>Phone:</h4>
				</div>
				
				<div style="padding: 4px; margin-left: 4px;float: left; border-radius: 5px;">';
					if (isset($dat['PHONE']))
					{
						$x = $dat['PHONE'];
					}
					else
					{
						$x = "N.A";
					}
					echo '<h5>'.$x.'</h5>
				</div>
				
			</div>
			
			<div class="container-fluid" style="background-color: #ECF0F1; border-radius: 10px; margin-top: 30px; padding-left: 0px;">
			
				<div style="background-color: #16A085; text-align: center; margin: 0px; color: white; width: 150px; float: left; border-radius: 5px; ">
					<h4>Gender:</h4>
				</div>
				
				<div style="padding: 4px; margin-left: 4px;float: left; border-radius: 5px;">';
					$gender = "";
					if (!isset($dat['GENDER']))
					{
						$gender = "N.A";
					}
					elseif ($dat['GENDER'] == 0)
					{
						$gender = "MALE";
					}
					else
					{
						$gender = "FEMALE";
					}
					echo '<h5>'.$gender.'</h5>
				</div>
				
			</div>
			
		</div>';
?>