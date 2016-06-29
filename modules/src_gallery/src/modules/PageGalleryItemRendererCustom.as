package modules 
{
    import caurina.transitions.Equations;
    import caurina.transitions.Tweener;
	import com.moto.template.common.events.MediaLoaderErrorEvent;
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.events.UpdateMotoObjectEvent;
    import com.moto.template.common.Moto;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.common.view.AnimatedProgressBar;
	import com.moto.template.common.view.IItemRenderer;
	import com.moto.template.common.view.MotoAnimatedItemRenderer;
	import com.moto.template.common.view.components.loaders.ImageLoader;
	import com.moto.template.common.tools.MotoArranger;
	import com.moto.template.common.view.ScaleModeEnum;
    import com.moto.template.modules.gallery.SimpleGalleryModule;
    import com.moto.template.shell.model.vo.ModuleItemVO;
    import com.moto.template.shell.model.vo.MotoObjectVO;
	import flash.display.BlendMode;
	import flash.errors.IOError;
	import flash.events.IOErrorEvent;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
  
    import flash.display.Loader;
    import flash.display.MovieClip;
    import flash.display.Sprite;
    import flash.display.Shape;
	import flash.display.Bitmap;
    import flash.events.Event;
    import flash.events.MouseEvent;
    import flash.events.ProgressEvent;
    import flash.events.TimerEvent;
    import flash.geom.Rectangle;
    import flash.geom.Point;
    import flash.utils.Timer;
    import flash.net.URLRequest;
 
	import caurina.transitions.Tweener;
	import caurina.transitions.properties.ColorShortcuts;
 
    public class PageGalleryItemRendererCustom extends MotoAnimatedItemRenderer
    {
		private var _image:ImageLoader;
		private var _imageHolder:MovieClip;
		private var thickness:Number = 0;
		private var _borderColor:Number = 0xFFFFFF;
		private var _borderOverColor:Number = 0xFFFFFF;
		private var _preloader:AnimatedProgressBar;
		
		//thumbnail property
		private var indent:Number = 20;
		private var newWidthItem:Number = 300;
		private var newHeightItem:Number = 200;
		private var _area:MovieClip;
		private var _border:MovieClip;
		
		private var smallImage:Boolean = true;
		
		private var childIndex:Number;
		
		private var enableBtnMode:Boolean = false;
        public function PageGalleryItemRendererCustom() 
        {
			ColorShortcuts.init();
			
            super();

			_area = this["areaHolder"];
			_imageHolder = this["imageHolder"]
			_image 	= imageHolder["image"];
			_border = border["clip"];
			_preloader = preloaderHolder["preloader"];
			
			hitArea = _area;
			
			_preloader.show();
			
			mouseChildren=false;
			addEventListener(Event.ADDED_TO_STAGE, _addedToStagehandler);
			addEventListener(Event.REMOVED_FROM_STAGE, _removeFromStageHandler);
        }
		
		
//---Override---		
		override public function updateRenderer(data:Object):void 
		{
			super.updateRenderer(data);
			
			var dataVO:ModuleItemVO = data as ModuleItemVO;
			
			_image.source = pathPrefix + dataVO.getPropertyValueByID(2);
			_image.addEventListener(Event.COMPLETE, loadCompleteHandler);
			_image.addEventListener(MediaLoaderErrorEvent.ERROR, errorHandler);

			newExtraData();
			
			setSize(extra.itemWidth, extra.itemHeight);
		}
		
		public function newExtraData():void 
		{
			thickness	= int(extra.thickness);
			_borderColor = int(extra.borderColor);
			_borderOverColor = int(extra.borderOverColor);
			MotoUtils.setColor(_border, _borderColor, extra.borderAlpha);
		}
		
		//---
        override public function setSize(newWidth:Number, newHeight:Number):void 
        {
			_area.width = newWidth;
			_area.height = newHeight;
			
			_image.setSize(newWidth, newHeight);

			_preloader.x = int(_image.width * .5);
			_preloader.y = int(_image.height * .5);

			addRoudRect(maskHolder, newWidth, newHeight, extra.roundness - Math.round(extra.roundness / 10));
			addRoudRect(_border, newWidth + thickness * 2, newHeight + thickness * 2, extra.roundness);
			_border.x = -thickness;
			_border.y = -thickness;
		}
		
		//---
		override public function getDimensions():Rectangle {
			return new Rectangle(0, 0, _area.width, _area.height);//!!!!!!!!!!!!!!!!
		}

		//---
		override protected function over():void{
			if(!enableBtnMode)
				return;
				
			super.over();
			Tweener.addTween(_border, { _color:_borderOverColor, time:0.3, transition:Equations.easeOutSine } );
		}
		
		//---
		override protected function out():void {
			if(!enableBtnMode)
				return;
			
			super.out();
			
			Tweener.addTween(_border, { _color:_borderColor, time:0.2, transition:Equations.easeOutSine } );
		}
		
//---Private---

		private function _addedToStagehandler(event:Event):void{
			removeEventListener(Event.ADDED_TO_STAGE, _addedToStagehandler);
			addEventListener("showComplete", showComplete);
		}
		
		private function _removeFromStageHandler(event:Event):void{
			removeEventListener(Event.REMOVED_FROM_STAGE, _removeFromStageHandler);
		}
		
		private function showComplete(e:Event):void 
		{
			enableBtnMode = true;
			buttonMode = true;
		}
		
		private function addRoudRect(mc:MovieClip, newWidth:Number, newHeight:Number, r:Number):void
		{
			mc.graphics.clear();
			mc.graphics.beginFill(0);
			mc.graphics.drawRoundRect(0, 0, newWidth, newHeight, r, r);
			mc.graphics.endFill();
		}
//---Events---
		private function loadCompleteHandler(e:Event):void 
		{
			_preloader.hide();
			show();
			dispatchEvent(new Event(Event.COMPLETE));
		}

		private function errorHandler(e:MediaLoaderErrorEvent):void
		{
			_preloader.hide();
			dispatchEvent(new IOErrorEvent(IOErrorEvent.IO_ERROR));
		}
	}
}