<?php

/* Calendar Plugin by Marko Štamcar (copyleft) 2005 */

############################## user editable settings  ###################################
##########################################################################################

$width = "154px"; // calendar table width - can be in px or %
$cell_width = "22px"; // day cell width
$legend_height = "20px"; // day of the week legend width

$entry_color = "#FFFFFF"; // day cell with some blog entries font color 
$entry_background_color = "#CCCCCC"; // day cell with some blog entries background color

$today_color = "#FFFFFF"; // today cell font color 
$today_background_color = "#000000"; // today cell background color

############################## do not edit below this line ###############################
##########################################################################################

function calendar($url,$class='') {

	global $width;
	global $cell_width;
	global $legend_height;
	global $entry_color;
	global $entry_background_color;
	global $today_color;
	global $today_background_color;
	global $l_calendar;
	global $blog_script;
	global $lang;
	global $mysql_table;
	
	if ($_GET['date'] != '') {
		$MyDate = intval($_GET['date']);
		$year = substr($MyDate,0,4);
		$month = substr($MyDate,4,-2);
	} else {
		$month = date("n");
		$year = date("Y");
	}
	
	$prev = $month - 1;
	$next = $month + 1;
	$yearP = $year;
	$yearN = $year;
	if ($prev == "0") { $yearP--; $prev = "12"; }
	if ($prev < 10) $prev = '0'.$prev;
	if ($next == "13") { $yearN++; $next = "1"; }
	if ($next < 10) $next = '0'.$next;

	$transform_month = array("1","2","3","4","5","6","7","8","9","10","11","12");
	$into_month = array('Janvier','F&eacute;vrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','D&eacute;cembre');
	
	for ($l = 0; $l < 12; $l++) {
		if ($month == $transform_month[$l]) {
			$month_word = $into_month[$l];
		}
	}

	$output .= '<table style="width: '.$width.'; height: '.$legend_height.'" class="'.$class.'">
	<tr>
		<!--<td style="width: 10%; text-align: left;"><a href="'.$url.'&date='.$yearP.$prev.'00">&laquo;</a></td>-->
		<td colspan="7"><b>'.strtoupper($month_word).' '.$year.'</b></td>
		<!--<td style="width: 10%; text-align: right;"><a href="'.$url.'&date='.$yearN.$next.'00">&raquo;</a></td>-->
	</tr>
	<tr align="center">
		<td><b>L</b></td>
		<td><b>M</b></td>
		<td><b>M</b></td>
		<td><b>J</b></td>
		<td><b>V</b></td>
		<td><b>S</b></td>
		<td><b>D</b></td>
	</tr>
	<tr align="center">';
	
	$no_days = date("t", mktime(0, 0, 0, $month, 1, $year));
	$first_day = date("w", mktime(0, 0, 0, $month, 1, $year));
	$today_day = date("j");
	$today_month = date("n");
	$today_year = date("Y");
	
	if ($first_day == "0") $first_day = "7";
	
	for ($i = 0; $i < $first_day-1; $i++) $output .= '<td style="width:'.$cell_width.';">&nbsp;</td>';

	for ($i = 1; $i <= $no_days; $i++) {
		//$result = mysql_query("SELECT id FROM blog WHERE DAYOFMONTH(timestamp)='$i' AND MONTH(timestamp) = '$month' AND YEAR(timestamp) = '$year' ORDER BY id DESC;");
		$selectDate = $year.$month.($i<10?'0'.$i:$i);
		$result = mysql_query("SELECT id FROM blog WHERE datepubli='$selectDate' LIMIT 1 ");
		$num = mysql_num_rows($result);
		//$res = mysql_fetch_array($result);
	
		if ($first_day == "8") { $first_day = "1"; $output .= '<tr style="width: '.$width.';" align="center">'; }
		if ($i < 10) $space = ' '; else $space = '';
	
		if ($i == $today_day && $month == $today_month && $year == $today_year) 
			$style = 'background-color: '.$today_background_color.'; color: '.$today_color.'; font-weight: bold;';
		else $style = '';
		if (intval($_GET['date']) == $selectDate) $style .= 'border:1px solid #000000;';
		
		if ($num < 1) $output .= '<td style="'.$style.' width: '.$cell_width.';">'.$i.'</td>';
		else $output .= '<td style="background-color:'.$entry_background_color.';'.$style.'width: '.$cell_width.';color: '.$entry_color.';"><a href="'.$url.'&date='.$selectDate.'" style="color: '.$today_color.'" title="Voir le sujet posté à cette date">'.$i.'</a></td>';
		
		if ($first_day == "7") $output .= '</tr>';
		$first_day++;
	}
	
	$output .= '</table>';
	
	return $output;

}
?>