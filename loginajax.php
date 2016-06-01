<?php
	require $_SERVER['DOCUMENT_ROOT'].'/scarlet/argon.php';
	class_alias("PasswordStorage","pwds");
	session_set_cookie_params(3600);
	session_start();
	$_SESSION['login'] = 0;
	function verification($inp,$hash)
	{
		mail($inp,"Verify your account on AntColony","<html><body><p>Thank you for deciding to join our awesome social network</p><p style='color:#ff0000'><a href = 'http://localhost/verify.php?myidentity=".$hash."'><b>CLICK Here to verify now</b></a></p></body></html>","From: admin@localhost");
	}
	function vhashfetch($email)
	{
		global $connec;
		$handle = oci_parse($connec,"select vhash from verification where email ='".$email."'");
		oci_execute($handle);
		$ansar = oci_fetch_array($handle);
		return $ansar[0];
	}
    $connec = oci_connect('antcolony','whatidodefinesme','localhost');
	if (!$connec) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	else if (isset($_POST['username'])&&isset($_POST['password']))
	{
		$handle = oci_parse($connec,'Select key,confirmed,email from users where id = '."'".$_POST['username']."'");
		oci_execute($handle);
		$ans = oci_fetch_array($handle,OCI_BOTH);
		if ($ans!=false&&$ans['CONFIRMED']==0&&pwds::verify_password($_POST['password'],$ans[0]))
		{
			echo "ANV";
			verification($ans['EMAIL'],vhashfetch($ans['EMAIL']));
			exit();
		}
		elseif (pwds::verify_password($_POST['password'],$ans[0]))
		{
			$_SESSION['login'] = 1;
			$_SESSION[$_POST['username']] = 1;
			setcookie('antcolonylogin',$_POST['username'],time()+3600,'/',$_SERVER['HTTP_HOST']);
			echo 'Yes';
		}
		else
		{
			echo 'Login Failed</br>';
			exit();
		}
	}
	else
	{
		echo 'Login Failed</br>';
		exit();
	}
?>