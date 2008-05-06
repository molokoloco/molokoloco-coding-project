<?  include_once("../menu/menu.php"); ?><?
include_once("data.php");

$id = '1';//intval($_GET['id']);
$action = $_GET['action'];
$mode = $_GET['mode'];

switch ($mode) {
	case 'fiche':
	$A = new FICHE($R,$R_data,$id);
	$A->createFICHE();
	break;
	
	case 'bdd':
	$A = new BDD($R,$R_data,$action,$id);
	$A->createBDD();
	break;
	
	default : //'liste'
	/*$A = new LISTE($R,$R_data,$id);
	$A->miseenavant = 'email';
	$A->createLISTE();*/
	$A = new FICHE($R,$R_data,$id);
	$A->createFICHE();
	break;
}
?><? include_once("../menu/menu_bas.php"); ?>