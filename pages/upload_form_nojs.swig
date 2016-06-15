<form enctype="multipart/form-data" method="post" action="upload.php?output=html">
	<input type="file" name="files[]" multiple data-max-size="{{max_upload_size}}MiB">
	<input type="submit" value="Submit">
</form> 
<ul id="upload-filelist"></ul>
