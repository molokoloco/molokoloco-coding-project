<? require('_header.php'); ?>

<? if ($ajax != 1) { ?>

<div id="header">
	<div id="header_space">&nbsp;</div>
	<div id="menu_menu">
		<?=$S->getRootMenuUl($S->arid);?>
	</div>
</div>

<div id="page">
	<div id="content">
	
<? } ?>

		<div class="box">
			<div id="maincontent">
				<?
				### db($S->arbo[$S->rid]['type_id']);
	
				switch($S->arbo[$S->rid]['type_id']) {
					case 1 : require '_accueil.php'; break;
//					case 8 : require '_cv.php'; break;
//					case 9 : require '_portofolio.php'; break;
//					case 10 : require '_expertise.php'; break;
//					case 6 : require '_services.php'; break;
					
					case 4 : // CMS
						if (@is_file('cache/page_rid_'.$S->rid.'.html')) include('cache/page_rid_'.$S->rid.'.html'); // LAST WRITED (CACHED) CONTENT
						else { // LAST SAVED CONTENT
							$cms = getCmsHtml($S->rid, 0);
							if (!empty($cms)) echo $cms;
							else echo '<h2>Pas de contenu pour cette page</h2>';
						}
					break;
					
					default : require '_accueil.php'; break;
				}
				?>
			</div>
			<div id="navigation" class="text">
				<img src="js/lightview/images/lightview/loading.gif" alt="rssloading" width="22" height="22" id="rssloading" style="margin:60px 0 0 120px;" />
				<script type="text/javascript">
				// <![CDATA[
				initFeed = function() {
					new Ajax.Updater(
						'navigation',
						'rss_viewer.php', {
							method: 'get',
							evalScripts:true,
							onComplete: $('rssloading').hide(),
							parameters: 'ajax=1&max=3&feed='+escapeURI('http://picasaweb.google.com/data/feed/base/all?alt=rss&kind=photo&access=public&tag=france&filter=1&hl=fr'),
							insertion: Insertion.Top
						}
					);
				};
				<? if ($ajax == 1) { ?>initFeed();<? }
				else { ?>Event.observe(window, 'load', initFeed, false); <? } ?>
				
				// ]]>
				</script>
			</div>
			<div class="clear">&nbsp;</div>	
		 </div>

<? if ($ajax != 1) { ?>

    </div>
    <div id="footer">
	<p><?=$S->getBottomMenuHtml($S->arid);?> | <a href="<?=$WWW;?>rss.php">RSS</a></p>
	</div>
</div>

<div style="text-align:center;width:972px;margin-left:auto;margin-right:auto;">
	<p><strong>Julien G</strong> - Freelance d&eacute;veloppement Internet LAMP<strong><br/>
	Email/Msn</strong> : <? $m = new emailcrypt($emailAdmin, $emailAdmin, '', true, false);?><br/>
	<a title="BornToBeWeb.fr" href="<?=$WWW;?>"><strong>BornToBeWeb.fr</strong></a><br/></p>
</div>

<div id="pagefoot">&nbsp;</div>

<? require('_footer.php'); ?>

<? } ?>