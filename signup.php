<?php
	require $_SERVER['DOCUMENT_ROOT'].'/scarlet/argon.php';
	class_alias("PasswordStorage","PST");
	$connec = oci_connect('antcolony','whatidodefinesme','localhost');
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
	function vhashgen($email)
	{
		global $connec;
		$vhash = getToken(32);
		$handle = oci_parse($connec,"insert into verification(email,vhash) values('".$email."','".$vhash."')");
		oci_execute($handle);
	}
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

	if(isset($_COOKIE['antcolonylogin']))
	{
		echo "Invalid request1";
	}
	else if (isset($_POST['lname'])&&isset($_POST['fname'])&&isset($_POST['id'])&&isset($_POST['pwd'])&&isset($_POST['email']))
	{
		$passhash = PST::create_hash($_POST['pwd']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$insertstr1 = "insert into users(id,key,email,confirmed,ip) values('".$_POST['id']."','".$passhash."','".$_POST['email']."',0,'".$ip."')";
		$handle = oci_parse($connec,$insertstr1);
		if (!oci_execute($handle))
		{
			echo "Invalid Request2";
		}
		else
		{
			$insertstr2 = "insert into profile(id,fname,lname,fpublic) values('".$_POST['id']."','".$_POST['fname']."','".$_POST['lname']."',1)";
			$handle = oci_parse($connec,$insertstr2);
			if (!oci_execute($handle))
			{
				echo "Invalid request3";
			}
			else
			{
				vhashgen($_POST['email']);
				verification($_POST['email'],vhashfetch($_POST['email']));
				echo "YES";
			}
		}
	}
	else
	{
		echo "Invalid Request4";
	}
?>