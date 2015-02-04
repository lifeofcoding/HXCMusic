<div class="genre-wrapper" style="display:<%= count > 0 ? 'none' : 'block' %>">
<div id="genre-form" class="genre-form">
<label>Genre</label>
<select id="genre" name="genre" class="genre_dropdown">
	<option value="">Featured</option>
	<option value="Rock">Rock</option>
	<option value="Dance">Dance</option>
	<option value="Hiphop">HipHop</option>
	<option value="Pop">Pop</option>
	<option value="Alternative">Alternative</option>
	<option value="Metal">Metal</option>
	<option value="Rap">Rap</option>
	<option value="Jazz">Jazz</option>
	<option value="Indie">Indie</option>
	<option value="R%26B">R&B</option>
	<option value="Country">Country</option>
</select>
</div>
	<span id="loading-genres"><img src="/images/loader-white.gif"></span>
</div>
<%
var results = App.Page.Music;
for(var i = count, len = results.length; i < len; ++i){
%>
	<div class="song_row a_song a_song_276 true">
    <div class="song_row_cover_art" style="background : url(<%= results[i].image %>); background : url(<%= results[i].image %>);">
        <div class="song_view_play_button"></div>
		<img src="<%= App.baseURL %>/images/glare_75x75.png" width="75" height="75">
    </div>
	<div class="song_row_box">
 	   <div class="song_row_metadata">
		   <a href="<%= App.baseURL %>/music/<%= App.Util.seoFriendlyDashes(results[i].artist) %>" class="song_row_title song_row_meta_text"><%= results[i].artist %></a>
	        <div class="song_row_artist song_row_meta_text"><a href="#"></a>
	        	        </div>
	        <div class="song_row_via song_row_meta_text">
	            
	            <span class="song_row_album song_row_meta_text"><a href="#"></a></span>
	        
	            
	        </div>
	    </div>
	</div>
</div>
<%
}
%>
<% if(typeof App.Page.Music.error !== 'undefined'){ %>
	<br><br><div class="error-wrapper"><div class="error-message">Explore Error</div><br>Unable to fetch artists, please try again later.</div>
<% } %>