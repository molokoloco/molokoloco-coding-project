<? 
require_once("../lib/racine.php"); 

$action = gpc('action');

if (!empty($action)) {
	
	require_once '../lib/fonctions_backup.php';
	
	switch($action) {
	
		case 'sqlBackup' : // EXPORT SQL
			$chemin = $wwwRoot.'admin/lib/_sqlback';
			$nom_fichier = 'sql_'.date("Y-m-d_H-i-s").'.sql';
			if (!sqlBackup($chemin , $nom_fichier)) {
				$intitule = 'Erreur pendant le sauvegarde de la base SQL';
				break;
			}
			$intitule = 'Sauvegarde de la base SQL r&eacute;ussie';
			telecharger($chemin.'/'.$nom_fichier);
		break;
		
		case 'sqlImport' : // IMPORT SQL
			$fileSql = $_FILES['filesql']['tmp_name'];
			$fileSqlContent = getFileContent($fileSql);
			unlink($fileSql);
			if (empty($fileSqlContent)) alert('Le fichier .sql semble vide','','alert');
			
			// LOAD DATA INFILE
			$totalReq = exeSql($fileSqlContent);
			alert('La base de donnée a été entièrement mise à jour : '.$totalReq.' requêtes ont été exécutées','','alert');
		break;
		
		case 'siteBackupActif' : // EXPERIENCES ACTIVE : SWF + XML + FICHIERS
			
			/*makeBigZip(
				$wwwRoot,
				'site_'.date("Y-m-d_H-i-s").'.zip',
				array('_BAK_', '_WORK_', 'ftp', 'js', 'admin', 'stats', '_notes'),
				array('.htaccess'),
				array(
					//'/home/site/'=>'admin/lib/all.php'
				)
			);*/

		break;
		
		case 'siteBackup' :
			
		break;

	}

}


?>