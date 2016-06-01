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
	if (!docheck())
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
	if (isset($_REQUEST['to']))
	{
		$flag = 1;
	}
	else
	{
		header("Refresh:0;url='./index.php'");
	}
	if ($flag==1)
	{
		require $_SERVER['DOCUMENT_ROOT'].'/compose_user.php';
	}
?>
	<!-- Javascript starts here -->
	<script>
		//$("html, body").animate({ scrollTop: $('#composeme').offset().top }, 3000);
		function logmeout()
		{
			document.cookie = "antcolonylogin=; expires="+(Date().getMillisceonds-3600);
			window.location = "http://localhost";
		}
		function toprofile(str)
		{
			window.location = "./profile.php?user="+str;
		}
		function sendto(str)
		{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
			if (xhttp.readyState == 4 && xhttp.status == 200)
			{
				var res = xhttp.responseText;
				if (res != "YES")
				{
					window.alert("Message sending Failed");
				}
				else
				{
					$("#sentok").attr("style","color:green; margin-left:40px;");
					$("#sentok").hide(2000);
				}
			}
			};
			var data = escape(tinyMCE.activeEditor.getContent());
			xhttp.open("POST","./messenger.php",true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("to="+str+"&data="+data);
		}
	</script>
  </body>
</html>