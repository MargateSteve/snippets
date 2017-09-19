<?php
/** index.php
 *
 *  This is the only front end page for the site. Everything is done elsewhere
 *  and simply viewed through this file.
 *
 *  All of the configuration is called through ''../app/init.php', which in turn
 *  calls app/core/App.php and app/core/Controller.php to convert the url into the correct
 *  class, method and view
 *
 *  $userchecks is an all-in-one login status/process check
**/

#  TO DO
#  =====
#
#  Pass in multiple sites
#
#
#  =====
//  Call the initialisation file
error_reporting(E_ALL);
require_once '../app/init.php';


foreach ($_SESSION as $key => $value) {
	//unset($_SESSION[$key]);
}
// Create a new instance of the App.
$app = new App ('main');

// That is it............
 ?>
