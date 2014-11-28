<% if $Slideshow %>
	<% control $Slideshow %>
		<div id="pager_$ID" class="slidestripePager $PagerCSSClass"></div>
		<div id="slideshow_$ID" class="slidestripe">
		<% loop $Images %>
			<img src="$ImagePath" width="$Slideshow.Width" height="$Slideshow.Height" title="$Caption"/>
		<% end_loop %>
		</div>
	<% end_control %>
<% end_if %>