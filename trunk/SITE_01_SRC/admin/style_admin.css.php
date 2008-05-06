<? header("Content-type: text/css");
require_once('./lib/racine.php'); ?>

BODY {
background-color: <?=$bgcolor1;?>;
margin: 0px;
font: 11px Verdana, arial, helvetica, sans-serif;
}

tr.hover:hover, td.hover:hover { background:#FFFFFF; }

/* ---------------------------------- TEXTE et LIEN ---------------------------------- */

H1, H2, H3 {
color: <?=$linkcolor;?>;
font-family:  Verdana, arial, Helvetica, sans-serif;
}
pre {
background-color: #EEE;
padding: 10px;
font: 11px Verdana, arial, helvetica, sans-serif;
border: 1px dashed #999999;
}
sup{
vertical-align: top;
color: <?=$linkcoloron;?>;
margin:0 0 0 1px;
}
a {
color: <?=$linkcolor;?>;
font-family:  Verdana, arial, Helvetica, sans-serif;
text-decoration : none;
}
a:hover {
color: <?=$linkcoloron;?>;
font-family:  Verdana, arial, Helvetica, sans-serif;
text-decoration : none;
}
a.menu {
color: <?=$linkcolor;?>;
text-decoration : none;
font: bold 10px Verdana, Arial, Helvetica, sans-serif;
}
a.sstitre {
color: <?=$fontcolor2;?>;
text-decoration : none;
font: bold 11px Verdana, Arial, Helvetica, sans-serif;
}
a.sstitreSelect {
color: <?=$linkcoloron;?>; /*#016AC5*/
text-decoration : none;
font: bold 11px Verdana, Arial, Helvetica, sans-serif;
}
.whiteLink, a.whiteLink {
color: <?=$bgcolor1;?>;
}
.titre {
font: 18px Verdana, arial, helvetica, sans-serif;
font-weight: bold;
}
.sstitre {
font-family: Arial, helvetica;
font-size:12px;
color:<?=$linkcolor;?>;
font-weight: bold;
}
.texte {
font: 10px Verdana, arial, helvetica, sans-serif;
color : <?=$fontcolor2;?>;
}
.comment {
font-size: 9px;
}

/* ---------------------------------- DIVERS ---------------------------------- */

HR {
height : 1px;
}
.table-separateur {
background: <?=$bgcolor2;?>;
}

/* ---------------------------------- INPUTS ---------------------------------- */

SELECT,INPUT,TEXTAREA {
COLOR: #000000;
BORDER-RIGHT: <?=$ligneentete;?> 1px solid;
BORDER-TOP: <?=$fontcolor2;?> 1px solid;
BORDER-LEFT: <?=$fontcolor2;?> 1px solid;
BORDER-BOTTOM: <?=$ligneentete;?> 1px solid;
font: 10px Verdana, arial, helvetica, sans-serif;
background: <?=$ligneon;?>;
}
INPUT {
height:18px;
}
INPUT.radio,INPUT.checkbox {
border: none;
background: ; /*#E4E4E4*/
vertical-align:middle;
}
.button {
cursor: pointer;
BORDER-RIGHT: <?=$ligneentete;?> 1px solid;
BORDER-TOP: <?=$fontcolor2;?> 1px solid;
FONT-SIZE: 12px;
BORDER-LEFT: <?=$fontcolor2;?> 1px solid;
COLOR: <?=$ligneentete;?>;
BORDER-BOTTOM: <?=$ligneentete;?> 1px solid;
BACKGROUND-COLOR: <?=$ligneon;?>;
height: 20px;
margin: 2px;
width: 120px;
}

/* ---------------------------------- BORDURES ---------------------------------- */

.bor1, .tablebor {
border: 1px solid <?=$ligneentete;?>;
}

/* ---------------------------------- TABLES et BACKGROUNDS ---------------------------------- */

.table-titre {
font: bold 12px Arial, Helvetica, sans-serif;
color : <?=$fontcolor1;?>;
text-align : center;
text-transform: uppercase;
background-color: <?=$bgcolor2;?>;
}
.table-titre2 {
font: 11px Verdana, Arial, Helvetica, sans-serif;
color : <?=$fontcolor2;?>;
background-color: <?=$bgcolor2;?>;
border-top: 0px solid <?=$fontcolor1;?>;
border-right: 0px solid <?=$fontcolor1;?>;
border-bottom: 0px solid <?=$fontcolor1;?>;
border-left: 1px solid <?=$fontcolor1;?>;
}
.table-sstitre {
font: bold 11px Verdana, arial, helvetica, sans-serif;
color : <?=$fontcolor2;?>;
text-align : center;
background: <?=$ligneentete;?>;
height: 25px;
}
.table-entete1 {
font: bold 12px Verdana, arial, helvetica, sans-serif;
color : <?=$fontcolor2;?>;
background: <?=$ligne1;?>;
height: 23px;
}
.table-entete2 {
font: bold 12px Verdana, arial, helvetica, sans-serif;
color : <?=$fontcolor2;?>;
background: <?=$ligne2;?>;
height: 23px;
}
.table-ligne1 {
font: 11px Verdana, arial, helvetica, sans-serif;
color : <?=$fontcolor2;?>;
background: <?=$ligne1;?>;
}
.table-ligne2 {
color : <?=$fontcolor2;?>;
font: 11px Verdana, arial, helvetica, sans-serif;
background: <?=$ligne2;?>;
}
.table-ligne3 {
color : <?=$fontcolor2;?>;
font: 11px Verdana, arial, helvetica, sans-serif;
background: <?=$ligneon;?>;
}
.table-dialogue {
color : <?=$linkcoloron;?>;
font: bold 12px Verdana, arial, helvetica, sans-serif;
border: 1px dashed <?=$linkcolor;?>;
}
.bgTableauPcP {
background: <?=$ligne1;?>; /*#EDEDED*/
font: 11px Verdana, Arial, Helvetica, sans-serif;
color: <?=$linkcolor;?>;
}
.bgTableauMenu {
background: <?=$bgcolor1;?>;
font: bold 11px Verdana, Arial, Helvetica, sans-serif;
color: <?=$linkcolor;?>;
border-top: 1px solid <?=$fontcolor2;?>;
border-right: 0px solid <?=$fontcolor2;?>;
border-bottom: 1px solid <?=$fontcolor2;?>;
border-left: 0px solid <?=$fontcolor2;?>;
}
.bor12 {
background: <?=$bgcolor2;?> url(../images/bg_fond.png) repeat-y;
}
.table-bas {
font: 11px Verdana, arial, helvetica, sans-serif;
color : <?=$fontcolor2;?>;
background: <?=$ligneentete;?>;
}
.bgTableauTitre {
background: #EDEDED;
color: <?=$linkcoloron;?>;
font: bold 12px Arial, Helvetica, sans-serif;
height: 25px;
border: 1px solid <?=$fontcolor2;?>;
}


.divError{
	clear:both;
	display:block;
	font-size:11px;
	color:#00A7DC;
	font-weight:normal;
	padding:0 0 0 262px;
	background:#EBEBEB;
}