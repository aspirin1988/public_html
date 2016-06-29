package slots{
	import caurina.transitions.properties.ColorShortcuts;
	import caurina.transitions.Tweener;
	import com.moto.template.common.*;
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import com.moto.template.common.utils.MotoUtils;
	import flash.geom.Rectangle;
	import flash.events.Event;
	import flash.events.MouseEvent;
    import flash.text.TextField;
	import flash.display.MovieClip;

	public class CloseButtonSlot extends AbstractMotoSlot {
		private var _button:MovieClip;
		//private var _bg:MovieClip;
		private var _item1:MovieClip;
		private var baseColor:Number;
		private var activeColor:Number;

		public function CloseButtonSlot() {

			super();

			ColorShortcuts.init();
			
			// Get slot elements
			//_bg = colorBG.getChildByName("bg") as MovieClip;
			_button = getChildByName("area") as MovieClip;
			_item1 = item.item as MovieClip;

			_button.buttonMode = true;
			
			this.mouseChildren = false;
			this.hitArea = _button;
			
			if (!limitedMode && Moto.getInstance().website)
				Moto.getInstance().website.addEventListener(MotoEvent.SWITCH_PAGE, switchPagesHandler);
		}
		
		function switchPagesHandler(event:Event):void 
		{
			gotoAndPlay("hide")
			hide();
		}
		
		override public function updateProperty(property:PropertyVO):void {

			switch (property.propertyType) {
				case 1 :
				{
					baseColor = Number(property.value);
					MotoUtils.setColor(_item1, Number(property.value));
					break;
				}
				case 2 :
				{
					activeColor = Number(property.value);
					break;
				}
			}
		};
		
		override public function setSize(newWidth:Number, newHeight:Number):void
		{
			//areaSlot.width =Math.ceil(newWidth);
			//areaSlot.height = Math.ceil(newHeight);
			//item.x= areaSlot.x+newWidth-25
			//item.y= areaSlot.y+Math.ceil(newHeight/2)-10
		};
		
		override public function getDimensions():Rectangle
		{
			return new Rectangle(0, 0, area.width, area.height);
		};
		
		override protected function mouseOverHandler(event:MouseEvent):void
		{
			this.gotoAndPlay(overFrameLabel);
			Tweener.addTween(_item1, { _color:activeColor, time:0.3 } );
		};
		
		override protected function mouseOutHandler(event:MouseEvent):void
		{
			this.gotoAndPlay(outFrameLabel);
			Tweener.addTween(_item1, { _color:baseColor, time:0.3 } );
		};
	}
}