<div id="utiles">
	<h1 class="t_page">Lexique</h1>
	
	<div class="selection">
		<ul>
			<?
			for($lettre='a'; $lettre!='aa'; $lettre++){
				// S'il existe des définition un lien sinon un span
				$A =& new Q("SELECT count(id) AS total FROM mod_lexique WHERE actif='1' AND titre LIKE '{$lettre}%' ");
				if ($A->V[0]['total'] > 0) {
					echo '<li><a id="lien_'.$lettre.'" onclick="open_div(\'lien_'.$lettre.'\', \'lettre_'.$lettre.'\');" href="javascript:void(0);">'.strtoupper($lettre).'</a></li>';
					//if (!isset($firstLetter)) $firstLetter = $lettre;
				}
				else
					echo '<li><span>'.strtoupper($lettre).'</span></li>';
			}
			?>
			<li><a id="lien_tous" onclick="open_div('lien_tous','lettre_tous');" href="javascript:void(0);" class="tous">Tous</a></li>
		</ul>
	</div>
	
	<?
	for ($lettre='a'; $lettre!='aa'; $lettre++){
		?>	
		<div class="lettre" id="lettre_<?=$lettre;?>"><? /* style="display:none;" */ ?>
			<?
			
			$A =& new Q("SELECT * FROM mod_lexique WHERE actif='1' AND titre LIKE '{$lettre}%' ORDER BY titre ASC ");
			
			foreach($A->V as $i=>$V) { 
				?>
				<div class="definition <?=$lettre;?>">
					<dl>
						<dt>
							<a href="javascript:void(0);" onclick="javascript:accordeonEffect('<?=$i;?>','div.definition.<?=$lettre;?>',{a_id:'a<?=$lettre;?>', div_id:'div<?=$lettre;?>'});" id="a<?=$lettre;?><?=$i;?>" class="off"><?=strtoupper($lettre);?> - <?=aff($V['titre']);?></a>
						</dt>
						<dd>
							<div id="div<?=$lettre;?><?=$i;?>" class="h_definition" <?=($i>0 ? 'style="display:none;"': '');?>>
								<div class="dg_definition">
									<div class="b_definition">
										<div class="m_definition">
											<?=aff($V['texte']);?>
										</div>
									</div>
								</div>
							</div>
						</dd>
					</dl>
				</div>
				<?
			}
			?>
		</div>
		<? 
	}

	js("open_div('lien_".$firstLetter."', 'lettre_".$firstLetter."');");
	
	?>
</div>