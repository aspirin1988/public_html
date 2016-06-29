package modules{
	import com.moto.template.common.events.ExternalDataProviderEvent;	
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.events.UpdateMotoObjectEvent;
	import com.moto.template.common.events.ItemRendererEvent;
	import com.moto.template.common.Moto;
	import com.moto.template.common.view.IItemRenderer;
	import com.moto.template.common.view.MotoAnimatedItemRenderer;
	import com.moto.template.common.tools.MotoArranger;
	import com.moto.template.common.tools.MotoPaginator;
	import com.moto.template.common.view.OrientationEnum;
	import com.moto.template.modules.gallery.SimpleGalleryModule;
	import com.moto.template.shell.model.vo.ModuleItemVO;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.common.utils.MotoUtils;
	
	import flash.display.Loader;
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.display.Bitmap;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.events.ProgressEvent;
	import flash.geom.Point;
	import flash.geom.Rectangle;
	import flash.net.URLRequest;
	
	import caurina.transitions.Tweener;
	import caurina.transitions.properties.ColorShortcuts;

	public class PageGalleryModuleCustom extends SimpleGalleryModule {

		private var _propertyGroupID:String = '';
		
		private var _preview:MovieClip;
		private var _contentHolder:MovieClip;
		private var _border:MovieClip;
		
		private var _btnHolder:MovieClip;
		private var _arrowRight:MovieClip;
		private var _arrowLeft:MovieClip;
		
		private var thumbSpacing:Number = 20;
		private var thumbAmount:Number = 3;

		private var moduleSize:Rectangle = new Rectangle(0, 0, 1, 1);
		
		private var lastImage:Number = -100;
		private var blockChangeImage:Boolean = false;
		
		private var disableArrowOut:Boolean = false;
		
		private var ITEM_WIDTH:Number = 200;
		private const ITEM_HEIGHT:Number = 200;
		
		private var xOffset:Number;
		
		public function PageGalleryModuleCustom() {
			
			ColorShortcuts.init();
			
			super();
			
			_preview = this["preview"];
			_contentHolder = this["contentHolder"];
			_btnHolder = this["btnHolder"];
			_arrowRight = btnHolder["arrowDown"];
			_arrowLeft = btnHolder["arrowUp"];

			_arrowRight.hitArea = _arrowRight['area'];
			_arrowLeft.hitArea = _arrowLeft['area'];
			
			addEventListener(INITIALIZATION_COMPLETE, _initializationHandler);
			addEventListener(INITIALIZATION_ERROR, _initializationHandler);

			addEventListener(Event.ADDED_TO_STAGE, _stageHandler);
			
			thumbnailsArranger.dynamicItemsDimensions = true;
			thumbnailsArranger.autoUpdate = false;
			thumbnailsArranger.rows = 1;
			thumbnailsArranger.orientation = OrientationEnum.COLUMNS;
			thumbnailsArranger.target = _contentHolder;
			thumbnailsArranger.rememberSelectedItem = false;
		}

		
//---override---

		override public function init(vo:MotoObjectVO):void 
		{
			_preview.visible = false;
			_btnHolder.visible = false;
			_contentHolder.visible = false;
			
			super.init(vo);
			extra.itemWidth = ITEM_WIDTH;
			extra.itemHeight = ITEM_HEIGHT;
		}

		//---
        override public function setSize(newWidth:Number, newHeight:Number):void 
        {
			super.setSize(newWidth, newHeight);
			// Size restriction
			if (thumbnailsArranger.itemsCollection.length > 0)
			{
				var allWidth:Number = ITEM_WIDTH + ( _arrowLeft.width + xOffset ) * 2;
				newWidth = Math.max(allWidth, newWidth);
				newHeight = Math.max(ITEM_HEIGHT, newHeight);
				
				super.setSize(newWidth, newHeight);
				
				var emptyWidth:Number = newWidth + thumbSpacing - (_arrowRight.width + xOffset) * 2 ;
				var thumbAmount:Number = int( newWidth / (ITEM_WIDTH + thumbSpacing));
				if (thumbAmount < 1)
					thumbAmount = 1;
				if (thumbAmount >= dataProvider.length)
					thumbAmount = dataProvider.length;
					
				var thumbsWidth:Number = (ITEM_WIDTH + thumbSpacing) * thumbAmount;
				var w:Number = int(ITEM_WIDTH + (emptyWidth - thumbsWidth)/thumbAmount);
				var h:Number = int((newHeight - thumbnailsArranger.verticalSpacing * (thumbnailsArranger.rows - 1)) / thumbnailsArranger.rows);
				
				thumbnailsArranger.itemWidth = w;
				thumbnailsArranger.itemHeight = h;
				thumbAmount *= thumbnailsArranger.rows;
				
				if (thumbnailsPaginator.itemsPerPage != thumbAmount)
				{
					thumbnailsPaginator.itemsPerPage = thumbAmount;
					thumbnailsArranger.showItems();
				}
				else
				{
					thumbnailsArranger.repositionItems();
				}
				
				_arrowRight.y = int((newHeight - _arrowRight.height) / 2);
				_arrowLeft.y = int((newHeight - _arrowLeft.height) / 2);
				_arrowRight.x = int(newWidth);
				_contentHolder.x = int(_arrowLeft.width + xOffset);
			}

			moduleSize.width = newWidth;
			moduleSize.height = newHeight;
			_preview.width = newWidth;
			_preview.height = newHeight;
		}

		override public function getDimensions():Rectangle
		{
			return moduleSize;
		};
		
		//select type menu
		override public function updateProperty(property:PropertyVO):void {
			switch (property.propertyType) {
				case 1:
					thumbSpacing = Number(property.value);
					thumbnailsArranger.horizontalSpacing = thumbSpacing;
					setSize(moduleSize.width, moduleSize.height);
					dispatchEvent(new UpdateMotoObjectEvent(UpdateMotoObjectEvent.MOTO_OBJECT_SIZE_UPDATED));
					break;
				case 2:
					xOffset = Number(property.value);
					setSize(moduleSize.width, moduleSize.height);
					dispatchEvent(new UpdateMotoObjectEvent(UpdateMotoObjectEvent.MOTO_OBJECT_SIZE_UPDATED));
					break;
				case 3:
					Tweener.addTween(_arrowLeft.arrowHolder.clip, { _color:uint(property.value), time:0 } );
					Tweener.addTween(_arrowRight.arrowHolder.clip, { _color:uint(property.value), time:0 } );
					break;
				case 4:
					extra.thickness = Number(property.value);
					updateExtraData();
					break;
				case 5:
					extra.borderColor = Number(property.value);
					updateExtraData();
					break;
				case 6:
					extra.borderOverColor = Number(property.value);
					updateExtraData();
				case 7:
					break;
				case 8:
					extra.roundness = Number(property.value);
					updateExtraData();
					break;
				case 9:
					//extra.textOffsetY = Number(property.value);
					//updateExtraData();
					thumbnailsArranger.rows = Number(property.value);
					updateExtraData();
					setSize(moduleSize.width, moduleSize.height);
					break;
				case 10:
					thumbnailsArranger.verticalSpacing = Number(property.value);
					setSize(moduleSize.width, moduleSize.height);
					dispatchEvent(new UpdateMotoObjectEvent(UpdateMotoObjectEvent.MOTO_OBJECT_SIZE_UPDATED));
					break;
				case 11:
					extra.borderAlpha = Number(property.value) / 100;
					updateExtraData();
					break;
				case 12:
					ITEM_WIDTH = Number(property.value);
					setSize(moduleSize.width, moduleSize.height);
					break;
				case 13:
					_propertyGroupID = String(property.value);
					break;
				
				default:
					break;
			}
		}
		
		//---
		override protected function thumbnailClickHandler(event:ItemRendererEvent):void 
		{
			if (!blockChangeImage && lastImage != event.itemRenderer.index)
			{
				super.thumbnailClickHandler(event);
				setCurrent(event.itemRenderer.index);
			}
			else
			{
				thumbnailsArranger.selectItemRendererByIndex(lastImage, false);
			}
		}

//---private---
		private function updateExtraData():void {
			if (thumbnailsArranger.itemsCollection.length > 0){
				for (var i = 0; i < thumbnailsArranger.itemsCollection.length; i++)
				{
					thumbnailsArranger.itemsCollection[i].newExtraData();
				}
				setSize(moduleSize.width, moduleSize.height);
			}
		}
		
		private function setCurrent(index:Number):void
		{
			var link:String = String(ModuleItemVO(dataProvider[index]).getPropertyValueByID(4));
			if (!MotoEvent.isEmptyLinkAction(link))
			{
				dispatchEvent(new MotoEvent(MotoEvent.MOTO_CLICK, link, true));
			}
			if (_propertyGroupID != "")
				Moto.getInstance().eventDispatcher.dispatchEvent(new ExternalDataProviderEvent(_propertyGroupID, dataProvider, index));
		}
		
		private function pageChangeHandler(e){

			if (!thumbnailsPaginator.hasNextPage()){
				Tweener.addTween(_arrowRight, { alpha:0.5, time:0.3 } );
				_arrowRight.buttonMode = false;
				if (!disableArrowOut)
				{
					_arrowRight.gotoAndPlay('out');
				}
			}
			else{
				Tweener.addTween(_arrowRight, { alpha:1, time:0.3 } );
				_arrowRight.buttonMode = true;
			}
			
			if (!thumbnailsPaginator.hasPreviousPage()){
				Tweener.addTween(_arrowLeft, { alpha:0.5, time:0.3 } );
				_arrowLeft.buttonMode = false;
				if (!disableArrowOut)
				{
					_arrowLeft.gotoAndPlay('out');
				}
			}
			else{
				Tweener.addTween(_arrowLeft, { alpha:1, time:0.3 } );
				_arrowLeft.buttonMode = true;
			}
			
			disableArrowOut = false;
			
			thumbnailsArranger.selectItemRendererByIndex(-1, false)
		}
		
		//---ARROWS---
        private function SetArrowsProperty(){
			if(thumbnailsPaginator.hasNextPage()){
				ShowArrows();
			}
			else{
				HideArrows();
			}
		}
		
		//---
		private function ShowArrows(){
			_arrowRight.visible = true;
			_arrowLeft.visible = true;
			
			_arrowRight.buttonMode = true;
			_arrowRight.mouseChildren = false;
			_arrowLeft.buttonMode = true;
			if (!thumbnailsPaginator.hasPreviousPage() && !limitedMode){
				_arrowLeft.alpha = 0.5;
				_arrowLeft.buttonMode = false;
			}
			_arrowLeft.mouseChildren = false;
			thumbnailsPaginator.previousButton = _arrowLeft;
			thumbnailsPaginator.nextButton = _arrowRight;
			thumbnailsPaginator.addEventListener("pageChange", pageChangeHandler);
		}
		
		//---
		private function HideArrows(){
			_arrowRight.visible = false;
			_arrowRight.buttonMode = false;
			_arrowLeft.visible = false;
			_arrowLeft.buttonMode = false;
		}
		
		//---
        private function _stageHandler(event:Event):void
        {
            switch (event.type)
            {
                case Event.ADDED_TO_STAGE:
                    addEventListener(Event.REMOVED_FROM_STAGE, _stageHandler);
                    stage.addEventListener(Event.RESIZE, _stageHandler);
 
					stage.addEventListener('ChangeComplete', changeCompleteHandler);
					
					_arrowLeft.addEventListener(MouseEvent.ROLL_OVER, overHandler);
					_arrowLeft.addEventListener(MouseEvent.ROLL_OUT, outHandler);
					_arrowRight.addEventListener(MouseEvent.ROLL_OVER, overHandler);
					_arrowRight.addEventListener(MouseEvent.ROLL_OUT, outHandler);
					
                    _stageHandler(new Event(Event.RESIZE));
                    break;
 
                case Event.REMOVED_FROM_STAGE:
                    stage.removeEventListener(Event.RESIZE, _stageHandler);
                    break;
 
                case Event.RESIZE:
				
                    break;
            }
        }
		
		private function _initializationHandler(event:Event):void {
			switch (event.type) {
				case INITIALIZATION_COMPLETE :
					_preview.visible = !(dataProvider.length || !limitedMode);
					_contentHolder.visible = !_preview.visible;
					_btnHolder.visible = !_preview.visible;;
					
					setSize(vo.width, vo.height);
					dispatchEvent(new UpdateMotoObjectEvent("motoObjectSizeUpdated"));
					
					if(!limitedMode)
						thumbnailsArranger.selectItemRendererByIndex(-1, false);
					
					SetArrowsProperty();
					
					break;

				case INITIALIZATION_ERROR :
					_preview.visible = limitedMode;
					break;
			}
		}
		
		private function changeCompleteHandler(e:Event):void 
		{
			blockChangeImage = false;
		}
		
		private function overHandler(e:MouseEvent):void 
		{
			if (e.currentTarget.buttonMode)
			{
				e.currentTarget.gotoAndPlay('over');
			}
		}
		
		private function outHandler(e:MouseEvent):void 
		{
			if (e.currentTarget.buttonMode)
			{
				e.currentTarget.gotoAndPlay('out');
			}
		}
	}
}