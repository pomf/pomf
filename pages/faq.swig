{% extends "layout.swig" %}

{% block title %}FAQ{% endblock %}
{% block body %}
<div class="jumbotron">
<h1>FAQ</h1>
{% include "nav.swig" %}
</div>
<ul>
    <li>Pantsu does NOT support Internet Explorer and older versions of Firefox, Chrome, Opera and Safari.</li>
    <li>Uploading tools for Pantsu can be found at Tools. (Currently working to update them all to work with pantsu.cat)</li>
    <li>Max file size is {{max_upload_size}}MiB, hopefully I won't have to change this to a even lower number. (read: don't be an ass).</li>
    <li>No Child porn, anything else goes until I get a lawsuit I guess. (unless its a virus, because that goes into the being an ass category).</li>
    <li>All filetypes are allowed, ALL! (gif,exe,jpg,rar,ass,srt,etc,etc).</li>
    <li>No data is collected or logged by us. This won't change. Cloudflare isn't in use</li>
    <li>Please contact me at contact @ pantsu.cat for any questions.</li>
    <li>If you wish to send me any DMCA requests please contact me at <a href="mailto:abuse@pantsu.cat>abuse@pantsu.cat</a></li>
    <li>All content uploaded is owned by the user who uploaded it.</li>
    <li>If any changes to these policies happen you will be informed about them.</li>
    <li>I will keep the site as transparent as possible. This will include any files that I remove will be publically listed and have a copy of the DMCA notice. For more information please visit the <a href="//transparency.pantsu.cat">transparency information</a> page.</li>
</ul>
{% include "banners.swig" %}
{% endblock %}
