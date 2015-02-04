<div id="altContent">
	<h1>HXCMusic Media Network</h1>
	<h2>Discover music like never before.</h2>

	<ul class="links">
		<li><a href="<%= App.baseURL %>/register" style="text-decoration:none" class="hxcmenu" id="register" title="Sign-Up Today!">Sign-Up</a></li>
		<li><a href="<%= App.baseURL %>/home" style="text-decoration:none" class="hxcmenu" id="home" title="Member Login">Member Login</a></li>
		<li><a href="<%= App.baseURL %>/music" style="text-decoration:none" class="hxcmenu" id="music" title="Browse Artists">Music</a></li>
		<li><a href="<%= App.baseURL %>/videos" style="text-decoration:none" class="hxcmenu" id="videos" title="Browse Videos">Videos</a></li>
		<li><a href="<%= App.baseURL %>/events" style="text-decoration:none" class="hxcmenu" id="events" title="Local Events">Events</a></li>
			</ul>
</div>
<div id="top">
	<!--<a id="logo" href="/" style="width:172px;">
		<span class="left_row_icon"></span>
		<img src="<%= App.baseURL %>/images/logo.me4.1.png" style="margin-top:3px;float:left;width:135px;">
	</a>-->
	<a id="logo" href="/" style="width: 212px;">
		<span class="left_row_icon"></span>
		<span id="logo-text"><span id="hxc">HXC</span>Music</span></a>
	<div id="top_right">
	    <a href="/home" data-bypass="true" id="sign_in_link" class="account_link">Login</a>
    <a href="/register" data-bypass="true" id="create_account_link" class="account_link">Create account</a>
		</div>
	<div id="top_search">
<!-- 		<form id="top_search_form">
			<input style="width: 400px;margin-top: 3px;" type="text" id="top_search_input" placeholder="Search Music..." />
		</form> -->
		
		
<div id="srchbox">
<form class="search" name="searchform" id="top_search_form" method="GET" action="<%= App.baseURL %>searchSuggest.php">

<div class="switch switch-blue">
      <input type="radio" class="switch-input" name="search-type" value="music" id="toggle-music" checked>
      <label for="toggle-music" class="switch-label switch-label-off">Music</label>
      <input type="radio" class="switch-input" name="search-type" value="videos" id="toggle-videos">
      <label for="toggle-videos" class="switch-label switch-label-on">Videos</label>
      <span class="switch-selection"></span>
</div>
<input class="search_query" style="width: 400px;margin-top: 3px;display: inherit;" type="text" id="top_search_input" title="Type Artist Name And/Or Song" name="search" value="Search for music..." autocomplete="off" onclick="this.value=''" x-webkit-speech="x-webkit-speech" />

<input type="hidden" name="type" id="type" value="mp3">
      <input type="hidden" name="page" value="1">
      <input type="hidden" name="changeurl" value="1">

	</form>

</div>

	</div>
	<div id="top_tip" class="top_tip_hidden"></div>
		  	<script type="text/javascript">
  ( function() {
    if (window.CHITIKA === undefined) { window.CHITIKA = { 'units' : [] }; };
    var unit = {"calltype":"async[2]","publisher":"ccastillo","width":550,"height":250,"sid":"Index HXC","color_site_link":"ffffff","color_button":"1b79b8","color_button_text":"ffffff"};
    var placement_id = window.CHITIKA.units.length;
    window.CHITIKA.units.push(unit);
    document.write('<div id="chitikaAdBlock-' + placement_id + '"></div>');
}());
</script>
<script type="text/javascript" src="//cdn.chitika.net/getads.js" async></script>
</div>
