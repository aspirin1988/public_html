package modules{
	import caurina.transitions.properties.ColorShortcuts;
	import caurina.transitions.Tweener;
	import com.moto.template.common.tools.MotoArranger;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.modules.menu.AbstractMenuItemRenderer;
	import com.moto.template.shell.model.vo.PropertyVO;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
//------------------------------------------------------------------------
	public class MainSubMenuItemRenderer extends AbstractMenuItemRenderer {
		private var _tf:TextField;
		private var _rectangle:MovieClip;

		private var textItemColor:Number;
//------------------------------------------------------------------------
		public function MainSubMenuItemRenderer() {
			ColorShortcuts.init();
			
			super();
			
			_tf = itemHolder["tf"];
			_rectangle = this["rectangle"];
			//------
			_tf.selectable = false;
			_tf.multiline= true;
			_tf.mouseWheelEnabled = false;
			_tf.autoSize = TextFieldAutoSize.LEFT;
			//------
			
			buttonMode=true;
			mouseChildren = false;
			
			addEventListener(Event.ADDED_TO_STAGE, addedToStgeHandler);
		}
		
		private function addedToStgeHandler(event:Event):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, addedToStgeHandler);
			show();
		}
//------------------------------------------------------------------------
//---override---
		override public function updateRenderer(data:Object):void {
			super.updateRenderer(data);

			MotoUtils.setHTMLTextFromPropertyVO(_tf, menuItemVO.getPropertyByID(1));
			
			textItemColor = uint(extra.textItemColor);
			setSize(_tf.width, _tf.height);
		}
		//---
		override public function setSize(newWidth:Number, newHeight:Number):void {
			_rectangle.width = newWidth;
			_rectangle.height = newHeight;
			
			_tf.y = int((newHeight - _tf.height) / 2);
		}
		//---
		override public function getDimensions():Rectangle {
			return new Rectangle(0, 0, _rectangle.width, _rectangle.height);
		}
		//------------------------
		override protected function over():void {
			gotoAndPlay("over");
			Tweener.addTween(_tf, { _color:textItemColor, time:0.3, delay:.1 } )
		}
		//---
		override protected function out():void {
			gotoAndPlay("out");
			Tweener.addTween(_tf, {_color:null, time:0.3, delay:.1})
		}
//------------------------------------------------------------------------
//---public---
		public function getContentDimension():Rectangle
		{
			return new Rectangle(0, 0, _tf.width, _tf.height);
		}
	}
}