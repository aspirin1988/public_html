package slots{
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	import flash.text.TextFieldAutoSize;

	public class IntroSlot extends AbstractMotoSlot
	{
		public function IntroSlot()
		{
			super();
			addEventListener(Event.ADDED_TO_STAGE, addedHandler);
		}
		
		private function addedHandler(e:Event):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, addedHandler);
			gotoAndPlay("show");
		}
		
		override public function init(vo:MotoObjectVO):void 
		{
			super.init(vo);
			preview.visible = limitedMode;
		}

		override public function updateProperty(property:PropertyVO):void
		{
			switch (property.propertyType)
			{
				case 1:
					MotoUtils.setHTMLTextFromPropertyVO(comp_name.textHolder.tf, property);
					comp_name.textHolder.tf.autoSize = TextFieldAutoSize.LEFT;
					comp_name.textHolder.tf.x = int( -comp_name.textHolder.tf.width / 2);
					setSize(getDimensions().width, getDimensions().height);
					break;
				case 2:
					MotoUtils.setHTMLTextFromPropertyVO(comp_name.textHolder2.tf, property);
					comp_name.textHolder2.tf.autoSize = TextFieldAutoSize.LEFT;
					comp_name.textHolder2.tf.x = int( -comp_name.textHolder2.tf.width / 2);
					setSize(getDimensions().width, getDimensions().height);
					break;
			}
		}
		
		override public function setSize(newWidth:Number, newHeight:Number):void 
		{
			comp_name.textHolder2.tf.y = int(comp_name.textHolder.tf.height);
		}
		
		override public function getDimensions():Rectangle
		{
			return new Rectangle(0, 0, preview.g.width, preview.g.height);
		}
		
		override protected function mouseOverHandler(event:MouseEvent):void
		{
		}
		
		override protected function mouseOutHandler(event:MouseEvent):void
		{
		}
	}
}