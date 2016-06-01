<?php
	$flag = 0;
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
	$ret = docheck();
	if ($ret == true)
	{
		$flag = 1;
	}
	if ($flag==1)
	{
		require $_SERVER['DOCUMENT_ROOT'].'/globaluserlist_log.php';
	}
	else
	{
		require $_SERVER['DOCUMENT_ROOT'].'/globaluserlist_notlog.php';
	}
?>