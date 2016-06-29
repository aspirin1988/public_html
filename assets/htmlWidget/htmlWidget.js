/**
 * moduleData = { id, x, y, width, height }
 * htmlWidget.init(moduleData);
 * htmlWidget.move(moduleData);
 * htmlWidget.dispose(id);
 * htmlWidget.hideAll();
 * htmlWidget.showAll();
 * htmlWidget.hide(id);
 * htmlWidget.show(id);
 * htmlWidget.resize();
 */

var htmlWidget = {
    swf:null
    ,getObj:function(id){return ( (document.getElementById) ? document.getElementById(id) : ( (document.all) ?  document.all[id] : ( (document.layers) ? document.layers[id] : null ) ));}
    ,config:{
        divId:'htmlWidget_',
        divClass:'htmlWidgetDiv',
        dispose:'remove',
        initresize:false,
        resizeTimer:null,
        swf:'website_swf',
        container:'htmlContainer',
        timestamp:new Date().getTime()
    }
    ,objects:{}
    ,cache:{}
    ,disposed:{}
    ,init:function(moduleData)
    {
		if (!moduleData['file'] || moduleData.file == '')
			return;
		moduleData.htmlFile = moduleData.file.split('.xml')[0]+'.html';
        this.disposed[moduleData.id] = false;
        if (this.config.initresize == false)
        {
            var f = window.onresize;
            window.onresize = function(){if (typeof f == 'function') f();htmlWidget.resize(false);};
            this.config.initresize = true;
        }

		if (this.isObject(moduleData.id))
        {
            this.show(moduleData.id);
            return;
        }
		
		this.setObject(moduleData.id, {params:moduleData});

        if (this.cache[moduleData.id])
        {
			this.cache[moduleData.id].moduleData = moduleData;
            this.fileResultHandler(this.cache[moduleData.id].data, moduleData.id);
            return;
        }

        var $t = this;
        var url =  (flashvars['basePath'] ? flashvars.basePath : '') + moduleData.file;
        url += '?time=' + this.config.timestamp;
        $.get(url, function(data)
        {
            $t.cache[moduleData.id] = {
                data:data,
                moduleData:moduleData
            };
            $t.fileResultHandler(data, moduleData.id);
        },"xml")
        .error(function(){
            $t.error(moduleData, "HtmlWidget: Error while reading external file");
        });
        
    }
	,copyObject:function(data)
	{
		var obj = {};
		for(var i in data)
		{
			if (typeof data[i] == 'object')
				obj[i] = this.copyObject(data[i]);
			else
				obj[i] = data[i];
		}
		return obj;
	}
	,mergeObject:function(obj, data)
	{
		for(var i in data)
		{
			if (typeof data[i] == 'object')
			{
				obj[i] = this.mergeObject(( typeof obj[i] == 'object' ? obj[i] : {}),data[i]);
			}
			else
				obj[i] = data[i];
		}
		return obj;
	}
	,addObject:function(id, data)
	{
		var obj = this.copyObject(data);
		this.objects[id] = obj;
	}
	,setObject:function(id, data)
	{
		var obj = this.getObject(id, {});
		obj = (data != null ? this.mergeObject(obj, data) : null);
		this.objects[id] = obj;
	}
	,getObjects:function()
	{
		return this.objects;
	}
	,getObject:function(id, def)
	{
		if (!def)
			def = null
		return (typeof this.objects[id] != 'undefined' && this.objects[id] != null ? this.objects[id] : def);
	}
	,isObject:function(id)
	{
		return (typeof this.objects[id] != 'undefined' && this.objects[id] != null);
	}
    ,fileResultHandler:function(data, id)
    {
        if (this.disposed[id])
        {
            this.disposed[id] = false;
            return;
        }
        if ( typeof data == 'string') 
        {
            var xml = new ActiveXObject('Microsoft.XMLDOM');
            xml.async = false;
            xml.loadXML( xmlData);
            data = xml;
        }
        var obj = this.xml2obj(data);
		this.setObject(id, obj);
        this.add(id, true);
    }
    ,hide:function(id)
    {
        if  ($('#' + this.config.divId + id).length > 0)
            $('#' + this.config.divId + id).hide();
    }
    ,show:function(id)
    {
        if  ($('#' + this.config.divId + id).length > 0)
            $('#' + this.config.divId + id).show();
        else
            this.add(id);
    }
    ,setHtml:function(id)
    {
        if (!this.isObjects[id])
            return;
        var obj = this.getObject(id);
		
        if (!frames[this.config.divId+'iframe_'+obj.params.id])
            return ;
        var d = frames[this.config.divId+'iframe_'+obj.params.id].document;
        d.open();
        d.write(obj.data);
        d.close();
    }
    ,resize:function(isFlash)
    {
        if (typeof isFlash == 'undefined')
            isFlash = true;
        if (isFlash)
        {
            clearTimeout(this.config.resizeTimer);        
            this.config.resizeTimer = setTimeout(function(){htmlWidget.resize(false);}, 10);
            return;
        }
        for(var i in this.objects)
            if (this.objects[i] != null)
                this.move(this.objects[i].params, false);
    }
    ,moveById:function(id, isFlash)
    {
		var params = this.getObject(id).params;
		params.id = id;
		this.move(params, isFlash);
	}
    ,move:function(data, isFlash)
    {
		if (!this.isObject(data.id))
            return;
		this.setObject(data.id, {params:data});
        if (typeof isFlash == 'undefined')
            isFlash = true;
        if (isFlash)
        {
            this.resize(true);
            return;
        }
		var $swf = $('#' + this.config.swf);
        var style = {};
		var offset = $swf.offset();
		if (data['snapping']) {
			//flash dimension
			var dimension = {
				width : $swf.width(),
				height : $swf.height()
			}
			style = {
				left: parseInt(data.x),
				top: parseInt(data.y)
			};
			style.left += offset.left;
			style.top += offset.top;
			if (typeof data['right'] != 'undefined') {
				style.width = dimension.width - parseInt(data.x) - parseInt(data.right);
			} else {
				style.width = parseInt(data.width);
			}
			if (typeof data['bottom'] != 'undefined') {
				style.height = dimension.height - parseInt(data.y) - parseInt(data.bottom);
			} else {
				style.height = parseInt(data.height);
			}
		} else {
			style = {
				width: parseInt(data.width),
				height: parseInt(data.height),
				left: parseInt(data.x),
				top: parseInt(data.y)
			};
			style.left += offset.left;
			style.top += offset.top;
		}
		if (typeof data['depth'] != 'undefined')
			style['z-index'] = data.depth;
        $('#' + this.config.divId + data.id).css(style);
    }
    ,add:function(id, recreate)
    {
        if (!this.isObject(id))
            return;
        if (typeof recreate == 'undefined')
            recreate = true;
        var obj = this.getObject(id);
        if  ($('#' + this.config.divId + obj.params.id).length < 1 || recreate == true)
        {
            var iframe = ''
                + '<iframe src="'+ (flashvars['basePath'] ? flashvars.basePath : '')+obj.params.htmlFile+'?t='+this.config.timestamp+'" id="'+this.config.divId+'iframe_'+obj.params.id+'" name="'+this.config.divId+'iframe_'+obj.params.id+'" scrolling="'+(obj.configuration.showScrollBars ? 'yes' : 'no')+'" allowtransparency="true"'
                + ' style="'+(obj.configuration.showScrollBars ? 'overflow:auto' : 'overflow:hidden')+'; padding:0px;margin:0px;border:0;" width="100%" height="100%" border="0" hspace="0" marginheight="0" marginwidth="0" vspace="0" frameborder="0" frameBorder="0">'
                + '</iframe>';
            var div = '<div id="' + this.config.divId + obj.params.id  + '" class="absolute '+this.config.divClass+'" style="overflow: hidden;margin:0px;padding:0px;'
                + '">' 
                + iframe
                + '</div>';
            if ($('#' + this.config.divId + obj.params.id).length > 0)
                $('#' + this.config.divId + obj.params.id).remove(div);
            $('#' + this.config.container).append(div);
            this.moveById(id, false);
        }
    }
    ,error:function(moduleData,text)
    {
        if (this.swf == null)
            this.swf = this.getObj(this.config.swf);
        this.swf[moduleData.errorHandler](text);
    }
    ,convert:function(v)
    {
        if (v == 'true') v = true;
        else if (v == 'false') v = false;
        return v;        
    }
    ,xml2obj:function(xml)
    {
        var obj = {
            configuration:{},
            data:{}
        };
        var $t = this;
        $(xml).find('configuration option').each(function(){
            obj.configuration[$(this).attr('id')] = $t.convert($(this).find('value').text());
        });
        obj.data = $(xml).find('data').text();
        return obj;
    }
    ,dispose:function(id)
    {
        this.disposed[id] = true;
        if (this.config.dispose == 'remove')
        {
            $('#' + this.config.divId+id).remove();
            this.objects[id] = null;
        }
        else
            $('#' + this.config.divId+id).hide();
    }
    ,hideAll:function()
    {
        $('.' + this.config.divClass).hide();
    }
    ,showAll:function()
    {
        $('.' + this.config.divClass).show();
    }
	,openPopup:function()
    {
        this.hideAll();
    }
	,closePopup:function()
    {
        this.showAll();
    }
};

var _debug={};

function initWidget(moduleData)
{
    htmlWidget.init(moduleData);
}

function moveWidget(obj)
{
    htmlWidget.move(obj);
}

function disposeWidget(id)
{
    htmlWidget.dispose(id);
}

function openPopup()
{
    htmlWidget.openPopup();
}

function closePopup()
{
    htmlWidget.closePopup();
}

function log(text)
{
    console.log(text);
}
