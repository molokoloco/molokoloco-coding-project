<? 
// appel de la classe
    require_once('zip.lib.php');
    // nom du fichier � ajouter dans l'archive
    $filename = 'index.php';
    
    // contenu du fichier
    $fp = fopen ($filename, 'r');
    $content = fread($fp, filesize($filename));
    fclose ($fp);
    
    // cr�ation d'un objet 'zipfile'
    $zip = new zipfile();
    // ajout du fichier dans cet objet
    $zip->addfile($content, $filename);
    // production de l'archive' Zip
    $archive = $zip->file();
    
    // ent�tes HTTP
    header('Content-Type: application/x-zip');
    // force le t�l�chargement
    header('Content-Disposition: inline; filename=archive.zip');
    
    // envoi du fichier au navigateur
    echo $archive;

//die(phpinfo()); 
?>