package modules {
	
	import caurina.transitions.properties.ColorShortcuts;
	import caurina.transitions.Tweener;
	import com.moto.template.common.events.ItemRendererEvent;
	import com.moto.template.common.tools.MotoArranger;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.common.view.AutoHider;
	import com.moto.template.common.events.UpdateMotoObjectEvent;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.events.UpdateMotoObjectEvent;
	import com.moto.template.common.Moto;
	import com.moto.template.common.view.OrientationEnum;
	import com.moto.template.modules.menu.AbstractMenuItemRenderer;
	import com.moto.template.modules.menu.DynamicMotoMenu;
	import com.moto.template.modules.menu.StaticMotoMenu;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;

	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.display.Shape;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Point;
	import flash.geom.Rectangle;
//------------------------------------------------------------------------
	public class MainMenuModule extends DynamicMotoMenu {
		
		private var _preview:MovieClip;
		private var _menuHolder:MovieClip;
		private var _submenuHolder:Sprite;
		
		
		
		//---
//		private var itemHeight:uint;
		private var gap:Number;
		//---
		private var maxWidthItem:Number = 0;
		//---
//------------------------------------------------------------------------		
		public function MainMenuModule() {
			
			ColorShortcuts.init();
			
			super();

			_preview = this["preview"];
			_menuHolder = contentHolder["menuHolder"];
			_submenuHolder=contentHolder["submenuHolder"];
			
			//------
			addEventListener(Event.ADDED_TO_STAGE, _stageHandler);
			addEventListener(INITIALIZATION_COMPLETE, _initializationHandler);
			addEventListener(INITIALIZATION_ERROR, _initializationHandler);

			subMenuAutoHideTimeout = 1;//seconds
			subMenuRemoveDelay = 0.3;

			itemsArranger.dynamicItemsDimensions = true;
			itemsArranger.rememberSelectedItem = false;
			itemsArranger.target = _menuHolder;
			itemsArranger.orientation = OrientationEnum.COLUMNS;
			itemsArranger.itemRendererCreationDelay = .2;

		}
//------------------------------------------------------------------------		
//---override---
		
		override public function init(vo:MotoObjectVO):void {
			super.init(vo);

			dispatchEvent(new UpdateMotoObjectEvent(UpdateMotoObjectEvent.MOTO_OBJECT_SIZE_UPDATED));//

			if (! limitedMode) {
				Moto.getInstance().eventDispatcher.addEventListener(MotoEvent.SWITCH_PAGE, _switchPageHandler);
			}

			updateSelectedMenuButton(Moto.getInstance().currentPage);
		}

		//select type menu-------------
		override public function updateProperty(property:PropertyVO):void {
			super.updateProperty(property)
			switch (property.propertyType) {
				case 1 :
					itemsArranger.verticalSpacing = uint(property.value);
					itemsArranger.repositionItems();
					dispatchEvent(new UpdateMotoObjectEvent("motoObjectSizeUpdated"));
					break;
				case 2 :
					gap = uint(property.value);
					break;
				case 3 :
					extra.circleColor = uint(property.value);
					updateRenderers();
					break;
				case 4 :
					extra.textItemColor = uint(property.value);
					break;
				case 5 :
					extra.activeCircleColor = uint(property.value);
					updateRenderers();
					break;
				case 6 :
					extra.activeBorderColor = uint(property.value);
					updateRenderers();
					break;
				case 7 :
					extra.internalColor1 = uint(property.value);
					updateRenderers();
					break;
				case 8 :
					extra.internalColor2 = uint(property.value);
					updateRenderers();
					break;
			}
		}
		//---
		override public function setSize(newWidth:Number, newHeight:Number):void {
		}
		//---
		override public function getDimensions():Rectangle {
			var item = itemsArranger.itemsCollection;
			var h:Number = 0;
			if (item.length)
			{
				for (var i:int = 0; i < item.length; i++)
				{
					if(item[i])
						h += item[i].getContentDimension().height + itemsArranger.verticalSpacing;
				}
				h -= itemsArranger.verticalSpacing;
			}
			else
			{
				h = 100;
			}
			
			maxWidthItem = Math.max(maxWidthItem, 100);
			return new Rectangle(0, 0, maxWidthItem, h);
		}
		//---subMenu---
		override public function getSubMenuTargetHolder():Sprite {
			return _submenuHolder;
		}
		//---
		override public function getItemRendererSubMenuPoint(itemRenderer:AbstractMenuItemRenderer):Point {
			var item:MainMenuItemRenderer = itemRenderer as MainMenuItemRenderer;
			return new Point( maxWidthItem + gap, int(item.y) ); ///new coordinate
		}
		//---

//------------------------------------------------------------------------		
//---private---
		private function _initializationHandler(event:Event):void {
			switch (event.type) {
				case INITIALIZATION_COMPLETE :
					_preview.visible = (menuItemsData.length || !limitedMode)?false:true;
					
					setSize(vo.width, vo.height);
					
					maxWidthItem = 0;
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
					
					maxWidthItem += 5;
					dispatchEvent(new UpdateMotoObjectEvent("motoObjectSizeUpdated"));
					
					break;

				case INITIALIZATION_ERROR :
					_preview.visible=limitedMode;
					break;
			}
		}
		
		//---
        private function _stageHandler(event:Event):void
        {
            switch (event.type)
            {
                case Event.ADDED_TO_STAGE:
				
					removeEventListener(Event.ADDED_TO_STAGE, _stageHandler);
                    addEventListener(Event.REMOVED_FROM_STAGE, _stageHandler);
//                    if (!limitedMode)
//					{
//						stage.addEventListener(Event.RESIZE, _stageHandler);
//					}
					_resizeHandler();
                    break;
 
                case Event.REMOVED_FROM_STAGE:
                    removeEventListener(Event.REMOVED_FROM_STAGE, _stageHandler);
                    stage.removeEventListener(Event.RESIZE, _stageHandler);

                    break;
 
                case Event.RESIZE:
					_resizeHandler();
                    break;
            }
        }
//------------------------------------

		//---
		//------------------------------------------------------------------------
		private function _resizeHandler():void 
		{
		}
//------------------------------------
		//---
		private function updateRenderers():void{
			var itemRenderer;
			if (itemsArranger.itemsCollection){
				for(var i=0; i<itemsArranger.itemsCollection.length; i++){
					itemRenderer = itemsArranger.itemsCollection[i];
					itemRenderer.updateRenderer(itemRenderer.data);
				}
			}
		}
//------------------------------------------------------------------------
		//---switch page---
		private function _switchPageHandler(event:Event):void {
			updateSelectedMenuButton(Moto.getInstance().currentPage);
		}
//--------------------------------
		//click button		
		override protected function itemClickHandler(event:ItemRendererEvent):void 
		{
			if (!Moto.getInstance().animationIsPlaying)
			{
				if (event.itemRenderer.index == selectedItemIndex)
				{
					itemClickAction(event.itemRenderer);
				}
				else
					super.itemClickHandler(event);
			}
		}
	}
}