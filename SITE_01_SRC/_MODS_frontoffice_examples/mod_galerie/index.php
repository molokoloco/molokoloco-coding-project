<?
if (empty($_SESSION[SITE_CONFIG]['G']) || $ssrub_id != $_SESSION[SITE_CONFIG]['G']['ssrub_id']) {
		
	$select = array(
		'S' => "m.id",
		'F' => "mod_catalogue_produits AS m",
		'W' => "m.actif='1'",
	);
	$fields = array();
	$fields[1][] = array('m','ssrubrique_id', '=', $ssrub_id);

	$_SESSION[SITE_CONFIG]['G'] = searchDb($select, $fields);
	$_SESSION[SITE_CONFIG]['G']['ssrub_id'] = $ssrub_id;
}

$total = intval($_SESSION[SITE_CONFIG]['G']['total']);
$from = $_SESSION[SITE_CONFIG]['G']['from'];
$where = "m.actif='1' AND ".$_SESSION[SITE_CONFIG]['G']['where'];

?>
<div id="index_galerie">
	<div class="titre_rub">
    	<h1 class="t_rub"><?=html(aff($S->arbo[$S->rrid]['titre_'.$lg]));?></h1>
        <strong><?=$total;?> r&eacute;sultat<?=s($total);?></strong>
    </div>
    
    <div class="centre_double">
    	<!-- Centre Gauche -->
        <div class="centre_gauche"><?php include_once('./gauche.php');?></div>
        
        <!-- Centre Droite -->
        <div class="centre_droite" id="galerie_liste">

			<?
			if ($total < 1) {
				?>
				<div class="element" style="width:100%;">
                	<p class="center <?=$article_couleur;?>">Pas de r&eacute;sultats</p>
                    <a href="javascript:history.back();">Retour</a>
                </div>
				<?
			}
			else {
		
				// Pagination
				$pagination = fetchValue('pagination_galeries');
				$pagination = ( $pagination < 1 ? 16 : $pagination );
				
				$pageArr = makePage(gpc('page'), $total, $pagination, thisPage('', '', array('page','recherche')), 'pageSel', 'page', true, '', '', 'page');
				//$pageHtml = $pageArr['precedante'].$pageArr['pageHtml'].$pageArr['suivante'];
				$page = $pageArr['page'];
				$nbpage = $pageArr['nbpage'];
				$debut = $pageArr['debut'];
				$offset = $pageArr['offset'];

				if (empty($filtre)) $filtre = 'm.ordre';
				if (empty($tri)) $tri = 'desc';
		
				// Boucle pagin&eacute;e	
				$G =& new Q("
					SELECT m.*
					FROM $from
					WHERE $where
					ORDER BY $filtre $tri
					LIMIT $debut,$offset
				");
				###db($G);

				$pageHtml = '
					<ul>
						'.( $page > 1 ? '<li><input type="image" class="no_roll" src="images/common/bouton/bt_prec.gif" value="Pr&eacute;c&eacute;dent" onclick="javascript:redir(\''.thisPage('page='.($page-1),'','page').'\');" /></li>' : '').'
						<li>Page</li>
						<li class="txt"><input type="text" value="'.$page.'" class="txt" onfocus="this.value=parseInt(this.value);this.select();" onblur="this.value=parseInt(this.value);" onchange="this.value=parseInt(this.value);redir(\''.thisPage('page=','','page').'\'+this.value);"/></li>
						<li><span>sur '.$nbpage.'</span></li>
						'.( $page < $nbpage ? '<li><input type="image" class="no_roll" src="images/common/bouton/bt_suiv.gif" value="Suivant" onclick="javascript:redir(\''.thisPage('page='.($page+1),'','page').'\');"/></li>' : '').'
					</ul>
				';
				
				echo '<div class="pagination haut">
					'.$pageHtml.'
				</div>';
				$js = '';
				foreach ($G->V as $i=>$V) {
					
					$produit_id = $V['id'];
					
					$P =& new Q("SELECT  MIN(prix) AS prix, MIN(prix_promo) AS prix_promo FROM mod_matieres WHERE actif='1' AND produit_id='$produit_id' LIMIT 1");
					$prix_min = ( $P->V['0']['prix_promo'] > 0 && $P->V['0']['prix_promo'] < $P->V['0']['prix'] ? $P->V['0']['prix_promo'] : $P->V['0']['prix'] );
					
					$ssrubrique_titre = fetchValues('titre', 'mod_catalogue_ssrubriques', 'id', $V['ssrubrique_id']);
					$produit_url = urlRewrite($ssrubrique_titre.'-_-'.$V['titre'], 'r'.$S->rid.'sr'.$V['ssrubrique_id'].'p'.$V['id']);

					?>
					<div class="element <?=(($i+1)%4==0 ? 'last' : '');?>">
						<div class="img">
							<?
							if (!empty($V['document'])) { 
								?>
								<p><a href="_get_model.php?id=<?=$V['id']?>" class="lightwindow" id="pop3D_<?=$V['id']?>"><img src="images/common/picto_3d.gif" alt="Vue 3D disponible" /></a></p>
								<?
								$js .= "
									$('pop3D_".$V['id']."').onclick = function() { return myPop('_get_model.php?id=".$V['id']."&IE=1', 'Modele_".$V['id']."', '680', '520'); };
									$('pop3D_".$V['id']."').className = '';
								";
							}
							$m =& new FILE();
							if ($m->isMedia('medias/catalogue/medium/'.$V['visuel_1'])) {
								echo '<a href="'.$produit_url.'" rel="'.$V['id'].'">';
								$m->image();
								echo '</a>';
							}
							?>
						</div>
						<a href="<?=$produit_url;?>"><?=html(aff($V['titre']));?></a>
						<? if ($prix_min > 0) { ?><span>&agrave; partir de <strong><?=$prix_min;?>&euro;</strong></span><? } ?>
					</div>
					<?	
				}	
			}
			
			js("if (client.isIe()) { // IE make mistake with lightwindow
				".$js."
			}");
			
			?>

			<div class="breaker" style="height:20px;">&nbsp;</div>
            <div class="pagination bas">
            	<?=$pageHtml;?>
            </div>
	
        </div>
    </div>    
</div>