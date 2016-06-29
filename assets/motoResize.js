function getViewportSize() {
	var size = [0, 0];
	if (typeof window.innerWidth != "undefined") {
		size = [window.innerWidth, window.innerHeight];
	}
	else if (typeof document.documentElement != "undefined" && typeof document.documentElement.clientWidth != "undefined" && document.documentElement.clientWidth != 0) {
		size = [document.documentElement.clientWidth, document.documentElement.clientHeight];
	}
	else {
		size = [document.getElementsByTagName("body")[0].clientWidth, document.getElementsByTagName("body")[0].clientHeight];
	}
	return size;
}

function createFullBrowserFlash() {
	swfobject.createCSS("html", "height:100%;");
	swfobject.createCSS("body", "height:100%;");
	swfobject.createCSS("#flashcontent", "margin:0; width:100%; height:100%; min-width:" + minWidth + "px; min-height:" + minHeight + "px;");
	window.onresize = function() {
		var el = document.getElementById("container");
		var size = getViewportSize();
		el.style.width = size[0] < minWidth ? minWidth + "px" : "100%";
		el.style.height = size[1] < minHeight ? minHeight + "px" : "100%";
	};
	window.onresize();
}

function setScreenHeight(value)
{
    if (!useFlashScroll)
    {
		$(window).scrollTop(0);
        $('#'+attributes.id).height(value);
        minHeight = value;
        return true;
    }
    return false;
}

function getScreenRect()
{
	var w = $(window);
	var swf = $('#'+attributes.id);
	var div = swf.parent();

	return [w.scrollLeft(), w.scrollTop(), Math.min(div.width(), swf.width()), Math.min(div.height(), swf.height())];
}

var $util = {
    swf:null
    ,getObj:function(id){return ( (document.getElementById) ? document.getElementById(id) : ( (document.all) ?  document.all[id] : ( (document.layers) ? document.layers[id] : null ) ));}
    ,bindEvent:function(obj, event, callback) 
    {
        if (typeof obj == 'string') 
            obj = document.getElementById(obj);
        if (!obj) 
            return false;
        if (obj.attachEvent) 
            obj.attachEvent('on' + event, callback);
        else if (obj.addEventListener) 
        {
            if (event == 'mousewheel') 
                obj.addEventListener('DOMMouseScroll', callback, false);
            obj.addEventListener(event, callback, false);
        }
        else 
            return false; 
        return true;
    }
    ,stopBrowserAction:function(e)
    {
        e = e ? e : window.event;
        if (e.preventDefault) 
            e.preventDefault();
        if (e.stopPropagation) 
            e.stopPropagation();
        e.cancelBubble = true;
        e.cancel = true;
        e.returnValue = false;
        return false;
    }
    ,getSwf:function()
    {
        if (this.swf == null)
            this.swf = this.getObj(attributes.id);
        return this.swf;
    }
    ,initBindScroll:function()
    {
        var swf = this.getSwf();
        $(window).bind("scroll", function(){
            if (swf['screenScroll'])
                swf['screenScroll'](getScreenRect());
        });
    }
    ,initMouseWheel:function()
    {
        var swf = this.getSwf();
        this.bindEvent(swf, 'mousewheel', function(e)
        {
            e = e ? e : window.event;
            var delta = e.detail ? e.detail * -1 : e.wheelDelta / 40;
            if (Math.abs(delta)>100) 
                delta = Math.round(delta/100);
            if (swf['mouseWheel'])
                swf.mouseWheel(delta);
            //return $util.stopBrowserAction(e); //uncomment if need to stop actions
        });
    }
    ,initHandlers:function()
    {
        if (!useFlashScroll)
            $util.initBindScroll();
        $util.initMouseWheel();    
    }   
    
};

function initializeUtils()
{
	$util.initHandlers();
}
