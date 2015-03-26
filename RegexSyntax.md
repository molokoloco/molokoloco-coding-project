# Tooltips Regex #

  * http://www.debuggex.com !!!
  * http://regex101.com
  * http://www.regexper.com (Regex visualization)
  * http://scriptular.com (Regex online testing)

```

$page_web = 'http://www.google.fr/';
$debut = 'Nombre de pages Web recensées par Google : ';
$fin = '\.<\/font><\/p><\/center><\/body>';
$page = fopen ($page_web, 'r')or die('Impossible d\'ouvrir la page '.$page_web.'.');
$contenu_html = '';
while (!feof ($page)) $contenu_html .= trim(fgets($page, 4096));
preg_match("/$debut(.*)$fin/s", $contenu_html, $valeur);
echo 'Le nombre de pages Web recensées par Google est de '.$valeur[1].'.';

________________________________________________________________________

// Javascript.. GET URL vars...

var GET = function() { 
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) { vars[key] = value; });
	return vars;
};

// Check URL...
return /^https?:\/\/[A-Za-z0-9\-_%&\?\/.=]{5,}$/.test(url);

// http://stackoverflow.com/questions/2662485/simple-php-regex-question
var isUrl = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#$*'()\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
var youtubeUrl = /(https?\:\/\/(www\.)?youtu(\.)?be(\.com)?\/.*(\?v=|\/v\/)([a-zA-Z0-9_\-]+).*)/g;
var dailymotionUrl = /https?:\/\/(?:www\.)?dailymotion.com\/video\/([\w-_]+)/g;
var vimeoUrl = /http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+)/g;



// jQuery

function getBackground() {
    if ($("body").css("backgroundImage")) {
        return $("body").css("backgroundImage").replace(/url\("?(.*?)"?\)/i, "$1");
    }
}
```

Test

```
var cleanName = function(str) {
    if ($.trim(str) == '') return str; // jQuery
    str = $.trim(str).toLowerCase();
    var special = ['&', 'O', 'Z', '-', 'o', 'z', 'Y', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', '.', ' ', '+', '\''],
        normal = ['et', 'o', 'z', '-', 'o', 'z', 'y', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'd', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', '_', '_', '-', '-'];
    for (var i = 0; i < str.length; i++) {
        for (var j = 0; j < special.length; j++) {
            if (str[i] == special[j]) {
                str = str.replace(new RegExp(str[i], 'gi'), normal[j]);
            }
        }
    }
    str = str.replace(/[^a-z0-9_\-]/gi, '_');
    str = str.replace(/[\-]{2,}/gi, '_');
    str = str.replace(/[\_]{2,}/gi, '_');
    return str;
};
```

```
________________________________________________________________________

// PHP.. EXTRACT IMAGE

function getFirstImage($document) { 
	preg_match('/<img[^>]+>/i',$document, $result);
	preg_match('/src=[\'|"](.+?)[\'|"]/i', $result[0], $img);
	return $img[1];
}


// EXTRACT URL
function _striplinks($document) { 
        preg_match_all("'<\s*a\s+.*href\s*=\s*           # find <a href=
                        ([\"\'])?                        # find single or double quote
                        (?(1) (.*?)\\1 | ([^\s\>]+))     # if quote found, match up to next matching quote, otherwise match up to next space
                        'isx",$document,$links);
        while(list($key,$val) = each($links[2])) {
            if(!empty($val))
                $match[] = $val;
        }             
        while(list($key,$val) = each($links[3]))  {
            if(!empty($val))
                $match[] = $val;
        }     
        return $match;
    }


var stripHtml = function(w) {
    if (!w) return w;
    return w.replace(/(<([^>]+)>)|nbsp;|\s{2,}|/ig, '');
};

// http://regexlib.com/Search.aspx?k=image
// Extract image source from RSS feed for Yahoo pipe.... PERL like syntax

.+src=['|"](.+?)['|"].+

'#<\s*?img\s+.*[^>]*>#i'

$pattern_img_src = '!.*<img.+src=("|\')([^\1]+)\1.*!Ui'; // '!<([biu])>(.)*<\1>!Ui'
preg_match($pattern_img_src,$desc,$links);
if (!$links[2]) continue;

preg_match_all('/<img[^>]+>/i',$html, $result);
$img = array();
foreach( $result as $img_tag) preg_match_all('/(alt|title|src)=("[^"]*")/i', $img_tag, $img[$img_tag]);
print_r($img);

backgroundStyle.match(/^url\(["']?(.*\.png)["']?\)$/i))
image = RegExp.$1;

// Extract script to find redirect...

if (preg_match_all('/<script[^<](.+?)<\/script>/si', $content, $value)) {
	foreach((array)$value[1] as $scriptFragment) {
		if (preg_match("/window\.location ?= ?['|\"](.+?)['|\"]/si", $scriptFragment, $value1) || preg_match("/window\.location\.replace\(['|\"](.+?)[^'|\"]\)/si", $scriptFragment, $valu1e)) {
			return $value1[1];
			break;
		}
	}
}

replace : onclick="xxxxxx"

onclick=(['""])[\s\S]*?([\s\S]*?['"])

________________________________________________________________________
/*
SYNTAXE REGEX  preg_match_all("/([\w\._-]+\@([\w_-]+\.)+[\w_-]+)/", $contenu, $cleanMail);

http://fr.php.net/manual/fr/reference.pcre.pattern.syntax.php

get HTTP form string...
preg_match_all("/http[^'|\"]*/i", $T->V[$i]['post_text'], $cleanHttp);

str_replace('_',' ',preg_replace("|[_0-9]{13}|",'','mails-_050802171336.txt'))

regex URL
/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(([0-9]{1,5})?\/.*)?$/ix
/<\/?[^>]*>/ >>>> strip tags

// Pattern Modifiers... /patern/i
# sont les délimiteurs de l'expression régulière. On voit déjà que j'ai spécifié deux options : i (insensible à la casse) et s (le caractère . peut aussi récupérer des retours à la ligne)

Caseless (i)  If this modifier is set, letters in the pattern match both upper and lower case letters.
Multiline mode (m)
Dot all (s)
Extended (x)
Anchored (A)
Dollar end only (D)
Extra analysis of pattern (S)
Pattern is ungreedy (U)
Extra (X)
Pattern is treated as UTF-8 (u)


Caractère  Description Exemple

^ Indique le début de la chaîne "^Le": toute chaîne commençant par "Le": "Le chiens", "Les avions"...
$ Indique la fin de la chaîne "soir$": toute chaîne se terminant par "soir": "bonsoir", "a ce soir"...
*  Le caractère apparaît zéro, une ou plusieurs fois "^jim*y$": "jiy", "jimmy", "jimmmmmmmmy"...
+  Le caractère apparaît au moins une fois "^jim+y$": "jimy", "jimmy", "jimmmmmmmmy"...
? La caractère apparaît zéro ou une fois "^lapins?$": "lapin" ou "lapins"
"?:" permet de rendre un groupe non capturant dans une regexp: /(?:to|ti)(ta|tu)/.exec('titu') -> ['titu', 'tu']

{x} Le caractère apparaît strictement x fois "^jim{2}y$": "jimmy".
{x,}  Le caractère apparaît au moins x fois "^jim{2,}y$": "jimmy", "jimmmmmy"...
{x,y}  Le caractère apparaît entre x et y fois "^sup{1,3}e{1,9}r$": "super", "supppeeeeeeeer"...
.  N\'importe quel caractère "^P.P$": "PHP", "PGP", "PCP"...
|  Opérateur OU "^b(a|o|u)tte$": "batte", botte" ou "butte"
[xy]  "x ou y" (identique à x|y) "^[rmg]ite$": "rite", "mite" ou "gite"
[x-y]  Tous les caractères entres x et y "^[a-z]{5}$": "teejj", "dkjsh", "jfjdn", "kgodj"...
"^[a-zA-Z]{3}": une chaîne commençant par trois lettres.
",[A-Z0-9]$": une chaîne se terminant par une virgule suivie d'une majuscule ou d'un chiffre.
\ general escape character with several uses
^ assert start of subject (or line, in multiline mode)
$ assert end of subject (or line, in multiline mode)
. match any character except newline (by default)
[ start character class definition
] end character class definition
| start of alternative branch
( start subpattern
) end subpattern
? extends the meaning of (, also 0 or 1 quantifier, also qu$antifier minimizer
* 0 or more quantifier
+ 1 or more quantifier
{ start min/max quantifier
} end min/max quantifier Part of a pattern that is in square brackets is called a "character class". In a character class the only meta-characters are:
\ general escape character
^ negate the class, but only if the first character
- indicates character range
] terminates the character class
\d any decimal digit
\D any character that is not a decimal digit
\s any whitespace character
\S any character that is not a whitespace character
\w any "word" character
\W any "non-word" character


\n
    Newline (hex 0A)
\r
    Carriage return (hex 0D)
\t
    Tab (hex 09)
\d
    Decimal digit
\D
    Charchater that is not a decimal digit
\h
    Horizontal whitespace character
\H
    Character that is not a horizontal whitespace character
\s
    Whitespace character
\S
    Character that is not a whitespace character
\v
    Vertical whitespace character
\V
    Character that is not a vertical whitespace character
\w
    "Word" character
\W
    "Non-word" character
\b
    Word boundary
\B
    Not a word boundary
\A
    Start of subject (independent of multiline mode)
\Z
    End of subject or newline at end (independent of multiline mode)
\z
    End of subject (independent of multiline mode)
\G
    First matching position in subject
n*
    Zero or more of n
n+
    One or more of n
n?
    Zero or one occurrences of n
{n}
    n occurrences
{n,}
    At least n occurrences
{,m}
    At the most m occurrences
{n,m}
    Between n and m occurrences


Regular expression examples for decimals input

Positive Integers --- ^\d+$
Negative Integers --- ^-\d+$
Integer --- ^-{0,1}\d+$
Positive Number --- ^\d*\.{0,1}\d+$
Negative Number --- ^-\d*\.{0,1}\d+$
Positive Number or Negative Number - ^-{0,1}\d*\.{0,1}\d+$
Phone number --- ^\+?[\d\s]{3,}$
Phone with code --- ^\+?[\d\s]+\(?[\d\s]{10,}$
Year 1900-2099 --- ^(19|20)[\d]{2,2}$
Date (dd mm yyyy, d/m/yyyy, etc.) --- ^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$
IP v4 --- ^(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]){3}$

Regular expression examples for Alphabetic input

Personal Name --- ^[\w\.\']{2,}([\s][\w\.\']{2,})+$
Username --- ^[\w\d\_\.]{4,}$
Password at least 6 symbols --- ^.{6,}$
Password or empty input --- ^.{6,}$|^$
email --- ^[\_]*([a-z0-9]+(\.|\_*)?)+@([a-z][a-z0-9\-]+(\.|\-*\.))+[a-z]{2,6}$
domain --- ^([a-z][a-z0-9\-]+(\.|\-*\.))+[a-z]{2,6}$

Other regular expressions
Match no input --- ^$
Match blank input --- ^\s[\t]*$
Match New line --- [\r\n]|$


// Javascript

if (searchVal.indexOf('quot') >= 0) searchVal.replace(/&quot;/g, '"');

// tester si une date est valide ? :)
!/Invalid|NaN/.test(new Date(str))


var reWhiteSpace = /^\s+$/;
var reDigit = /^\d$/;
var reInteger = /^\d+$/;
var reSignedInteger = /^(\+|\-)?\d+$/;
var reFloat = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;
var reSignedFloat = /^(((\+|\-)?\d+(\.\d*)?)|((\+|\-)?(\d*\.)?\d+))$/;
var reLetter = /^[a-zA-Z]$/;
var reAlphabetic = /^[a-zA-Z]+$/;
var reLetterOrDigit = /^([a-zA-Z]|\d)$/;
var reAlphanumeric = /^[a-zA-Z0-9]+$/;
var reEmail = /^([a-z\d]+([\.\-_]?[a-z\d]+)*)@([a-z\d]+[\.\-]?[a-z\d]+|[\.]?[a-z\d]+)+\.([a-z]{2}|com|net|org|edu|biz}info|gov)$/i;
var reZipCode = /^\d{5}$/;
var reDep = /^((\d\d)|(2A)|(2B)|(97[1-6]))$/;
//var reDate = /^(\d{2}\/){2}\d{4}$/;
var reDate = /^\d{4}(\-\d{2}){2}$/;
var reUrl = /^http\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}$/;

return reAlphabetic.test(MyString);


'12/02/1978'.match( /([0-9]+)/gi)
//> ["12", "02", "1978"]


function escape($filename) { // any non-word characters will be replaced
    Return preg_replace('/[^\w\._]/', '_', $filename);
}

new line ?
"/;$/"
preg_match("/;[\040]*\$/", $sql_line)

$ligne = " jsnbdfnbsdfnbs:dnbfs;:d [56456] j:ln:lhnsldf[56456]  SdqsdqSD";
preg_match("/(\[[0-9]+\])/", $ligne, $Tid);
print_r($Tid).'-';
//>>>> telele_0101464564551313.jpg

function bb_strip($s) {
    return ereg_replace("\[/?[^] ]*/?\]",'',$s);
}

$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
);
$text = preg_replace($search, '', $document);


________________________________________________________________________
REGEX
Expression Signification
[.ph.]  Les deux caractères "ph", ensemble
[=e=]  Dans la locale FR : e, é, è, ë, ê
[:alnum:] Caractères alphanumériques 0 to 9 OR A to Z or a to z.
[:alpha:] Caractères alphabétiques
[:blank:] Espaces et tabulations
[:cntrl:] Caractères de contrôle
[:digit:] Chiffres décimaux
[:graph:] Caractères hors espaces
[:lower:] Minuscules
[:print:] Caractères affichables
[:punct:] Ponctuations   . , " ' ? ! ; :
[:space:] Caractères d'espacement
[:upper:] Majuscules
[:digit:]      Only the digits 0 to 9
[:xdigit:] Chiffres hexadécimaux
*/
________________________________________________________________________

FIND ALL EMAIL IN A FILE

$contenu = join('',file($fichier_csv));
preg_match_all("/([\w\._-]+\@([\w_-]+\.)+[\w_-]+)/", $contenu, $cleanMail);
//preg_match_all("/(\"(.*?)\"\s+)?<?([\w\._-]+\@([\w_-]+\.)+[\w_-]+)>?/", $contenu, $cleanMail);
$one = 0;
for ($i=0 ; $i<count($cleanMail[0]); $i++) {
        $email = $cleanMail[0][$i];
        $Resultot = mysql_query("SELECT id FROM $Rub_6 WHERE email='$email' ",$connexion) or die();
        if (mysql_num_rows($Resultot) < 1) {
            mysql_query("INSERT INTO $Rub_6 VALUES ('','$email')", $connexion) or die(mysql_error($connexion));
            $one++;
        }
}
_________________________________________________________

RECHERCHE MOT CLE DANS UN FICHIER

<pre>
<?php
$fichier = "server_log.txt";
$s = "callingyou.mp3";
$motif = "/$s/";
$pointeur = fopen($fichier, "r");
$i = 0;
if ($pointeur) {
  while (!feof($pointeur)) {
    $ligne = fgets($pointeur);
    if (preg_match($motif, $ligne, $r)) {
      echo $ligne.'';
      $i++;
      }
    }
  fclose($pointeur);
  echo "Motif '$s' trouvé $i fois.";
  }
?>
</pre>
<xmp>
PRINT HTM CODE
</xmp>

```

Dreamweaver Search/replace with regexp

```
// Ex.
// Search
/status/[0-9]{1,}</a>
// Replace with
</a>)
// White space 
^\s*$
```