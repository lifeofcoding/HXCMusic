<%
   var details = arguments[0];
   if(typeof details === 'undefined'){
	details = {};
   	details.image = App.baseURL + '/images/music_big.png';
   	details.title = 'Not Available';
   	details.published = 'N/A';
   	details.wiki = '';
   }
%>
<table>
	<tr>
		<td class="download-image">
			<img src="<%= details.image %>" width="200px" height="200px">
			<br><strong class="download-quality">Quality: </strong>
			<span class="playtime"></span>
		</td>
		<td class="download-details">
			<span class="from-the-album">From The Album:</span><br>
			<strong class="download-title"><%= details.title %></strong><br>
			<span class="download-published"><%= details.published %></span>
		<%= details.wiki %>
	</td>
	</tr>
</table>