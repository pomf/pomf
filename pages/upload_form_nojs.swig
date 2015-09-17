<form enctype="multipart/form-data" method="post" action="upload.php">
    <input type="file"  name="files[]" multiple data-max-size="{{max_upload_size}}MiB">
    <br>
    <input type="submit" value="Submit">
</form> 
<ul id="upload-filelist"></ul>
