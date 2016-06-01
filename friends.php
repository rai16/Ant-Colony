<?php
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
	function dataid($connec,$str)
	{
		$handle = oci_parse($connec,"select * from profile where id = '".$str."'");
		oci_execute($handle);
		$dat = oci_fetch_array($handle,OCI_BOTH);
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
		$AnswerData['DP'] = $x;
		if (isset($dat['CITY']))
		{
			$xc = $dat['CITY'];
		}
		else
		{
			$xc = "N.A";
		}
		if (isset($dat['COUNTRY']))
		{
			$yc = $dat['COUNTRY'];
		}
		else
		{
			$yc = "N.A";
		}
		$AnswerData['FNAME'] = $dat['FNAME'];
		$AnswerData['LNAME'] = $dat['LNAME'];
		$AnswerData['CITY'] = $xc;
		$AnswerData['COUNTRY'] = $yc;
		$AnswerData['PURL'] = "./profile.php?user=".$str;
		return $AnswerData;
	}
	if (!docheck())
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
	$handle = oci_parse($connec,"select * from profile where id = '".$_COOKIE['antcolonylogin']."'");
	oci_execute($handle);
	$dat = oci_fetch_array($handle,OCI_BOTH);
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
			echo '<button type="button" class="btn btn-success btn-lg" id="edit_profile_btn" style="margin-left: 70px; margin-bottom: 30px; margin-left:37%;" onclick="editme()">Edit Profile</button>';
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
				<a href="posts.php" class="list-group-item">Posts</a>
<?php
				$handle = oci_parse($connec,"select count(fid) as fcount from friends where id ='".$dat['ID']."' group by id");
				oci_execute($handle);
				$ans = oci_fetch_array($handle,OCI_BOTH);
				echo '<a href="#" class="list-group-item active"><span class="badge">'.$ans['FCOUNT'].'</span>Friends</a>';
?>
<a href="./globaluserlist.php" class="list-group-item">Global User List</a>
			  </div>
			
		</div>
		
		<div class="col-md-8" style="margin-top: 70px;">
				<h2 style="text-align: center;color: red">Friends List</h2>
				<input id = "searchstr" type="text" name="value" style="width: 250px; margin-left: 71.5%;" placeholder="Search a friend" onkeyup="searchfriends()">
				<br><br>
				 <div class="list-group" id = "friendslist">
<?php
			$handle = oci_parse($connec,"select fid from friends where id ='".$dat['ID']."'");
			oci_execute($handle);
			for ($cntr = 0;$cntr<$ans['FCOUNT'];$cntr++)
			{
					$ret = oci_fetch_array($handle,OCI_BOTH);
					$ret1 = dataid($connec,$ret['FID']);
					echo '<span id="fid'.$cntr.'"><a href="'.$ret1['PURL'].'" class="list-group-item">';
					echo '<img src="'.$ret1['DP'].'" class="img-rounded"  width="55" height="55" style="float: left;">';
					echo '<h4 class="list-group-item-heading flist">'.$ret1['FNAME'].' '.$ret1['LNAME'].'</h4>';
					echo '<p class="list-group-item-text">'.$ret1['CITY'].' '.$ret1['COUNTRY'].'</p>
					</a><br></span>';
			}
?>
				</div>
				<?php echo '<div id = "hiddenme" style="visibility: hidden">'.$ans['FCOUNT'].'</div>'; ?>
		</div>
		<script>
		function logmeout()
		{
			document.cookie = "antcolonylogin=; expires="+(Date().getMillisceonds-3600);
			window.location = "http://localhost";
		}
		function searchfriends()
		{
			var str = document.getElementById("searchstr").value;
			str = str.toLowerCase();
			var flag = 0;
			if (str=="")
			{
				flag = 1;
			}
			var cntr;
			var endcntr = document.getElementById("hiddenme").innerHTML;
			for (cntr =0;cntr<endcntr;cntr++)
			{
				if (flag == 0)
				{
					var flista = document.getElementsByClassName("flist");
					var ans = flista[cntr].innerHTML.toLowerCase().search(str);
					if (ans==-1)
					{
						var tobehidden = "#fid"+cntr;
						$(tobehidden).hide();
					}
					else
					{
						var tobeshown = "#fid"+cntr;
						$(tobeshown).show();
					}
				}
				else
				{
					var tobeshown = "#fid"+cntr;
					$(tobeshown).show();
				}
			}
		}
		function editme()
		{
			window.location = "./edit_profile.php";
		}
		</script>
  </body>
</html>