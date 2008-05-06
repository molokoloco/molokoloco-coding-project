<?php

/*
* EXAMPLES
*
*		CMSItemDrawer::getItemH1( "", "Titre de niveau 1" );
*
*		pour dessiner un paragraphe important : 
*		CMSItemDrawer::getItemImportant( "", "Paragraphe important" ); 
*		
*		OU bien :
*		 
*		$drawer =& new CMSItemDrawer(); 
*		$drawer->getView( "h1", "", "Titre de niveau 1" );
*		$drawer->getView( "important", "", "Paragraphe important" );
*/



define( "FMK_CLS_PTH", "../../../../../Framework/ver_1_0/Classes/" );
require_once FMK_CLS_PTH."drawers/ClassCMSItemDrawer.php";

/**************************************************************
*                                                             *
*  Cration de l'objet permettant de construire les  lm nts  *
*                                                             *
***************************************************************/
$drawer =& new CMSItemDrawer();
$components = $drawer->getAllowedComponents();
$components["ALL"] = "TOUT";
$count = count( $components );

/**************************************************************
*                                                             *
*  Rcup ration du contexte demandant l'affichage d'un bloc   * 
*                                                             *
***************************************************************/
$cur_bloc = ( ( isset( $_GET["bloc"] ) && $_GET["bloc"] !== "" ) ? strval( $_GET["bloc"] ) : "" );
$element = ( $cur_bloc !== "" ? ( $cur_bloc == "ALL" ? "Tous les blocs !" : $components[$cur_bloc] ) : "" );
$bgcolor = "white";

/**************************************************************
*                                                             *
* Dfinition des param tres utiliss par les fonctions du CMS *
*                                                             *
***************************************************************/
//
// Texte simple
//
$default_text = "Je ne voudrais pas rentrer dans des choses trop dimensionnelles, mais laper u des nods fait que je suis mon  meilleur  (en euros : ) modle car il faut toute la splendeur du aware puisque the final conclusion of the spirit is perfection Il y a un an, je t'aurais parl  de mes muscles";
//
// Html simple
//
$default_html_nolist = '<p>Je ne voudrais pas rentrer dans des choses trop <strong>dimensionnelles</strong>, mais l&rsquo;aper&ccedil;u des no&oelig;ds &hellip; fait que je suis mon &laquo; meilleur &raquo; (en euros : &euro;) mod&egrave;le car il faut toute la splendeur du aware puisque the final conclusion of the spirit is perfection Il y a un an, je t\'aurais parl&eacute; de mes muscles</p>';
//
// Html avec puces
//
$default_html = '<p>Je ne voudrais pas rentrer dans des choses trop <strong>dimensionnelles</strong>, mais l&rsquo;aper&ccedil;u des no&oelig;ds &hellip; fait que je suis mon &laquo; meilleur &raquo; (en euros : &euro;) mod&egrave;le car il faut toute la splendeur du aware puisque the final conclusion of the spirit is perfection Il y a un an, je t\'aurais parl&eacute; de mes muscles</p>
<ul>
	<li>Je ne <strong>voudrais</strong> pas rentrer dans des <a class="cms_a_external" href="#">choses</a> trop <em>dimens</em></li>
	<li>Je ne <strong>voudrais</strong> pas rentrer dans des <a class="cms_a_internal" href="#">choses</a> trop <em>dimens</em></li>
	<li>Je ne <strong>voudrais</strong> pas rentrer dans des <a class="cms_a_download" href="#">choses</a> trop <em>dimens</em></li>
</ul>';
//
// Liste (puces / numros)
//
$default_item = "mais, je suis mon meilleur mod le car il faut toute la splendeur";
$default_items = array( $default_item, $default_item, $default_item );
//
// Glossaire
//
$glos_desc = "Mais, je suis mon meilleur modle car il faut toute la splendeur mais, je suis mon meilleur mod le car il faut toute la splendeur mais, je suis mon meilleur modle car il faut toute la splendeur";
$default_glossary = array( array( "title" => "Titre de la def", "description" => $glos_desc ),
						   array( "title" => "Titre de la def", "description" => $glos_desc ) );
//
// Images
//
$default_image = "./images/visu_p.gif";
$default_legend = "L gende de l'image";
$default_author = "Yann Arthus-Bertrand";
$default_href = './images/image.jpg';
//
// Code
//
$default_code = 'var SendDataToFlashMovie = function(movieName,data) { // To test
	var flashMovie = getFlashObject(movieName);
	flashMovie.SetVariable("/:"+data,document.controller);
}';
//
// Documents
//
$default_doc_label = "Document numro";
$default_doc = "./doc/user_guide.doc";
$default_docs = array(	array( "href" => "./doc/user_guide.doc", "title" => $default_doc_label." 1" ),
						array( "href" => "./doc/user_guide.doc", "title" => "" ),
						array( "href" => "./doc/user_guide.doc", "title" => '<img src="./images/puce.gif" />' ) );
//
// Liens (interne, externe)
//
$default_label_1 = "";
$default_link_1 = "http://www.boursorama.com/";
$default_label_2 = "D couvrez Le Monde";
$default_link_2 = "http://www.lemonde.fr/";
$default_label_3 = "Afficher une alerte Hello world !";
$default_link_3 = "javascript:alert('Hello world');";
$default_links = array( array( "href" => $default_link_1, "title" => $default_label_1 ),
						array( "href" => $default_link_2, "title" => $default_label_2 ),
						array( "href" => $default_link_3, "title" => $default_label_3 ) );
//
// Liens des focus
//
$default_url_link = array( "href" => $default_link_2, "title" => $default_label_2 );
$default_doc_link = array( "href" => $default_doc, "title" => "Document  t lcharger" );
// 
// Table
//
$default_table_heads = array( "Intitul  1", "Intitul 2", "Intitul  3" );
$default_table_rows =  array( array( "Valeur 1-1", "<strong>Valeur 1-2</strong>", "Valeur 1-3" ),
							  array( '<span style="color:red">Valeur 2-1</span>', "Valeur 2-2", "Valeur 2-3" ),
							  array( "Valeur 3-1", "Valeur 3-2", "<em>Valeur 3-3</em>" ) );
// 
// Focus
//
$default_focus_title = "Titre de l'article";



/**************************************************************
*                                                             *
*  Construction du menu haut permettant de tester les blocs   *
*                                                             *
***************************************************************/

$menu = "";
foreach( $components as $bloc => $item ) {
	$menu.= '<td style="padding:3px 3px 3px 4px;border-left:1px solid white;border-bottom:1px solid white" 
				 onmouseover="this.style.backgroundColor=\'#EEEEEE\'" onmouseout="this.style.backgroundColor=\'#CCCCCC\'">';
	if( strval( $bloc ) === $cur_bloc ) {
		$menu.= '<span style="color:red">'.$item.'</span>';
	} else {
		$menu.= '<a style="display:block" href="test_item_drawer.php?bloc='.$bloc.'">'.$item.'</a>';
	}
	$menu.= '</td>';
}

/**************************************************************
*                                                             *
* Construction du contenu de la page : aperu et code du bloc *
*                                                             *
***************************************************************/
if( $cur_bloc == "" ) 
{
	$preview = '<div style="padding:32px;font-weight:bolder;">';
	$preview.= "Cliquez sur un des blocs ci-dessus pour en afficher l'aper&ccedil;u";
	$preview.= '</div>';
} 
else 
{
	$html = "";
	switch( $cur_bloc ) 
	{
		case "0" :	case "ALL" :	/* br */
			$html.= $drawer->getItemBr( "" );
			if( $cur_bloc != "ALL" ) break;

		case "1" :	case "ALL" :	/* hr */
			$html.= $drawer->getItemHr( "" );
			if( $cur_bloc != "ALL" ) break;

		case "2" :	case "ALL" :	/* h1 */
			$html.= $drawer->getItemH1( "", "Titre de niveau 1" );
			if( $cur_bloc != "ALL" ) break;

		case "3" :	case "ALL" :	/* h2 */
			$html.= $drawer->getItemH2( "", "Titre de niveau 2" );
			if( $cur_bloc != "ALL" ) break;

		case "4" :	case "ALL" :	/* h3 */
			$html.= $drawer->getItemH3( "", "Titre de niveau 3" );
			if( $cur_bloc != "ALL" ) break;

		case "5" :	case "ALL" :	/* h4 */
			$html.= $drawer->getItemH4( "", "Titre de niveau 4" );
			if( $cur_bloc != "ALL" ) break;

		case "6" :	case "ALL" :	/* p */
			$html.= $drawer->getItemP( "", $default_text );
			if( $cur_bloc != "ALL" ) break;

		case "7" :	case "ALL" :	/* important */
			$html.= $drawer->getItemImportant(  "", $default_text );
			if( $cur_bloc != "ALL" ) break;

		case "8" :	case "ALL" :	/* note */
			$html.= $drawer->getItemNote( "", $default_text );
			if( $cur_bloc != "ALL" ) break;

		case "9" :	case "ALL" :	/* marquee */
			$html.= $drawer->getItemMarquee( "", $default_text, "left", "10", "10" );
			if( $cur_bloc != "ALL" ) break;

		case "10" :	case "ALL" :	/* code */
			$code = "<?php\n\t#\n";
			$code.= "\t# Remplir le journal des transactions\n";
			$code.= "\t#\n";
			$code.= "\t\$fp = fopen( \"./cache/logs.txt\", \"w\" );\n";
			$code.= "\tfputs( \$fp, \"Il est pass&eacute; par ici ;-)\" );\n";
			$code.= "\tfclose( \$fp );\n?>";
			$code.= "\t\n";
			ob_start();
			highlight_string( $code );
			$code = ob_get_contents();
			ob_end_clean();
			$html.= $drawer->getItemCode( "", $default_code );
			$html.= $drawer->getItemCode( "", $code );
			if( $cur_bloc != "ALL" ) break;

		case "11" :	case "ALL" :	/* ul */
			$html.= $drawer->getItemUl( "", $default_items );
			if( $cur_bloc != "ALL" ) break;

		case "12" :	case "ALL" :	/* ol */
			$html.= $drawer->getItemOl( "", $default_items );
			if( $cur_bloc != "ALL" ) break;
		
		case "13" :	case "ALL" :	/* dl */
			$html.= $drawer->getItemDl( "", $default_glossary );
			if( $cur_bloc != "ALL" ) break;

		case "14" :	case "ALL" :	/* citation */
			$html.= $drawer->getItemCitation( "cite", $default_text."\n".$default_text, "Henry Troyat" );
			$html.= $drawer->getItemCitation( "text_citeleft", $default_text, "Bernard Clavel", $default_html );
			$html.= $drawer->getItemCitation( "text_citeright", $default_text, "Victor Hugo", $default_html_nolist, $default_items );
			$html.= $drawer->getItemCitation( "text_citeleft", $default_text, "Saint Augustin", "", $default_items );
			$html.= $drawer->getItemCitation( "text_citeright", $default_text, "Jean-Christophe Grang ", $default_html_nolist, "" );
			if( $cur_bloc != "ALL" ) break;

		case "15" :	case "ALL" :	/* rte */
			$html.= $drawer->getItemRte( "", $default_html );
			if( $cur_bloc != "ALL" ) break;

		case "16" :	case "ALL" :	/* rte_image */
			$html.= $drawer->getItemRteImage( $type="text_imageleft", $default_html, $default_image, $default_legend, "" );
			$html.= $drawer->getItemRteImage( $type="text_imageright", $default_html, $default_image, "", $default_author );
			$html.= $drawer->getItemRteImage( $type="text_linkimageleft", $default_html_nolist, $default_image, $default_legend, $default_author, $default_href );
			$html.= $drawer->getItemRteImage( $type="text_linkimageright", $default_html_nolist, $default_image, "", "", $default_href );
			if( $cur_bloc != "ALL" ) break;

		case "17" :	case "ALL" :	/* image */
			$html.= $drawer->getItemImage( "image", $default_image, "", "" );
			$html.= $drawer->getItemImage( "image", $default_image, "", $default_author );
			$html.= $drawer->getItemImage( "linkimage", $default_image, $default_legend, "", $default_href );
			$html.= $drawer->getItemImage( "linkimage", $default_image, $default_legend, $default_author, $default_href );
			if( $cur_bloc != "ALL" ) break;

		case "18" :	case "ALL" :	/* table */
			$html.= $drawer->getItemTable( "", $default_table_rows, "", "" );
			$html.= $drawer->getItemTable( "", $default_table_rows, $default_table_heads, "Tableau avec titre et en-ttes" );
			if( $cur_bloc != "ALL" ) break;

		case "19" :	case "ALL" :	/* focus */
			$html.= $drawer->getItemFocus( "focus", $default_focus_title, $default_html, "", "", "", "", "", $default_doc_link );
			$html.= $drawer->getItemFocus( "focus_imageleft", $default_focus_title, $default_html_nolist, $default_image, $default_legend, $default_author );
			$html.= $drawer->getItemFocus( "focus_imageright", $default_focus_title, $default_html, $default_image, $default_legend, "" );
			$html.= $drawer->getItemFocus( "focus_linkimageleft", $default_focus_title, $default_html_nolist, $default_image, "", "", $default_href, $default_url_link, $default_doc_link );
			$html.= $drawer->getItemFocus( "focus_linkimageright", $default_focus_title, $default_html, $default_image, "", $default_author, $default_href, $default_url_link, $default_doc_link );
			if( $cur_bloc != "ALL" ) break;

		case "20" :	case "ALL" :	/* a_externe */
			$html.= $drawer->getItemAExterne( "", $default_link_1, "" );
			$html.= $drawer->getItemAExterne( "", $default_link_2, "D couvrez Le Monde" );
			$html.= $drawer->getItemAExterne( "", $default_link_3, "" );
			if( $cur_bloc != "ALL" ) break;

		case "21" :	case "ALL" :	/* a_interne */
			$html.= $drawer->getItemAInterne( "", "test_item_drawer.php?bloc=20", "Elment CMS pr cdent :-)" );
			$html.= $drawer->getItemAInterne( "", "test_item_drawer.php?bloc=22", "El ment CMS suivant :-)" );
			if( $cur_bloc != "ALL" ) break;

		case "22" :	case "ALL" :	/* a_doc */
			$html.= $drawer->getItemAInterne( "", $default_doc, $default_doc_label." 1" );
			$html.= $drawer->getItemAInterne( "", $default_doc, $default_doc_label." 2" );
			$html.= $drawer->getItemAInterne( "", $default_doc, $default_doc_label." 3" );
			if( $cur_bloc != "ALL" ) break;

		case "23" :	case "ALL" :	/* a_list */
			$html.= $drawer->getItemAList( "", $default_links );
			if( $cur_bloc != "ALL" ) break;

		case "24" :	case "ALL" :	/* a_doc_list */
			$html.= $drawer->getItemADocList( "", $default_docs );
			if( $cur_bloc != "ALL" ) break;

		case "25" :	case "ALL" :	/* video */
			$html.= $drawer->getItemVideo( "flash", "player.swf", "320", "256", "8" );
			$html.= $drawer->getItemVideo( "local", "./video/video.avi", "200", "210" );
			$html.= $drawer->getItemVideo( "external", "http://www.youtube.com/v/__C-MjmVUrU&rel=1", "425", "355" );
			if( $cur_bloc != "ALL" ) break;

		case "26" :	case "ALL" :	/* iframe */
			$html.= $drawer->getItemIFrame( "", "http://www.google.fr/" );
			if( $cur_bloc != "ALL" ) break;

		case "27" :	case "ALL" :	/* flash */
			$html.= $drawer->getItemFlash( "", "animation.swf", "250", "250", "8" );
			$html.= $drawer->getItemFlash( "", "animation.swf", "300", "300", "8", "low", "#444444" );
			if( $cur_bloc != "ALL" ) break;

		case "28" :	case "ALL" :	/* bouton */
			$html.= $drawer->getItemBouton( "", "Texte bouton 1", 'javascript:alert(\'Click sur l\\\'ancien bouton :-)\');' );
			if( $cur_bloc != "ALL" ) break;

		case "29" :	case "ALL" :	/* sommaire */
			$html.= $drawer->getItemSommaire( "" );
			if( $cur_bloc != "ALL" ) break;

		default: 
			break;
	}

	$preview = "\n\t\t".'<div id="cms" style="text-align:left;">'.$html.'</div>';
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CMS - Test des blocs</title>
<link rel="stylesheet" href="./css/cms.css" type="text/css" />
<link rel="stylesheet" href="./css/lightbox.css" type="text/css" />
<script src="./js/002_flashObject.js" type="text/javascript"></script>
<script src="./js/prototype.js" type="text/javascript"></script>
<script src="./js/scriptaculous.js?load=effects" type="text/javascript"></script>
<script src="./js/lightbox.js" type="text/javascript"></script>
<style type="text/css">
body{
	background:#FFF;
	margin:16px;
}
*{
	margin:0;
	padding:0;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	list-style:none;
}
h1.cms{
	font-size:15px;
	background:#CCCCCC;
	padding:5px;
}
</style>
</head>

<body style="">

<h1 style="font-size:24px;padding:10px 0;">Test des &eacute;l&eacute;ments du CMS</h1>

<table border="0" cellspacing="0" cellpadding="0" width="100%" style="border:1px solid white">

	<!-- MENU -->
	<tr valign="middle">
		<?=$menu; ?>
	</tr>

	<!-- ESPACE -->
	<tr valign="middle"><td colspan="<?=$count?>">&nbsp;</td></tr>

	<!-- PREVISUALISATION -->
	<tr valign="middle">
		<td colspan="<?=$count?>" style="border:1px dotted white;font-size:14px;padding:6px;">
			Aper&ccedil;u retourn&eacute; pour l'&eacute;l&eacute;ment :
			<b><big style="font-size:18px;"><?=$element?></big></b>
			<center>
			<div style="margin:8px 0;border:1px dotted white;background-color:<?=$bgcolor?>;">
				<?=$preview; ?>
			</div>
			</center>
		</td>
	</tr>

<?php if( $cur_bloc !== "" ) { ?>
	<!-- ESPACE -->
	<tr valign="middle"><td colspan="<?=$count?>">&nbsp;</td></tr>

	<!-- CODE HTML -->
	<tr valign="middle">
		<td colspan="<?=$count?>" style="border:1px dotted white;font-size:14px;padding:6px;">
			Code html retourn&eacute; pour l'&eacute;l&eacute;ment : 
			<b><big style="font-size:18px;"><?=$element?></big></b>
			<textarea style="width:100%;height:320px;font-size:16px;margin:8px 0;">
			<?=str_replace( "\n\t\t", "\n", $preview ); ?>
			</textarea>
		</td>
	</tr>

<?php } ?>

</table>


</body>
</html>
