<!DOCTYPE html>
<html>
<head>
<title>Verification Page</title>
</head>
<body>
<?php
	$connec = oci_connect('antcolony','whatidodefinesme','localhost');
	if (!$connec) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	if(isset($_COOKIE['antcolonylogin'])&&!isset($_REQUEST['myidentity']))
	{
		echo "<p style='color:#ff0000; font-size:30px;'>Invalid request.Redirecting to homepage.</p>";
	}
	else
	{
		$handle = oci_parse($connec,"select email from verification where vhash='".$_REQUEST['myidentity']."'");
		$exret = oci_execute($handle);
		$ans = oci_fetch_array($handle,OCI_NUM);
		if ($ans!=false)
		{
			$handle = oci_parse($connec,"update users set confirmed = 1 where email = '".$ans[0]."'");
			$exret = oci_execute($handle);
			if ($exret==true)
			{
				echo "<p style='color:#ff0000;font-size:30px;'>Your account is verified.Redirecting to homepage.</p>";
			}
			else
			{
				echo "<p style='color:#ff0000; font-size:30px;'>Verification Failed.Redirecting to homepage.</p>";
			}
		}
		else
		{
			echo "<p style='color:#ff0000; font-size:30px;'>Wrong Hash.Redirecting to homepage.</p>";
		}
	}
	header("Refresh:5;url='./index.php'");
?>
</body>
</html>