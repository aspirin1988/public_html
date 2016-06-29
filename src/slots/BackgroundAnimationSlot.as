package slots{
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.Moto;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;

	public class BackgroundAnimationSlot extends AbstractMotoSlot
	{
		public function BackgroundAnimationSlot()
		{
			super();
		}

		override public function init(vo:MotoObjectVO):void 
		{
			super.init(vo);
			preview.visible = limitedMode;
		}
		
		override public function show():void 
		{
			super.show();
			holder.gotoAndPlay("show");
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