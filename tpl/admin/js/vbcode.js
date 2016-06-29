// *******************************************************

tags = new Array();
if (navigator.userAgent.indexOf('Opera') != -1) {//опера блин
  advanced_code = 0;
} else {
if (navigator.appName && navigator.appName.indexOf("Microsoft") != -1 &&   navigator.userAgent.indexOf("Windows") != -1 &&
navigator.userAgent.indexOf("Windows 3.1") == -1) {
 advanced_code = 1; // use IE 4+ specific createRange functionality
} else {
 advanced_code = 0;
}
}


// *******************************************************
// replacements for unsupported array functions (because arrayname.push(var)
// and arrayname.pop() are not implemented in IE until version 5.5)

function getarraysize(thearray) {
// replacement for arrayname.length property
        for (i = 0; i < thearray.length; i++) {
                if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
                        return i;
                }
        return thearray.length;
}

function arraypush(thearray,value) {
// replacement for arrayname.push(value)
        thearraysize = getarraysize(thearray);
        thearray[thearraysize] = value;
}

function arraypop(thearray) {
// replacement for arrayname.pop()
        thearraysize = getarraysize(thearray);
        retval = thearray[thearraysize - 1];
        delete thearray[thearraysize - 1];
        return retval;
}

// *******************************************************

function setmode(modevalue) {
// sets cookie for normal (0) and enhanced (1) modes
        document.cookie = "vbcodemode="+modevalue+"; path=/; expires=Wed, 1 Jan 2020 00:00:00 GMT;";
}

function normalmode(theform) {
return true;
// checks value of mode radio buttons. returns true if normal mode
//        if (null != theform.mode && theform.mode[1].checked) return false;
//        else return true;
}

function stat(thevalue) {
// places mini-help text into help/error textbox
// strings are stored in vbcode_language.js
        document.vbform.status.value = eval(thevalue+"_text");
}

// *******************************************************

function closetag(theform) {
// closes last opened tag
        if (normalmode(theform))
                stat('enhanced_only');
        else
                if (tags[0]) {
                        theform.message.value += "[/"+ arraypop(tags) +"]";
                        }
                else {
                        stat('no_tags');
                        }
        //theform.closecurrent.checked=false;
        theform.message.focus();
}

function closeall(theform) {
// closes all open tags
        if (normalmode(theform))
                stat('enhanced_only');
        else {
                if (tags[0]) {
                        while (tags[0]) {
                                theform.message.value += "[/"+ arraypop(tags) +"]";
                                }
                        theform.message.value += " ";
                        }
                else {
                        stat('no_tags');
                        }
                }
        //theform.closealltags.checked=false;
        theform.message.focus();
}

// *******************************************************

function ieWrap(textfield, open, close) {
	textfield.focus();
	sel = document.selection.createRange();
	sel.text=open+sel.text+close;
}
// From http://www.massless.org/mozedit/
function mozWrap(textfield, open, close) {
	var selLength = textfield.textLength;
	var selStart = textfield.selectionStart;
	var selEnd = textfield.selectionEnd;
	if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;

	var s1 = (textfield.value).substring(0,selStart);
	var s2 = (textfield.value).substring(selStart, selEnd)
	var s3 = (textfield.value).substring(selEnd, selLength);
	textfield.value = s1 + open + s2 + close + s3;
}
function anyWrap(textfield, open, close, prompttext) {
	inserttext = prompt(tag_prompt+"\n"+open+"xxx"+close, prompttext);
	if ((inserttext != null) && (inserttext != ""))
	textfield.value += open+inserttext+close;
}

function insertCode(textfield, vbcode, prompttext){
	var open = "<"+vbcode+">";
	var close = "</"+vbcode+">";
	if(advanced_code){
		ieWrap(textfield, open, close);
	}else if(textfield.selectionEnd && (textfield.selectionEnd - textfield.selectionStart > 0)){
		mozWrap(textfield, open, close);
	}else{
		anyWrap(textfield, open, close, prompttext);
	}
}

function insertIMG(textfield, vbcode, prompttext){
	var open = "<"+vbcode+">";
	var close = "</"+vbcode+">";
	if(advanced_code){
		ieWrap(textfield, '\n'+vbcode, '\n');
	}else if(textfield.selectionEnd && (textfield.selectionEnd - textfield.selectionStart > 0)){
		mozWrap(textfield, '\n'+vbcode, '\n');
	}else{
		anyWrap(textfield, '\n'+vbcode, '\n', prompttext);
	}
}



function vbcode(theform,vbcode,prompttext) {
// insert [x]yyy[/x] style markup
        if ((normalmode(theform)) || (vbcode=="IMG")) {
                insertCode(theform.text, vbcode, prompttext);
        }
        else {
                donotinsert = false;
          
                for (i = 0; i < tags.length; i++) {
                        if (tags[i] == vbcode)
                                donotinsert = true;
                        }
                if (donotinsert)
                        stat("already_open");
                else {
                        theform.text.value += "["+vbcode+"]";
                        arraypush(tags,vbcode);
                        }
                }
        theform.text.focus();//message.
}


function vbcode_f(theform,vbcode,prompttext) {
// insert [x]yyy[/x] style markup
        if ((normalmode(theform)) || (vbcode=="IMG")) {
                insertCode(theform.message, vbcode, prompttext);
        }
        else {
                donotinsert = false;
                for (i = 0; i < tags.length; i++) {
                        if (tags[i] == vbcode)
                                donotinsert = true;
                        }
                if (donotinsert)
                        stat("already_open");
                else {
                        theform.message.value += "["+vbcode+"]";
                        arraypush(tags,vbcode);
                        }
                }
        theform.message.focus();
}

// *******************************************************

function vbcode2(theform,vbcode,prompttext) {

		 theform.message.focus();
                 sel = document.selection.createRange();
                 sel.text=vbcode;
	         theform.message.focus();
}

// *******************************************************

function fontformat(theform,thevalue,thetype) {
// insert two-parameter markup - [x=y]zzz[/x]
        if (normalmode(theform)) {
                if (thevalue != 0) {
                        inserttext = prompt(font_formatter_prompt+" "+thetype,"");
                        if ((inserttext != null) && (inserttext != ""))
                                theform.message.value += "["+thetype+"="+thevalue+"]"+inserttext+"[/"+thetype+"]";
                        }
                }
        else {
                theform.message.value += "["+thetype+"="+thevalue+"]";
                arraypush(tags,thetype);
                }
        theform.sizeselect.selectedIndex = 0;
        theform.fontselect.selectedIndex = 0;
        theform.colorselect.selectedIndex = 0;
        theform.message.focus();
}

// *******************************************************

function namedlink(theform,thetype) {
// inserts named url or email link - [url=mylink]text[/url]
        linktext = prompt(link_text_prompt,"");
                var prompttext;
                if (thetype == "URL") {
                        prompt_text = link_url_prompt;
                        prompt_contents = "http://";
                        }
                else {
                        prompt_text = link_email_prompt;
                        prompt_contents = "";
                        }
        linkurl = prompt(prompt_text,prompt_contents);
        if ((linkurl != null) && (linkurl != "")) {
                if ((linktext != null) && (linktext != ""))
                        theform.message.value += "["+thetype+"="+linkurl+"]"+linktext+"[/"+thetype+"]";
                else
                        theform.message.value += "["+thetype+"]"+linkurl+"[/"+thetype+"]";
                }
        theform.message.focus();
}

// *******************************************************

function dolist(theform) {
// inserts list with option to have numbered or alphabetical type
        listtype = prompt(list_type_prompt, "");
        if ((listtype == "a") || (listtype == "1")) {
                thelist = "[list="+listtype+"]\n";
                listend = "[/list="+listtype+"]";
                }
        else {
                thelist = "[list]\n";
                listend = "[/list]";
                }
        listentry = "initial";
        while ((listentry != "") && (listentry != null)) {
                listentry = prompt(list_item_prompt, "");
                if ((listentry != "") && (listentry != null))
                        thelist = thelist+"[*]"+listentry+"\n";
                }
        theform.message.value += thelist+listend;
        theform.message.focus();
}

// *******************************************************


function smilie(smilietext) {
if (advanced_code) {
        document.vbform.message.focus();
        sel = document.selection.createRange();
        sel.collapse();
        sel.text= " "+smilietext+" ";
} else {
    document.vbform.message.value += smilietext+" ";
    document.vbform.message.focus();
}
}

// *******************************************************

function uEnCode(obj){
  obj.focus();
  var obj2 = document.selection.createRange();
  if(obj2.text != null && obj2.text!="")
    obj2.text = uCode(obj2.text);
}
function uCode(text){
  ecd = "";
  if (text != "") {
     for (i = 0; i < text.length; i++) ecd += "\&#" + String(text.charCodeAt(i)) + ";";
  }
  return ecd;
}
