package modules{
	import caurina.transitions.properties.ColorShortcuts;
	import caurina.transitions.Tweener;
	import com.moto.template.common.Moto;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.modules.menu.AbstractMenuItemRenderer;
	import flash.display.GradientType;
	import flash.display.Graphics;
	import flash.display.MovieClip;
	import flash.display.SpreadMethod;
	import flash.events.Event;
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
//------------------------------------------------------------------
	public class MainMenuItemRenderer extends AbstractMenuItemRenderer {
		private var _tf:TextField;
		private var _holder:MovieClip;
		private var _circle1:MovieClip;
		private var _circle2:MovieClip;
		private var _circle3:MovieClip;
		private var _circle4:MovieClip;
		
		private var textItemColor:Number;
		private var circleColor:Number;
		
		private var overItemColor:Number;
		private var outItemColor:Number;
		private var itemHeight:Number;
		private var distance:Number = 5;
		private var _radius:Number = 5.5;

//------------------------------------------------------------------
		public function MainMenuItemRenderer() {
			ColorShortcuts.init();
			
			super();
			
			_tf = textHolder.clip.tf as TextField;
			_holder = areaHolder as MovieClip;
			_circle1 = circle.clip.clip as MovieClip;
			_circle2 = circle.clip1.clip as MovieClip;
			_circle3 = circle.clip2.clip as MovieClip;
			_circle4 = circle.clip3.clip as MovieClip;
			//------
			_tf.selectable = false;
			_tf.multiline= true;
			_tf.mouseWheelEnabled = false;
			_tf.autoSize = TextFieldAutoSize.LEFT;
			//------
			
			hitArea = _holder;
			buttonMode = true;
			mouseChildren = false;
			
			addEventListener(Event.ADDED_TO_STAGE, addedToStageHandler);
		}
		
		private function addedToStageHandler(event:Event):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, addedToStageHandler);
			show();
		}
//------------------------------------------------------------------
//---override---
		override public function updateRenderer(data:Object):void {
			super.updateRenderer(data);
			
			MotoUtils.setHTMLTextFromPropertyVO(_tf, menuItemVO.getPropertyByID(1));
			MotoUtils.setColor(_circle1, extra.circleColor);
			MotoUtils.setColor(_circle2, extra.activeCircleColor);
			MotoUtils.setColor(_circle3, extra.activeBorderColor);
			addGradient(_circle4, _radius, extra.internalColor1, extra.internalColor2);
			
			textItemColor = uint(extra.textItemColor);
			
			var _width:Number = _circle1.width + _tf.width + distance;
			setSize(_width, _tf.height);
		}
		//---getDimensions---
		override public function getDimensions():Rectangle {
			return new Rectangle(0, 0, _holder.width, _holder.height);//!!!!!!!!!!!!!!!!
		}
		//---
		public function getContentDimension():Rectangle
		{
			var _width:Number = _circle1.width + _tf.width + distance;
			return new Rectangle(0, 0, _width, _tf.height);
		}
		//---Size
		override public function setSize(newWidth:Number, newHeight:Number):void {
			_holder.width = newWidth;
			_holder.height = newHeight;
			
			_tf.x = _circle1.width + distance;
			_tf.y = int((newHeight - _tf.height) / 2);
			circle.y = int(newHeight / 2) - 0.5;
		}
		//---ROLL OVER/OUT
		override protected function over():void{
			gotoAndPlay("over")
			Tweener.addTween(textHolder.clip, { _color:textItemColor , time:0.3, delay:.1 } );
		}
		//---
		override protected function out():void {
			gotoAndPlay("out")
			Tweener.addTween(textHolder.clip, { _color:null , time:0.3, delay:.1 } );
		}
//---------------------------------------
		private function addGradient(target:MovieClip, radius:Number, color01:Number, color02:Number):void
		{
			if(!width || !height)
				return;
			
			var colors:Array = [color01, color02];
			var alphas:Array = [1, 1];
			var ratios:Array = [0, 255];
				
			var matr:Matrix = new Matrix();
			matr.createGradientBox( radius, radius, Math.PI / 2, 0, 0 );
			var g:Graphics = target.graphics;
			g.clear();
			g.beginGradientFill( GradientType.LINEAR, colors, alphas, ratios, matr, SpreadMethod.PAD );
			g.drawCircle( radius / 2, radius / 2, radius);
			g.endFill();
			_circle4.x = -_radius / 2;
			_circle4.y = -_radius / 2;
		}
	}
}