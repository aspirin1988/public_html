package slots{
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.Moto;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;

	public class AnimatedLinesSlot extends AbstractMotoSlot
	{
		public function AnimatedLinesSlot()
		{
			super();
			
			addEventListener(Event.ADDED_TO_STAGE, addedHandler);
		}
		
		private function addedHandler(e:Event):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, addedHandler);
			if (!limitedMode)
				Moto.getInstance().eventDispatcher.addEventListener(MotoEvent.SWITCH_PAGE, switchPagesHandler);
		}
		
		override public function init(vo:MotoObjectVO):void 
		{
			super.init(vo);
			preview.visible = limitedMode;
			if (limitedMode)
				gotoAndStop('show');
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
		
		private function switchPagesHandler(e:MotoEvent):void 
		{
			gotoAndPlay("show");
		}
	}
}