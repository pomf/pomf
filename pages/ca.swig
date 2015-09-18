{% extends "layoutca.swig" %}

{% block body %}
<div class="jumbotron">

   <header>
       <h1>Ropa Interior~</h1>
   </header>
	<p class="lead">Màxima grandària de pujada {{max_upload_size}}MiB, read the <a href="faq.html">FAQ</a></p>
	{% include "check_scriptca.swig" %}
	{% include "check_fileapica.swig" %}
	{% include "upload_formca.swig" %}
</div>
{% include "banners.swig" %}
{% include "nav.swig" %}
<script src="pomf.min.js"></script>
{% endblock %}
