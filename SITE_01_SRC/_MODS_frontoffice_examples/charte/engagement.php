<div id="engagement">
	<h1 class="t_page">Les 6 engagements de la Charte Qualit&eacute; Fleur</h1>
	<? include('charte/smenu.php');?>
	<?

	$A =& new Q(" SELECT * FROM mod_charte WHERE id='$engagement_id' ORDER BY id ASC");
	$V = $A->V[0];
	
	?><div class="page">
		
		<div class="h_t_engagement">
			<div class="b_t_engagement">
				<div class="f_t_engagement<?=$engagement_id;?>">
					<h2><?=aff($V['titre']);?></h2>

					<div id="player"><? include('alt_flash.php');?></div>
					<script type="text/javascript">
						// <![CDATA[
						var so = new SWFObject("swf/player.swf", "player", "482", "286", "8", "");	
						so.addParam("quality", "high");
						so.addVariable("id", "<?=$engagement_id;?>");
						so.write("player");
						// ]]>
					</script>

				</div>
			</div>
		</div>

		<?=quote(aff($V['texte']));?>
		
		<p><a  href="javascript:void(0)" onclick="myLightWindow.activateWindow({href: 'video.php', title: '', width: '482', height:'286'});" class="video"><span>Voir la vid&eacute;o compl&egrave;te</span></a></p>
		
		<div class="secrets">
			<h3><?=cs(aff($V['titre_bloc']), 60);?></h3>
			<div class="f_secrets">
				<?
				$A =& new Q(" SELECT * FROM mod_charte_blocs WHERE actif=1 AND charte_id='$engagement_id' ORDER BY ordre DESC ");
				foreach($A->V as $V) {
					$m =& new FILE();
					if ($m->isMedia('imgs/charte/medium/'.$V['visuel'])) {
						$m->css = 'visu_gauche';
						$m->popImage();
					} ?>
					<h4><?=cs(aff($V['titre']), 60);?></h4>
					<?=quote(aff($V['texte']));?>
				<? } ?>
			</div>
		</div>
	</div>
</div>