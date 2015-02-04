<form class="uploader-wrapper" action="#" method="post" enctype="multipart/form-data" id="MyUploadForm">
	<div id="output"></div>
	<input name="hasImage" id="hasImage" type="hidden" value="<%= App.User.image %>">
	<input name="username" id="username" type="hidden" value="<%= App.User.username %>">
	<input name="imageFile" id="imageInput" type="file" />
</form>