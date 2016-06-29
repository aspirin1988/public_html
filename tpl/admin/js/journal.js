var dom = document.getElementById?1:0;
var ie4 = document.all && document.all.item;
var opera = window.opera; //Opera
var ie5 = dom && ie4 && !opera; 
var nn4 = document.layers; 
var nn6 = dom && !ie5 && !opera;
var vers=parseInt(navigator.appVersion);

function document_write($text)
{
	document.write($text);
	return false;
}

function swap(num){
//    var obj=document.getElement("re"+num);
    var obj=document.getElementById("re"+num);

    if(obj.style.visibility=='visible'){
          obj.style.display='none';
          obj.style.visibility='hidden';
    }else{
          obj.style.display='block';
          obj.style.visibility='visible';
    }
	return false;
  }

function swap2(num){
    var obj=document.getElementById(num);

    if(obj.style.visibility=='hidden'){
          obj.style.display='';
          obj.style.visibility='visible';
    }else{
          obj.style.display='none';
          obj.style.visibility='hidden';
    }
  }
  
function swapMore(oid){
	
	swap2('more'+oid);
	swap2('linkmore'+oid);

	return false;
}
function swapMore2(oid){
	
	var obj=document.getElementById('more' + oid);
	if(obj.innerHTML == ''){
		obj.innerHTML = '<span style="text-decoration:inherit;color:red;"><b>[загрузка...]</b><span>';
		loadV('?post='+oid);
	}
	swap2('more'+oid);
	swap2('linkmore'+oid);

	return false;
}
  
function clUploadData () {
        this.type = "text/javascript";

		this.oldScript  = document.createElement("SCRIPT");
        this.oldScript.type = "text/javascript";
		document.body.appendChild(this.oldScript);
        
		this.upload = function ( theparams ) {
			
				var tParams = theparams || "";
                var newScript = document.createElement("SCRIPT");
                newScript.type = "text/javascript";
                newScript.src  = theparams;

				document.body.replaceChild(newScript,this.oldScript);
                this.oldScript = newScript;
        }
}

if(document.body == null) document.write("<body></body>");
var uploadObject = new clUploadData();

function loadV(theparams)
{
//	window.status = 'загрузка...';
	uploadObject.upload(theparams);
}

function loadV2(link){
//	window.status = 'загрузка...';
	var script = document.createElement('script');
		script.defer = false;
		script.src = link;
	document.body.appendChild(script);
}