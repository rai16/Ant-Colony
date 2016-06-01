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
	if ($_POST['flag'] == 1&&docheck())
	{
		$loggedone = $_COOKIE['antcolonylogin'];
		$friend = $_POST['user'];
		$handle = oci_parse($connec,"insert into friends(id,fid) values ('".$loggedone."','".$friend."')");
		$ret = oci_execute($handle);
		if ($ret == true)
		{
			echo "YES";
		}
		else
		{
			echo "NO";
		}
	}
	else if ($_POST['flag'] == 0 && docheck())
	{
		$loggedone = $_COOKIE['antcolonylogin'];
		$friend = $_POST['user'];
		$handle = oci_parse($connec,"delete from friends where id = '".$loggedone."' and fid = '".$friend."'");
		$ret = oci_execute($handle);
		if ($ret == true)
		{
			echo "YES";
		}
		else
		{
			echo "NO";
		}
	}
	else if (!docheck())
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
?>