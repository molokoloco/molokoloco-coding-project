<div id="plan">
	<?

	// Menu UL/LI de l'ensemble des pages de l'arbo qui s'affiche au menu
	$S->maxLevel = 4;
	$S->isJsMenu = false;
	
	echo $S->getRootMenuUlRecur();
	
	?><br />
	<br />
	<ul>
		<li>
			<?
			
			// Menu UL/LI des pages de l'arbo qui ne s'affiche pas au menu
			$S->sep = '</li><li>';
			echo $S->getBottomMenuHtml();
			?>
		</li>
	</ul>
</div>