<div class="menu_g">
	<ul>
		<li><a href="index2.php?goto=charte" class="<?=($goto=='charte'?'on':'');?>">Pr&eacute;sentation</a></li>
		<li class="dernier"><a href="index2.php?goto=engagement" class="<?=($goto=='engagement'?'on':'');?>">Les 6 engagements de la Charte Qualit&eacute; Fleurs</a></li>
	</ul>
	
	<? if($goto=='engagement'){?>
	<div class="menu_engagement">
		<?
		$A =& new Q(" SELECT * FROM mod_charte  ORDER BY id ASC");
		foreach($A->V as $V) {
			?>
			<div class="h_engagement <?=($engagement_id == $V['id'] ? ' on':'');?>">
				<div class="b_engagement">
					<div class="f_engagement<?=$V['id'];?>">
						<a href="index2.php?goto=engagement&engagement_id=<?=$V['id'];?>"><?=aff($V['titre']);?></a>
					</div>
				</div>
			</div>
			<?
		}
		?>
	</div>
	<? }?>
</div>