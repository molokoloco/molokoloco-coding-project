<table width="100%"  border="0" cellspacing="0" cellpadding="10">
<tr>
<td><table width="100%" height="40" border="0" cellpadding="3" cellspacing="4" bgcolor="#EDEDED" class="table-dialogue">
<tr>
<td align="center" bgcolor="#FFFFFF" ><?
if ($intitule != '') { echo $intitule.'<br />'; }
if ($info == "crea"){echo "Cr&eacute;ation effectu&eacute;e";}
if ($info == "creadoc"){echo "Cr&eacute;ation Fiche technique effectu&eacute;e";}
if ($info == "erreur"){echo "Erreur dans le traitement du formulaire";}
if ($info == "nosel"){echo "Vous n'avez rien s&eacute;lectionn&eacute;";}
if ($info == "manquedoc"){echo "Attention vous devez ins&eacute;rer une photo";}
if ($info == "suppdoc"){echo "Suppression de la photo effectu&eacute;e";}
if ($info == "modif"){echo "Modification effectu&eacute;e";}
if ($info == "modiftitre"){echo "Modification du titre Fiche technique effectu&eacute;";}
if ($info == "supp"){echo "Suppression effectu&eacute;e";}
if ($info == "ordre"){echo "Ordre modifi&eacute;";}
?></td>
</tr>
</table></td>
</tr>
</table>
