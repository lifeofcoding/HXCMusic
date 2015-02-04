<div id="current_playlist_header">
	<div id="current_playlist_clear">Clear Queue</div>
	<div id="playlists"<% if(!App.User.isLoggedIn){ %> style="display:none;"<% }else{ %> style="display:none;"<% } %>>
		<select name="playlist">
			<option value="default">Default Playlist</option>
			<%
			   var playlists = App.Model.Playlists.models;
			   for(var ii = 0, len = playlists.length; ii < len; ++ii){
			%>
			<option value="<%= playlists[ii].get('id') %>"><%= playlists[ii].get('title') %></option>
			<% } %>
			<option value="new">New Playlist</option>
		</select>
	</div>
	<div id="current_playlist_close"></div>
</div>
<ul id="current_playlist_rows">
	<%
	   var results = App.Model.Queue.models;
	   for(var i = 0, len = results.length; i < len; ++i){
	%>
		<li>
			<a href="#" data-bypass="true" data-playtime="<%= results[i].get('bitrate') %>" data-song-id="<%= results[i].get('songId') %>" data-song-title="<%= results[i].get('name') %>"><%= decodeURI(results[i].get('name')) %></a>
			<a class="close" href="#" data-bypass="true">x</a>
		</li>
	<% } %>
</ul>