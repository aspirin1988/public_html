package popups{
	import com.moto.template.common.Moto;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.view.AdvancedScrollbar;
	import com.moto.template.shell.view.components.AbstractMotoPopup;	
	import com.moto.template.shell.model.vo.PopupVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.model.vo.SlotVO;
	
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Point;

	public class WebsitePopup extends AbstractMotoPopup {
		private var _popupHolder:MovieClip;
		private var _bg:MovieClip;

		public function WebsitePopup () {
			super ();

			_bg = bg.getChildByName("bg") as MovieClip;
			_popupHolder = getChildByName("popupHolder") as MovieClip;
			addEventListener (Event.ADDED_TO_STAGE, addedToStageHandler);
		}
		override public function hide ():void {
			gotoAndPlay ("hide");
			_popupHolder.gotoAndPlay ("hide");
		}
		private function addedToStageHandler (event:Event):void {
			stage.addEventListener (Event.RESIZE, resizeHandler);

			resizeHandler (null);
		}
		private function resizeHandler (event:Event):void {
			if (stage) {
				_bg.width = stage.stageWidth + 20;
				_bg.height = stage.stageHeight + 20;
				_bg.x = -((stage.stageWidth - 980) / 2);
				_bg.y = -((stage.stageHeight - 780) / 2);
			}
		}

		override protected function closeButtonClickHandler (event:MouseEvent):void {
			stage.removeEventListener (Event.RESIZE, resizeHandler);

			_popupHolder.gotoAndPlay ("hide");
			gotoAndPlay ("hide");
			//_popupHolder.closeButton.gotoAndPlay ("hide");
		}
	}
}