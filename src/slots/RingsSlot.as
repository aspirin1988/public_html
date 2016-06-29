package slots{
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	import flash.utils.setTimeout;

	public class RingsSlot extends AbstractMotoSlot
	{
		private var delayBeforeShow:Number = 0;

		public function RingsSlot()
		{
			super();
		}
		
		override public function updateProperty(property:PropertyVO):void
		{
			switch (property.propertyType)
			{
				case 1:
					delayBeforeShow = Number(property.value) * 1000;
					break;
			}
		}
		
		override public function getDimensions():Rectangle 
		{
			return new Rectangle(0, 0, area.width, area.height);
		}
		
		override public function show():void 
		{
			setTimeout(customShow, delayBeforeShow);
		}
		
		private function customShow():void 
		{
			gotoAndPlay('show');
		}
		
		override protected function mouseOverHandler(event:MouseEvent):void
		{
		}
		
		override protected function mouseOutHandler(event:MouseEvent):void
		{
		}
	}
}