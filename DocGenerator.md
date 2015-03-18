_Library edited from 2005 to 2007..._ Framework here : [trunk/SITE\_01\_SRC/admin/lib/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fadmin%2Flib) et ici [trunk/SITE\_01\_SRC/admin/lib/class/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fadmin%2Flib%2Fclass)

# Exporting article to the Words (.doc) format #

[./admin/lib/class/class\_words/clsMsDocGenerator.php](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/admin/lib/class/class_words/clsMsDocGenerator.php)

[./admin/lib/class/class\_zip/zip.lib.php](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/admin/lib/class/class_zip/zip.lib.php)

```
require('admin/lib/racine.php');

//header('Content-Type: text/html; charset=utf-8');
//mb_http_input("");
mb_http_output(($isUtf8 ? 'utf-8' : 'iso-8859-1')); // utf-8 ???

// ------------------------- GET VARS (Cf. HTACCESS) ----------------------------------//
$article_id = intval(gpc('article_id'));
if ($article_id < 1) d('D&eacute;sol&eacute; il manque la r&eacute;f&eacute;rence &agrave; l\'article (ID)');


// Fetch article ------------------------------------------------------ //
$A =& new Q("
	SELECT art.*, cprma.cat_id
	FROM mod_articles AS art, cms_pages_relation_mod_articles AS cprma
	WHERE art.id='{$article_id}' AND cprma.prod_id=art.id
	LIMIT 1
");
if (!isset($A->V[0]['id'])) d('D&eacute;sol&eacute; cet article n\'est pas accessible');
$A = $A->V[0];

/* MAKE WORD ///////////////////////////////////////////////////////////////// */
require_once('./admin/lib/class/class_words/clsMsDocGenerator.php');

$titleFormat = array(
	'text-align' 	=> 'left',
	'font-weight' 	=> 'bold',
	'font-size'		=> '18pt',
	'font-family'	=> 'Arial',
	'color'			=> 'red'
);
	
$doc = new clsMsDocGenerator();
$doc->setFontFamily('Verdana');
$doc->addParagraph('');
$doc->addParagraph($article_ariane, array('font-weight'=>'bold','font-family'=>'Arial'));
$doc->addParagraph(html(aff($A['titre'])), $titleFormat);
$doc->addParagraph('Par '.html(aff($J['prenom'].' '.$J['nom'])).' / '.relativeDate($A['datepub']), array('font-family'=>'Arial'));
$doc->addParagraph('');


if (!empty($A['image_1'])) {
	$img_name = $A['image_1'];
	$img_content = file_get_contents('./medias/articles/grand/'.$A['image_1']);
	$img_content_big = file_get_contents('./medias/articles/pop/'.$A['image_1']);
	
	$doc->addParagraph($doc->bufferImage($img_name,300,300), array('text-align'=> 'left'));
	//if (!empty($A['image_1'])) $doc->addImage($WWW.'medias/articles/grand/'.$A['image_1'], 300, 300 );
	if (!empty($A['image_leg_1'])) $doc->addParagraph('Visuel : '.html(aff($A['image_leg_1'])), array('font-family'=>'Arial'));
	$doc->addParagraph('');
}

$doc->addParagraph(quote(aff($A['chapeau'])), array('font-weight'=>'bold','font-family'=>'Arial'));

if (!empty($A['titre_inter'])) $doc->addParagraph(html(aff($A['titre_inter'])), array('font-weight'=>'bold','font-family'=>'Arial'));

$doc->addParagraph(quote(aff($A['texte_1'])), array('font-family'=>'Arial'));
$doc->addParagraph('');

$doc_name = cleanName(aff($A['titre'])).'.doc';
$doc_content = $doc->output($doc_name);


// MAKE ZIP ///////////////////////////////////////////////////////////////////
require_once('./admin/lib/class/class_zip/zip.lib.php');

set_time_limit(3600);
ini_set('memory_limit', 8000000);

$zip =& new zipfile();
$zip->addfile($doc_content, $doc_name);

if (!empty($A['image_1'])) {
	$zip->addfile($img_content, $img_name);
	$zip->addfile($img_content_big,  str_replace('.'.getExt($img_name), '',$img_name).'_big.'.getExt($img_name));
}
$archive = $zip->file();

$archive_nom = cleanName(aff($A['titre'])).'.zip';

header('Content-Disposition: attachment; filename="'.$archive_nom.'"');
header('Content-Type: application/x-zip');
if(navDetect() == 'msie') {
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
}
else header('Pragma: no-cache');
$maintenant = gmdate('D, d M Y H:i:s').' GMT';
header('Last-Modified: '.$maintenant);
header('Expires: '.$maintenant); 
header('Content-Length: '.strlen($archive));

die($archive);
```