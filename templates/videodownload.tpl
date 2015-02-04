<div class="videodownload-wrapper">
	<%
	   var details = arguments[0];
	%>
	<table>
		<tr>
			<td class="download-image"><img src="<%= details.videoImage %>" width="200px" height="200px"></td>
			<td class="download-details">
			<%= App.Util.decode(details.videoDesc) %>
		</td>
		</tr>
	</table>
</div>