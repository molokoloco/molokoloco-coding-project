<?
$admin = $_SESSION[SITE_CONFIG]['ADMIN']; // Stock Admin login
	$_SESSION[SITE_CONFIG] = array(); // Reset All config
	
	$_SESSION[SITE_CONFIG]['ADMIN'] = $Admin; // Add Admin login

?>