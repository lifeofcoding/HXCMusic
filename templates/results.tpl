<% if(App.resultsType === 'music' && count === 0){ %><div id="bio"></div><br><% } %>
<% if(count === 0){ %>
<center><iframe id="ad-frame" src="<%= App.baseURL %>/mediaget/inside.php?search=<%= App.Search.getParams().search %>" scrolling="no"></iframe></center>
<% } %>
<% if(App.resultsType === 'music' && count === 0){ %><div id="albums"></div><br><% } %>
<%
   if(App.resultsType === 'music'){
	var results = App.Model.Music.models,
   		checkFavs, isFav;
	for(var i = count, len = results.length; i < len; ++i){
	if(typeof App.Model.Favorites !== 'function'){
		checkFavs = App.Model.Favorites.findWhere({
			songId: results[i].get('id')
		});
		isFav = (typeof checkFavs !== 'undefined');
	}else{
		isFav = false;
	}
%>

	<div class="song_row a_song true">
		<div class="song_row_cover_art" style="background : url(<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(results[i].get('name'))) %>.png); background : url(<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(results[i].get('name'))) %>.png); background-size: 75px 75px;">
			<img class="validateImg" src="" data-validate-url="<%= App.apiURL %>/albumcover/<%= App.Util.seoFriendlyDashes(encodeURI(results[i].get('name'))) %>.png" style="position:absolute;">
        <div class="song_row_play_button song_view_play_button play-song" data-playtime="<%= results[i].get('bitrate') %>" data-song-id="<%= results[i].get('id') %>" data-song-title="<%= results[i].get('name') %>"></div>
			<img src="<%= App.baseURL %>/images/glare_75x75.png" width="75" height="75">
    </div>
	<div class="song_row_box">
 	   <div class="song_row_metadata">
		   <a href="<%= App.baseURL %>/music/<%= App.Util.seoFriendlyDashes(decodeURI(results[i].get('name'))) %>" data-bypass="true" data-playtime="<%= results[i].get('bitrate') %>" data-song-id="<%= results[i].get('id') %>" data-song-title="<%= results[i].get('name') %>" class="song_row_title song_row_meta_text play-song"><%= decodeURI(App.Util.fixMixedUni(results[i].get('name'))) %></a>
	        <div class="song_row_artist song_row_meta_text"><a href="#"></a>
	        	        </div>
	        <div class="song_row_via song_row_meta_text">
	            
	            <span class="song_row_album song_row_meta_text"></span>
	        
	            
	        </div>
	    </div>
		<div class="song_actions">
	    
	        <div class="song_action song_action_love song_view_love song_view_love_276 tooltip <%= isFav ? 'on' : '' %>" title="Add to Favorites" data-song-id="<%= results[i].get('id') %>"></div>
	    
            <div class="song_action song_action_queue song_view_queue tooltip" title="Add to Queue" data-song-id="<%= results[i].get('id') %>"></div>
	        <div class="song_action song_action_share song_view_share tooltip" title="Share This Song" data-song-id="<%= results[i].get('id') %>"></div>
	    </div>
	</div>

    <div class="song_row_recent_loves">
		<a href="#" class="download-button" data-bypass="true" data-playtime="<%= results[i].get('bitrate') %>" data-song-id="<%= results[i].get('id') %>" data-song-title="<%= results[i].get('name') %>"><img src="<%= App.baseURL %>/images/download2.png"></a>
		<div class="recent_loves_box">
			<br class="clear">
        </div>
	</div>
</div>
<%
	}
   }else{
   	var results = App.Model.Videos.models;
	for(var i = 0, len = results.length; i < len; ++i){
%>
		<div class="song_row a_song true">
		<a href="<%= App.baseURL %>/video/<%= results[i].id %>/<%= App.Util.seoFriendlyDashes(results[i].get('title')) %>">
		<div class="song_row_cover_art" style="background : url(<%= results[i].get('image') %>); background : url(<%= results[i].get('image') %>); background-size: 75px 75px;">
        <div class="song_row_play_button song_view_play_button"></div>
			<img src="<%= App.baseURL %>/images/glare_75x75.png" width="75" height="75">
    	</div>
		</a>
	<div class="song_row_box">
 	   <div class="song_row_metadata">
		   <a href="<%= App.baseURL %>/video/<%= results[i].id %>/<%= App.Util.seoFriendlyDashes(results[i].get('title')) %>" class="song_row_title song_row_meta_text"><%= results[i].get('title') %></a>
	        <div class="song_row_artist song_row_meta_text"><a href="#"></a>
	        	        </div>
	        <div class="song_row_via song_row_meta_text">
	            
	            <span class="song_row_album song_row_meta_text"></span>
	        
	            
	        </div>
	    </div>
		<div class="song_actions" style="display:none">
	    
	        <div class="song_action song_action_love song_view_love song_view_love_276 tooltip" title="Add this song to favorites" data-song-id="<%= results[i].get('id') %>"></div>
	    
            <div class="song_action song_action_queue song_view_queue tooltip" title="Add to Queue" data-song-id="<%= results[i].get('id') %>"></div>
	        <div class="song_action song_action_share song_view_share tooltip" title="Share this song" data-song-id="<%= results[i].get('id') %>"></div>
	    </div>
	</div>

    <div class="song_row_recent_loves">
		<a href="#" class="download-video-button" data-bypass="true" data-video-id="<%= results[i].get('id') %>" data-video-title="<%= results[i].get('title') %>" data-video-image="<%= results[i].get('image') %>" data-video-desc="<%= App.Util.encode(results[i].get('description')) %>"><img src="<%= App.baseURL %>/images/download2.png"></a>
		<div class="recent_loves_box">
			<br class="clear">
        </div>
	</div>
</div>
<%
   }
  }
%>