{#
Copyright (c) 2013 Peter Lejeck <peter.lejeck@gmail.com>
Copyright (c) 2014 Eric Johansson <neku@pomf.se>
Copyright (c) 2015 the Pantsu.cat developers <hostmaster@pantsu.cat>

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#}

{% extends "layout.swig" %}

{% block title %}FAQ{% endblock %}
{% block body %}
<div class="jumbotron">
	<h1><abbr title="Frequently asked questions">FAQ</abbr></h1>
	{% include "nav.swig" %}
</div>
<ul>
	<li>Pantsu does NOT support Internet Explorer and older versions of Firefox, Chrome, Opera and Safari.</li>
	<li>Uploading tools for Pantsu can be found at Tools. (Currently working to update them all to work with pantsu.cat)</li>
	<li>Max file size is {{max_upload_size}}MiB, hopefully I won't have to change this to a even lower number. (read: don't be an ass).</li>
	<li>No Child porn, anything else goes until I get a lawsuit I guess. (unless its a virus, because that goes into the being an ass category).</li>
	<li>All filetypes are allowed, ALL! (gif,exe,jpg,rar,ass,srt,etc,etc).</li>
	<li>No data is collected or logged by us. This won't change. Cloudflare isn't in use</li>
	<li>Please contact me at <a href="mailto:contact@pantsu.cat">contact@pantsu.cat</a> for any questions.</li>
	<li>If you wish to send me any DMCA requests please contact me at <a href="mailto:abuse@pantsu.cat">abuse@pantsu.cat</a></li>
	<li>If any changes to these policies happen you will be informed about them.</li>
	<li>I will keep the site as transparent as possible. This will include any files that I remove will be publically listed and have a copy of the DMCA notice. For more information please visit the <a href="//transparency.pantsu.cat">transparency information</a> page.</li>
</ul>
{% include "banners.swig" %}
{% endblock %}
