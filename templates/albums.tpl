<% var results = arguments[0]; %>
<div id="albums-wrapper">
	<center>
		<table>
			<tr>
			<% for(var i = 0, len = results.length; i < len; ++i){ %>
				<td>
					<a href="#" data-bypass="true" class="download-album">
						<img src="<%= results[i].image %>" data-artist="<%= results[i].artist %>" data-album="<%= results[i].title %>">
					</a>
				</td>
			<% } %>
			</tr>
		</table>
	</center>
	<span id="scroll-left" class="scroll-indicator" data-direction="left"><img src="<%= App.baseURL %>/images/control_double_left.png"></span>
	<span id="scroll-right" class="scroll-indicator" data-direction="right"><img src="<%= App.baseURL %>/images/control_double_right.png"></span>
</div>