<%
	var results = App.Model.Favorites.models;
	for(var i = 0, len = results.length; i < len; ++i){
%>

	<div class="song_row a_song true">
		<div class="song_row_cover_art" style="background : url(<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(results[i].get('name'))) %>.png); background : url(<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(results[i].get('name'))) %>.png); background-size: 75px 75px;">
        	<img class="validateImg" src="" data-validate-url="<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(results[i].get('name'))) %>.png" style="position:absolute;">
		<div class="song_row_play_button song_view_play_button play-song" data-playtime="<%= results[i].get('bitrate') %>" data-song-id="<%= results[i].get('songId') %>" data-song-title="<%= results[i].get('name') %>"></div>
		<img src="<%= App.baseURL %>/images/glare_75x75.png" width="75" height="75">
    </div>
	<div class="song_row_box">
 	   <div class="song_row_metadata">
		   <a href="<%= App.baseURL %>/music/<%= App.Util.seoFriendlyDashes(decodeURI(results[i].get('name'))) %>" data-bypass="true" data-playtime="<%= results[i].get('bitrate') %>" data-song-id="<%= results[i].get('songId') %>" data-song-title="<%= results[i].get('name') %>" class="song_row_title song_row_meta_text play-song"><%= decodeURI(App.Util.fixMixedUni(results[i].get('name'))) %></a>
	        <div class="song_row_artist song_row_meta_text"><a href="#"></a>
	        	        </div>
	        <div class="song_row_via song_row_meta_text">
	            
	            <span class="song_row_album song_row_meta_text"></span>
	        
	            
	        </div>
	    </div>
		<div class="song_actions">
			<div class="song_action song_action_remove song_view_remove tooltip icon-remove" title="Remove This Song" data-song-id="<%= results[i].get('songId') %>"></div>
            <div class="song_action song_action_queue song_view_queue tooltip" title="Add to Queue" data-song-id="<%= results[i].get('songId') %>"></div>
	        <div class="song_action song_action_share song_view_share tooltip" title="Share This song" data-song-id="<%= results[i].get('songId') %>"></div>
	    </div>
	</div>

    <div class="song_row_recent_loves">
		<a href="#" class="download-button" data-bypass="true" data-song-id="<%= results[i].get('songId') %>" data-playtime="<%= results[i].get('bitrate') %>" data-song-title="<%= results[i].get('name') %>"><img src="<%= App.baseURL %>/images/download2.png"></a>
		<div class="recent_loves_box">
			<br class="clear">
        </div>
	</div>
</div>
<% } %>
<% if(results.length === 0){ %>
	<div class="error-wrapper"><div class="error-message">No Favorites Found.</div><br>Start by searching for music and adding your favorite songs!</div>
<% } %>