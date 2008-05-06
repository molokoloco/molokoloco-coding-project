<?  include_once("../menu/menu.php"); ?>
<?

if (!@is_dir('../lib/_sqlback')) {
	mkdir('../lib/_sqlback', 0755);
	chmod('../lib/_sqlback', 0777);
}

?><table width="100%" border="0" cellpadding="0" cellspacing="0" class="borCote">
	<tr>
		<td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="borCote">
				<tr>
					<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="23" nowrap class="table-titre">Sauvegarde</td>
								<td width="67%" align="center" class="table-titre2"></td>
							</tr>
						</table></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="15">
				<tr valign="top">
					<td width="98%" align="center"><? if ($info != '' || $intitule != '') { include("../lib/actions_infos.php"); } ?>
					<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="bgTableauTitre">
							<tr>
								<td height="20" align="center">&nbsp;Options de sauvegarde &nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle">&nbsp;</td>
							</tr>
						</table>
						<br />
						<table border="0" cellspacing="0" cellpadding="4">
							<tr>
								<td height="25" valign="top"><table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
										<td nowrap background="../images/images/button_02.png"><a href="actions.php?action=sqlBackup" target="actionFrame" class="menu">Sauvegarder la base de donn&eacute;e mySQL</a></td>
										<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
									</tr>
								</table></td>
								</tr>
							<tr>
								<td height="50" valign="top" class="comment">Attention le fichier sql ne stock pas les m&eacute;dias</td>
							</tr>
							<tr>
								<td valign="top">
								<form action="actions.php?action=sqlImport" target="actionFrame" method="POST" enctype="multipart/form-data" id="F1" name="F1" style="margin:0; padding:0;">
								<script type="text/JavaScript">
								<!--
								function V1() { // Verif formulaire
									whom = document.F1;
									var error = "";
									if (whom.filesql.value == '') error += "- Choisissez un fichier .sql à importer";
									else if (getExt(whom.filesql.value) != 'sql') error += "- Fichier .sql uniquement";
									if (error != "") alert(error);
									else if (confirm('Etes vous sur de vouloir effacer toutes les données existantes (mise à jour complète) avec le ficher suivant : '+baseName(whom.filesql.value))) whom.submit();
								}
								//-->
								</script>
								
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td><table border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
												<td nowrap background="../images/images/button_02.png"><a href="javascript:V1()" class="menu">Restaurer la base de donn&eacute;e mySQL</a></td>
												<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
											</tr>
										</table></td>
										<td>&nbsp;&nbsp;&nbsp;</td>
										<td><input type="file" size="20" id="filesql" name="filesql"/></td>
									</tr>
								</table>
								</form></td>
								</tr>
							<tr>
								<td valign="top" class="comment">Importer un fichier de sauvegarde mySQL et mettre les donn&eacute;es existantes &agrave; jour </td>
							</tr>
							<!--<tr>
								<td height="25" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
										<td nowrap background="../images/images/button_02.png"><a href="actions.php?action=siteBackupActif" target="actionFrame" class="menu" onClick="return confirm('Attention, ne quittez pas la page avant la fin de l\'op&eacute;ration (plusieur minutes)');">Sauvegarder le site : SWF + XML + MEDIAS</a></td>
										<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
									</tr>
								</table></td>
								</tr>
							<tr>
								<td height="50" valign="top" class="comment">Enregistrer un fichier au format ZIP du site actuel</td>
							</tr>-->
							<!--<tr>
								<td height="25" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
										<td nowrap background="../images/images/button_02.png"><a href="actions.php?action=siteBackup" target="actionFrame" class="menu">Sauvegarder toutes les exp&eacute;riences : SWF + XML + FICHIERS</a></td>
										<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
									</tr>
								</table></td>
								</tr>
							<tr>
								<td height="50" valign="top" class="comment">Enregistrer un fichier au format ZIP du site actuel</td>
							</tr>-->
						</table>
						<br />
						<br />
						<br /></td>
				</tr>
			</table></td>
	</tr>
</table>
<? include_once("../menu/menu_bas.php"); ?>
