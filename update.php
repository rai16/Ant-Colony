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
	if (docheck()==false)
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
	if (isset($_POST['fname'])&&isset($_POST['lname'])&&isset($_POST['dob'])&&isset($_POST['city'])&&isset($_POST['country'])&&isset($_POST['gender'])&&isset($_POST['fpublic']))
	{
		if ($_POST['gender']=="male")
		{
			$x = 0;
		}
		else
		{
			$x = 1;
		}
		if ($_POST['fpublic']=="public")
		{
			$y = 1;
		}
		else
		{
			$y = 0;
		}
		$handle = oci_parse($connec,"update profile set phone = '".$_POST['phone']."',fname = '".$_POST['fname']."',lname = '".$_POST['lname']."',dob = to_date('".$_POST['dob']."','dd/mm/yyyy'),city='".$_POST['city']."',country ='".$_POST['country']."',gender =".$x.",fpublic = ".$y." where id = '".$_COOKIE['antcolonylogin']."'");
		$ret = oci_execute($handle);
		if ($ret==true)
		{
			echo "YES";
			exit();
		}
		else
		{
			echo "NO";
			exit();
		}
	}
	else
	{
		echo "NO";
	}
?>