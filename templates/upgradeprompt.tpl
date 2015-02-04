<div class="upgrade-wrapper">
	<img src="<%= App.baseURL %>/images/protect.png"><br>
	<% if(arguments[0]){ %>
		<span>
			You have reached your download limit for today.<br>
			<small style="font-size: 13px;">Upgrade to premium for unlimited daily downloads, and improved speed.</small>
		</span>
	<% }else{ %>
		<span>
			Oops, This is a premium feature.<br>
			<small style="font-size: 13px;">Upgrade for premium access, unlimited downloads, and improved speed.</small>
		</span>
	<% } %>
</div>