{#
Copyright (c) 2013 Peter Lejeck <peter.lejeck@gmail.com>
Copyright (c) 2014 Eric Johansson <neku@pomf.se>
Copyright (c) 2015 the Pantsu.cat developers <hostmaster@pantsu.cat>
Copyright (c) 2015 Cupkle <cupkle@openmailbox.org>

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
	<h1>FAQ</h1>
	{% include "navca.swig" %}
</div>
<ul>
	<li>Pantsu no soporta Internet Explorer o versions velles de Firefox, Chrome, Opera o Safari.</li>
	<li>Existeixen estris de pujada per a Pantsu en <a href="/toolsca.html">Estris</a>.</li>
	<li>El pes màxim per fitxer és de {{max_upload_size}}MiB. Per favor, no abuseu el servici.</li>
	<li>No es permiteix pornografia infantil o virus.</li>
	<li>Qualsevol tipus de fitxer està permitit (gif,exe,jpg,rar,ass,srt,etc,etc).</li>
	<li>No guardem ningun tipus de dades sobre els usuaris, cosa que no canviarà. No utilitzem Cloudflare</li>
	<li>Contacteu l'administrador amb l'adreça <a href="mailto:contact@pantsu.cat">contact@pantsu.cat</a> (en anglés).</li>
	<li>Envieu qualsevol petició DMCA a <a href="mailto:abuse@pantsu.cat">abuse@pantsu.cat</a></li>
	<li>Si canviem la nostra política, t'informarem.</li>
	<li>La pàgina intenta ser el més transparent possible. Qualsevol arxiu borrat serà llistat públicament amb la còpia de l'avís DMCA. Per a més informació, visiteu <a href="//transparency.pantsu.cat">informació de transparència</a> page.</li>
</ul>
{% include "bannersca.swig" %}
{% endblock %}
