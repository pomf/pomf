{% extends "layout.swig" %}

{% block body %}
<div class="jumbotron">
	<h1>{{siteName}}~</h1>
	<p class="lead">Max upload size is {{max_upload_size}}&nbsp;MiB, read the <a href="faq.html"><abbr title="Frequently asked questions">FAQ</abbr></a></p>
	{% include "upload_form.swig" %}
</div>
{% include "banners.swig" %} {# Deprecated since version 2.1.0. #}
{% include "nav.swig" %}
{% endblock %}
