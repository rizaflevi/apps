<?php
/**
 * logout.php
 *
 * Session destruction process
 *
 */
	include "sys_connect.php";
	if (!isset($_SESSION['gUSERCODE'])) {
		session_start();
	}

	include "sysfunction.php";
	$USERNAME = '';
	if (isset($_GET['id']))	{
		$USERNAME = $_GET['id'];
	} else {
		session_destroy(); // clean up session ID
		header("Location: web_index.php");
	}

	if ($USERNAME!='') {
		APP_LOG_ADD('logout');
		RecUpdate('TbUser', ['USER_LOGIN', 'LAST_LOGIN', 'UP_DATE'], [0, date("Y-m-d H:i:s"), $USERNAME], "USER_CODE='$USERNAME'");
	}
	
	$_SESSION = array(); // deregister all current session variables

  /**
   * Cookie destroy
   */
  $params = session_get_cookie_params();
  setcookie(session_name(), 0, 1, $params['path']);
  unset($params);
  /*if (isset($_COOKIE[session_name()])) // PHP Manual (session_destroy)
  {
    setcookie(session_name(), '', time() - 42000, '/');
  }*/

	session_destroy(); // clean up session ID

	header("Location: index.php");
?>
