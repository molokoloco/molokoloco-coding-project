<?
require('admin/lib/racine.php');
require('admin/lib/class/class_simplepie/simplepie.inc');

$feed = (isset($_GET['feed']) ? clean(urldecode($_GET['feed'])) : 'http://picasaweb.google.fr/data/feed/base/user/molokoloco?kind=album&alt=rss&hl=fr&access=public');
$max = (isset($_GET['max']) ? intval($_GET['max']) : 7);
$ajax = ($_GET['ajax'] == 1 ? 1 : 0);

// Parse it
$F =& new SimplePie();
$F->set_feed_url($feed);
$F->enable_order_by_date(false);
if ($ajax) $F->set_output_encoding('UTF-8');
//$F->set_image_handler('./image.php');
//$F->set_favicon_handler('./image.php');
### 
$F->enable_cache(false);
//$F->set_cache_location ('./cache');
//$F->set_cache_duration(3600);
/*$F->set_javascript('embed'); // Will load <script src="?embed" type="text/javascript"></script> when $enclosure->embed() is called.*/
// $tags = array('base', 'blink', 'body', 'doctype', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'input', 'marquee', 'meta', 'noscript', 'object', 'param', 'script', 'style')
$F->strip_htmltags(array_merge($F->strip_htmltags, array('div', 'table', 'tbody', 'tr', 'td', 'span')));
//$F->encode_instead_of_strip(false);


$F->init();
$F->handle_content_type();

if (!$ajax) { 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<? echo ($F->get_encoding()) ? $F->get_encoding() : 'utf-8'; ?>"/>
	<meta http-equiv="imagetoolbar" content="no"/>
	<meta http-equiv="content-language" content="<?=$lg;?>"/>
	<title><?=htmlentities($F->get_title());?></title>
	<meta name="description" content="<?=htmlentities(aff($meta_description));?>"/>
	<meta name="keywords" content="<?=htmlentities(aff($meta_key));?>"/>
	<meta name="robots" content="index, follow, all"/>
	<meta name="revisit_after" content="7Days"/>
	<meta name="identifier-url" content="<?=$WWW;?>"/>
	<meta name="author" content="Julien Gu&eacute;zennec aka molokoloco 2008"/>	
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
	html, body {
		text-align:left;
		padding:4px;
	}
	body, p, div {
		color:#666666;
		font-size:11px;
	}
	</style>
</head>

<body>
<? } ?>

	<style type="text/css">
	#channel h1 {
		font-size:14px;
	}
	#channel h2 {
		font-size:13px;
	}
	#channel h3 {
		font-size:12px;
	}
	</style>
	
	<!--<h1><a href="<?=htmlspecialchars($F->subscribe_url());?>" target="_blank"><?=$F->get_title();?></a> (<?=$F->get_item_quantity(); ?>)</h1>
	<div class="dsep">&nbsp;</div>-->
	<div id="channel">
		<?
		 if ($F->data){
			$items = $F->get_items(0, $max);
			foreach($items as $i=>$item) {
				$enclosure = $item->get_enclosure(0);
				if (!$favicon = $F->get_favicon()) $favicon = './images/feed/favicons/alternate.png';
				//$feedFrom = $item->get_feedsource();
				//$feedSource = $feedFrom->get_title();
				?>
				<div class="item">
					<img src="<?=$favicon;?>" alt="Favicon" class="favicon" align="left" hspace="4" />
					<h2><?=$item->get_date('d/m/Y'); ?> - <a href="<?=$item->get_permalink(); ?>" target="_blank" title="<?=$feedSource;?>"><?=$item->get_title(); ?></a></h2>
					<?
					
					$c = $item->get_content();
					$c = str_replace('<br>', '<br />', $c);
					
					$c = reduceImgInHtml($c, '120');
					//$c = maskImgInHtml($c, '250');
					
					$c = wrap($c, 50);
					
					echo $c;

					/*if ($enclosure->get_type()) {
						// Use the embed() method to embed the enclosure into the page inline.
						echo '<div align="center">';
						echo $enclosure->embed(array(
							'audio' => './images/feed/place_audio.png',
							'video' => './images/feed/place_video.png',
							'mediaplayer' => './images/feed/mediaplayer.swf',
							'alt' => '<img src="./images/feed/mini_podcast.png" class="download" border="0" title="Download the Podcast (' . $enclosure->get_extension() . '; ' . $enclosure->get_size() . ' MB)" />',
							'altclass' => 'download'
						));
						echo ' (' . $enclosure->get_type();
						if ($enclosure->get_size()) echo '; ' . $enclosure->get_size() . ' MB';								
						echo ')';
						echo '</div>';
						
						if ($enclosure && $link = $enclosure->get_link()) {
							$type = $enclosure->get_type();
							echo '<a href="'.$link.'" target="_blank">';
							if ($type == 'image/jpeg' || $type == 'image/gif' || $type == 'image/png') {
								list($w, $h, $type, $attr) = @getimagesize($link); 
								if ($w > $h) { $h = floor( ($h/$w)*120); $w = 120; }
								else { $w = floor(($w/$h)*90); $h = 90; }
								echo '<img src="'.$link.'" width="'.$w.'" height="'.$h.'" border="0" align="left" hspace="6"/>';
							}
							else echo  $enclosure->get_link();
							echo '</a>';
						}
					}*/
					?>
				</div>
				<? if ($i < count($items)-1) { ?>
				<div class="spacer"><div class="dsep">&nbsp;</div></div>
				<? } 
			} 
		} 
		?>
	</div>
	<!--Powered by <?=SIMPLEPIE_LINKBACK; ?>, a product of <a href="http://www.skyzyx.com">Skyzyx Technologies</a>-->
	
<? if (!$ajax) { ?>
</body>
</html>
<? } ?>
