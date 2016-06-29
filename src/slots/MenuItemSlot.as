package slots
{
	import caurina.transitions.Equations;
	import caurina.transitions.Tweener;
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.Moto;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.common.view.components.loaders.ImageLoader;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import flash.display.GradientType;
	import flash.display.Graphics;
	import flash.display.MovieClip;
	import flash.display.SpreadMethod;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	
	public class MenuItemSlot extends AbstractMotoSlot
	{
		private var _area:MovieClip;
		private var _preloader:MovieClip;
		private var _image:ImageLoader;
		private var _infoHolder:MovieClip;
		
		private var topLeft:Number = 0;
		private var topRight:Number = 0;
		private var botLeft:Number = 0;
		private var botRight:Number = 0;
		private var infoRoundness:Number = 0;
		private var infoColor1:Number;
		private var infoColor2:Number;
		
		private var deltaXInfoMask:Number = 10;
		private var _tf:TextField;
		private var infoGap:Number = 43;
		private var infoHolderY:Number = 0;
		private var stickInfo:String;
		private var isMenu:Boolean = false;
		private var _clickArea:MovieClip;
		
		public function MenuItemSlot()
		{
			super();
			_area = area;
			_clickArea = clickArea;
			_preloader = preloader;
			_infoHolder = infoHolder;
			_image = imageHolder.g.image;
			_tf = _infoHolder.textHolder.tf;
			_tf.autoSize = TextFieldAutoSize.LEFT;
			addEventListener(Event.ADDED_TO_STAGE, addedHandler);
			
			imageHolder.g.alpha = 0;
			
			mouseChildren = false;
			hitArea = _clickArea;
		}
		
		private function addedHandler(e:Event):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, addedHandler);
			if (!limitedMode)
				Moto.getInstance().eventDispatcher.addEventListener(MotoEvent.SWITCH_PAGE, switchPagesHandler);
		}

		private function switchPagesHandler(e:MotoEvent):void 
		{
			gotoAndPlay("hide");
			Tweener.addTween(_infoHolder, { x: -_infoHolder.width - deltaXInfoMask, time:0.2, transition:Equations.easeOutSine } );
		}
		
		override public function init(vo:MotoObjectVO):void 
		{
			super.init(vo);
			setSize(vo.width, vo.height)
		}
		
		override public function show():void 
		{
		}
		
		override protected function mouseOverHandler(event:MouseEvent):void 
		{
			super.mouseOverHandler(event);
			if (!isMenu)
				return;
			
			Tweener.removeTweens(_infoHolder);
			Tweener.removeTweens(_tf);
			_tf.x = int((_infoHolder.infoBack.width - _tf.width) / 2) + (stickInfo == 'left' ? 10 : -10);
			switch (stickInfo)
			{
				case "left":
					Tweener.addTween(_infoHolder, { x: -deltaXInfoMask, time:0.3, transition:Equations.easeOutSine } );
					Tweener.addTween(_tf, { x: _tf.x - 10, delay:0.3, time:0.1, transition:Equations.easeOutSine } );
				break;
				case "right":
					Tweener.addTween(_infoHolder, { x: int(getDimensions().width + deltaXInfoMask - _infoHolder.infoBack.width), time:0.3, transition:Equations.easeOutSine } );
					Tweener.addTween(_tf, { x: _tf.x + 10, delay:0.3, time:0.1, transition:Equations.easeOutSine } );
				break;
			}
		}
		
		override protected function mouseOutHandler(event:MouseEvent):void 
		{
			super.mouseOutHandler(event);
			if (!isMenu)
				return;
				
			Tweener.removeTweens(_infoHolder);
			switch (stickInfo)
			{
				case "left":
					Tweener.addTween(_infoHolder, { x: -_infoHolder.width - deltaXInfoMask, time:0.2, transition:Equations.easeOutSine } );
				break;
				case "right":
					Tweener.addTween(_infoHolder, { x: getDimensions().width + deltaXInfoMask, time:0.2, transition:Equations.easeOutSine } );
				break;
			}
		}
		
		override public function updateProperty(property:PropertyVO):void
		{						
			switch (property.propertyType)
			{
				case 1:
					_image.source = pathPrefix + String(property.value);
					if (!_image.hasEventListener(Event.COMPLETE))
						_image.addEventListener(Event.COMPLETE, imageCompleteHandler);
					break;
				case 2:
					isMenu = MotoUtils.convertToBoolean(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 3:
					break;
				case 4:
					break;
				case 5:
					break;
				case 6:
					break;
				case 7:
					break;
				case 8:
					break;
				
				case 10:
					topLeft = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 11:
					topRight = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 12:
					botLeft = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 13:
					botRight = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;

				case 14:
					MotoUtils.setHTMLTextFromPropertyVO(_tf, property);
					_infoHolder.visible = !(_tf.text == '');
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 15:
					infoColor1 = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 16:
					infoColor2 = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 17:
					infoRoundness = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 18:
					infoHolderY = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 19:
					stickInfo = property.value;
					setSize(getDimensions().width, getDimensions().height);
					break;
			}
		}
		
		override public function setSize(newWidth:Number, newHeight:Number):void
		{
			_area.width = newWidth;
			_area.height = newHeight;
			_image.setSize(newWidth, newHeight);

			addRoudRect(maskHolder, newWidth, newHeight);
			addRoudRect(maskHolder2, newWidth + deltaXInfoMask * 2, newHeight);
			addRoudRect(_clickArea, newWidth, newHeight);
			
			addGradient(_infoHolder.infoBack, newWidth + deltaXInfoMask - infoGap, _tf.textHeight + 11, infoColor1, infoColor2, infoRoundness);

			_tf.x = int((_infoHolder.infoBack.width - _tf.width) / 2) + (stickInfo == 'left' ? 10 : -10);
			_tf.y = Math.round((_infoHolder.infoBack.height - _tf.textHeight) / 2) + 2;
			
			_infoHolder.y = Math.min(infoHolderY, newHeight-_infoHolder.height);
			_preloader.x = newWidth *.5;
			_preloader.y = newHeight * .5;
			
			_infoHolder.shadowHolder.y = _infoHolder.infoBack.height;
			_infoHolder.shadowHolder2.y = _infoHolder.infoBack.height;
			_infoHolder.shadowHolder2.x = _infoHolder.infoBack.width;

			_infoHolder.shadowHolder.visible = (stickInfo == 'left');
			_infoHolder.shadowHolder2.visible = !(stickInfo == 'left');
			
			if (stage && !isMenu)
			{
				switch (stickInfo)
				{
					case "left":
						_infoHolder.x = -deltaXInfoMask;
					break;
					case "right":
						_infoHolder.x = int(newWidth + deltaXInfoMask - _infoHolder.infoBack.width);
					break;
				}
				return;
			}

			maskHolder2.x = -deltaXInfoMask;
			_infoHolder.x = stickInfo == 'left' ? ( -_infoHolder.width - deltaXInfoMask) : (newWidth + deltaXInfoMask);
		}
		
		override public function getDimensions():Rectangle
		{
			return new Rectangle(0,0,_area.width,_area.height);
		};
		
		private function addRoudRect(mc:MovieClip, newWidth:Number, newHeight:Number):void
		{
			mc.graphics.clear();
			mc.graphics.beginFill(0);
			mc.graphics.drawRoundRectComplex(0, 0, newWidth, newHeight, topLeft, topRight, botLeft, botRight);
			mc.graphics.endFill();
		}
		
		private function addGradient(target:MovieClip, _width:Number, _height:Number, color01:Number, color02:Number, r:Number):void
		{
			var matr:Matrix = new Matrix();
			matr.createGradientBox( _width, _height, 0, 0, 0 );
			var g:Graphics = target.graphics;
			g.clear();
			g.beginGradientFill( GradientType.LINEAR, [ color01, color02 ], [ 1, 1 ], [ 0, 0xFF], matr, SpreadMethod.PAD );
			switch (stickInfo) 
			{
				case "left":
					g.drawRoundRectComplex( 0, 0, _width, _height, 0, r, 0, r );
				break;
				case "right":
					g.drawRoundRectComplex( 0, 0, _width, _height, r, 0, r, 0 );
				break;
			}
			g.endFill();
		}
		
		private function imageCompleteHandler(event:Event):void
		{
			_image.removeEventListener(Event.COMPLETE, imageCompleteHandler);
			
			Tweener.addTween(_preloader, {alpha:0});
			gotoAndPlay("show");
			
			imageHolder.g.scaleX = 1.5;
			imageHolder.g.scaleY = 1.5;
			imageHolder.g.x = int((getDimensions().width - imageHolder.g.width) / 2);
			imageHolder.g.y = int((getDimensions().height - imageHolder.g.height) / 2);
			Tweener.addTween(imageHolder.g, { alpha:1, x:0, y:0, scaleX:1, scaleY:1, time:0.7, transition:Equations.easeOutSine } );
			
			if (isMenu)
				return;
			
			switch (stickInfo)
			{
				case "left":
					Tweener.addTween(_infoHolder, { x: -deltaXInfoMask, delay:0.5, time:0.3, transition:Equations.easeOutSine } );
					Tweener.addTween(_tf, { x: _tf.x - 10, delay:0.8, time:0.1, transition:Equations.easeOutSine } );
				break;
				case "right":
					Tweener.addTween(_infoHolder, { x: int(getDimensions().width + deltaXInfoMask - _infoHolder.infoBack.width), delay:0.5, time:0.3, transition:Equations.easeOutSine } );
					Tweener.addTween(_tf, { x: _tf.x + 10, delay:0.8, time:0.1, transition:Equations.easeOutSine } );
				break;
			}
		}
	}
}