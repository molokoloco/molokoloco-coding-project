<? require('_header.php'); ?>

<? if ($ajax != 1) { ?>

<div id="header">
	<div id="header_space">&nbsp;</div>
	<div id="menu_menu">
		<?=$S->getRootMenuUl($S->rid);?>
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
					case 8 : require '_cv.php'; break;
					case 9 : require '_portofolio.php'; break;
					case 10 : require '_expertise.php'; break;
					case 6 : require '_services.php'; break;
					
					case 4 : // CMS
						if (@is_file('cache/page_rid_'.$S->rid.'.html')) include('cache/page_rid_'.$S->rid.'.html'); // LAST WRITED (CACHED) CONTENT
						else echo getCmsHtml($S->rid, 0); // LAST SAVED CONTENT
					break;
					
					default : require '_accueil.php'; break;
				}
				?>
			</div>
			<div id="navigation" class="text">
				<? if ($S->rid == 15) { // 15 > Bonus

					?><img src="js/lightview/images/lightview/loading.gif" alt="rssloading" width="22" height="22" id="rssloading" style="margin:60px 0 0 120px;" />
					<script type="text/javascript">
					// <![CDATA[
					initFeed = function() {
						new Ajax.Updater(
							'navigation',
							'rss_viewer.php', {
								method: 'get',
								evalScripts:true,
								onComplete: $('rssloading').hide(),
								parameters: 'ajax=1&max=7&feed=http://picasaweb.google.fr/data/feed/base/user/molokoloco?kind=album&alt=rss&hl=fr&access=public',
								insertion: Insertion.Top
							}
						);
					};
					<? if ($ajax == 1) { ?>initFeed();<? }
					else { ?>Event.observe(window, 'load', initFeed, false); <? } ?>
					// ]]>
					</script>
					<?
					
				}
				elseif ($S->arbo[$S->rid]['type_id'] == 10) { // 10 Expertise

					?><img src="js/lightview/images/lightview/loading.gif" alt="rssloading" width="22" height="22" id="rssloading" style="margin:60px 0 0 120px;" />
					<script type="text/javascript">
					// <![CDATA[
					initFeed = function() {
						new Ajax.Updater(
							'navigation',
							'rss_viewer.php', {
								method: 'get',
								evalScripts:true,
								onComplete: $('rssloading').hide(),
								parameters: 'ajax=1&max=3&feed=http://www.google.com/reader/public/atom/user/11601043898330304613/state/com.google/broadcast',
								insertion: Insertion.Top
							}
						);
					};
					<? if ($ajax == 1) { ?>initFeed();<? }
					else { ?>Event.observe(window, 'load', initFeed, false); <? } ?>
					// ]]>
					</script>
					<?
					
				}
				else {
					?>
					<a href="./site_demo/admin/" target="_blank"><img src="images/btn_admin.png" alt="Demo admin" class="highlight" height="64" width="250" /></a>
					<div class="dsep">&nbsp;</div>
					<a href="http://www.hairbox.fr" target="_blank"><img src="images/btn_site.png" alt="Site" class="highlight" height="64" width="250" /></a>
					
					<div class="dsep">&nbsp;</div>
					<h3>Contact</h3>
					<p style="margin:0 0 0 20px;">
						<strong>Julien Gu&eacute;zennec</strong><br />
						<strong>Portable</strong> : 06 61 75 64 98<br />
						<strong>Email/Msn</strong> : <? /*$m = new emailcrypt($emailAdmin, $emailAdmin, '', true, false);*/ ?>
					</p>
					<div class="spacer">&nbsp;</div>
					<div class="dsep">&nbsp;</div>
					<h3>R&eacute;f&eacute;rences - freelance</h3>
					<ul class="site">
						<?
						$P = new Q("SELECT * FROM mod_portofolio WHERE icone!='' AND type='1' AND actif='1' ORDER BY date DESC LIMIT 6");
						foreach($P->V as $V) {
							?><li><a href="<?='medias/portofolio/grand/'.$V['visuel'];?>" title="<?=$V['url'];?>" target="_blank" style="background:url(<?='medias/portofolio/mini/'.$V['icone'];?>) no-repeat scroll 0px;" class="lightwindow" rel="Reference[Freelance]"><?=ucfirst(str_replace('http://www.','',$V['url']));?></a></li><?
						}
						?>
					</ul>
					<div class="breaker">&nbsp;</div>
					<div class="dsep">&nbsp;</div>
					<h3>R&eacute;f&eacute;rences - agence</h3>
					<ul class="site">
						<?
						$P = new Q("SELECT * FROM mod_portofolio WHERE icone!='' AND type='0' AND actif='1' ORDER BY date DESC LIMIT 9");
						foreach($P->V as $V) {
							?><li><a href="<?='medias/portofolio/grand/'.$V['visuel'];?>" title="<?=$V['url'];?>" target="_blank" style="background:url(<?='medias/portofolio/mini/'.$V['icone'];?>) no-repeat scroll 0px;" class="lightwindow" rel="Reference[Agence]"><?=ucfirst(str_replace('http://www.','',$V['url']));?></a></li><?
						}
						?>
					</ul>
					<div class="breaker">&nbsp;</div>
					<div class="dsep">&nbsp;</div>
					<h3>Fonctionnalit&eacute;s</h3>
					<div id="info_defil">
						<div id="info_scroll">
							<?=$infoScroll;?>
						</div>
					</div>
					<?
				}
				?>
			</div>
			<div class="clear">&nbsp;</div>	
		 </div>

<? if ($ajax != 1) { ?>

    </div>
    <div id="footer">
      <div style="display:inline; float:right; margin-right:20px;">
			<!--<a href="https://addons.mozilla.org/fr/firefox/addon/5579" onclick="javascript:try{PicLensLite.start(); return false;}catch(e){return true};" target="_blank" title="Get immersive ! My sites with PicLens : http://piclens.com/"><img src="http://lite.piclens.com/images/PicLensButton.png" alt="PicLens" width="16" height="12" border="0" style="margin-right:60px;"/></a> --><a href="http://del.icio.us/post?url=<?=urlencode($WWW);?>&amp;title=<?=urlencode($WWW);?>" title="Bookmark this site on del.icio.us." rel="nofollow"><img src="images/feeds/delicious.png" alt="Delicious"/></a>&nbsp;<a href="http://digg.com/submit?phase=2&amp;url=<?=urlencode($WWW);?>&amp;title=<?=urlencode($WWW);?>" title="Digg this site on digg.com." rel="nofollow"><img src="images/feeds/digg.png" alt="Digg"/></a>&nbsp;<a href="http://reddit.com/submit?url=<?=urlencode($WWW);?>&amp;title=<?=urlencode($WWW);?>" title="Submit this site on reddit.com." rel="nofollow"><img src="images/feeds/reddit.png" alt="Reddit"/></a>&nbsp;<a href="http://www.furl.net/storeIt.jsp?u=<?=urlencode($WWW);?>&amp;t=<?=urlencode($WWW);?>" title="Submit this site on furl.net." rel="nofollow"><img src="images/feeds/furl.png" alt="Furl"/></a>&nbsp;<a href="http://www.facebook.com/sharer.php?u=<?=urlencode($WWW);?>&amp;t=<?=urlencode($WWW);?>" title="Share on Facebook." rel="nofollow"><img src="images/feeds/facebook.png" alt="Facebook"/></a>&nbsp;<a href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?=urlencode($WWW);?>&amp;title=<?=urlencode($WWW);?>" title="Bookmark this site on Google." rel="nofollow"><img src="images/feeds/google.png" alt="Google"/></a>&nbsp;<a href="http://fusion.google.com/add?source=atgs&amp;feedurl=<?=urlencode($WWW);?>rss.php" title="Add to my Google" rel="nofollow"><img src="images/feeds/google.png" alt="Google"/></a>&nbsp;<a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?=urlencode($WWW);?>&amp;t=<?=urlencode($WWW);?>" title="Bookmark this site on Yahoo." rel="nofollow"><img src="images/feeds/yahoo.png" alt="Yahoo"/></a>&nbsp;<a href="http://add.my.yahoo.com/content?lg=fr&amp;url=<?=urlencode(urlencode($WWW));?>" title="Add to my Yahoo." rel="nofollow"><img src="images/feeds/yahoo.png" alt="Yahoo"/></a>&nbsp;<a href="http://technorati.com/cosmos/search.html?url=<?=urlencode($WWW);?>" title="Search Technorati for links to this site." rel="nofollow"><img src="images/feeds/technorati.png" alt="Technorati"/></a>&nbsp;<a href="http://blogs.icerocket.com/search?q=<?=urlencode($WWW);?>" title="Search IceRocket for links to this site." rel="nofollow"><img src="images/feeds/icerocket.png" alt="Icerocket"/></a>
		</div>
		<p><?=$S->getRootMenuHtml($S->rid);?> | <a href="<?=$WWW;?>rss.php">RSS</a></p>

  </div>
</div>

<div style="text-align:center;width:972px;margin-left:auto;margin-right:auto;">
	<p><strong>Julien Gu&eacute;zennec</strong> - Freelance d&eacute;veloppement Internet LAMP<br />
		<strong>Adresse</strong> : <a href="http://maps.google.fr/maps?f=l&amp;hl=fr&amp;geocode=&amp;q=borntobeweb&amp;near=&amp;sll=48.892797,2.353477&amp;sspn=0.020089,0.037165&amp;ie=UTF8&amp;ll=48.895985,2.359529&amp;spn=0.020088,0.037165&amp;z=15&amp;iwloc=A&amp;om=0" title="Localiser sur Google Map" target="_blank">8, rue Boucry 17B11, 75018 PARIS</a> | <strong>T&eacute;l&eacute;phone</strong> : 01 76 67 93 73 | <strong>Portable</strong> : 06 61 75 64 98 | <strong>Email/Msn</strong> : <? $m = new emailcrypt($emailAdmin, $emailAdmin, '', true, false);?><br/>
	<a title="BornToBeWeb.fr" href="<?=$WWW;?>"><strong>BornToBeWeb.fr</strong></a> &copy; 1998 - 2008 | <strong>N&deg; siret</strong> : En cours d'immatriculation<br/></p>
</div>

<div id="pagefoot">&nbsp;</div>

<? require('_footer.php'); ?>

<? } ?>