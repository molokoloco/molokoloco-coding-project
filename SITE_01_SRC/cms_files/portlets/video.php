<!--VIDEO FLV-->
<div class="portlet cms_video">
	<div id="flash_video"></div>
	<script type="text/javascript">
	// <![CDATA[
	var so = new SWFObject("player_320x240_sanslogo.swf", "player", "322", "271", "8", "");	
	so.addParam("quality", "high");
	so.addParam("bgcolor", "");
	so.addParam("salign", "TL");
	so.addParam("allowFullScreen", "true");	
	so.addVariable("sPathFlv", "video/stream.flv");
	so.write("flash_video");
	// ]]>
	</script>
</div>

<!--VIDEO EXTERNE-->
<div class="portlet cms_video">
	<object width="425" height="355">
		<param name="movie" value="http://www.youtube.com/v/__C-MjmVUrU&rel=1"></param>
		<param name="wmode" value="transparent"></param>
		<embed src="http://www.youtube.com/v/__C-MjmVUrU&rel=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed>
	</object>
</div>

<!--VIDEO LOCALE-->
<div class="portlet cms_video">
	<object width="232" height="207">
		<embed TYPE="application/x-mplayer2" src="video/video.avi" autostart="true" controller="false" width="232" height="207">
	</object>
</div>