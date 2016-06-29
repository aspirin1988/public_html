package
{
	import com.moto.template.common.Moto;
	import com.moto.template.common.view.IAnimatedProgressBar;

	import flash.display.StageAlign;
	import flash.display.StageScaleMode;

	import common.MotoWebsiteLoader;

	public class MotoWebsite extends MotoWebsiteLoader
	{

		public function MotoWebsite()
		{
			super();

			// Configure stage
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.stageFocusRect = false;

			_preloader = appPreloader as IAnimatedProgressBar;
		}

		override protected function init():void
		{
			super.init();
		}

		override public function showWebsite():void
		{
			// Show website
			Moto.getInstance().website.play();
		}
	}
}
