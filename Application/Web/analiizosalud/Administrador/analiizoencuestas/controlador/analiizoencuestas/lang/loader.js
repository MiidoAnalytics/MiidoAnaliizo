function include(file_path, file_name){
	var s = document.createElement("script");
	s.type = "text/javascript";
	s.src = file_path+"/"+file_name+".js";
	document.body.appendChild(s);
	//document.getElementsByTagName('head')[0].appendChild(l);
	//document.head.appendChild(l);
}

function include_once(file_path, file_name){
	var sc = document.getElementsByTagName("script");

	for (var x in sc)
		if (sc[x].src != null && sc[x].src.indexOf(file_path) != -1)
			return;

	include(file_path, file_name);
}

//beta
function loadScript(src, callback) {
  var s,
      r,
      t;
  r = false;
  s = document.createElement('script');
  s.type = 'text/javascript';
  s.src = src;
  s.onload = s.onreadystatechange = function() {
    if ( !r && (!this.readyState || this.readyState == 'complete') )
    {
      r = true;
      if (callback !== undefined) {
        callback();
      }
    }
  };
  t = document.getElementsByTagName('script')[0];
  t.parentNode.insertBefore(s, t);
}
var browserName = navigator.userAgent.toLowerCase();
if( browserName.search(/iphone|ipod|ipad|android/) > -1 ){
  if(document.createStyleSheet) {
    document.createStyleSheet('css/mobile.css');
  }
  else {
    var styles = "css/mobile.css";
    var newSS=document.createElement('link');
    newSS.rel='stylesheet';
    newSS.href=escape(styles);
    newSS.type = 'data:text/css';
    document.getElementsByTagName("head")[0].appendChild(newSS);
  }
}