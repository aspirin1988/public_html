package modules{
	import com.moto.template.common.view.OrientationEnum;
	import com.moto.template.modules.menu.DynamicMotoMenu;
	import com.moto.template.modules.menu.AbstractMenuItemRenderer;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import flash.display.MovieClip;
	import flash.events.*;
	import flash.geom.Rectangle;
//------------------------------------------------------------------------
	public class MainSubMenu extends DynamicMotoMenu {
		private var _menuHolder:MovieClip;
		private var textDistance:Number = 0;
		private var maxWidthItem:Number = 0;
//------------------------------------------------------------------------
		public function MainSubMenu() {
			super();

			_menuHolder= menuHolder["clip"];
			
			addEventListener(INITIALIZATION_COMPLETE, _initializationHandler);
			
			itemsArranger.autoUpdate = false;
			itemsArranger.orientation=OrientationEnum.COLUMNS;
			itemsArranger.target = _menuHolder;
			itemsArranger.dynamicItemsDimensions = true;
		}
//------------------------------------------------------------------------
//---override---
		override public function init(vo:MotoObjectVO):void {
			super.init(vo);
			show();
		}
		//---
		override protected function initListControls(dataProvider:Array):void 
		{
			super.initListControls(dataProvider);
			
			if (!limitedMode) 
			{
				updateSelectedMenuButton(moto.currentPage);
			}
		}
		//---
		override public function updateProperty(property:PropertyVO):void {
			super.updateProperty(property);
			switch (property.propertyType) {
				case 1 :
					itemsArranger.verticalSpacing = uint(property.value);
					itemsArranger.repositionItems();
					break;
				case 4 :
					extra.textItemColor = uint(property.value);
					break;
			}
		}
		//---getDimensions---
		override public function getDimensions():Rectangle {
			return new Rectangle(0, 0, _menuHolder.width, _menuHolder.height);
		}
		//---
		override public function show():void 
		{
			gotoAndPlay("show");
		}
		override public function hide():void 
		{
			gotoAndPlay("hide");
		}
//------------------------------------------------------------------------		
//---private---
		private function _initializationHandler(event:Event):void {
			switch (event.type) {
				case INITIALIZATION_COMPLETE :
					var item = itemsArranger.itemsCollection;
					if (item)
					{
						for (var i:int = 0; i < item.length; i++)
						{
							if (item[i].getContentDimension().width > maxWidthItem)
							{
								maxWidthItem = item[i].getContentDimension().width;
							}
						}
						for (i = 0; i < item.length; i++)
						{
							var _height:Number = item[i].getContentDimension().height;
							item[i].setSize(maxWidthItem, _height);
						}
					}
					break;
			}
		}
	}
}