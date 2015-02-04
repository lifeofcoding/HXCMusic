<%
   var details = arguments[0];
%>
<div class="stream-wrapper" data-song-id="<%= details.songId %>" data-song-title="<%= details.songTitle %>">
	<div class="stream-album">
		<img src="<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(details.songTitle)) %>.png" width="42px" height="42px">
	</div>
	<div class="stream-details">
		<%= details.songTitle %>		
	</div>
</div>