<%
	 var details = arguments[0];
%>
<table>
	<tr>
		<td class="bio-image"><img src="<%= details.image %>" width="320px" height="250px"></td>
		<td class="bio-details"><strong class="bio-title"><%= details.artist %></strong><br>
		<%= details.bio %><br><br>
		<strong>Genres:</strong> 
		<% for(var b = 0, blen = details.genres.length; b < blen; ++b){ %>
			<%= details.genres[b] %><% if((b + 1) !== blen){ %>, <% } %>
		<% } %>
		<br><br>
		<strong>We think you may also like:</strong><br>
		<% for(var i = 0, len = details.suggestions.length; i < len; ++i){ %>
			<a href="/music/<%= App.Util.seoFriendlyDashes(details.suggestions[i]) %>"><%= details.suggestions[i] %></a><% if((i + 1) !== len){ %>, <% } %>
		<% } %>
	</td>
	</tr>
</table>