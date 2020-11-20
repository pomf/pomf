{% extends "layout.swig" %}

{% block title %}Tools{% endblock %}
{% block body %}
<div class="jumbotron">
<h1>Pomf Tools</h1>
</div>
    <h3>
      To upload using curl or make a tool you can post using:

      <br/>

      curl -i -F files[]=@yourfile.jpeg {{siteUrl}}/upload.php (JSON Response)
    </h3>
<section>
	<h2>pomfload</h2>
	<dl>
		<dt>Download</dt>
		<dd><a href="https://github.com/ewhal/pomfload">https://github.com/ewhal/pomfload</a></dd>
		<dt>Contact</dt>
		<dd><a href="https://twitter.com/AnonymousLink">https://twitter.com/AnonymousLink</a></dd>
	</dl>
</section>
<section>
	<h2>ShareX</h2>
	<dl>
		<dt>Download</dt>
		<dd><a href="https://getsharex.com">https://getsharex.com</a></dd>
		<dt>Source</dt>
		<dd><a href="https://github.com/ShareX/ShareX">https://github.com/ShareX/ShareX</a></dd>
		<dt>Contact</dt>
		<dd><a href="https://webchat.freenode.net/?channels=%23ShareX">#ShareX on irc.freenode.net</a></dd>
	</dl>
</section>
<section>
	<h2>Gyazowin</h2>
	<dl>
		<dt>Download</dt>
		<dd><a href="https://github.com/avail/gyazowin">https://github.com/avail/gyazowin</a></dd>
		<dt>Contact</dt>
		<dd><a href="https://twitter.com/4vail">https://twitter.com/4vail</a></dd>
	</dl>
</section>
<section>
	<h2>Pomfshare</h2>
	<dl>
		<dt>Download</dt>
		<dd><a href="https://github.com/Nyubis/Pomfshare">https://github.com/Nyubis/Pomfshare</a></dd>
		<dt>Contact</dt>
		<dd><a href="https://github.com/Nyubis">https://github.com/Nyubis</a></dd>
	</dl>
</section>
<section>
	<h2>Pomf Rehost</h2>
	<dl>
		<dt>Download</dt>
		<dd><a href="https://git.fuwafuwa.moe/lesderid/pomf-rehost">https://git.fuwafuwa.moe/lesderid/pomf-rehost</a></dd>
		<dt>Contact</dt>
		<dd><a href="mailto:les@fuwafuwa.moe">les@fuwafuwa.moe</a></dd>
	</dl>
</section>
{% include "nav.swig" %}
{% endblock %}
