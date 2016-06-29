package modules{
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.modules.form.items.TextItem;
	import com.moto.template.shell.model.vo.ModuleItemVO;
	import com.moto.template.shell.model.vo.ModuleVO;
	import com.moto.template.shell.model.vo.MotoObjectVO;
	import com.moto.template.shell.model.vo.PropertyVO;
	
	import com.moto.template.modules.mediaPlayer.SimpleMusicPlayerModule;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.events.MouseEvent;
	import caurina.transitions.Tweener;
	import flash.text.TextFieldAutoSize;
	import caurina.transitions.properties.SoundShortcuts;

	public class MP3PlayerModule extends SimpleMusicPlayerModule {
		private var _button:MovieClip;
		private var _holderON:MovieClip;
		private var _holderOFF:MovieClip;


		public function MP3PlayerModule() {
			_holderON = MovieClip(getChildByName("holderON"));
			_holderOFF = MovieClip(getChildByName("holderOFF"));
			_button = MovieClip(getChildByName("button"));

			buttonMode = true;
			_button.addEventListener(MouseEvent.CLICK, buttonClickHandler);

			SoundShortcuts.init();

			addEventListener(INITIALIZATION_ERROR, initializationErrorHandler);
		}
		
		override public function init(vo:MotoObjectVO):void {
			super.init(vo);

			// Turn off music in the limited mode
			if (limitedMode)
			{				
				_muteSound = true;
				Tweener.addTween(_soundChannel, {
					_sound_volume: 0,
					time: 0
				});
			}
		}
		
		private function switchLabel():void
		{
			var labelPropertyVO:PropertyVO;
			if (!_muteSound)
			{
				_holderON.alpha = 1;
				_holderOFF.alpha = 0;
			}
			else
			{
				_holderON.alpha = 0;
				_holderOFF.alpha = 1;
			}
		}
		
		override public function switchVolume():void
        {
        	_muteSound = !_muteSound;
			switchLabel();
			Tweener.removeTweens(_soundChannel);
			if (_muteSound)
			{
				Tweener.addTween(_soundChannel, {
			  		_sound_volume: 0, onComplete:function ():void { pauseTrack() },
			  		time: 2
			  	});
			}
			else
			{
				if (!limitedMode && !isPlaying)
				{
					playTrack();
					Tweener.addTween(_soundChannel, {
						_sound_volume: 0,
						time: 0
					});
				}
				Tweener.addTween(_soundChannel, {
			  		_sound_volume: defaultVolume,
					delay: .2,
			  		time: 20
			  	});
			}
        }
		
		override protected function initConfiguration():void 
		{
			super.initConfiguration();
			
			if (autoLoadAndPlay && !limitedMode)
			{
				_muteSound = false;
			}
			else
			{
				_muteSound = true;
			}
			switchLabel();
		}
		
		private function initializationErrorHandler(event:Event) {

		}
		override public function getDimensions():Rectangle {
			return new Rectangle(0,0,_button.width,_button.height);
		}

		private function buttonClickHandler(event:MouseEvent):void {
			switchVolume();
		}
		override protected function getCurrentTrackURL():String
        {
            var propertyVO:ModuleItemVO = getCurrentTrackData();
            if (propertyVO)
                return pathPrefix + propertyVO.getPropertyValueByID(1);
            else
                return "";
        }
	}
}