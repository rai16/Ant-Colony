<?php
	require $_SERVER['DOCUMENT_ROOT'].'/scarlet/argon.php';
	public class CLogin
	{
		protected $status = 0;
		private $username;
		private $password;
		function __construct($user,$pass)
		{
			$this->username = $user;
			$this->password = $pass;
		}
		function login()
		{
			$dbcon = new database();
			if ($dbcon==false)
			{
				return false;
			}
			$ans = $dbcon.query("Select key from users where id = '".$this->username."'");
			if ($ans==false