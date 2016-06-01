<?php
	$connec = oci_connect('antcolony','whatidodefinesme','localhost');
	if (!$connec) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$handle = oci_parse($connec,"select id from users where id ='".$_REQUEST['idcheck']."'");
	oci_execute($handle);
	$ans = oci_fetch_array($handle,OCI_BOTH);
	if ($ans[0]==$_REQUEST['idcheck']&&$ans!=false)
	{
		echo "no";
	}
	else
	{
		echo "yes";
	}
?>