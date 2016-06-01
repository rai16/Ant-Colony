<?php
    session_set_cookie_params(3600);
	session_start();
	$connec = oci_connect('antcolony','whatidodefinesme','localhost');
	$print1 = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ANTColony</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
  </head>
  
  <style>
  
	#header_name
	{
		font-family: Avant Garde,Avantgarde,Century Gothic,CenturyGothic,AppleGothic,sans-serif;
		color: #eeffff;
		margin-left: 80px;
		margin-top: 40px;
		font-size: 3.7em;
	}
	
	html { 
	background: url("login_background.jpg") no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	}
	
	#login_div
	{
		clear: left;
		height: 330px;
		background-color: white;
		border-radius: 20px;
		border: 5px solid grey;
		background-color: #283A44;
	}
	
  </style>';
?>
<script>
function attacher()
{
	var m1 = document.getElementById("username");
	var m2 = document.getElementById("password");
	m1.addEventListener('keypress', function (e) {
    var key = e.which || e.keyCode;
    if (key === 13) {
		loginme();
	}
	});
	m2.addEventListener('keypress', function (e) {
    var key = e.which || e.keyCode;
    if (key === 13) {
		loginme();
	}
	});
}
</script>
<body>
<?php
	$print = '<div class="col-sm-5">
			<img src="anticon.png" class="img-circle" alt="Cinque Terre" width="100" height="100" style="float: left; margin: 30px;">
			<h1 id="header_name"> <span style="font-size: 1.3em;"><b>ANT</b></span>Colony</h1>
		</div>
		
		
		<div class="container col-md-offset-4 col-md-4" id="login_div">
			
					<h2 style="margin-left: 160px; color: white;">Login</h2>
					
					 <form role="form">
						<div class="form-group">
							<label for="username" style="color: white;">Username:</label>
							<input type="username" class="form-control" id="username">
						</div>
						<div class="form-group" id="parentpassword">
							<label for="pwd" style="color: white;">Password:</label>
							<input type="password" class="form-control" id="password">
						</div>
						<div class="checkbox">
							<label style="color: white;"><input type="checkbox">Keep me logged in</label>
						</div>
					</form>
					<button class="btn btn-success btn-lg" onclick="loginme()">Submit</button>
		</div>
		<div class="container col-md-offset-4 col-md-4">
			<h3 style="color: white; float: left; margin-top: 36px;"> Don\'t have an account yet? </h3>
			<button type="button" class="btn btn-primary btn-lg"  data-toggle="modal" data-target="#registerModal"  style="margin-top: 23px; margin-left: 15px;">Register</button>
		
			<div id="registerModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

    <!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							 <img src="anticon.png" class="img-circle" width="80" height="80px" style="float: left;">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title" style="float: left; margin: 19px;">Register</h2>
						</div>
						
						<div class="modal-body" id="parentremform">
							
							<form class="form-horizontal" role="form" id="remform">
							
								<div class="form-group">
									<label class="control-label col-sm-3" for="fname">First Name:</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="fname" placeholder="Enter first name">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-sm-3" for="lname">Last Name:</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="lname" placeholder="Enter last name">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-sm-3" for="email">Email:</label>
									<div class="col-sm-9" id="parentemailcheck">
										<input type="email" class="form-control" id="email" placeholder="Enter a valid email id" onkeyup="emailchecktobedone()" onchange="emailchecktobedone()">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-sm-3" for="uname">Username:</label>
									<div class="col-sm-9" id="parentusercheck">
										<input type="text" class="form-control" id="uname" placeholder="Enter a username" onkeyup="actiontobedone()">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-sm-3" for="pass">Password:</label>
									<div class="col-sm-9">
										<input type="password" class="form-control" id="pass" placeholder="Enter your password">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-sm-3" for="cpass">Confirm Password:</label>
									<div class="col-sm-9">
										<input type="password" class="form-control" id="cpass" placeholder="Retype your password">
									</div>
								</div>
							</form>
							
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-primary btn-large" id="signup" onclick="allok()">Sign up</button>
							<button type="button" class="btn btn-default btn-large" data-dismiss="modal">Close</button>
						</div>
					</div>

				</div>
			</div>

		
		</div>';
	if (isset($_COOKIE['antcolonylogin']))
	{
		if (isset($_SESSION[$_COOKIE['antcolonylogin']])&&$_SESSION[$_COOKIE['antcolonylogin']]==1)
		{
			header("Refresh:0;url='./profile.php?user=".$_COOKIE['antcolonylogin']);
		}
		else	
		{
			session_unset();
			setcookie('antcolonylogin',"",time()-100);
			echo $print1;
			echo $print;
		}
	}
	else
	{
		echo $print1;
		echo $print;
	}
?>
	<!-- Javascript starts here -->
<script>
		var cfname=0, clname=0, cemail=0, cuname=0, cpass=0, ccpass=0;
		 // SET uname=1 only if the username is valid
		 var flage = false;
		 var flagem = false;
		function emailchecktobedone()
		{
			if ($("#email").val()!="")
			{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if (xhttp.readyState == 4 && xhttp.status == 200)
				{
					var res = xhttp.responseText;
					if (res == "yes"&&isEmail(document.getElementById("email").value))
					{
						var par = document.getElementById("email");
						$(par).css("border","2px solid green");
						cemail = 1;
						if (document.getElementById("enexist"))
						{
							var noderem = document.getElementById("enexist");
							noderem.parentNode.removeChild(noderem);
						}
						flagem = true;
					}
					else if (res == "yes" &&!isEmail(document.getElementById("email").value))
					{
						if (document.getElementById("enexist"))
						{
							var noderem = document.getElementById("enexist");
							noderem.parentNode.removeChild(noderem);
						}
						var par = document.getElementById("email");
						$(par).css("border","");
						flagem = false;
					}
					else
					{
						if (!document.getElementById("enexist"))
						{
							var exists = document.createElement("p");
							$(exists).attr("id","enexist");
							$(exists).html("<b>Only one account can be associated with one email address.</b>");
							$(exists).attr("style","color:red");
							var par = document.getElementById("parentemailcheck");
							$(par).append(exists);
							var m = $("#email");
							m.css("border","2px solid red");
						}
						flagem = false;
					}
				}
			};
			var emailcheck = document.getElementById("email").value;
			xhttp.open("POST","./emailexistajax.php",true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("emailcheck="+emailcheck);
			}
		}
		function actiontobedone()
		{
			if ($("#uname").val()!="")
			{
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					if (xhttp.readyState == 4 && xhttp.status == 200)
					{
						var res = xhttp.responseText;
						if (res == "yes")
						{
							if (!document.getElementById("uexist"))
							{
								var exists = document.createElement("p");
								$(exists).attr("id","uexist");
								$(exists).html("<b>This username is available</b>");
								$(exists).attr("style","color:green");
								var par = document.getElementById("parentusercheck");
								$(par).append(exists);
								var m = $("#uname");
								m.css("border","2px solid green");
							}
							if (document.getElementById("unexist"))
							{
								var noderem = document.getElementById("unexist");
								noderem.parentNode.removeChild(noderem);
							}
							flage = true;
						}
						else
						{
							if (!document.getElementById("unexist"))
							{
								var exists = document.createElement("p");
								$(exists).attr("id","unexist");
								$(exists).html("<b>This username is <em>NOT</em> available</b>");
								$(exists).attr("style","color:red");
								var par = document.getElementById("parentusercheck");
								$(par).append(exists);
								var m = $("#uname");
								m.css("border","2px solid red");
							}
							if (document.getElementById("uexist"))
							{
								var noderem = document.getElementById("uexist");
								noderem.parentNode.removeChild(noderem);
							}
							flage = false;
						}
					}	
				};
				var idcheck = document.getElementById("uname").value;
				xhttp.open("POST","./existajax.php",true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("idcheck="+idcheck);
			}
			else
			{
				var m = $("#uname");
				m.css("border","");
				if (document.getElementById("unexist"))
				{
					var noderem = document.getElementById("unexist");
					noderem.parentNode.removeChild(noderem);
				}
				if (document.getElementById("uexist"))
				{
					var noderem = document.getElementById("uexist");
					noderem.parentNode.removeChild(noderem);
				}
			}
		}
		$("#fname").blur(function(){
	
			var fname=  $("#fname").val();
			if(!isLetter(fname) && fname!=""){
		
				$("#fname").css("border","2px solid red");
				$("#fname").val("");
				cfname = 0;
			}
			else if(isLetter(fname) && fname!=""){
				$("#fname").css("border","2px solid green");
				cfname=1;
			}
		});
		
		$("#lname").blur(function(){
	
			var lname=  $("#lname").val();
			if(!isLetter(lname) && lname!=""){
		
				$("#lname").css("border","2px solid red");
				$("#lname").val("");
				clname = 0;
			}
			else if(isLetter(lname) && lname!=""){
				$("#lname").css("border","2px solid green");
				clname =1;
			}
		});
		$("#email").blur(function(){
	
			var email=  $("#email").val();
			if(!isEmail(email) && email!=""){
		
				$("#email").css("border","2px solid red");
				$("#email").val("");
				cemail = 0;
			}
			else if(isEmail(email) && email!=""&&echeckme()){
				$("#email").css("border","2px solid green");
				cemail=1;
			}
		});	
		$("#pass").blur(function(){
	
			var pass= $("#pass").val();
			if(pass.length<6 && pass!=""&&!isPass(pass))
			{
				$("#pass").css("border","2px solid red");
				$("#pass").val("");
				cpass = 0;
			}
			else if(pass!=""&&isPass(pass))
			{
				$("#pass").css("border","2px solid green");
				cpass=1;
			}
		});
	    $("#cpass").blur(function(){
	
			var pass= $("#cpass").val();
			var conf_pass= $("#pass").val();
			if(pass!=conf_pass && conf_pass!="")
			{
				$("#cpass").css("border","2px solid red");
				$("#cpass").val("");
				ccpass=1;
			}
			else if(conf_pass!="")
			{
				$("#cpass").css("border","2px solid green");
				ccpass=1;
			}
		});
		$("#uname").blur(function(){
			var ans;
			ans = $("#uname").val();
			if (isuname(ans)&&ans!=""&&checkme())
			{
				$("#uname").css("border","2px solid green");
				cuname = 1;
			}
			else if (!checkme()&&isuname(ans)&&ans!="")
			{
				cuname = 0;
			}
			else
			{
				cuname = 0;
				var m = $("#uname");
				m.val("Alphanumeric combination only");
				m.css("color","gray");
				m.focus(function(){
					if (m.val()=="Alphanumeric combination only")
					{
						m.val("");
						m.css("color","");
						m.css("border","");
						if (document.getElementById("uexist"))
						{
							var remch = document.getElementById("uexist");
							remch.parentNode.removeChild(remch);
						}
						else if (document.getElementById("unexist"))
						{
							var remch = document.getElementById("unexist");
							remch.parentNode.removeChild(remch);
						}
					}
				});
				m.css("border","2px solid red");
			}
		});
		function checkme()
		{
			return flage;
		}
		function echeckme()
		{
			return flagem;
		}
		function isuname(ans) {
			var regex = /^[A-Za-z0-9]+$/;
			return regex.test(ans);
		}
		function isEmail(email) {
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test(email);				 
		}	
		function isLetter(inputtxt)  
		{  
			var letters = /^[A-Za-z]+$/;
			return letters.test(inputtxt);
	    }
		function isPass(pass)
		{
			var regex = /^[A-Za-z0-9!@#$%^*]+$/;
			return regex.test(pass);
		}
		function allok()
		{
			//window.alert(cfname+" "+clname+" "+cemail+" "+cuname+" "+cpass+" "+ccpass);
			if(cfname == 1 && clname == 1 && cemail == 1 && cuname == 1 && cpass == 1 && ccpass == 1)
			{
				//window.alert("Fuck all");
				var fname = document.getElementById("fname").value;
				var lname = document.getElementById("lname").value;
				var email = document.getElementById("email").value;
				var id = document.getElementById("uname").value;
				var pass = document.getElementById("pass").value;
				var fsubmit = document.getElementById("signup");
				$.post("signup.php",{
					fname : fname,
					lname : lname,
					email : email,
					id : id,
					pwd : pass
				},function(data,status){
					if (status=="success")
					{
						if (data=="YES")
						{
							var m = document.getElementById("remform");
							m.parentNode.removeChild(m);
							var t = document.createElement("p");
							t.innerHTML = "Verify your email";
							var par = document.getElementById("parentremform");
							$(par).append(t);
							$("#signup").hide();
						}
					}
				});
			}
			else
			{
				window.alert("Fill all values correctly");
			}
		}
			
</script>    
<script>
$(document).ready(function(){
	attacher();
});
</script>
<script id='prev'>
function loginme()
{
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function()
	{
		if (xhttp.readyState == 4 && xhttp.status == 200)
		{
			var resp = xhttp.responseText;
			if (resp=="Yes")
			{
				var did = document.getElementById("username").value;
				window.open("./profile.php?user="+did,"_self");
			}
	        else
			{	
				if (!document.getElementById("idpasswordwarning"))
				{
					var x = document.createElement("p");
					$(x).attr("style","color:#ffff00");
					$(x).attr("id","idpasswordwarning");
					if (resp!="ANV")
					{
						x.innerHTML = "<b>Wrong Username or Password</b>";
					}
					else
					{
						x.innerHTML = "<b>Account Not Verified.Verification Email sent again</b>";
					}
					var par = document.getElementById("parentpassword");
					$(par).append(x);
					par = document.getElementById("password");
					$(par).focus(function(){
						$(x).hide(1500);
					});
					var par1 = document.getElementById("username");
					$(par1).focus(function(){
						$(x).hide(1500);
					});
				}
				else
				{
					var shon = document.getElementById("idpasswordwarning");
					if (resp!="ANV")
					{
						shon.innerHTML = "<b>Wrong Username or Password</b>";
					}
					else
					{
						shon.innerHTML = "<b>Account Not Verified.Verification email sent once again</b>";
					}
					$(shon).show();
				}
			}
		}
	};
	var did = document.getElementById("username").value;
	var dpwd = document.getElementById("password").value;
	xhttp.open("POST","./loginajax.php",true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("username="+did+"&"+"password="+dpwd);
}
</script>
</body>
</html>