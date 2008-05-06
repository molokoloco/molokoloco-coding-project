<? include_once("../menu/menu_light.php"); ?>
<table width="100%" border="0" cellpadding="3" cellspacing="1"><?

$mediaDir = $root.$rep.'bibliotheque/';
$medias = getFile($mediaDir);

$imageDir = $root.$rep.'bibliotheque/mini/';
$images = getFile($imageDir);

foreach((array)$medias as $file) {
	?>
<tr class="table-ligne1">
	<td width="220" valign="top"><a href="<?=$mediaDir.$file?>" target="_blank" class="menu"><?=affCleanName($file);?></a></td>
<td valign="top"><input type="text" value="<?=$WWW.$rep.'medias/'.$file?>" onfocus="this.select();" style="width:100%" /></td>
	</tr>
	<?
}
foreach((array)$images as $file) {
	?><tr class="table-ligne1">
	<td align="center" valign="top"><a href="<?=$WWW.$rep.'medias/grand/'.$file?>" target="_blank"><img src="<?=$imageDir.$file;?>" border="0" style="border:1px solid #016AC5;" title="<?=affCleanName($file);?>" /></a></a></td>
	<td valign="top"><input type="text" value="<?=$WWW.$rep.'medias/mini/'.$file?>" onfocus="this.select();" style="width:100%" /><br>
	<input type="text" value="<?=$WWW.$rep.'medias/medium/'.$file?>" onfocus="this.select();" style="width:100%" /><br>
	<input type="text" value="<?=$WWW.$rep.'medias/grand/'.$file?>" onfocus="this.select();" style="width:100%" /><br>
<input type="text" value="<?=$WWW.$rep.'medias/bannieres/'.$file?>" onfocus="this.select();" style="width:100%" /><br></td>
</tr><?
}
?></table>
<? include_once("../menu/menu_bas.php"); ?>