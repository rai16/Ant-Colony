<?php
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
		<div class="list-group" style="width: 300px; margin-left: 70px;">
		<a href="#" class="list-group-item" style="margin-top: 60%;">Global User List</a>
			  </div>
			
		</div>
		
		<div class="col-md-8" style="margin-top: 70px;">
				<h2 style="text-align: center;color: red">Global User List</h2>
				<input id = "searchstr" type="text" name="value" style="width: 250px; margin-left: 71.5%;" placeholder="Search a friend" onkeyup="searchfriends()">
				<br><br>
				 <div class="list-group" id = "friendslist">
<?php
			$handle = oci_parse($connec,"select count(id) as fcount from profile where fpublic = 1 group by fpublic");
			oci_execute($handle);
			$ans = oci_fetch_array($handle,OCI_BOTH);
			$handle = oci_parse($connec,"select id from profile where fpublic = 1 ");
			oci_execute($handle);
			for ($cntr = 0;$cntr<$ans['FCOUNT'];$cntr++)
			{
					$ret = oci_fetch_array($handle,OCI_BOTH);
					$ret1 = dataid($connec,$ret['ID']);
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
		</script>
  </body>
</html>