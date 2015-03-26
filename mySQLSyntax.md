**Sommaire :**




# SQL REQUEST #

  * http://dev.mysql.com/doc/refman/5.0/fr/index.html
  * http://dev.mysql.com/doc/refman/5.0/fr/string-functions.html

SQL joins :
![http://www.b2bweb.fr/wp-content/uploads/BHVicYICMAAdHGv.jpg](http://www.b2bweb.fr/wp-content/uploads/BHVicYICMAAdHGv.jpg)

## OPERATORS ##

```

:=
||, OR, XOR
&&, AND
BETWEEN, CASE, WHEN, THEN, ELSE
=, <=>, >=, >, <=, <, <>, !=, IS, LIKE, REGEXP, IN
|
&
<<, >>
-, +
*, /, DIV, %, MOD
^
- (unary minus), ~ (unary bit inversion)
NOT, !
BINARY, COLLATE

```

## REQUESTS EXAMPLES ##

```

SELECT SUM(champ) FROM table WHERE ...

INSERT INTO table (cle,blabla) VALUES (1337,'truc') ON DUPLICATE KEY UPDATE blabla='truc';

REPLACE INTO user_pref (prf_idt, uid, prf_usr_val) VALUES ($prf_idt, $uid, $prf_usr_val)

// Trouver les doublons....
SELECT ref FROM produits GROUP BY ref HAVING COUNT( ref ) > 1

// La clause HAVING peut utiliser des fonctions d'agrégation, alors que la clause WHERE ne le peut pas :
SELECT user, MAX(salary) FROM users GROUP BY user HAVING MAX(salary)>10;

SELECT a, COUNT(b) FROM test_table GROUP BY a DESC

UPDATE wp_posts SET post_content = REPLACE(post_content, "<tags>", "[tags]");

UPDATE mod_blocs_vip SET quantite=(quantite+1) WHERE id='1' LIMIT 1

SELECT DISTINCT (cat_id), id, titre_$lg FROM mod_ec_idees WHERE valide=1 GROUP BY cat_id ORDER BY note DESC LIMIT 6

SELECT DISTINCT store_type FROM stores WHERE NOT EXISTS (SELECT * FROM cities_stores WHERE cities_stores.store_type=stores.store_type);

SELECT QUOTE("Don't"); -> 'Don\'t!'

ORDER BY id*0+RAND() DESC LIMIT 0, 1 // Cf. plus bas...

SELECT 'a' REGEXP '^[a-z]';

WHERE ville_id IS NULL OR ville_id='3'

SELECT REPLACE('www.mysql.com', 'www.', '');

SELECT ROUND(-1.23);

ORDER BY rang*1 >>>> FORCE LE CHAMPS VARCHAR EN INT

SELECT LOWER('QUADRATIQUE'), UPPER(str)

SELECT CONCAT(last_name,', ',first_name) AS full_name

SELECT t1.name, t2.salary FROM employee AS t1, info AS t2 WHERE t1.name = t2.name;

SELECT * FROM mod_blocs_vip WHERE actif='1' AND datedeb<=NOW() AND datefin>=NOW() ORDER BY ordre DESC LIMIT 4
SELECT CURRENT_DATE() as date

// La ligne contenant le maximum d'une certaine colonne
SELECT article, dealer, price FROM shop WHERE price=(SELECT MAX(price) FROM shop);

SELECT * FROM Livres WHERE Prix NOT IN (40, 50, 72);
SELECT * FROM Livres WHERE Prix BETWEEN 40 AND 50;

// les enregistrements dans une table qui n'ont pas de correspondances dans une autre :
SELECT table1.* FROM table1 LEFT JOIN table2 ON table1.id=table2.id WHERE table2.id IS NULL;

AND (e.ville_id=v.id OR e.ville_id IS NULL)

SELECT MIN(mee_dat) AS min, MAX(mee_dat) AS max FROM meeting

SELECT field1_index, field2_index FROM test_table WHERE field1_index = '1'
UNION
SELECT field1_index, field2_index FROM test_table WHERE field2_index = '1';

SELECT TRIM(' bar '); -> 'bar'
SELECT TRIM(LEADING 'x' FROM 'xxxbarxxx'); -> 'barxxx'
SELECT TRIM(BOTH 'x' FROM 'xxxbarxxx'); -> 'bar'
SELECT TRIM(TRAILING 'xyz' FROM 'barxxyz'); -> 'barx'

SELECT LEFT(RIGHT(mee_date,4),2) -> 04 (mee_date = 20070412)

// Strange day...

SELECT article, SUBSTRING(MAX(CONCAT(LPAD(price,6,'0'),dealer)),7) AS dealer, 0.00+LEFT(MAX( CONCAT(LPAD(price,6,'0'),dealer)),6) AS price FROM shop GROUP BY article;

INSERT INTO new_table(date_field) SELECT DATEADD(s, timestamp_field, '19700101') FROM old_table

SELECT SUBSTRING(datedeb,1,4) as year


// COMPLEX

SELECT M.titre, M.fichier FROM
(SELECT app_id FROM
	(SELECT id FROM mod_utilisateurs WHERE mod_utilisateurs.boxid='77007629c3e0e146') User
	LEFT JOIN
	(SELECT app_id, box_id FROM apps_relations_box) Rel
	ON User.id=Rel.box_id) App
LEFT JOIN
(SELECT id, titre, fichier FROM mod_apps) M
ON App.app_id=M.id

```

## Manipulating DB ##

```
// Selecting a database:
USE database;

// Listing databases:
SHOW DATABASES;

// Listing tables in a db:
SHOW TABLES;

// Describing the format of a table:
DESCRIBE table;

// Creating a database:
CREATE DATABASE db_name;

// Creating a table:
CREATE TABLE table_name (field1_name TYPE(SIZE), field2_name TYPE(SIZE));
// Ex: mysql> CREATE TABLE pet (name VARCHAR(20), sex CHAR(1), birth DATE);

// Load tab-delimited data into a table:
LOAD DATA LOCAL INFILE "infile.txt" INTO TABLE table_name;
// (Use \n for NULL)

// Inserting one row at a time:
mysql> INSERT INTO table_name VALUES ('MyName', 'MyOwner', '2002-08-31');
// (Use NULL for NULL)

```

## DB Product example ##

// http://www.gsdesign.ro/blog/database-design-example-for-a-configurable-product-eshop/

![http://www.gsdesign.ro/blog/wp-content/uploads/2010/12/screenshot_001.jpeg](http://www.gsdesign.ro/blog/wp-content/uploads/2010/12/screenshot_001.jpeg)

Selecting products
The most important thing is how do we select products, and how do we only show one product for the configurable ones. For this we use GROUP BY.

```
SELECT * FROM `products`
GROUP BY CASE `products`.`configurable` WHEN 'yes' THEN `products`.`id_configuration` ELSE `products`.`id` END
```

Filtering products
The bellow example shows the query for a single filter/feature (with id 38) and 2 values selected:

```
SELECT * FROM `products`
WHERE `id` IN (SELECT id_product FROM `product-features` WHERE `id_feature` = 38 AND `value` IN ('Test value', 'New Value') GROUP BY id_product HAVING COUNT(*) >= 1)
GROUP BY CASE `products`.`configurable` WHEN 'yes' THEN `products`.`id_configuration` ELSE `products`.`id` END
```

Get active filters
After you have a list of filter products you will want to print out a list of filters that are applicable to this list, since not all are available to the current selected products.

```
SELECT `id_feature` FROM `product-features`
WHERE `id_product` IN (SELECT `id` FROM `products` WHERE ___YOUR_CONDITIONS____) GROUP BY `id_feature`
```

Notice that i left out the GROUP BY from the product subselect.

Associate count of products for a filter
Its very useful to show the count of products for a certain value of a filter. The bellow query is how you can select that count for one filter.

```
SELECT *, COUNT(*) AS `count` FROM (
SELECT *
FROM `product-features`
WHERE
`id_product` IN (SELECT `id` FROM `products` WHERE ___YOUR_CONDITIONS____) AND
`id_feature` = 36
) AS `tbl` GROUP BY `value`
```


## MYSQL TO PHP ##

```
mysql_affected_rows($this->db_connect_id)
mysql_close
mysql_connect($this->server, $this->user, $this->password);
mysql_data_seek
mysql_db_name
mysql_errno
mysql_error
mysql_fetch_array
mysql_fetch_assoc
mysql_fetch_field
mysql_fetch_lengths
mysql_fetch_object
mysql_fetch_row
mysql_field_flags
mysql_field_len
mysql_field_name($query_id, $offset);
mysql_field_seek
mysql_field_table
mysql_field_type
mysql_free_result($query_id);
mysql_insert_id
mysql_list_dbs
mysql_list_processes
mysql_list_tables
mysql_num_fields($query_id);
mysql_num_rows($query_id);
mysql_pconnect
mysql_query($query, $this->db_connect_id);
mysql_real_escape_string
mysql_select_db($this->dbname);
```

## PROCESS ##

```
$pList = mysql_query('SHOW PROCESSLIST') or die(mysql_error());
while($process = mysql_fetch_array($pList,MYSQL_ASSOC)) {
    if(intval($process['Time'])>$processTimeLimit) {
        mysql_query('KILL '.$process['Id']) or die(mysql_error());
        echo "<tr>\n" ;
        echo "\t<td>".$process['Id']."</td>\n" ;
        echo "\t<td>".$process['db']."</td>\n" ;
        echo "\t<td>".$process['Time']."</td>\n" ;
        echo "</tr>\n" ;
    }
}
```

## RANDOM ##

```
$REQ = mysql_query("SELECT media_thumb_id,galerie_id FROM Media WHERE galerie_id='$galerie_id_select' ",$db) or die(mysql_error());
$nb = mysql_num_rows($REQ);
if ($nb > 0) {
    //srand ((float)microtime()*1000000);
    $rand_row = rand(1, $nb);
    mysql_data_seek($REQ, $rand_row-1);
    $row = mysql_fetch_array($REQ);
    $media_thumb_id_select = $row["media_thumb_id"];
    $galerie_id_reselect = $row["galerie_id"];
}
```

## DATE ##

cf : http://dev.mysql.com/doc/refman/5.0/fr/date-and-time-functions.html

```
SELECT YEAR('98-02-03');
-> 1998

select * from tbrel_MoviesTv t where DATEPART(DAY, t.tv_dtExibition) = 25 and DATEPART(MONTH, t.tv_dtExibition) = 2 and DATEPART(YEAR, t.tv_dtExibition) = 2007 and '20:52' between tv_hour and tv_hour_out order by tv_hour

SELECT CURRENT_DATE();
MONTH(), DAYOFMONTH(), HOUR(), MINUTE() and SECOND()

SELECT MONTH(NOW()) AS m,
DAYOFMONTH(NOW()) AS d,
HOUR(NOW()) AS h,
MINUTE(NOW()) AS m,
SECOND(NOW()) AS s;

Depuis MySQL 5.0.25, il est aussi possible d'utiliser la configuration locale pour obtenir directement des noms français.

SELECT DATE_FORMAT(now(), '%W %d %M %Y');
-> 'dimanche 04 mars 2007'

la date '28/02/07' est ambigue, car elle peut représenter le 28 février ou bien le 7 février 2028, suivant le format attendu.
set datetime_format = '%d/%m/%y %H:%i:%s';
insert into test_date(datecol) values ('28/02/07 23:12:00');
select * from test_date;

-> '2028-02-07 23:12:00'

--------------------------------------------------------------------------------------------------------

// GET Min/Max date

$D = new Q("
SELECT MIN(actu_date) AS min, MAX(actu_date) AS max
FROM mod_actus
LIMIT 2
");
$maxDate = dateToArray($D->V[0]['max']);
$minDate = dateToArray($D->V[0]['min']);
```

## RECHERCHE SQL BOLEENNE ##

http://developpeur.journaldunet.com/tutoriel/out/051006-mysql-recherche-fulltext-booleenne.shtml

```
SELECT * FROM cuisine WHERE MATCH(ingredients, recette, plat) AGAINST ('>lapin <civet ~rabbit') IN BOOLEAN MODE;
```

Voici les différentes expressions disponibles :

+mot Le mot doit être contenu dans le résultat
-mot Le mot ne doit pas être contenu dans le résultat
~mot Le mot ne doit pas forcément être dans le résultats. Les résultat qui le contiennent ont moins d'importance.
<mot Donne une plus faible importance au mot
>mot Donne une plus grande importance au mot
mot**Le mot commence par...
"mot1 mot2" Les mots doivent se suivre dans cet ordre
( ) Groupement de plusieurs expressions**

## RECHERCHE APPROXIMATIVES ##

Pour utiliser LIKE, il nous faut transformer le mot-clé "exemple" en "%e%x%e%m%p%l%e%":

```
function approx($rech) {
    for ($i = 0; $i < strlen($rech); $i++) {
        $tableau[]=$rech[$i];
    }
    return implode("%", $tableau);
}

$sql_req = "SELECT * FROM dictionnaire WHERE mot LIKE ";
$sql_req .= approx($_POST['maRecherche']);
$sql_req .= ";";

lancer une recherche sur une base sans avoir à se soucier des accents ni dans la chaîne à chercher, ni dans la base stockée:

$recherche = sql_regcase($chaine);
$recherche = ereg_replace("eéèêë", "e", $chaine);
$requetesql = "SELECT mot FROM table WHERE champ REGEXP '$recherche';");
```

## MYSQL TO XML ##

```
function parseQuery($requete) {
    $i=1;
    $str = '<?xml version="1.0" encoding="UTF-8"?>
    $str .= '<result_xml>';
    $result = mysql_query($requete) or die('Erreur SQL !<br>'.$this->s_query.'<br>'.mysql_error());
    if ($result) {
        while($data = mysql_fetch_object($result)) {
            $str .= "<result_".$i.">\n";
            foreach($data as $ident=>$valeur) {
                $str.="<".$ident.">".utf8_encode($valeur)."</".$ident. ">\n";
            }
            $str .="</result_".$i.">\n";
            $i++;
        }
    }
    $str.="</result_xml>";
    echo $str;
}
```

## VRAC ##

```
$sql = "SELECT commandes.*,clients.*
FROM commandes LEFT JOIN clients
ON commandes.clt_id = clients.clt_id
WHERE ( commandes.cmd_date LIKE '%$tab[0]%'
OR commandes.cmd_etat LIKE '%$tab[0]%'
OR clients.clt_nom LIKE '%$tab[0]%'
OR clients.clt_prenom LIKE '%$tab[0]%' ) ";

--------------------------------------------------------------------------------------------------------

CREATE TABLE ref (
ref_id int(8) NOT NULL auto_increment,
ref_commune varchar(150) default NULL,
ref_dep tinyint(2) default NULL,
ref_mo varchar(150) default NULL,
ref_type varchar(150) default NULL,
ref_surface int(8) default NULL,
ref_annee int(4) default NULL,
ref_gmb varchar(150) default NULL,
ref_desc text NOT NULL,
PRIMARY KEY (ref_id)
) TYPE=MyISAM;

--------------------------------------------------------------------------------------------------------

salary DECIMAL(5,2)
In this example, 5 is the precision and 2 is the scale. The precision represents the number of significant decimal digits that will be stored for values, and the scale represents the number of digits that will be stored following the decimal point.

```

## mySQL types ##

```
TINYINT[(M)] [UNSIGNED] [ZEROFILL]
A very small integer. The signed range is -128 to 127. The unsigned range is 0 to 255.
BIT
BOOL
These are synonyms for TINYINT(1).
SMALLINT[(M)] [UNSIGNED] [ZEROFILL]
A small integer. The signed range is -32768 to 32767. The unsigned range is 0 to 65535.
MEDIUMINT[(M)] [UNSIGNED] [ZEROFILL]
A medium-size integer. The signed range is -8388608 to 8388607. The unsigned range is 0 to 16777215.
INT[(M)] [UNSIGNED] [ZEROFILL]
A normal-size integer. The signed range is -2147483648 to 2147483647. The unsigned range is 0 to 4294967295.
INTEGER[(M)] [UNSIGNED] [ZEROFILL]
This is a synonym for INT.
BIGINT[(M)] [UNSIGNED] [ZEROFILL]
A large integer. The signed range is -9223372036854775808 to 9223372036854775807. The unsigned range is 0 to 18446744073709551615. Some things you should be aware of with respect to BIGINT columns:
All arithmetic is done using signed BIGINT or DOUBLE values, so you shouldn't use unsigned big integers larger than 9223372036854775807 (63 bits) except with bit functions! If you do that, some of the last digits in the result may be wrong because of rounding errors when converting the BIGINT to a DOUBLE. MySQL 4.0 can handle BIGINT in the following cases:
Use integers to store big unsigned values in a BIGINT column.
In MIN(big_int_column) and MAX(big_int_column).
When using operators (+, -, *, etc.) where both operands are integers.
You can always store an exact integer value in a BIGINT column by storing it as a string. In this case, MySQL will perform a string-to-number conversion that involves no intermediate double representation.
`-', `+', and `*' will use BIGINT arithmetic when both arguments are integer values! This means that if you multiply two big integers (or results from functions that return integers) you may get unexpected results when the result is larger than 9223372036854775807.

FLOAT(precision) [UNSIGNED] [ZEROFILL]
A floating-point number. precision can be <=24 for a single-precision floating-point number and between 25 and 53 for a double-precision floating-point number. These types are like the FLOAT and DOUBLE types described immediately below. FLOAT(X) has the same range as the corresponding FLOAT and DOUBLE types, but the display size and number of decimals are undefined. In MySQL Version 3.23, this is a true floating-point value. In earlier MySQL versions, FLOAT(precision) always has 2 decimals. Note that using FLOAT may give you some unexpected problems as all calculations in MySQL are done with double precision. See section A.5.6 Solving Problems with No Matching Rows <No_matching_rows.html>. This syntax is provided for ODBC compatibility.
FLOAT[(M,D)] [UNSIGNED] [ZEROFILL]
A small (single-precision) floating-point number. Allowable values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38. If UNSIGNED is specified, negative values are disallowed. The M is the display width and D is the number of decimals. FLOAT without arguments or FLOAT(X) where X <= 24 stands for a single-precision floating-point number.
DOUBLE[(M,D)] [UNSIGNED] [ZEROFILL]
A normal-size (double-precision) floating-point number. Allowable values are -1.7976931348623157E+308 to -2.2250738585072014E-308, 0, and 2.2250738585072014E-308 to 1.7976931348623157E+308. If UNSIGNED is specified, negative values are disallowed. The M is the display width and D is the number of decimals. DOUBLE without arguments or FLOAT(X) where 25 <= X <= 53 stands for a double-precision floating-point number.
DOUBLE PRECISION[(M,D)] [UNSIGNED] [ZEROFILL]
REAL[(M,D)] [UNSIGNED] [ZEROFILL]
These are synonyms for DOUBLE.
DECIMAL[(M[,D])] [UNSIGNED] [ZEROFILL]
An unpacked floating-point number. Behaves like a CHAR column: ``unpacked'' means the number is stored as a string, using one character for each digit of the value. The decimal point and, for negative numbers, the `-' sign, are not counted in M (but space for these is reserved). If D is 0, values will have no decimal point or fractional part. The maximum range of DECIMAL values is the same as for DOUBLE, but the actual range for a given DECIMAL column may be constrained by the choice of M and D. If UNSIGNED is specified, negative values are disallowed. If D is omitted, the default is 0. If M is omitted, the default is 10. Prior to MySQL Version 3.23, the M argument must include the space needed for the sign and the decimal point.
DEC[(M[,D])] [UNSIGNED] [ZEROFILL]
NUMERIC[(M[,D])] [UNSIGNED] [ZEROFILL]
These are synonyms for DECIMAL.
DATE
A date. The supported range is '1000-01-01' to '9999-12-31'. MySQL displays DATE values in 'YYYY-MM-DD' format, but allows you to assign values to DATE columns using either strings or numbers. See section 6.2.2.2 The DATETIME, DATE, and TIMESTAMP Types <DATETIME.html>.
DATETIME
A date and time combination. The supported range is '1000-01-01 00:00:00' to '9999-12-31 23:59:59'. MySQL displays DATETIME values in 'YYYY-MM-DD HH:MM:SS' format, but allows you to assign values to DATETIME columns using either strings or numbers. See section 6.2.2.2 The DATETIME, DATE, and TIMESTAMP Types <DATETIME.html>.
TIMESTAMP[(M)]
A timestamp. The range is '1970-01-01 00:00:00' to sometime in the year 2037. In MySQL 4.0 and earlier, TIMESTAMP values are displayed in YYYYMMDDHHMMSS, YYMMDDHHMMSS, YYYYMMDD, or YYMMDD format, depending on whether M is 14 (or missing), 12, 8, or 6, but allows you to assign values to TIMESTAMP columns using either strings or numbers. From MySQL 4.1, TIMESTAMP is returned as a string with the format 'YYYY-MM-DD HH:MM:SS'. If you want to have this as a number you should add +0 to the timestamp column. Different timestamp lengths are not supported. From version 4.0.12, the --new option can be used to make the server behave as in version 4.1. A TIMESTAMP column is useful for recording the date and time of an INSERT or UPDATE operation because it is automatically set to the date and time of the most recent operation if you don't give it a value yourself. You can also set it to the current date and time by assigning it a NULL value. See section 6.2.2 Date and Time Types <Date_and_time_types.html>. The M argument affects only how a TIMESTAMP column is displayed; its values always are stored using 4 bytes each. Note that TIMESTAMP(M) columns where M is 8 or 14 are reported to be numbers while other TIMESTAMP(M) columns are reported to be strings. This is just to ensure that one can reliably dump and restore the table with these types! See section 6.2.2.2 The DATETIME, DATE, and TIMESTAMP Types <DATETIME.html>.
TIME
A time. The range is '-838:59:59' to '838:59:59'. MySQL displays TIME values in 'HH:MM:SS' format, but allows you to assign values to TIME columns using either strings or numbers. See section 6.2.2.3 The TIME Type <TIME.html>.
YEAR[(2|4)]
A year in 2- or 4-digit format (default is 4-digit). The allowable values are 1901 to 2155, 0000 in the 4-digit year format, and 1970-2069 if you use the 2-digit format (70-69). MySQL displays YEAR values in YYYY format, but allows you to assign values to YEAR columns using either strings or numbers. (The YEAR type is unavailable prior to MySQL Version 3.22.) See section 6.2.2.4 The YEAR Type <YEAR.html>.
[NATIONAL] CHAR(M) [BINARY]
A fixed-length string that is always right-padded with spaces to the specified length when stored. The range of M is 0 to 255 characters (1 to 255 prior to MySQL Version 3.23). Trailing spaces are removed when the value is retrieved. CHAR values are sorted and compared in case-insensitive fashion according to the default character set unless the BINARY keyword is given. NATIONAL CHAR (or its equivalent short form, NCHAR) is the SQL-99 way to define that a CHAR column should use the default CHARACTER set. This is the default in MySQL. CHAR is a shorthand for CHARACTER. MySQL allows you to create a column of type CHAR(0). This is mainly useful when you have to be compliant with some old applications that depend on the existence of a column but that do not actually use the value. This is also quite nice when you need a column that only can take 2 values: A CHAR(0), that is not defined as NOT NULL, will occupy only one bit and can take only 2 values: NULL or "". See section 6.2.3.1 The CHAR and VARCHAR Types <CHAR.html>.
CHAR
This is a synonym for CHAR(1).
[NATIONAL] VARCHAR(M) [BINARY]
A variable-length string. Note: trailing spaces are removed when the value is stored (this differs from the SQL-99 specification). The range of M is 0 to 255 characters (1 to 255 prior to MySQL Version 4.0.2). VARCHAR values are sorted and compared in case-insensitive fashion unless the BINARY keyword is given. See section 6.5.3.1 Silent Column Specification Changes <Silent_column_changes.html>. VARCHAR is a shorthand for CHARACTER VARYING. See section 6.2.3.1 The CHAR and VARCHAR Types <CHAR.html>.
TINYBLOB
TINYTEXT
A BLOB or TEXT column with a maximum length of 255 (2^8 - 1) characters. See section 6.5.3.1 Silent Column Specification Changes <Silent_column_changes.html>. See section 6.2.3.2 The BLOB and TEXT Types <BLOB.html>.
BLOB
TEXT
A BLOB or TEXT column with a maximum length of 65535 (2^16 - 1) characters. See section 6.5.3.1 Silent Column Specification Changes <Silent_column_changes.html>. See section 6.2.3.2 The BLOB and TEXT Types <BLOB.html>.
MEDIUMBLOB
MEDIUMTEXT
A BLOB or TEXT column with a maximum length of 16777215 (2^24 - 1) characters. See section 6.5.3.1 Silent Column Specification Changes <Silent_column_changes.html>. See section 6.2.3.2 The BLOB and TEXT Types <BLOB.html>.
LONGBLOB
LONGTEXT
A BLOB or TEXT column with a maximum length of 4294967295 (2^32 - 1) characters. See section 6.5.3.1 Silent Column Specification Changes <Silent_column_changes.html>. Note that because the server/client protocol and MyISAM tables has currently a limit of 16M per communication packet / table row, you can't yet use this the whole range of this type. See section 6.2.3.2 The BLOB and TEXT Types <BLOB.html>.
ENUM('value1','value2',...)
An enumeration. A string object that can have only one value, chosen from the list of values 'value1', 'value2', ..., NULL or the special "" error value. An ENUM can have a maximum of 65535 distinct values. See section 6.2.3.3 The ENUM Type <ENUM.html>.
SET('value1','value2',...)
A set. A string object that can have zero or more values, each of which must be chosen from the list of values 'value1', 'value2', ... A SET can have a maximum of 64 members. See section 6.2.3.4 The SET Type <SET.html>.
```