<?php
	$handle = oci_parse($connec,"select * from profile where id = '".$_REQUEST['to']."'");
	oci_execute($handle);
	$dat = oci_fetch_array($handle,OCI_BOTH);
	if ($dat == false)
	{
		header("Refresh:0;url='./index.php'");
		exit();
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
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
  tinymce.init(
	{ selector:'textarea', height: 300,width: 700,plugins: [
		'advlist autolink lists link image preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media contextmenu paste code',
		'textcolor'
	],toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image forecolor backcolor',content_css: [
		'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
		'//www.tinymce.com/css/codepen.min.css'
	],media_live_embeds: true
  });
  </script>
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
		font-size: 2.2em;
		font-weight: bold;
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
	}
	h5{
		color: #16A085;
		font-size: 2em;
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
		margin-left: 15px;
	}
	h6{
		color: #16A085;
		font-size: 1.2em;
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
		margin-left: 15px;
	}
	p{
		color: black;
		font-size: 1em;
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
					<li><a href="./index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="#" onclick="logmeout()"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
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
			echo '<p style="text-align:center; color:red">'.$dat['FNAME'].' '.$dat['LNAME'].'</p>';
			echo '<button type="button" class="btn btn-success btn-lg" id="to_profile_btn" style="margin-left: 70px; margin-bottom: 30px; margin-left:33%;" onclick="toprofile(\''.$dat['ID'].'\')">Back To Profile</button>';
			echo '<div class="list-group" style="width: 300px; margin-left: 70px;">';
			  $about = "./profile.php?user=".$_COOKIE['antcolonylogin'];
				echo '<a href="'.$about.'" class="list-group-item">About</a>';
			?>
			<?php
				$handle = oci_parse($connec,"select count(sid) as icount from unread where rid = '".$_COOKIE['antcolonylogin']."' group by rid");
				oci_execute($handle);
				$ret1 = oci_fetch_array($handle,OCI_BOTH);
				if ($ret1 == false)
				{
					echo '<a href="messages.php" class="list-group-item">Messages</a>';
				}
				else
				{
					echo '<a href="messages.php" class="list-group-item"><span class="badge">'.$ret1['ICOUNT'].'</span>Messages</a>';
				}
			?>
				<a href="posts.php" class="list-group-item"><span class="badge">3</span>Posts</a>
			<?php
				$handle = oci_parse($connec,"select count(fid) as fcount from friends where id ='".$_COOKIE['antcolonylogin']."' group by id");
				oci_execute($handle);
				$ans = oci_fetch_array($handle,OCI_BOTH);
				echo '<a href="./friends.php" class="list-group-item"><span class="badge">'.$ans['FCOUNT'].'</span>Friends</a>';
			?>
			<a href="./globaluserlist.php" class="list-group-item">Global User List</a>
			  </div>
			
		</div>
		
		<div class="col-md-8" style="margin-top: 70px;">
		<div class="container-fluid">
			<ul class="nav navbar-inverse navbar-right">
					<?php echo'<li ><a href="./sentmessages.php?to='.$dat['ID'].'" style="background-color:white"><span class="glyphicon glyphicon-open"></span> Sent Mail</a></li>'; ?>
					</ul>
					<ul class="nav navbar-inverse navbar-right">
					<?php echo'<li><a href="./inboxuser.php?to='.$dat['ID'].'" style="background-color:white"><span class="glyphicon glyphicon-inbox"></span> Inbox</a></li>'; ?>
				</ul>
			<ul class="nav navbar-inverse navbar-right">
				<?php echo'<li><a href="./compose.php?to='.$dat['ID'].'" style="background-color:white"><span class="glyphicon glyphicon-pencil" ></span>Compose</a></li>'; ?>
			</ul>
			</div>
			<br>
			<textarea id="composeme"></textarea>
			<p style="color:green; margin-left:40px; visibility:hidden" id="sentok"><b>Sent Message</b></p>
			<?php echo '<button class="btn btn-success btn-lg" onclick="sendto(\''.$dat['ID'].'\')">Send Message</button>'; ?>
		</div>