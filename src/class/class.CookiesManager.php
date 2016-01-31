<?php
/*
Cookies Manager Class 1.1
http://codecanyon.net/item/cookies-manager-class/4498668
*/
class CookiesManager
{
	public function get_seconds($value, $type)
	{
		switch($type)
		{
			case 'minute':
				return ($value * 60);
			break;
			case 'minutes':
				return ($value * 60);
			break;
			case 'hour':
				return ($value * 60 * 60);
			break;
			case 'hours':
				return ($value * 60 * 60);
			break;
			case 'day':
				return ($value * 24 * 60 * 60);
			break;
			case 'days':
				return ($value * 24 * 60 * 60);
			break;
			case 'week':
				return ($value * 7 * 24 * 60 * 60);
			break;
			case 'weeks':
				return ($value * 7 * 24 * 60 * 60);
			break;
			case 'month':
				return ($value * 30 * 24 * 60 * 60);
			break;
			case 'months':
				return ($value * 30 * 24 * 60 * 60);
			break;
			case 'year':
				return ($value * 365 * 24 * 60 * 60);
			break;
			case 'years':
				return ($value * 365 * 24 * 60 * 60);
			break;
		}
	}

	public function set($name,$value,$time,$time_type)
	{
		setcookie($name, $value, time()+$this->get_seconds($time, $time_type));
	}

	public function count()
	{
		return count($_COOKIE);
	}

	public function destroy($name)
	{
		setcookie($name, 0, time()-3600);
	}

	public function destroy_all()
	{
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-3600);
				setcookie($name, '', time()-3600, '/');
			}
		}
	}
}
?>