<%
   var results = App.Suggested;
	for(var i = 0, len = results.length; i < len; ++i){
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