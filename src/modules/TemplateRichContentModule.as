package modules{
	import caurina.transitions.properties.ColorShortcuts;
	import caurina.transitions.Tweener;
	import com.moto.template.common.view.AdvancedScrollbar;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.modules.richContent.RichContentModule;
	import flash.display.MovieClip;
	import com.moto.template.modules.catalog.InfoModule;
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	import flash.events.Event;
	import com.moto.template.common.utils.MotoUtils;

	public class TemplateRichContentModule extends RichContentModule {
		private var _propertyContentYOffset:uint = 0;
		private var _propertyContentHeight:uint = 0;
		private var _propertyTrackHeight:uint = 0
		private var _propertyScrollbarYOffset:int = 0
		private var _button:MovieClip;
		private var _track:MovieClip;
		private var _trackGuide:MovieClip;
		private var _scrollbarClip:MovieClip;
		private var _scrollbar:AdvancedScrollbar;
		private var _preview:MovieClip;
		
		private var _colorBase:uint;
		private var _colorActive:uint;

		public function TemplateRichContentModule() {
			super();

			_scrollbarClip = MovieClip(getChildByName("scrollbar"));
			_button = _scrollbarClip.getChildByName("button") as MovieClip;
			_track = _scrollbarClip.getChildByName("track") as MovieClip;
			_trackGuide = _scrollbarClip.getChildByName("trackGuide") as MovieClip;
			
			_preview = MovieClip(getChildByName("preview"));
			_preview.visible = false;

			addEventListener(INITIALIZATION_COMPLETE, moduleInitializationCompleteHandler);
			addEventListener(Event.ADDED_TO_STAGE, addedToStageHandler);
			
			ColorShortcuts.init();
		}
		override public function init(vo:MotoObjectVO):void {
			_preview.visible = false;

			super.init(vo);
		}
		override public function getDimensions():Rectangle
		{
		return new Rectangle(0, 0, moduleArea.width, moduleArea.height);
		}

		override public function setSize(newWidth:Number, newHeight:Number):void
		{
      super.setSize(newWidth, newHeight);
			//maskedHolder.y = _propertyContentYOffset;
			
			moduleArea.width = newWidth;
			moduleArea.height = newHeight;
			
			_moduleAreaRectangle = new Rectangle(moduleArea.x, moduleArea.y,
				moduleArea.width, moduleArea.height);

			maskedHolder.scrollRect = _moduleAreaRectangle;
			
			// Move track and button
			_scrollbarClip.x = _moduleAreaRectangle.right + 8;
			_scrollbarClip.y = _propertyScrollbarYOffset;
			_track.g.height = _propertyTrackHeight + 6;
			_trackGuide.height = _propertyTrackHeight;
			
			
			
			
			_preview.width = newWidth;
			_preview.height = newHeight;

			// Scrollbar
			if (_scrollbar) {
				_scrollbar.update();
			}
		}
		override public function updateProperty(property:PropertyVO):void {
			switch (property.propertyType) {
					
				case 1 :
					_colorBase = uint(property.value);
					MotoUtils.setColor(_button.g, _colorBase);
					break;
                 
				case 2 :
					_colorActive = uint(property.value);
					break;
					
				case 3 :
					break;
					
				case 4 :
					MotoUtils.setColor(_track.g, Number(property.value));
					MotoUtils.setColor(_button.arr1.g, Number(property.value));
					MotoUtils.setColor(_button.arr2.g, Number(property.value));
					break;
				
				case 5 :
				    _track.alpha = uint(property.value) / 100;
					break;
					
				case 6 :
					_propertyScrollbarYOffset = uint(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
                 
				case 7 :
					_propertyTrackHeight = uint(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
			}
		}
		
		override public function dispose():void {
			super.dispose();

			// Scrollbar
			if (_scrollbar) {
				_scrollbar.remove();
			}
		}
		
		private function moduleInitializationCompleteHandler(event:Event):void {
			if (limitedMode && richContentHolder.numChildren == 0) {
				_preview.visible = true;
			}

			// Scrollbar
			if (_scrollbar) {
				_scrollbar.update();
			}
		}
		
		private function addedToStageHandler(event:Event):void {
			_scrollbar = new AdvancedScrollbar(contentHolder, moduleAreaRectangle, stage);
			_scrollbar.roundedPositionValues = true;
			_scrollbar.setControls(_trackGuide, _button, _track, null, null, _scrollbarClip);
			_button.addEventListener(MouseEvent.ROLL_OVER, mouseOverHandler);
			_button.addEventListener(MouseEvent.ROLL_OUT, mouseOutHandler);
		}
		
		private function mouseOverHandler(event:Event):void {
			_button.gotoAndPlay("over");
			Tweener.addTween(_button.g, { _color:_colorActive, time:1 } );
		}
		
		private function mouseOutHandler(event:Event):void {
			_button.gotoAndPlay("out");
			Tweener.addTween(_button.g, { _color:_colorBase, time:1 } );
		}
	}
}