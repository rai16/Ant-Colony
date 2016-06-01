<?php
	$connec = oci_connect("antcolony","whatidodefinesme","localhost");
	if (!$connec) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$handle = oci_parse($connec,"select email from verification where email='".$_POST['emailcheck']."'");
	oci_execute($handle);
	$ans = oci_fetch_array($handle,OCI_BOTH);
	if ($ans[0]==$_POST['emailcheck']&&$ans!=false)
	{
		echo "no";
	}
	else
	{
		echo "yes";
	}
?>