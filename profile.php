<?php
	session_set_cookie_params(3600);
	session_start();
	$connec = oci_connect('antcolony','whatidodefinesme','localhost');
	if (!$connec) 
	{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$case = -1;
	if (!isset($_REQUEST['user']))
	{
		header("Refresh:0;url='./index.php'");
		exit();
	}
	else if (!isset($_COOKIE['antcolonylogin']))
	{
		$handle = oci_parse($connec,"select fpublic from profile where id ='".$_REQUEST['user']."'");
		oci_execute($handle);
		$ans = oci_fetch_array($handle,OCI_BOTH);
		if ($ans!=false)
		{
			if ($ans[0]==0)
			{
				header("Refresh:0;url='./index.php'");
				exit();
			}
			else
			{
				$case = 1;
			}
		}
		else
		{
			header("Refresh:0;url='./index.php'");
			exit();
		}
	}
	else if (isset($_SESSION[$_COOKIE['antcolonylogin']])&&$_SESSION[$_COOKIE['antcolonylogin']]==1)
	{
		if ($_REQUEST['user']==$_COOKIE['antcolonylogin'])
		{
			$case = 2;
		}
		else
		{
			$case = 3;
		}
	}
	else
	{
		session_unset();
		setcookie('antcolonylogin',"",time()-100);
		header("Refresh:0");
	}
	if ($case == 1)
	{
		require $_SERVER['DOCUMENT_ROOT'].'/publicprofile.php';
	}
	else if ($case == 2)
	{
		require $_SERVER['DOCUMENT_ROOT'].'/actualprofile.php';
	}
	else if ($case == 3)
	{
		require $_SERVER['DOCUMENT_ROOT'].'/peopleprofile.php';
	}
?>
	<!-- Javascript starts here -->
	<script>
		if ($("#follow_btn"))
		{
			$("#follow_btn").click(function(){
				//window.alert("Fuck");
				if($("#follow_btn").val()=="follow")
				{
					var x = document.getElementById("hiddenme").innerHTML;
					$.post("friends_process.php",{
						user : x,
						flag : 1
					},function(data,status){
					if (status=="success")
					{
						if (data=="YES")
						{
							$("#follow_btn").val("unfollow");
							$("#follow_btn").attr("class","btn btn-danger btn-lg");
							$("#follow_btn").html("Un-follow");
						}
					}
					});
				}
				else
				{
					var x = document.getElementById("hiddenme").innerHTML;
					$.post("friends_process.php",{
						user : x,
						flag : 0
					},function(data,status){
					if (status=="success")
					{
						if (data=="YES")
						{
							$("#follow_btn").val("follow");
							$("#follow_btn").attr("class","btn btn-success btn-lg");
							$("#follow_btn").html("Follow");
						}
					}
					});
				}
			});
		}
		function logmeout()
		{
			document.cookie = "antcolonylogin=; expires="+(Date().getMillisceonds-3600);
			window.location = "http://localhost";
		}
		function editme()
		{
			window.location = "./edit_profile.php";
		}
		function sendme(str)
		{
			window.location = "./compose.php?to="+str;
		}
	</script>	
  </body>
</html>