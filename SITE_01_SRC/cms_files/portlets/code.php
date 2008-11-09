<!--CODE-->
<div class="portlet cms_code">
	<div class="d1">
		<div class="d2">
			<pre><code>var SendDataToFlashMovie = function(movieName,data) { // To test
	var flashMovie = getFlashObject(movieName);
	flashMovie.SetVariable("/:"+data,document.controller);
}</code></pre>
		</div>
	</div>
</div>