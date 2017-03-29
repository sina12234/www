<?php
if(session_status()!=PHP_SESSION_ACTIVE)session_start();
$sid = session_id();
$sname = session_name();
if(!empty($sid) && !empty($sname)){
	setcookie($sid, $sname, 3600*24*30, "/", ".yunke.com");
}
?>
(function(){
var domain = document.domain.split('.').slice(-2).join('.');
<?php
if(!empty($sid) && !empty($sname)){
	echo "document.cookie = '$sname=$sid;path=/;domain='+domain;\n";
}
foreach($_COOKIE as $k=>$v){
	if(!empty($v)){
		echo "document.cookie = '$k=$v;path=/;domain='+domain;\n";
	}
}
?>


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
//获取cookie
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
    }
    return "";
}
//清除cookie  
function clearCookie(name) {  
    setCookie(name, "", -1);  
}  
function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}
if(getCookie("sid")=="")clearCookie("sid");
})();
