Best CSS ressources here http://css-tricks.com/snippets/ but... i have stollen somes snippets here : [http://www.hongkiat.com/blog/css-snippets-for-designers/](http://www.hongkiat.com/blog/css-snippets-for-designers/) Shame on me for eternity, but i know well all this things, only forget to write it... nice to wrap them here...


---


**Sommaire :**




---


# CSS Snippets #

With so many <a href='http://www.hongkiat.com/blog/web-design-trend-2013/‎'>new trends</a> advancing every year it can be difficult keeping up with the industry. Website designers and frontend developers have been deeply ingrained into the newer <a href='http://www.hongkiat.com/blog/tag/css3/‎'>CSS3 properties</a>, determining the ultimate <a href='http://www.hongkiat.com/blog/complete-guide-to-cross-browser-compatibility-check/'>browser support</a> and quirky hacks. But there are also brilliant CSS2 code snippets which have been unrequited in comparison.

<img src='http://media02.hongkiat.com/css-snippets-for-designers/css-snippets.jpg' width='600' height='369'>

For this article I want to present<strong> 50 handy CSS2/CSS3 code snippets</strong> for any web professional. These are perfect for storing in your development IDE of choice, or even keeping them saved in a small CSS file. Either way I am sure designers &amp; developers can find some use for some of the snippets in this collection.<br>
<br>
<strong>Recommended Reading:</strong> <a href='http://www.hongkiat.com/blog/20-useful-css-tips-for-beginners/‎'>20 Useful CSS Tips For Beginners</a>

<h2>1. CSS Resets</h2>

<pre><code>	html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video { <br>
	 margin: 0; <br>
	 padding: 0; <br>
	 border: 0; <br>
	 font-size: 100%; <br>
	 font: inherit; <br>
	 vertical-align: baselinebaseline; <br>
	 outline: none; <br>
	 -webkit-box-sizing: border-box; <br>
	 -moz-box-sizing: border-box; <br>
	 box-sizing: border-box; <br>
	} <br>
	html { height: 101%; } <br>
	body { font-size: 62.5%; line-height: 1; font-family: Arial, Tahoma, sans-serif; } <br>
	 <br>
	article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section { display: block; } <br>
	ol, ul { list-style: none; } <br>
	 <br>
	blockquote, q { quotes: none; } <br>
	blockquote:before, blockquote:after, q:before, q:after { content: ''; content: none; } <br>
	strong { font-weight: bold; } <br>
	 <br>
	table { border-collapse: collapse; border-spacing: 0; } <br>
	img { border: 0; max-width: 100%; } <br>
	 <br>
	p { font-size: 1.2em; line-height: 1.0em; color: #333; }<br>
</code></pre>

Basic CSS browser resets are some of the most common snippets you’ll find online. This is a customized snippet by myself which is based off <a href='http://meyerweb.com/eric/tools/css/reset/'>Eric Meyer’s reset codes</a>. I have included a bit for responsive images and set all core elements to <strong>border-box</strong>, keeping margins and padding measurements aligned properly.<br>
<br>
<h2>2. Classic CSS Clearfix</h2>

<pre><code>.clearfix:after { content: "."; display: block; clear: both; visibility: hidden; line-height: 0; height: 0; }<br>
.clearfix { display: inline-block; }<br>
 <br>
html[xmlns] .clearfix { display: block; }<br>
* html .clearfix { height: 1%; }<br>
</code></pre>

This clearfix code has been around the Web for years circulating amongst savvy web developers. You should apply this class onto a container which holds floating elements. This will ensure any content which comes afterwards <strong>will not float but instead be pushed down and cleared</strong>.<br>
<br>
<h2>3. 2011 Updated Clearfix</h2>

<pre><code>.clearfix:before, .container:after { content: ""; display: table; }<br>
.clearfix:after { clear: both; }<br>
<br>
/* IE 6/7 */<br>
.clearfix { zoom: 1; }<br>
</code></pre>

From what I can tell there isn’t a major difference in rendering between this newer version and the classic version. Both of these classes will effectively clear your floats, and they should work in all modern browsers and even legacy Internet Explorer 6-8.<br>
<br>
<h2>4. Cross-Browser Transparency</h2>

<pre><code>.transparent {<br>
    filter: alpha(opacity = 50); /* internet explorer */<br>
    -khtml-opacity: 0.5;      /* khtml, old safari */<br>
    -moz-opacity: 0.5;       /* mozilla, netscape */<br>
    opacity: 0.5;           /* fx, safari, opera */<br>
}<br>
</code></pre>

<a href='http://perishablepress.com/cross-browser-transparency-via-css/'>Code Source</a>

Some of the newer CSS3 properties have pampered us into thinking they may be applied everywhere. Unfortunately opacity is one such example where CSS still requires some minor updates. <strong>Appending the filter property</strong> should handle any older versions of IE with grace.<br>
<br>
<h2>5. CSS Blockquote Template</h2>

<pre><code>blockquote {<br>
    background: #f9f9f9;<br>
    border-left: 10px solid #ccc;<br>
    margin: 1.5em 10px;<br>
    padding: .5em 10px;<br>
    quotes: "\201C""\201D""\2018""\2019";<br>
}<br>
blockquote:before {<br>
    color: #ccc;<br>
    content: open-quote;<br>
    font-size: 4em;<br>
    line-height: .1em;<br>
    margin-right: .25em;<br>
    vertical-align: -.4em;<br>
}<br>
blockquote p {<br>
    display: inline;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/simple-and-nice-blockquote-styling/'>Code Source</a>


Not everybody needs to use blockquotes inside their website. But I feel these are an excellent HTML element for <strong>separating quoted or repeated content within blogs or webpages</strong>. This basic chunk of CSS offers a default style for your blockquotes so they don’t appear as drab and bland.<br>
<br>
<h2>6. Individual Rounded Corners</h2>

<pre><code>#container {<br>
    -webkit-border-radius: 4px 3px 6px 10px;<br>
    -moz-border-radius: 4px 3px 6px 10px;<br>
    -o-border-radius: 4px 3px 6px 10px;<br>
    border-radius: 4px 3px 6px 10px;<br>
}<br>
<br>
/* alternative syntax broken into each line */<br>
#container {<br>
    -webkit-border-top-left-radius: 4px;<br>
    -webkit-border-top-right-radius: 3px;<br>
    -webkit-border-bottom-right-radius: 6px;<br>
    -webkit-border-bottom-left-radius: 10px;<br>
    <br>
    -moz-border-radius-topleft: 4px;<br>
    -moz-border-radius-topright: 3px;<br>
    -moz-border-radius-bottomright: 6px;<br>
    -moz-border-radius-bottomleft: 10px;<br>
}<br>
</code></pre>

Most developers are familiar with the CSS3 rounded corners syntax. But how would you go about <strong>setting different values for each of the corners</strong>? Save this code snippet and you should never run into the problem again! I’ve included both a condensed version and a longer base with each corner radius broken down into a different property.<br>
<br>
<h2>7. General Media Queries</h2>

<pre><code>/* Smartphones (portrait and landscape) ----------- */<br>
@media only screen <br>
and (min-device-width : 320px) and (max-device-width : 480px) {<br>
  /* Styles */<br>
}<br>
<br>
/* Smartphones (landscape) ----------- */<br>
@media only screen and (min-width : 321px) {<br>
  /* Styles */<br>
}<br>
<br>
/* Smartphones (portrait) ----------- */<br>
@media only screen and (max-width : 320px) {<br>
  /* Styles */<br>
}<br>
<br>
/* iPads (portrait and landscape) ----------- */<br>
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) {<br>
  /* Styles */<br>
}<br>
<br>
/* iPads (landscape) ----------- */<br>
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {<br>
  /* Styles */<br>
}<br>
<br>
/* iPads (portrait) ----------- */<br>
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {<br>
  /* Styles */<br>
}<br>
<br>
/* Desktops and laptops ----------- */<br>
@media only screen and (min-width : 1224px) {<br>
  /* Styles */<br>
}<br>
<br>
/* Large screens ----------- */<br>
@media only screen and (min-width : 1824px) {<br>
  /* Styles */<br>
}<br>
<br>
/* iPhone 4 ----------- */<br>
@media only screen and (-webkit-min-device-pixel-ratio:1.5), only screen and (min-device-pixel-ratio:1.5) {<br>
  /* Styles */<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/media-queries-for-standard-devices/'>Code Source</a>

This is an excellent template which you can find on <a href='http://css-tricks.com/snippets/css/media-queries-for-standard-devices/'>CSS-Tricks</a> for other bits and pieces of media queries. However I’ve copied their example in full which includes tons of real mobile devices. These codes will even target retina-based devices using <pre><code>min-device-pixel-ratio</code></pre>.<br>
<br>
<h2>8. Modern Font Stacks</h2>

<pre><code>/* Times New Roman-based serif */<br>
font-family: Cambria, "Hoefler Text", Utopia, "Liberation Serif", "Nimbus Roman No9 L Regular", Times, "Times New Roman", serif;<br>
<br>
/* A modern Georgia-based serif */<br>
font-family: Constantia, "Lucida Bright", Lucidabright, "Lucida Serif", Lucida, "DejaVu Serif," "Bitstream Vera Serif", "Liberation Serif", Georgia, serif;<br>
<br>
/*A more traditional Garamond-based serif */<br>
font-family: "Palatino Linotype", Palatino, Palladio, "URW Palladio L", "Book Antiqua", Baskerville, "Bookman Old Style", "Bitstream Charter", "Nimbus Roman No9 L", Garamond, "Apple Garamond", "ITC Garamond Narrow", "New Century Schoolbook", "Century Schoolbook", "Century Schoolbook L", Georgia, serif;<br>
<br>
/*The Helvetica/Arial-based sans serif */<br>
font-family: Frutiger, "Frutiger Linotype", Univers, Calibri, "Gill Sans", "Gill Sans MT", "Myriad Pro", Myriad, "DejaVu Sans Condensed", "Liberation Sans", "Nimbus Sans L", Tahoma, Geneva, "Helvetica Neue", Helvetica, Arial, sans-serif;<br>
<br>
/*The Verdana-based sans serif */<br>
font-family: Corbel, "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", "Bitstream Vera Sans", "Liberation Sans", Verdana, "Verdana Ref", sans-serif;<br>
<br>
/*The Trebuchet-based sans serif */<br>
font-family: "Segoe UI", Candara, "Bitstream Vera Sans", "DejaVu Sans", "Bitstream Vera Sans", "Trebuchet MS", Verdana, "Verdana Ref", sans-serif;<br>
<br>
/*The heavier “Impact” sans serif */<br>
font-family: Impact, Haettenschweiler, "Franklin Gothic Bold", Charcoal, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", sans-serif;<br>
<br>
/*The monospace */<br>
font-family: Consolas, "Andale Mono WT", "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", "DejaVu Sans Mono", "Bitstream Vera Sans Mono", "Liberation Mono", "Nimbus Mono L", Monaco, "Courier New", Courier, monospace;<br>
</code></pre>

<a href='http://www.sitepoint.com/eight-definitive-font-stacks/'>Code Source</a>

It is difficult brainstorming your own CSS font stacks for designing new webpages. I hope this snippet may alleviate some torture and give you a few templates for getting started. If you want to find more examples check out <a href='http://cssfontstack.com/'>CSS Font Stacks</a> which is one of my favorite resources.<br>
<br>
<h2>9. Custom Text Selection</h2>

<pre><code>::selection { background: #e2eae2; }<br>
::-moz-selection { background: #e2eae2; }<br>
::-webkit-selection { background: #e2eae2; }<br>
</code></pre>

Some newer web browsers will allow you to define the highlight color on your webpage. <strong>This is set to light blue by default</strong>, but you can setup any color value which tickles your fancy. This snippet includes the typical <pre><code>::selection</code></pre> target along with vendor prefixes for Webkit and Mozilla.<br>
<br>
<h2>10. Hiding H1 Text for Logo</h2>

<pre><code>h1 {<br>
    text-indent: -9999px;<br>
    margin: 0 auto;<br>
    width: 320px;<br>
    height: 85px;<br>
    background: transparent url("images/logo.png") no-repeat scroll;<br>
}<br>
</code></pre>

I first noticed this technique being implemented on the old <a href='http://web.archive.org/web/20080229090645/http://digg.com/'>Digg layout</a>. You can setup an H1 tag which also has your website’s name in plaintext for SEO purposes. But using CSS we can <strong>move this text so it isn’t visible, and replace it with a custom logo image</strong>.<br>
<br>
<h2>11. Polaroid Image Border</h2>

<pre><code>img.polaroid {<br>
    background:#000; /*Change this to a background image or remove*/<br>
    border:solid #fff;<br>
    border-width:6px 6px 20px 6px;<br>
    box-shadow:1px 1px 5px #333; /* Standard blur at 5px. Increase for more depth */<br>
    -webkit-box-shadow:1px 1px 5px #333;<br>
    -moz-box-shadow:1px 1px 5px #333;<br>
    height:200px; /*Set to height of your image or desired div*/<br>
    width:200px; /*Set to width of your image or desired div*/<br>
}<br>
</code></pre>

<a href='http://www.smipple.net/snippet/kettultim/Polaroid%20Image%20Border%20-%20CSS3'>Code Source</a>

Applying this basic snippet will allow you to implement <strong>.polaroid</strong> classes onto your images. This will <strong>create the old photo-style effect with a large white border and some slight box shadows</strong>. You’ll want to update the width/height values to match that of your image dimensions and website layout.<br>
<br>
<h2>12. Anchor Link Pseudo Classes</h2>

<pre><code>a:link { color: blue; }<br>
a:visited { color: purple; }<br>
a:hover { color: red; }<br>
a:active { color: yellow; }<br>
</code></pre>

<a href='http://www.ahrefmagazine.com/web-design/30-useful-css-snippets-for-developers'>Code Source</a>

Most CSS developers know about the anchor link styles and <pre><code>:hover</code></pre> effects. But I wanted to include this small code snippet as a reference for newcomers. These are the four default states for an anchor link, and also a few other HTML elements. Keep this handy until you can memorize some of the more obscure ones.<br>
<br>
<h2>13. Fancy CSS3 Pull-Quotes</h2>

<pre><code>.has-pullquote:before {<br>
	/* Reset metrics. */<br>
	padding: 0;<br>
	border: none;<br>
	<br>
	/* Content */<br>
	content: attr(data-pullquote);<br>
	<br>
	/* Pull out to the right, modular scale based margins. */<br>
	float: right;<br>
	width: 320px;<br>
	margin: 12px -140px 24px 36px;<br>
	<br>
	/* Baseline correction */<br>
	position: relative;<br>
	top: 5px;<br>
	<br>
	/* Typography (30px line-height equals 25% incremental leading) */<br>
	font-size: 23px;<br>
	line-height: 30px;<br>
}<br>
<br>
.pullquote-adelle:before {<br>
	font-family: "adelle-1", "adelle-2";<br>
	font-weight: 100;<br>
	top: 10px !important;<br>
}<br>
<br>
.pullquote-helvetica:before {<br>
	font-family: "Helvetica Neue", Arial, sans-serif;<br>
	font-weight: bold;<br>
	top: 7px !important;<br>
}<br>
<br>
.pullquote-facit:before {<br>
	font-family: "facitweb-1", "facitweb-2", Helvetica, Arial, sans-serif;<br>
	font-weight: bold;<br>
	top: 7px !important;<br>
}<br>
</code></pre>

<a href='http://miekd.com/articles/pull-quotes-with-html5-and-css/'>Code Source</a>


Pull-quotes are different from blockquotes in that they appear off to the side of your blog or news article. These often reference quoted text from the article, and so they appear slightly different than blockquotes. This default class has some basic properties along with 3 unique font families to choose from.<br>
<br>
<h2>14. Fullscreen Backgrounds with CSS3</h2>

<pre><code>html { <br>
    background: url('images/bg.jpg') no-repeat center center fixed; <br>
    -webkit-background-size: cover;<br>
    -moz-background-size: cover;<br>
    -o-background-size: cover;<br>
    background-size: cover;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/perfect-full-page-background-image/'>Code Source</a>

I should note that this code will not work properly in older browsers which do not support CSS3 syntax. However if you’re looking for a quick solution and don’t care about legacy support, this is the best chunk of code you’ll find! <strong>Great for adding big photographs into the background of your website while keeping them resizable and fixed as you scroll</strong>.<br>
<br>
<h2>15. Vertically Centered</h2>

.center {<br>
<blockquote>position: absolute;<br>
left: 50%;<br>
top: 50%;<br>
transform: translate(-50%, -50%); /<b>Yep!</b>/<br>
width: 48%;<br>
height: 59%;<br>
}</blockquote>

<h2>15b. Vertically Centered Content</h2>

<pre><code>.container {<br>
    min-height: 6.5em;<br>
    display: table-cell;<br>
    vertical-align: middle;<br>
}<br>
</code></pre>

<a href='http://www.w3.org/Style/Examples/007/center'>Code Source</a>

Using the margin: 0 auto technique it is very easy to embed content into the horizontal center of your page. However vertical content is a lot harder, especially considering scrollbars and other methods. But this is a pure CSS solution which should work flawlessly without JavaScript.<br>
<br>
<h2>16. Force Vertical Scrollbars</h2>

<pre><code>html { height: 101% }<br>
</code></pre>

When your page content doesn’t fill the entire height of your browser window then you don’t end up getting any scrollbars. However resizing will force them to appear and append an extra 10-15 pixels to the width of your window, pushing over your webpage content. This snippet will ensure your <strong>HTML element is always just a little bit higher than the browser which forces scrollbars to stay in place at all times.</strong>

<h2>17. CSS3 Gradients Template</h2>

<pre><code>#colorbox {<br>
    background: #629721;<br>
    background-image: -webkit-gradient(linear, left top, left bottom, from(#83b842), to(#629721));<br>
    background-image: -webkit-linear-gradient(top, #83b842, #629721);<br>
    background-image: -moz-linear-gradient(top, #83b842, #629721);<br>
    background-image: -ms-linear-gradient(top, #83b842, #629721);<br>
    background-image: -o-linear-gradient(top, #83b842, #629721);<br>
    background-image: linear-gradient(top, #83b842, #629721);<br>
}<br>
</code></pre>

CSS3 gradients are another wondrous  part of the newer specifications. Many of the vendor prefixes are difficult to memorize, so this code snippet should save you a bit of time on each project.<br>
<br>
<h2>18. @font-face Template</h2>

<pre><code>@font-face {<br>
    font-family: 'MyWebFont';<br>
    src: url('webfont.eot'); /* IE9 Compat Modes */<br>
    src: url('webfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */<br>
    url('webfont.woff') format('woff'), /* Modern Browsers */<br>
    url('webfont.ttf')  format('truetype'), /* Safari, Android, iOS */<br>
    url('webfont.svg#svgFontName') format('svg'); /* Legacy iOS */<br>
}<br>
	<br>
body {<br>
    font-family: 'MyWebFont', Arial, sans-serif;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/using-font-face/'>Code Source</a>

Here is another bit of CSS3 code which isn’t the easiest to memorize. Using @font-face you may embed your own TTF/OTF/SVG/WOFF files into your website and generate custom font families. Use this template as a base example for your own projects in the future.<br>
<br>
<h2>19. Stitched CSS3 Elements</h2>

<pre><code>p {<br>
    position:relative;<br>
    z-index:1;<br>
    padding: 10px;<br>
    margin: 10px;<br>
    font-size: 21px;<br>
    line-height: 1.3em;<br>
    color: #fff;<br>
    background: #ff0030;<br>
    -webkit-box-shadow: 0 0 0 4px #ff0030, 2px 1px 4px 4px rgba(10,10,0,.5);<br>
    -moz-box-shadow: 0 0 0 4px #ff0030, 2px 1px 4px 4px rgba(10,10,0,.5);<br>
    box-shadow: 0 0 0 4px #ff0030, 2px 1px 6px 4px rgba(10,10,0,.5);<br>
    -webkit-border-radius: 3px;<br>
    -moz-border-radius: 3px;<br>
    border-radius: 3px;<br>
}<br>
<br>
p:before {<br>
    content: "";<br>
    position: absolute;<br>
    z-index: -1;<br>
    top: 3px;<br>
    bottom: 3px;<br>
    left :3px;<br>
    right: 3px;<br>
    border: 2px dashed #fff;<br>
}<br>
<br>
p a {<br>
    color: #fff;<br>
    text-decoration:none;<br>
}<br>
<br>
p a:hover, p a:focus, p a:active {<br>
    text-decoration:underline;<br>
}<br>
</code></pre>

<a href='http://kitmacallister.com/2011/css3-stitched-elements/'>Code Source</a>


<h2>20. CSS3 Underline text with background</h2>

<pre><code>.wp_syntax td.code {<br>
background:-moz-linear-gradient(top,rgba(30,87,153,0.125) 1px,#ffffff 1px);<br>
background:-webkit-gradient(linear,left top,left bottom,color-stop(1px,rgba(30,87,153,0.125)),color-stop(1px,#ffffff));<br>
background:-webkit-linear-gradient(top,rgba(30,87,153,0.125) 1px,#ffffff 1px);<br>
background:-o-linear-gradient(top,rgba(30,87,153,0.125) 1px,#ffffff 1px);<br>
background:-ms-linear-gradient(top,rgba(30,87,153,0.125) 1px,#ffffff 1px);<br>
background:linear-gradient(to bottom,rgba(30,87,153,0.125) 1px,#ffffff 1px);<br>
filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799',endColorstr='#ffffff',GradientType=0 );<br>
background-repeat:repeat;<br>
background-size:100% 20px;<br>
background-position:0 23px;<br>
}<br>
</code></pre>

<h2>20b. CSS3 Zebra Stripes b</h2>

<pre><code>tbody tr:nth-child(odd) {<br>
    background-color: #ccc;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/css3-zebra-striping-a-table/'>Code Source</a>

Possibly the best item to include zebra stripes is within a table of data. It can be difficult when users are scanning 40 or 50 rows to determine exactly which cell is lined up to which row. By adding zebra stripes on default we can update odd rows with varying background colors.<br>
<br>
<h2>21. Fancy Ampersand</h2>

<pre><code>.amp {<br>
    font-family: Baskerville, 'Goudy Old Style', Palatino, 'Book Antiqua', serif;<br>
    font-style: italic;<br>
    font-weight: normal;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/fancy-ampersand/'>Code Source</a>

This class would be applied to one span element wrapped around your ampersand character in page content. It will apply some classic serif fonts and use italics to enhance the ampersand symbol. Try it out on a demo webpage and see how you like the design.<br>
<br>
<h2>22. Drop-Cap Paragraphs</h2>

<pre><code>p:first-letter{<br>
    display: block;<br>
    margin: 5px 0 0 5px;<br>
    float: left;<br>
    color: #ff3366;<br>
    font-size: 5.4em;<br>
    font-family: Georgia, Times New Roman, serif;<br>
}<br>
</code></pre>

Typically you’ll notice dropped capitals appear in printed mediums, such as newspapers and books. However this can also be a neat effect in webpages or blogs where there is enough extra room in the layout. This CSS rule is targeting all paragraphs but you may limit this based on a single class or ID.<br>
<br>
<h2>23. Inner CSS3 Box Shadow</h2>

<pre><code>#mydiv { <br>
    -moz-box-shadow: inset 2px 0 4px #000;<br>
    -webkit-box-shadow: inset 2px 0 4px #000;<br>
    box-shadow: inset 2px 0 4px #000;<br>
}<br>
</code></pre>

The box shadow property has offered immense changes into how we build websites. You can portray box shadows on nearly any element, and they all generally look great. This piece of code will force inner shadows which is a lot harder to design around, but in the right cases it looks pristine.<br>
<br>
<h2>24. Outer CSS3 Box Shadow</h2>

<pre><code>#mydiv { <br>
    -webkit-box-shadow: 0 2px 2px -2px rgba(0, 0, 0, 0.52);<br>
    -moz-box-shadow: 0 2px 2px -2px rgba(0, 0, 0, 0.52);<br>
    box-shadow: 0 2px 2px -2px rgba(0, 0, 0, 0.52);<br>
}<br>
</code></pre>

In relation to the inner CSS3 shadows I also want to present an outer shadow code snippet. Note the 3rd number in our syntax represents blur distance while the 4th number represents the spread. You can learn more about these values from <a href='http://www.w3schools.com/cssref/css3_pr_box-shadow.asp'>W3Schools</a>.<br>
<br>
<h2>25. Triangular List Bullets</h2>

<pre><code>ul {<br>
    margin: 0.75em 0;<br>
    padding: 0 1em;<br>
    list-style: none;<br>
}<br>
li:before { <br>
    content: "";<br>
    border-color: transparent #111;<br>
    border-style: solid;<br>
    border-width: 0.35em 0 0.35em 0.45em;<br>
    display: block;<br>
    height: 0;<br>
    width: 0;<br>
    left: -1em;<br>
    top: 0.9em;<br>
    position: relative;<br>
}<br>
</code></pre>

<a href='http://jsfiddle.net/chriscoyier/yNZTU/'>Code Source</a>


Believe it or not it is actually possible <strong>to generate triangle-shaped bullets solely in CSS3</strong>. This is a really cool technique which does look awesome in respected browsers. The only potential issue is a major lack of support for fallback methods.<br>
<br>
<h2>25.b Triangle</h2>

/<b>Pour faire un triangle vers la droite</b>/<br>
<br>
<pre><code>.triangle { <br>
    width: 0px;<br>
    height: 0px;<br>
    border-top: 20px solid transparent;<br>
    border-bottom: 20px solid transparent;<br>
    border-left: 20px solid #000000;<br>
}<br>
</code></pre>

<h2>26. Centered Layout Fixed Width</h2>

<pre><code>#page-wrap {<br>
    width: 800px;<br>
    margin: 0 auto;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/centering-a-website/'>Code Source</a>

I know earlier it was mentioned how to setup horizontal positioning. I want to jump back in with this <strong>quick snippet for horizontal positioning</strong>, which is perfect to be used on fixed-width layouts.<br>
<br>
<h2>27. CSS3 Column Text</h2>

<pre><code>#columns-3 {<br>
    text-align: justify;<br>
    -moz-column-count: 3;<br>
    -moz-column-gap: 12px;<br>
    -moz-column-rule: 1px solid #c4c8cc;<br>
    -webkit-column-count: 3;<br>
    -webkit-column-gap: 12px;<br>
    -webkit-column-rule: 1px solid #c4c8cc;<br>
}<br>
</code></pre>

<a href='http://www.djavupixel.com/development/css-development/master-css3-ultimate-css-code-snippets/'>Code Source</a>

CSS3 columns would be nice to see in website layouts, but the reality is how we can split up text based on column styles. Use this snippet to place any number of columns inline with your paragraphs, where text will split evenly based on your column number.<br>
<br>
<h2>28. CSS Fixed Footer</h2>

<pre><code>#footer {<br>
    position: fixed;<br>
    left: 0px;<br>
    bottom: 0px;<br>
    height: 30px;<br>
    width: 100%;<br>
    background: #444;<br>
}<br>
 <br>
/* IE 6 */<br>
* html #footer {<br>
    position: absolute;<br>
    top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe  =  document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');<br>
}<br>
</code></pre>

<a href='http://www.flashjunior.ch/school/footers/fixed.cfm'>Code Source</a>

This is actually a lot more useful than it sounds, but appending a fixed footer into your website is quite simple. These footers will scroll with the user and may contain helpful information about your site or unique contact details. Ideally this would only be implemented in cases where it truly adds value to the user interface.<br>
<br>
<h2>29. Transparent PNG Fix for IE6</h2>

<pre><code>.bg {<br>
    width:200px;<br>
    height:100px;<br>
    background: url(/folder/yourimage.png) no-repeat;<br>
    _background:none;<br>
    _filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src = '/folder/yourimage.png',sizingMethod = 'crop');<br>
}<br>
<br>
/* 1px gif method */<br>
img, .png {<br>
    position: relative;<br>
    behavior: expression((this.runtimeStyle.behavior = "none")&amp;amp;&amp;amp;(this.pngSet?this.pngSet = true:(this.nodeName  ==  "IMG" &amp;amp;&amp;amp; this.src.toLowerCase().indexOf('.png')&amp;gt;-1?(this.runtimeStyle.backgroundImage  =  "none",<br>
       this.runtimeStyle.filter  =  "progid:DXImageTransform.Microsoft.AlphaImageLoader(src = '" + this.src + "', sizingMethod = 'image')",<br>
       this.src  =  "images/transparent.gif"):(this.origBg  =  this.origBg? this.origBg :this.currentStyle.backgroundImage.toString().replace('url("','').replace('")',''),<br>
       this.runtimeStyle.filter  =  "progid:DXImageTransform.Microsoft.AlphaImageLoader(src = '" + this.origBg + "', sizingMethod = 'crop')",<br>
       this.runtimeStyle.backgroundImage  =  "none")),this.pngSet = true));<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/png-hack-for-ie-6/'>Code Source</a>


Using transparent images inside websites has become a very common practice. This started with gif images, but has evolved into alpha-transparent PNGs. Unfortunately older legacy versions of Internet Explorer have never supported the transparency. Adding this brief CSS snippet should clear up the problem.<br>
<br>
<h2>30. Cross-Browser Minimum Height</h2>

<pre><code>#container {<br>
    min-height: 550px;<br>
    height: auto !important;<br>
    height: 550px;<br>
}<br>
</code></pre>

Developers who have needed to work with <pre><code>min-height</code></pre> know all about the shady support. Many newer browsers can handle this perfectly, however Internet Explorer and older versions of Firefox do have trouble. This set of codes should provide a fix to any related bugs.<br>
<br>
<h2>31. CSS3 Glowing Inputs</h2>

<pre><code>input[type = text], textarea {<br>
    -webkit-transition: all 0.30s ease-in-out;<br>
    -moz-transition: all 0.30s ease-in-out;<br>
    -ms-transition: all 0.30s ease-in-out;<br>
    -o-transition: all 0.30s ease-in-out;<br>
    outline: none;<br>
    padding: 3px 0px 3px 3px;<br>
    margin: 5px 1px 3px 0px;<br>
    border: 1px solid #ddd;<br>
}<br>
 <br>
input[type = text]:focus, textarea:focus {<br>
    box-shadow: 0 0 5px rgba(81, 203, 238, 1);<br>
    padding: 3px 0px 3px 3px;<br>
    margin: 5px 1px 3px 0px;<br>
    border: 1px solid rgba(81, 203, 238, 1);<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/glowing-blue-input-highlights/'>Code Source</a>

I really enjoy this basic custom CSS3 class because of how it overwrites the default browser behavior. Users of Chrome &amp; Safari know about annoying input outlines in forms. Adding these properties into your stylesheet will setup a whole new design for basic input elements.<br>
<br>
<h2>32. Style Links Based on Filetype</h2>

<pre><code>/* external links */<br>
a[href^ = "http://"] {<br>
    padding-right: 13px;<br>
    background: url('external.gif') no-repeat center right;<br>
}<br>
 <br>
/* emails */<br>
a[href^ = "mailto:"] {<br>
    padding-right: 20px;<br>
    background: url('email.png') no-repeat center right;<br>
}<br>
 <br>
/* pdfs */<br>
a[href$ = ".pdf"] {<br>
    padding-right: 18px;<br>
    background: url('acrobat.png') no-repeat center right;<br>
}<br>
</code></pre>

<a href='http://www.designyourway.net/blog/resources/31-css-code-snippets-to-make-you-a-better-coder/'>Code Source</a>

Quite the obscure bit of CSS but I love the creativity! You can determine the file type of your links using CSS selectors and implement icons as background images. These may include the various protocols (HTTP, FTP, IRC, mailto) or simply the file types themselves (mp3, avi, pdf).<br>
<br>
<h2>33. Force Code Wraps</h2>

<pre><code>pre {<br>
    white-space: pre-wrap;       /* css-3 */<br>
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */<br>
    white-space: -pre-wrap;      /* Opera 4-6 */<br>
    white-space: -o-pre-wrap;    /* Opera 7 */<br>
    word-wrap: break-word;       /* Internet Explorer 5.5+ */<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/make-pre-text-wrap/'>Code Source</a>

The typical pre tags are used in layouts to display large chunks of code. This is preformatted text like you would find inside Notepad or Textedit, except you’ll often notice long lines produce horizontal scrollbars. This block of CSS will <strong>force all pre tags to wrap code</strong> instead of breaking outside the container.<br>
<br>
<h2>34. Force Hand Cursor over Clickable Items</h2>

<pre><code>a[href], input[type = 'submit'], input[type = 'image'], label[for], select, button, .pointer {<br>
    cursor: pointer;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/give-clickable-elements-a-pointer-cursor/'>Code Source</a>

There are lots of default clickable HTML elements which do not always display the hand pointer icon. Using this set of CSS selectors you may force the pointer over a number of key elements, along with any other objects using the class <strong>.pointer</strong>.<br>
<br>
<h2>35. Webpage Top Box Shadow</h2>

<pre><code>body:before {<br>
    content: "";<br>
    position: fixed;<br>
    top: -10px;<br>
    left: 0;<br>
    width: 100%;<br>
    height: 10px;<br>
<br>
    -webkit-box-shadow: 0px 0px 10px rgba(0,0,0,.8);<br>
    -moz-box-shadow: 0px 0px 10px rgba(0,0,0,.8);<br>
    box-shadow: 0px 0px 10px rgba(0,0,0,.8);<br>
    z-index: 100;<br>
}<br>
</code></pre>

<a href='http://css-tricks.com/snippets/css/top-shadow/'>Code Source</a>


Developers may not find a great use for this other than some pleasing aesthetics. But I really enjoy this effect and it’s definitely something unique! Simply append this CSS code targeting your body element <strong>to display a dark drop shadow fading down from the top of your webpage</strong>.<br>
<br>
<h2>36. CSS3 Speech Bubble</h2>

<pre><code>.chat-bubble {<br>
    background-color: #ededed;<br>
    border: 2px solid #666;<br>
    font-size: 35px;<br>
    line-height: 1.3em;<br>
    margin: 10px auto;<br>
    padding: 10px;<br>
    position: relative;<br>
    text-align: center;<br>
    width: 300px;<br>
    -moz-border-radius: 20px;<br>
    -webkit-border-radius: 20px;<br>
    -moz-box-shadow: 0 0 5px #888;<br>
    -webkit-box-shadow: 0 0 5px #888;<br>
    font-family: 'Bangers', arial, serif; <br>
}<br>
.chat-bubble-arrow-border {<br>
    border-color: #666 transparent transparent transparent;<br>
    border-style: solid;<br>
    border-width: 20px;<br>
    height: 0;<br>
    width: 0;<br>
    position: absolute;<br>
    bottom: -42px;<br>
    left: 30px;<br>
}<br>
.chat-bubble-arrow {<br>
    border-color: #ededed transparent transparent transparent;<br>
    border-style: solid;<br>
    border-width: 20px;<br>
    height: 0;<br>
    width: 0;<br>
    position: absolute;<br>
    bottom: -39px;<br>
    left: 30px;<br>
}<br>
</code></pre>

<a href='http://html5snippets.com/snippets/35-css3-comic-bubble-using-triangle-trick'>Code Source</a>

Numerous user interface purposes come to mind when discussing speech bubbles. These could be handy in discussion comments, or creating bulletin boards, or displaying quoted text. Simply add the following classes into your stylesheet and you can find related HTML codes from <a href='http://html5snippets.com/snippets/35-css3-comic-bubble-using-triangle-trick'>this CSS3 snippets post</a>.<br>
<br>
<h2>37. Default H1-H5 Headers</h2>

<pre><code>h1,h2,h3,h4,h5{<br>
	color: #005a9c;<br>
}<br>
h1{<br>
	font-size: 2.6em;<br>
	line-height: 2.45em;<br>
}<br>
h2{<br>
	font-size: 2.1em;<br>
	line-height: 1.9em;<br>
}<br>
h3{<br>
	font-size: 1.8em;<br>
	line-height: 1.65em;<br>
}<br>
h4{<br>
	font-size: 1.65em;<br>
	line-height: 1.4em;<br>
}<br>
h5{<br>
	font-size: 1.4em;<br>
	line-height: 1.25em;<br>
}<br>
</code></pre>

<a href='https://snipt.net/freshmaker99/headers/'>Code Source</a>

I have offered lots of common syntax including browser CSS resets and a few HTML element resets. This template includes <strong>default styles for all major heading elements ranging from H1-H5</strong>. You may also consider adding H6 but I have never seen a website using all six nested headers.<br>
<br>
<h2>38. Pure CSS Background Noise</h2>

<pre><code>body {<br>
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAMAAAAp4XiDAAAAUVBMVEWFhYWDg4N3d3dtbW17e3t1dXWBgYGHh4d5eXlzc3OLi4ubm5uVlZWPj4+NjY19fX2JiYl/f39ra2uRkZGZmZlpaWmXl5dvb29xcXGTk5NnZ2c8TV1mAAAAG3RSTlNAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEAvEOwtAAAFVklEQVR4XpWWB67c2BUFb3g557T/hRo9/WUMZHlgr4Bg8Z4qQgQJlHI4A8SzFVrapvmTF9O7dmYRFZ60YiBhJRCgh1FYhiLAmdvX0CzTOpNE77ME0Zty/nWWzchDtiqrmQDeuv3powQ5ta2eN0FY0InkqDD73lT9c9lEzwUNqgFHs9VQce3TVClFCQrSTfOiYkVJQBmpbq2L6iZavPnAPcoU0dSw0SUTqz/GtrGuXfbyyBniKykOWQWGqwwMA7QiYAxi+IlPdqo+hYHnUt5ZPfnsHJyNiDtnpJyayNBkF6cWoYGAMY92U2hXHF/C1M8uP/ZtYdiuj26UdAdQQSXQErwSOMzt/XWRWAz5GuSBIkwG1H3FabJ2OsUOUhGC6tK4EMtJO0ttC6IBD3kM0ve0tJwMdSfjZo+EEISaeTr9P3wYrGjXqyC1krcKdhMpxEnt5JetoulscpyzhXN5FRpuPHvbeQaKxFAEB6EN+cYN6xD7RYGpXpNndMmZgM5Dcs3YSNFDHUo2LGfZuukSWyUYirJAdYbF3MfqEKmjM+I2EfhA94iG3L7uKrR+GdWD73ydlIB+6hgref1QTlmgmbM3/LeX5GI1Ux1RWpgxpLuZ2+I+IjzZ8wqE4nilvQdkUdfhzI5QDWy+kw5Wgg2pGpeEVeCCA7b85BO3F9DzxB3cdqvBzWcmzbyMiqhzuYqtHRVG2y4x+KOlnyqla8AoWWpuBoYRxzXrfKuILl6SfiWCbjxoZJUaCBj1CjH7GIaDbc9kqBY3W/Rgjda1iqQcOJu2WW+76pZC9QG7M00dffe9hNnseupFL53r8F7YHSwJWUKP2q+k7RdsxyOB11n0xtOvnW4irMMFNV4H0uqwS5ExsmP9AxbDTc9JwgneAT5vTiUSm1E7BSflSt3bfa1tv8Di3R8n3Af7MNWzs49hmauE2wP+ttrq+AsWpFG2awvsuOqbipWHgtuvuaAE+A1Z/7gC9hesnr+7wqCwG8c5yAg3AL1fm8T9AZtp/bbJGwl1pNrE7RuOX7PeMRUERVaPpEs+yqeoSmuOlokqw49pgomjLeh7icHNlG19yjs6XXOMedYm5xH2YxpV2tc0Ro2jJfxC50ApuxGob7lMsxfTbeUv07TyYxpeLucEH1gNd4IKH2LAg5TdVhlCafZvpskfncCfx8pOhJzd76bJWeYFnFciwcYfubRc12Ip/ppIhA1/mSZ/RxjFDrJC5xifFjJpY2Xl5zXdguFqYyTR1zSp1Y9p+tktDYYSNflcxI0iyO4TPBdlRcpeqjK/piF5bklq77VSEaA+z8qmJTFzIWiitbnzR794USKBUaT0NTEsVjZqLaFVqJoPN9ODG70IPbfBHKK+/q/AWR0tJzYHRULOa4MP+W/HfGadZUbfw177G7j/OGbIs8TahLyynl4X4RinF793Oz+BU0saXtUHrVBFT/DnA3ctNPoGbs4hRIjTok8i+algT1lTHi4SxFvONKNrgQFAq2/gFnWMXgwffgYMJpiKYkmW3tTg3ZQ9Jq+f8XN+A5eeUKHWvJWJ2sgJ1Sop+wwhqFVijqWaJhwtD8MNlSBeWNNWTa5Z5kPZw5+LbVT99wqTdx29lMUH4OIG/D86ruKEauBjvH5xy6um/Sfj7ei6UUVk4AIl3MyD4MSSTOFgSwsH/QJWaQ5as7ZcmgBZkzjjU1UrQ74ci1gWBCSGHtuV1H2mhSnO3Wp/3fEV5a+4wz//6qy8JxjZsmxxy5+4w9CDNJY09T072iKG0EnOS0arEYgXqYnXcYHwjTtUNAcMelOd4xpkoqiTYICWFq0JSiPfPDQdnt+4/wuqcXY47QILbgAAAABJRU5ErkJggg == );<br>
    background-color: #0094d0;<br>
}<br>
</code></pre>

<a href='https://coderwall.com/p/m-uwvg'>Code Source</a>


Designers have seen this effect added into websites for a long time, although they generally use repeating tile images with alpha-transparency. However we can embed Base64 code into CSS to generate brand new images. This is the case as in the snippet above which generates <strong>a small noise texture above the body background</strong>, or you can create a customized noise background over at <a href='http://www.noisetexturegenerator.com'>NoiseTextureGenerator</a>.<br>
<br>
<h2>39. Continued List Ordering</h2>

<pre><code>ol.chapters {<br>
    list-style: none;<br>
    margin-left: 0;<br>
}<br>
<br>
ol.chapters &amp;gt; li:before {<br>
    content: counter(chapter) ". ";<br>
    counter-increment: chapter;<br>
    font-weight: bold;<br>
    float: left;<br>
    width: 40px;<br>
}<br>
<br>
ol.chapters li {<br>
    clear: left;<br>
}<br>
<br>
ol.start {<br>
	counter-reset: chapter;<br>
}<br>
<br>
ol.continue {<br>
	counter-reset: chapter 11;<br>
}<br>
</code></pre>

<a href='http://timmychristensen.com/css-ordered-list-numbering-examples.html'>Code Source</a>


I feel this may not be an extremely popular snippet, but it does have its market among developers. There may be situations where you’ll need to <strong>continue a list of items but split into two separate UL elements</strong>. Check out the code above for an awesome CSS-only fix.<br>
<br>
<h2>40. CSS Tooltip Hovers</h2>

<pre><code>a { <br>
    border-bottom:1px solid #bbb;<br>
    color:#666;<br>
    display:inline-block;<br>
    position:relative;<br>
    text-decoration:none;<br>
}<br>
a:hover,<br>
a:focus {<br>
    color:#36c;<br>
}<br>
a:active {<br>
    top:1px; <br>
}<br>
 <br>
/* Tooltip styling */<br>
a[data-tooltip]:after {<br>
    border-top: 8px solid #222;<br>
    border-top: 8px solid hsla(0,0%,0%,.85);<br>
    border-left: 8px solid transparent;<br>
    border-right: 8px solid transparent;<br>
    content: "";<br>
    display: none;<br>
    height: 0;<br>
    width: 0;<br>
    left: 25%;<br>
    position: absolute;<br>
}<br>
a[data-tooltip]:before {<br>
    background: #222;<br>
    background: hsla(0,0%,0%,.85);<br>
    color: #f6f6f6;<br>
    content: attr(data-tooltip);<br>
    display: none;<br>
    font-family: sans-serif;<br>
    font-size: 14px;<br>
    height: 32px;<br>
    left: 0;<br>
    line-height: 32px;<br>
    padding: 0 15px;<br>
    position: absolute;<br>
    text-shadow: 0 1px 1px hsla(0,0%,0%,1);<br>
    white-space: nowrap;<br>
    -webkit-border-radius: 5px;<br>
    -moz-border-radius: 5px;<br>
    -o-border-radius: 5px;<br>
    border-radius: 5px;<br>
}<br>
a[data-tooltip]:hover:after {<br>
    display: block;<br>
    top: -9px;<br>
}<br>
a[data-tooltip]:hover:before {<br>
    display: block;<br>
    top: -41px;<br>
}<br>
a[data-tooltip]:active:after {<br>
    top: -10px;<br>
}<br>
a[data-tooltip]:active:before {<br>
    top: -42px;<br>
}<br>
</code></pre>

<a href='http://www.impressivewebs.com/pure-css-tool-tips/'>Code Source</a>


There are lots of open source jQuery-based tooltips which you can implement on your websites. But CSS-based tooltips are very rare, and this is one of my favorite snippets. Just copy this over into your stylesheet and using the new HTML5 data-attributes <strong>you can setup the tooltip text via</strong> <pre><code>data-tooltip</code></pre>.<br>
<br>
<h2>41. Dark Grey Rounded Buttons</h2>

<pre><code>.graybtn {<br>
    -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;<br>
    -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;<br>
    box-shadow:inset 0px 1px 0px 0px #ffffff;<br>
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ffffff), color-stop(1, #d1d1d1) );<br>
    background:-moz-linear-gradient( center top, #ffffff 5%, #d1d1d1 100% );<br>
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr = '#ffffff', endColorstr = '#d1d1d1');<br>
    background-color:#ffffff;<br>
    -moz-border-radius:6px;<br>
    -webkit-border-radius:6px;<br>
    border-radius:6px;<br>
    border:1px solid #dcdcdc;<br>
    display:inline-block;<br>
    color:#777777;<br>
    font-family:arial;<br>
    font-size:15px;<br>
    font-weight:bold;<br>
    padding:6px 24px;<br>
    text-decoration:none;<br>
    text-shadow:1px 1px 0px #ffffff;<br>
}<br>
.graybtn:hover {<br>
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #d1d1d1), color-stop(1, #ffffff) );<br>
    background:-moz-linear-gradient( center top, #d1d1d1 5%, #ffffff 100% );<br>
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr = '#d1d1d1', endColorstr = '#ffffff');<br>
    background-color:#d1d1d1;<br>
}<br>
.graybtn:active {<br>
    position:relative;<br>
    top:1px;<br>
}<br>
</code></pre>

<a href='http://html5snippets.com/snippets/1-a-css-rounded-gray-button'>Code Source</a>

As another helpful template for web developers I have included this simplistic CSS3 buttons class. I am using the class name <strong>.graybtn</strong> which is appropriate for the colors, but this isn’t to say you couldn’t change the styles to match your own website. Check out the hex values inside a color wheel to match similar hues in different color ranges.<br>
<br>
<h2>42. Display URLS in a Printed Webpage</h2>

<pre><code>@media print   {  <br>
  a:after {  <br>
    content: " [" attr(href) "] ";  <br>
  }<br>
}<br>
</code></pre>

<a href='http://www.smipple.net/snippet/bramloquet/Print%20the%20url%20after%20your%20links'>Code Source</a>


If you run a news website or resource with lots of print material, this is possibly one of the greatest snippets you’ll ever find. Anchor links in your webpage will look and display exactly as normal. However when printed your users will be able to see the link text along with the full hyperlinked URL. This is handy when visitors need to access a webpage you’ve linked but <strong>cannot see the URL in a typical printed document</strong>.<br>
<br>
<h2>43. Disable Mobile Webkit Highlights</h2>

<pre><code>body {<br>
    -webkit-touch-callout: none;<br>
    -webkit-user-select: none;<br>
    -khtml-user-select: none;<br>
    -moz-user-select: none;<br>
    -ms-user-select: none;<br>
    user-select: none;<br>
}<br>
</code></pre>

Depending on your experience working in mobile this snippet may not appear very helpful. But when accessing mobile websites in Safari and other Webkit-based engines, you’ll notice a grey box surrounds elements as you tap them. Just append these styles into your website and it should <strong>remove all native mobile browser highlights.</strong>

<h2>44. CSS3 Polka-Dot Pattern</h2>

<pre><code>body {<br>
    background: radial-gradient(circle, white 10%, transparent 10%),<br>
    radial-gradient(circle, white 10%, black 10%) 50px 50px;<br>
    background-size: 100px 100px;<br>
}<br>
</code></pre>

<a href='http://dabblet.com/gist/1457668'>Code Source</a>

I was a bit taken back when initially finding this snippet online. But it is a really interesting method for generating CSS3-only BG patterns on the fly. I have targeted the body element as default but you could apply this onto any container div in your webpage.<br>
<br>
<h2>45. CSS3 Checkered Pattern</h2>

<pre><code>body {<br>
    background-color: white;<br>
    background-image: linear-gradient(45deg, black 25%, transparent 25%, transparent 75%, black 75%, black), <br>
    linear-gradient(45deg, black 25%, transparent 25%, transparent 75%, black 75%, black);<br>
    background-size: 100px 100px;<br>
    background-position: 0 0, 50px 50px;<br>
}<br>
</code></pre>

<a href='http://dabblet.com/gist/1457677'>Code Source</a>

Similar to the polka-dots above we can also create a full seamless checkerboard pattern. This method requires a bit more syntax to get working, but it looks flawless in all CSS3-supported browsers. Also you can change the color values from white and black to match that of your own website color scheme.<br>
<br>
<h2>46. Github Fork Ribbon</h2>

<pre><code>.ribbon {<br>
    background-color: #a00;<br>
    overflow: hidden;<br>
    /* top left corner */<br>
    position: absolute;<br>
    left: -3em;<br>
    top: 2.5em;<br>
    /* 45 deg ccw rotation */<br>
    -moz-transform: rotate(-45deg);<br>
    -webkit-transform: rotate(-45deg);<br>
    /* shadow */<br>
    -moz-box-shadow: 0 0 1em #888;<br>
    -webkit-box-shadow: 0 0 1em #888;<br>
}<br>
.ribbon a {<br>
    border: 1px solid #faa;<br>
    color: #fff;<br>
    display: block;<br>
    font: bold 81.25% 'Helvetiva Neue', Helvetica, Arial, sans-serif;<br>
    margin: 0.05em 0 0.075em 0;<br>
    padding: 0.5em 3.5em;<br>
    text-align: center;<br>
    text-decoration: none;<br>
    /* shadow */<br>
    text-shadow: 0 0 0.5em #444;<br>
}<br>
</code></pre>

<a href='http://unindented.org/articles/2009/10/github-ribbon-using-css-transforms/'>Code Source</a>

As a big user on Github this basic code snippet blew my mind. You can quickly generate Github corner ribbons in your webpage using CSS3 transform properties. This is perfect for open source plugins or code packs which have a popular following on Github. Also great for hosted HTML/CSS/JS demos if you have an active Github repo.<br>
<br>
<h2>47. Condensed CSS Font Properties</h2>

<pre><code>p {<br>
  font: italic small-caps bold 1.2em/1.0em Arial, Tahoma, Helvetica;<br>
}<br>
</code></pre>

<a href='http://www.csspop.com/view/542'>Code Source</a>

The main reason web developers don’t always use this condensed font property is because not every setting is needed. But having an understanding of this shorthand may save you a lot of time and space in your stylesheets. Keep this snippet handy just in case you ever want to shorten the formatting of your font styles.<br>
<br>
<h2>48. Paper Page Curl Effect</h2>

<pre><code>ul.box {<br>
    position: relative;<br>
    z-index: 1; /* prevent shadows falling behind containers with backgrounds */<br>
    overflow: hidden;<br>
    list-style: none;<br>
    margin: 0;<br>
    padding: 0; <br>
}<br>
<br>
ul.box li {<br>
    position: relative;<br>
    float: left;<br>
    width: 250px;<br>
    height: 150px;<br>
    padding: 0;<br>
    border: 1px solid #efefef;<br>
    margin: 0 30px 30px 0;<br>
    background: #fff;<br>
    -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;<br>
    -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset; <br>
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset; <br>
}<br>
<br>
ul.box li:before,<br>
ul.box li:after {<br>
    content: '';<br>
    z-index: -1;<br>
    position: absolute;<br>
    left: 10px;<br>
    bottom: 10px;<br>
    width: 70%;<br>
    max-width: 300px; /* avoid rotation causing ugly appearance at large container widths */<br>
    max-height: 100px;<br>
    height: 55%;<br>
    -webkit-box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);<br>
    -moz-box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);<br>
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);<br>
    -webkit-transform: skew(-15deg) rotate(-6deg);<br>
    -moz-transform: skew(-15deg) rotate(-6deg);<br>
    -ms-transform: skew(-15deg) rotate(-6deg);<br>
    -o-transform: skew(-15deg) rotate(-6deg);<br>
    transform: skew(-15deg) rotate(-6deg); <br>
}<br>
<br>
ul.box li:after {<br>
    left: auto;<br>
    right: 10px;<br>
    -webkit-transform: skew(15deg) rotate(6deg);<br>
    -moz-transform: skew(15deg) rotate(6deg);<br>
    -ms-transform: skew(15deg) rotate(6deg);<br>
    -o-transform: skew(15deg) rotate(6deg);<br>
    transform: skew(15deg) rotate(6deg); <br>
}<br>
</code></pre>

<a href='http://www.csspop.com/view/524'>Code Source</a>

This page curl effect can be applied to almost any container which holds website content. Immediately I thought about image media and quoted text, but really this could be anything at all. Check out the snippet’s <a href='http://www.csspop.com/view/524'>live demo page</a> for a better grasp of how these page curls function.<br>
<br>
<h2>49. Glowing Anchor Links</h2>

<pre><code>a {<br>
	color: #00e;<br>
}<br>
a:visited {<br>
	color: #551a8b;<br>
}<br>
a:hover {<br>
	color: #06e;<br>
}<br>
a:focus {<br>
	outline: thin dotted;<br>
}<br>
a:hover, a:active {<br>
	outline: 0;<br>
}<br>
a, a:visited, a:active {<br>
	text-decoration: none;<br>
	color: #fff;<br>
	-webkit-transition: all .3s ease-in-out;<br>
}<br>
a:hover, .glow {<br>
	color: #ff0;<br>
	text-shadow: 0 0 10px #ff0;<br>
}<br>
</code></pre>

<a href='http://www.csspop.com/view/625'>Code Source</a>

CSS3 text shadows offer a unique method of styling your webpage typography. And more specifically this snippet is an excellent resource for <strong>custom creative links with glowing hover effects</strong>. I doubt this effect can be pulled off elegantly in the majority of websites, but if you have the patience to get it looking nice you are sure to impress visitors.<br>
<br>
<h2>50. Featured CSS3 Display Banner</h2>

<pre><code><br>
&gt;.featureBanner {<br>
    position: relative;<br>
    margin: 20px<br>
}<br>
.featureBanner:before {<br>
    content: "Featured";<br>
    position: absolute;<br>
    top: 5px;<br>
    left: -8px;<br>
    padding-right: 10px;<br>
    color: #232323;<br>
    font-weight: bold;<br>
    height: 0px;<br>
    border: 15px solid #ffa200;<br>
    border-right-color: transparent;<br>
    line-height: 0px;<br>
    box-shadow: -0px 5px 5px -5px #000;<br>
    z-index: 1;<br>
}<br>
<br>
.featureBanner:after {<br>
    content: "";<br>
    position: absolute;<br>
    top: 35px;<br>
    left: -8px;<br>
    border: 4px solid #89540c;<br>
    border-left-color: transparent;<br>
    border-bottom-color: transparent;<br>
}<br>
</code></pre>

Generally you would need to setup a background image to duplicate this effect in other browsers. But in CSS3-supported engines we can generate <strong>dynamic banners which hang off the edge of your content wrappers, all without images</strong>! These may look good attached onto e-commerce products, image thumbnails, video previews, or blog articles, to list just a few ideas.<br>
<br>
<h1>Responsive TYPO</h1>

<a href='http://css-tricks.com/viewport-sized-typography/'>http://css-tricks.com/viewport-sized-typography/</a>

<br>
<br>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, target-densityDpi=device-dpi" /><br>
<br>
<br>
<br>
<pre><code>I use that code and after I make my declaration in EM.<br>
<br>
@media only screen and (min-width: 480px) {<br>
	body { font-size: 12px;}<br>
}<br>
@media only screen and (min-width: 768px) {<br>
	body { font-size: 13px;}<br>
}<br>
@media only screen and (min-width: 1000px) {<br>
	body { font-size: 14px;}<br>
}<br>
@media only screen and (min-width: 1170px) {<br>
	body { font-size: 15px;}<br>
}<br>
@media only screen and (min-width: 1340px) {<br>
	body { font-size: 16px; }<br>
}<br>
@media only screen and (min-width: 1580px) {<br>
	body { font-size: 17px; }<br>
}<br>
</code></pre>



<h1>CSS only custom-styled select</h1>

<ul><li><a href='http://jsbin.com/juvixufu/10/edit'>http://jsbin.com/juvixufu/10/edit</a></li></ul>

<pre><code>label {<br>
      display:block;<br>
      margin-top:2em;<br>
      font-size: 0.9em;<br>
      color:#777;<br>
    }<br>
<br>
    .dropdown {<br>
      position: relative;<br>
      display:block;<br>
      margin-top:0.5em;<br>
      overflow:hidden;<br>
      width:100%;<br>
      max-width:100%;<br>
    }<br>
<br>
    select {<br>
      /* Make sure the select is wider than the container so we can clip the arrow */<br>
      width:110%;<br>
      max-width:110%;<br>
      min-width:110%;<br>
      /* Remove select styling */<br>
      appearance: none;<br>
      -webkit-appearance: none;<br>
      /* Ugly Firefox way of doing it */<br>
      -moz-appearance: window;<br>
      text-indent: 0.01px;<br>
      text-overflow: "";<br>
      /* Magic font size number to prevent iOS text zoom */<br>
      font-size:16px;<br>
      font-weight: bold;<br>
      background:none;<br>
      border: none;<br>
      color: #444;<br>
      outline: none;<br>
      /* Padding works surpringly well */<br>
      padding: .4em 19% .4em .8em;<br>
      font-family: helvetica, sans-serif;<br>
      line-height:1.2;<br>
      margin:.2em;<br>
    }<br>
    <br>
    /* This hides native dropdown button arrow in IE */<br>
    select::-ms-expand {<br>
      display: none;<br>
    }<br>
<br>
     /* Custom arrow - could be an image, SVG, icon font, etc. */<br>
    .dropdown:after {<br>
      background: none;<br>
      color: #bbb;<br>
      content: "\25BC";<br>
      font-size: .7em;<br>
      padding:0;<br>
      position: absolute;<br>
      right: 1em;<br>
      top: 1.2em;<br>
      bottom: .3em;<br>
      z-index: 1;<br>
      /* This hack makes the select behind the arrow clickable in some browsers */<br>
      pointer-events:none;<br>
    }<br>
   <br>
    /* Hover style - tricky because we're clipping the overflow */<br>
    .dropdown:hover {<br>
      border:1px solid #888;<br>
    }<br>
    <br>
    /* Focus style */<br>
    select:focus {<br>
      outline: none;<br>
      box-shadow: 0 0 3px 3px rgba(180,222,250, .85);<br>
    }<br>
    <br>
    /* This hides focus around selected option in FF */<br>
    select:-moz-focusring {<br>
      color: transparent;<br>
      text-shadow: 0 0 0 #000;<br>
    }<br>
    <br>
    <br>
    <br>
     /* These are just demo button-y styles, style as you like */<br>
    .button {<br>
      border: 1px solid #bbb;<br>
      border-radius: .3em;<br>
      box-shadow: 0 1px 0 1px rgba(0,0,0,.04);<br>
      background: #f3f3f3; /* Old browsers */<br>
      background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */<br>
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */<br>
      background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Chrome10+,Safari5.1+ */<br>
      background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Opera 11.10+ */<br>
      background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* IE10+ */<br>
      background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); /* W3C */<br>
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */<br>
    }<br>
</code></pre>

<pre><code>    &lt;label class="wrapper" for="states"&gt;This label is stacked above the select&lt;/label&gt;<br>
    &lt;div class="button dropdown" id="states"&gt;<br>
        &lt;select&gt;<br>
          &lt;option value="AL"&gt;Alabama&lt;/option&gt;<br>
          &lt;option value="AK"&gt;Alaska&lt;/option&gt;<br>
          &lt;option value="AZ"&gt;Arizona&lt;/option&gt;<br>
        &lt;/select&gt;<br>
    &lt;/div&gt;<br>
</code></pre>

<h1>CSS pretty underline link</h1>

<ul><li><a href='http://codepen.io/molokoloco/pen/LEbayj'>http://codepen.io/molokoloco/pen/LEbayj</a></li></ul>

<pre><code>.has-custom-underline a {<br>
  color: #8db359;<br>
  text-decoration: none;<br>
  background-image: -webkit-linear-gradient(rgba(141, 179, 89, 0.25) 0%, #8db359 100%);<br>
  background-image: linear-gradient(rgba(141, 179, 89, 0.25) 0%, #8db359 100%);<br>
  background-repeat: repeat-x;<br>
  background-size: 1px 1px;<br>
  background-position: 0 95%;<br>
  text-shadow: 3px 0 white, 2px 0 white, 1px 0 white, -1px 0 white, -2px 0 white, -3px 0 white;<br>
}<br>
@media (-webkit-min-device-pixel-ratio: 1.75), (min-resolution: 168dpi) {<br>
  .has-custom-underline a {<br>
    background-image: -webkit-linear-gradient(rgba(141, 179, 89, 0.25) 0%, #8db359 100%);<br>
    background-image: linear-gradient(rgba(141, 179, 89, 0.25) 0%, #8db359 100%);<br>
    background-position: 0 93%;<br>
  }<br>
}<br>
.has-custom-underline a:hover {<br>
  color: #709143;<br>
  background-image: -webkit-linear-gradient(top, #7ea34b 0%, #7ea34b 100%);<br>
  background-image: linear-gradient(to bottom, #7ea34b 0%, #7ea34b 100%);<br>
}<br>
.has-custom-underline a::-moz-selection,<br>
.has-custom-underline a &gt; *::-moz-selection {<br>
  background-color: #c9dbb0;<br>
  color: #57534a;<br>
  text-shadow: none;<br>
}<br>
.has-custom-underline a::selection,<br>
.has-custom-underline a &gt; *::selection {<br>
  background-color: #c9dbb0;<br>
  color: #57534a;<br>
  text-shadow: none;<br>
}<br>
.has-custom-underline a::-moz-selection,<br>
.has-custom-underline a &gt; *::-moz-selection {<br>
  background-color: #c9dbb0;<br>
  color: #57534a;<br>
  text-shadow: none;<br>
}<br>
.has-custom-underline h1 a, .has-custom-underline h2 a, .has-custom-underline h3 a {<br>
  background-size: 1px 2px;<br>
}<br>
@media (-webkit-min-device-pixel-ratio: 1.75), (min-resolution: 168dpi) {<br>
  .has-custom-underline h1 a, .has-custom-underline h2 a, .has-custom-underline h3 a {<br>
    background-position: 0 93%;<br>
    background-image: -webkit-linear-gradient(#8db359 0%, #8db359 100%);<br>
    background-image: linear-gradient(#8db359 0%, #8db359 100%);<br>
    background-size: 1px 1px;<br>
    background-position: 0 93%;<br>
  }<br>
}<br>
</code></pre>

<h1>More</h1>

Here are more articles published in the pass you may be interested in:<br>
<br>
<ul><li><a href='http://www.hongkiat.com/blog/css-back-to-basics-terminology-explained/'>http://www.hongkiat.com/blog/css-back-to-basics-terminology-explained/</a>
</li><li><a href='http://www.hongkiat.com/blog/20-useful-css-tips-for-beginners/'>http://www.hongkiat.com/blog/20-useful-css-tips-for-beginners/</a>
</li><li><a href='http://www.hongkiat.com/blog/top-css-editors-reviewed/'>http://www.hongkiat.com/blog/top-css-editors-reviewed/</a>
</li><li><a href='http://www.hongkiat.com/blog/keep-css3-markup-slim/‎'>http://www.hongkiat.com/blog/keep-css3-markup-slim/‎</a>
</li><li><a href='http://www.hongkiat.com/blog/css-priority-level/'>http://www.hongkiat.com/blog/css-priority-level/</a>
</li><li><a href='http://www.hongkiat.com/blog/css3-pseudo-classes/'>http://www.hongkiat.com/blog/css3-pseudo-classes/</a>