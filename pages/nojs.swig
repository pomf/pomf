{% extends "layout.swig" %}

{% block body %}
<div class="jumbotron">
    <header>
        <h1>Pantsu~</h1>
        <p lang="ca">Ropa Interior~</p>
    </header>
	<p class="lead">Max upload size is {{max_upload_size}}MiB, read the <a href="faq.html">FAQ</a></p>
	{% include "check_fileapi.swig" %}
	{% include "upload_form_nojs.swig" %}
</div>
{% include "banners.swig" %}
{% include "nav.swig" %}
{% endblock %}
