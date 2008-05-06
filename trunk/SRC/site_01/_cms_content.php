<br />
<table width="100%" height="1" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="243" valign="top" id="col_1"><? 

getColonneBloc('pages_relation_bloc_gauche', 'pages_relation_encart_gauche', $S->rid);
	
?><div class="spacer">&nbsp;</div>
</td>
<td valign="top" id="col_2"><?

if (!empty($visuel)) $page_col = 2; // Si visuel fusion

if ($page_col == 3) { // Mode 2 ou 3 colonnes > Taille IMG + CSS
	$tableWidth = '520';
	$imgC = '1';
	$main_dW = '12';
	$headStyle = '';
}
else {
	$tableWidth = '757';
	$imgC = '3';
	$main_dW = '28';
	$headStyle = 3;
}

?><table width="<?=$tableWidth;?>" height="1" border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="33" valign="bottom" class="mainHead<?=$headStyle;?>Bg1"><table width="97%" border="0" align="right" cellpadding="0" cellspacing="0">
<tr>
<td height="20" class="boxHead" id="boxHead"><strong><?=quote(aff($titre));?></strong></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="1" height="100%"><img src="images/images/main<?=$imgC;?>_g.png" alt="" width="6" height="100%"></td>
<td background="images/images/main<?=$imgC;?>_c.png" bgcolor="#f7e8db"><table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
<tr>
<td height="100%"><?=$NAV_TOP;?><?

if (!$isCMS) echo $THE_CONTENT;

else { // Default values can be overwrited on the fly

	switch ($texte_col) {
		case 1 : $width = ''; break;
		case 2 : $width = '49%'; break;
		case 3 : $width = '32%'; break;
	}
	
	if (empty($texte1)) js ("printInfo(TRAVAUX);");
	
	else {

		if (!empty($visuel)) { // Texte 2 ou 3 colonnes Image fusion 2 col en haut a droite

			?><?=$visuel_top;?><?
			
			?><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="33%" valign="top"><?
			if (!empty($titre_page)) { ?><h4><?=quote(aff($titre_page));?></h4>
			<br />&nbsp;<? } ?>
			<? if (!empty($chapeau)) { ?><h2><?=quote(aff($chapeau));?></h2>
			<br />&nbsp;<? } ?><?=$visuel1;?><?=quote(aff($texte1));?></td>
			<td>&nbsp;</td>
			<td width="66%" valign="top"><?=$visuel;?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>

			<? if ($texte_col != 3) { 
				?><td valign="top"><br />
				<?=$visuel2;?><?=quote(aff($texte2));?></td><?
			} else {
				?><td width="49%" valign="top"><br />
				<?=$visuel2;?><?=quote(aff($texte2));?></td>
				<td>&nbsp;</td>
				<td width="49%" valign="top"><br />
				<?=$visuel3;?><?=quote(aff($texte3));?></td><?
			} ?>

			</tr>
			</table></td>
			</tr>
			</table><?
		}
		else { // Texte 1, 2 ou 3 colonnes
			
			if (!empty($titre_page)) { ?><h4><?=quote(aff($titre_page));?></h4>
			<br /><? } ?>
			<? if (!empty($chapeau)) { ?><h2><?=quote(aff($chapeau));?></h2><? } ?>
			<br /><?=$visuel_top;?><?
			
			?><table width="100%" border="0" cellspacing="6" cellpadding="0">
				<tr>
					<td width="<?=$width?>" valign="top"><?=$visuel1;?><?=quote(aff($texte1));?></td>
					<? if ($texte_col != 1) { 
						?><td valign="top">&nbsp;</td>
						<td width="<?=$width?>" valign="top"><?=$visuel2;?><?=quote(aff($texte2));?></td><?
					} if ($texte_col == 3) {
						?><td valign="top">&nbsp;</td>
						<td width="<?=$width?>" valign="top"><?=$visuel3;?><?=quote(aff($texte3));?></td><?
					} ?>
				</tr>
			</table><?
		}
	}
}
?><?=$NAV_BOT;?></td>
</tr>
</table></td>
<td width="1" height="100%"><img src="images/images/main<?=$imgC;?>_d.png" alt="" width="<?=$main_dW;?>" height="100%"></td>
</tr>
</table></td>
</tr>
<tr>
<td height="38"><img src="images/images/main<?=$imgC;?>_foot.png" alt="" width="<?=$tableWidth;?>" height="37"></td>
</tr>
</table></td><?

if ($page_col == 3) { 
	?><td width="202" valign="top" id="col_3"><?
	
	getColonneBloc('pages_relation_bloc_droite', 'pages_relation_encart_droite', $S->rid);
	
	?><div class="spacer">&nbsp;</div>
	</td><?
}
?></tr>
</table>
<?=$PUB_BAS;?>