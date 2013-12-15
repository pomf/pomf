function createRequestObject(){
var request_o;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer"){
request_o = new ActiveXObject("Microsoft.XMLHTTP");
}else{
request_o = new XMLHttpRequest();
}
return request_o;
}

var http = createRequestObject();

function liveSearch()
{
var url = "../includes/api.php?do=search";
var s = document.getElementById('qsearch').value;
var params = "&s="+s;
http.open("POST", url, true);

http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
http.setRequestHeader("Content-length", params.length);
http.setRequestHeader("Connection", "close");

http.onreadystatechange = function() {
if(http.readyState == 4 && http.status != 200) {
document.getElementById('searchResults').innerHTML='<li>Loading...</li>';
}
if(http.readyState == 4 && http.status == 200) {
document.getElementById('searchResults').innerHTML = http.responseText;
}
}
http.send(params);
}

function sendToSearch(str){
document.getElementById('qsearch').value = str;
document.getElementById('searchResults').innerHTML = "";
}
