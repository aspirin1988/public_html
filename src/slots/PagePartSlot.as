package slots{
	import com.moto.template.common.Moto;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import common.events.PagePartEvent;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.geom.Rectangle;

	public class PagePartSlot extends AbstractMotoSlot
	{
		private var _holder:MovieClip;
		private var _preview:MovieClip;
		private var _preview2:MovieClip;
		
		public function PagePartSlot()
		{
			super();
			
			_holder = holder as MovieClip;
			_preview = preview as MovieClip;
			_preview2 = preview2 as MovieClip;
			
			Moto.getInstance().eventDispatcher.addEventListener(PagePartEvent.CHANGE, switchPagesHandler);
		}
		
		override public function show():void 
		{
			super.show();
			_holder.gotoAndPlay("show");
		}
		
		override public function init(vo:MotoObjectVO):void 
		{
			super.init(vo);
			_preview.visible = limitedMode;
			_preview2.visible = limitedMode;
			_holder.visible = !limitedMode;
			setSize(vo.width, vo.height);
			
			if (!limitedMode)
				return;
			
			var currentLayout:uint = Moto.getInstance().currentLayout;		
			switch (currentLayout)
			{
				case 1:
					_preview.visible = false;
					_preview2.visible = true;
					break;
				case 2:
					_preview.visible = true;
					_preview2.visible = false;
					break;
			}
		}
		
		override public function updateProperty(property:PropertyVO):void
		{
			switch (property.propertyType)
			{
				case 1 :
					//MotoUtils.setColor(_holder, Number(property.value));
					//MotoUtils.setColor(_preview, Number(property.value));
					break;
			}
		}
		
/*		override public function setSize(newWidth:Number, newHeight:Number):void
		{
			super.setSize(newWidth, newHeight);
			
			_preview.width = newWidth;
			_preview.height = newHeight;
			_holder.width = newWidth;
			_holder.height = newHeight;
		}*/
		
		override public function getDimensions():Rectangle
		{
			return new Rectangle(0, 0, _preview.width, _preview.height);
		}
		
		function switchPagesHandler(e:PagePartEvent):void
		{
			_holder.gotoAndPlay(e.label);
		}
	}
}