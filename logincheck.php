<?php
	class logincheck 
	{
		private $id;
		public function check()
		{
			if (!isset($_COOKIE['antcolonylogin']))
			{
				return false;
			}
			else
			{
				if (!isset($_SESSION[$_COOKIE['antcolonylogin']]))
				{
					return false;
				}
				elseif ($_SESSION[$_COOKIE['antcolonylogin']]==1)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}