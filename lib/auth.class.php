<?php
/**
 * Authentificate class
 */
 class auth
 {
 	var $user="";
 	var $pass="";
 	var $table="";

	function auth($work_table = 'cms_admins')
	{
	 	$this->table = $work_table;
	 	$_SESSION['auth_err'] = 0;
	 	if (isset($_REQUEST['action'])&& $_REQUEST['action'] == 'logout')
	 		$this->logout();
	 	else
	 	{
	 		!isset($_SESSION['auth'])?$_SESSION['auth'] = 'off':null;
		 	if (isset($_REQUEST['user'],$_REQUEST['passw']) && $_SESSION['auth'] != 'on')
		 	{
			 	$this->user = $_REQUEST['user'];
				$this->pass = $_REQUEST['passw'];
		 		$this->authentificate();
		 	}
	 	}
		return $this;
	}

	function authentificate()
	{
    
		global $db;
		$sql = "
			SELECT
				*
			FROM
				$this->table
			WHERE
				user = '$this->user'
			AND
				passw = '$this->pass'
		";
		$result = $db->Query( $sql );
		if ( $db->NumRows() == 1 )
		{
			$session_info = array();
			$session_info = $db->ArrayResult;

			$_SESSION['userid'] = $session_info[0]['id'];
			$_SESSION['user'] = $session_info[0]['user'];
			$_SESSION['username'] = $session_info[0]['name'];
			$_SESSION['passw'] = $session_info[0]['passw'];
			unset($session_info);
			$_SESSION['auth'] = 'on';

			return true;
		}else{
			$db->Close();
			$_SESSION['auth'] = 'off';
			$_SESSION['auth_err'] = 1;
			return false;
		}

	}

	function logout()
	{
		$_SESSION = array();
		$_SESSION['auth'] = 'off';
		session_unset();
		session_destroy();
		print "<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=index.php\">";
	}
 }
 ?>