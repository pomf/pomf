<!DOCTYPE HTML>
<html>
	<head>
		<title>Moe Panel</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
			<link rel="stylesheet" href="css/noscript.css" />
		</noscript>
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
	</head>
	<body class="homepage">

		<!-- Wrapper-->
			<div id="wrapper">
				
				<!-- Nav -->
					<nav id="nav">
						<a href="#me" class="fa fa-home active"><span>Home</span></a>
						<a href="#files" class="fa fa-folder"><span>Files</span></a>
						<a href="http://twitter.com/nekunekus" target="_BLANK" class="fa fa-twitter"><span>Twitter</span></a>
					</nav>

				<!-- Main -->
					<div id="main">
						
						<!-- Me -->
							<article id="me" class="panel">
								<header>
									<h1><?php session_start();
									if(!isset($_SESSION['id'])){
										header('Location: ../login/');
										}
									echo 'Hi '.$_SESSION['email'];?></h1>
									<span class="byline">Are you being cayoot today?</span>
								</header>
								<a href="#files" class="jumplink pic">
									<img src="images/cute.png" alt="" />
								</a>
							</article>

						<!-- Work --> 
							<article id="files" class="panel">
								<header>
									<h2>Files</h2>
								</header>
								<p>Search for any file by orginal name, extension or new name, each search is limited to 5 results for now. Later on this will only show your files and you will be able to delete them and whatnot.</p>
								
								<script type="text/javascript"> 
//<![CDATA[

function showResult(str)
{
if (str.length==0)
{ 
document.getElementById("livesearch").innerHTML="";
document.getElementById("livesearch").style.border="0px";
return;
}
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
else
{
// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
document.getElementById("livesearch").style.border="1px solid #A5ACB2";
}
}
xmlhttp.open("GET","../includes/api.php?do=search&q="+str,true);
xmlhttp.send();
}
//]]>
</script>
<form>
<table border="0">
<tr><td>File Search:</td>
<td><input type="text" size="15" onKeyUp="showResult(this.value)"></td>
</tr>
</table>
<div id="livesearch"></div>
</form>


						</div>
				<!-- Footer -->
					<div id="footer">
						<ul class="links">
							<li><a href="http://pomf.se/">Pomf</a></li>
							<li><a href="http://p.pomf.se/">Paste</a></li>
							<li><a href="http://blog.pomf.se">Blog</a></li>
							<li><a href="http://pomf.se/faq.html">FAQ</a></li>
							<li><a href="../includes/api.php?do=logout">Logout</a></li>
						</ul>
					</div>
		
			</div>

	</body>
</html>