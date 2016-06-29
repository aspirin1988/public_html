package slots
{
	import caurina.transitions.Equations;
	import caurina.transitions.Tweener;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.common.view.components.loaders.ImageLoader;
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
	public class ImageSlot extends AbstractMotoSlot
	{
		private var _area:MovieClip;
		private var _maskHolder:MovieClip;
		private var _maskHolder1:MovieClip;
		private var _image:ImageLoader;
		private var _borderBg:MovieClip;
		private var _preloader:MovieClip;
		
		private var border:Number = 0;
		private var radius:Number = 0;
		private var _color01:Number;
		private var _color02:Number;

		public function ImageSlot()
		{
			super();
			
			_preloader = preloader as MovieClip;
			_area = area as MovieClip;
			_maskHolder = maskHolder as MovieClip;
			_maskHolder1 = imageHolder.maskHolder as MovieClip;
			_image = imageHolder.image as ImageLoader;
			_borderBg = imageHolder.borderBg.clip as MovieClip;
			
			_image.alpha = 0;
			_borderBg.alpha = 0;
			
			mouseChildren = false;
			buttonMode = true;
			hitArea = _area;
		}
		
		override public function updateProperty(property:PropertyVO):void
		{
			switch (property.propertyType)
			{
				case 1:
					_image.source = pathPrefix + property.value;
					_image.addEventListener(Event.COMPLETE, imageCompleteHandler);
					break;
				case 2:
					_color01 = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 3:
					_color02 = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 4:
					border = Number(property.value);
					_borderBg.visible = border != 0;
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 5:
					radius = Number(property.value);
					setSize(getDimensions().width, getDimensions().height);
					break;
			}
		}

		override public function setSize(newWidth:Number, newHeight:Number):void
		{
			_area.width = newWidth;
			_area.height = newHeight;

			_image.setSize(newWidth, newHeight);
			//_borderBg.width = newWidth;
			//_borderBg.height = newHeight;
			addGradient(_borderBg, newWidth + border * 2, newHeight + border * 2, _color01, _color02, radius);
			_borderBg.x = -border;
			_borderBg.y = -border;
			
			_preloader.x = newWidth *.5;
			_preloader.y = newHeight * .5;
			
			addRoudRect(_maskHolder, newWidth, newHeight, radius);
			addRoudRect(_maskHolder1, newWidth, newHeight, radius);
		}

		override public function getDimensions():Rectangle
		{
			return new Rectangle(0, 0, _area.width, _area.height);
		};

		private function addRoudRect(mc:MovieClip, newWidth:Number, newHeight:Number, r:Number):void
		{
			mc.graphics.clear();
			mc.graphics.beginFill(0);
			mc.graphics.drawRoundRect(0, 0, newWidth, newHeight, r, r);
			mc.graphics.endFill();
		}
		
		private function imageCompleteHandler(event:Event):void
		{
			Tweener.addTween(_image, { alpha:1, time:0.8, transition:Equations.easeOutSine } );
			Tweener.addTween(_borderBg, { alpha:1, time:0.8, transition:Equations.easeOutSine } );
			Tweener.addTween(_preloader, {alpha:0});
			gotoAndPlay("show");
		}

		override protected function mouseOverHandler(event:MouseEvent):void
		{
			gotoAndPlay("over");
		}

		override protected function mouseOutHandler(event:MouseEvent):void
		{
			gotoAndPlay("out");
		}

		private function addGradient(target:MovieClip, _width:Number, _height:Number, color01:Number, color02:Number, r:Number):void
		{
			var matr:Matrix = new Matrix();
			matr.createGradientBox( _width, _height, Math.PI / 4, 0, 0 );
			var g:Graphics = target.graphics;
			g.clear();
			g.beginGradientFill( GradientType.LINEAR, [ color01, color02 ], [ 1, 1 ], [ 0, 0xFF], matr, SpreadMethod.PAD );
			g.drawRoundRect( 0, 0, _width, _height, r, r );
			g.endFill();
		}
	}
}
