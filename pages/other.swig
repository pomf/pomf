{#
Copyright (c) 2013 Peter Lejeck <peter.lejeck@gmail.com>
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

{% block title %}Other Services{% endblock %}
{% block body %}
<div class="jumbotron">
	<h1>Other Services</h1>
</div>
<ul>
	<li><a href="//git.pantsu.cat">Git</a></li>
	<li><a href="firefox/">Firefox /g/uide</a></li>
	<li><a href="http://mg7aqol36qpx7oij.onion/">Hidden Service</a>
	<li><a href="//no.pantsu.cat">JS Free version</a>
</ul>
{% include "nav.swig" %}
{% endblock %}
