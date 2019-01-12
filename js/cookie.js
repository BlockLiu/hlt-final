function getCookie(cname)
{
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

function checkCookie(cname)
{
  var username=getCookie(cname);
  if (username!="")
  	return true;
  else 
  	return false;
}

function delCookie(cname){
	document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
}

function setCookie(cname,id){
	document.cookie = cname + "=" + id;
}