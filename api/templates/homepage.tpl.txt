<%
   var results = App.Suggested;
   if(results.length > 0){ $('#homepage').css({background:'#000000'}); }
	for(var i = 0, len = results.length; i < len; ++i){
%>
<div class="homepage-tiles">
	<a href="music/<%= App.Util.seoFriendlyDashes(results[i].artist) %>"><img src="<%= results[i].image %>"></a>
	<span class="artist-name"><%= results[i].artist %></span>
</div>
<%
   }
%>
	<div id="homepage-content">
	<center><span id="homepage-title"><h1>Explore music like never before</h1></span></center>
	<div id="homepage-searchbox">
		<form class="search" name="searchform" id="homepage_search_form" method="GET" action="http://hxcmusic.com/searchSuggest.php">

		<input class="search_query" style="width: 400px;margin-top: 3px;" type="text" id="homepage_search_input" title="Type Artist Name And/Or Song" name="search" value="Search for music..." autocomplete="off" onclick="this.value=''" x-webkit-speech="x-webkit-speech">

		<input type="hidden" name="type" id="type" value="mp3">
      	<input type="hidden" name="page" value="1">
      	<input type="hidden" name="changeurl" value="1">

	</form>
	</div>
	</div>