<?php
// This should be modifed as your own use warrants.

require_once('../simplepie.php');
SimplePie_Misc::display_cached_file($_GET['i'], './cache', 'spi');
?>
