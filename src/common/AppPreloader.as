package common
{
	import com.moto.template.common.view.AnimatedProgressBar;
	import flash.display.MovieClip;
	import flash.text.TextField;

	public class AppPreloader extends AnimatedProgressBar
	{		
		public function AppPreloader()
		{
			line.clip.width = 0;
		}

		override public function setProgress(value:Number, total:Number):void
		{
			super.setProgress(value, total);
			
			// Update textfield
			line.clip.width = line.width * percentLoaded / 100;
			line.clip1.width = line.width - line.clip.width;
			line.clip1.x = line.clip.width;
		}
	}
}
