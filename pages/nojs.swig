{% extends "layout.swig" %}

{% block body %}
<div class="jumbotron">
	<h1>Pantsu~</h1>
	<p class="lead">Max upload size is {{max_upload_size}}MiB, read the <a href="faq.html">FAQ</a></p>
	{% include "upload_form_nojs.swig" %}
</div>
{% include "banners.swig" %} {# Deprecated since version 2.1.0. #}
{% include "nav.swig" %}
{% endblock %}
