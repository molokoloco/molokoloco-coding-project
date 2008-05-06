<? header("Content-type: text/css");
require_once('./lib/racine.php');
require_once('../css/styles.css'); ?>

/* OVERWRITE SITE STYLE WHITE BACKGROUND */
body { background: #FFFFFF; font-size:11px; }

h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, a.h1, a.h2, a.h3, a.h4, a.h5, a.h6 {
	color:#047CC4;
	font-family:"Century Gothic","Trebuchet MS",Arial,Helvetica,sans-serif;
	font-size-adjust:none;
	font-style:normal;
	font-variant:normal;
	font-weight:400;
	letter-spacing:-1px;
	line-height:26px;
	margin:10px 0;
	page-break-after:avoid;
}

h1, h1 a, .h1 { font-size:30px; }
h2, h2 a, .h2 { font-size:24px; }
h3, h3 a, .h3 { font-size:18px; }
h4, h4 a, .h4 { font-size:12px; }
h5, h5 a, .h5 { font-size:10px; }