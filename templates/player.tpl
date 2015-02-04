	<div id="bottom_controls">
		<div id="prev_button" class="controls_button"></div>
		<div id="play_button" class="play_button controls_button"></div>
		<div id="next_button" class="controls_button"></div>
	</div>
	<div id="volume">
		<div id="volume_speaker" class="volume_on"></div>
		<div id="volume_back">
			<div id="volume_thumb"></div>
		</div>
	</div>
	<div id="display">
<!-- 		<audio id="player-element" src="http://mediaelementjs.com/media/AirReview-Landmarks-02-ChasingCorporate.mp3" type="audio/mp3" controls="false"></audio> -->
		<span class="now-playing"><strong>Now Playing:</strong> <span class="now-playing-artist"></span></span>
		<span id="audio-span"></span>
		<div id="display_coverart" class="hide_when_stopped display_none"> <img src="<% App.baseURL %>/images/glare_45x45.png" width="40" height="40" class="display_coverart_glare" /></div>
<!-- 		<div id="display_logo" class="hide_when_playing"></div> -->
		<div id="display_text" class="hide_when_stopped display_none"> <a id="display_song"></a> <a id="display_artist"></a>
			<div id="display_album"></div>
			<a id="display_domain" target="_blank" outbound_type="bottom_player_source"></a> </div>
		<div id="display_time" class="hide_when_stopped display_none">
			<div id="display_time_count"></div>
			<div id="display_progress"></div>
			<div id="display_progressed"></div>
			<div id="display_seek_thumb"></div>
			<div id="display_time_total"></div>
		</div>
		<div id="current_song_love_icon" class="hide_when_stopped display_none tooltip" title="Love this song"></div>
		<div id="current_song_share_icon" class="hide_when_stopped display_none tooltip" title="Share this song"></div>
	</div>
	<div id="playlist_button" title="Open or close Queue" class="tooltip"></div>
	<div id="shuffle_button" title="Shuffe" class="tooltip"></div>
	<a href="#" id="player-download-button" data-bypass="true">
		<div id="download_button" title="Download" class="tooltip icon-uniE600"></div>
	</a>