package common
{
	import com.moto.template.common.Moto;
	import com.moto.template.common.view.IAnimatedProgressBar;
	import com.moto.template.common.events.MotoEvent;
	import com.moto.template.common.events.MotoProgressEvent;
	import com.moto.template.shell.MotoApplication;

	import flash.display.Loader;
	import flash.display.MovieClip;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;

	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;

	import flash.system.LoaderContext;
	import flash.system.ApplicationDomain;

	import flash.net.URLRequest;
	import flash.net.URLVariables;

	public class MotoWebsiteLoader extends MovieClip
	{
		protected var _preloader:IAnimatedProgressBar;

		private var _rslLoader:Loader;

		private var _rslVersion:String = "1.7.0";
		
		private var _rslURI:String = "admin/MotoCMS.swf";

		public function MotoWebsiteLoader()
		{
			super();
			
			if (root && root.loaderInfo && root.loaderInfo.parameters)
			{
				var parameters:Object = root.loaderInfo.parameters;
				if (parameters["rslUri"])
					_rslURI = String(parameters["rslUri"]);
				if (parameters["rslVersion"])
					_rslVersion = String(parameters["rslVersion"]);
			}

			_rslLoader = new Loader();

			_rslLoader.contentLoaderInfo.addEventListener(
				Event.COMPLETE, rslLoaderCompleteHandler);
			_rslLoader.contentLoaderInfo.addEventListener(
				IOErrorEvent.IO_ERROR, rslLoaderIOErrorHandler);
			_rslLoader.contentLoaderInfo.addEventListener(
				SecurityErrorEvent.SECURITY_ERROR, rslLoaderSecurityErrorHandler);

			var loaderContext:LoaderContext = new LoaderContext(
				false, ApplicationDomain.currentDomain);

			var urlRequest:URLRequest = new URLRequest(_rslURI);

			if (!loaderInfo.loaderURL.indexOf("http"))
			{
				var variables:URLVariables = new URLVariables();
				variables.version = _rslVersion;
				urlRequest.data = variables;
			}

			_rslLoader.load(urlRequest, loaderContext);
		}

		protected function rslLoaderCompleteHandler(event:Event):void
		{
			// remove event listeners
			_rslLoader.contentLoaderInfo.removeEventListener(
				Event.COMPLETE, rslLoaderCompleteHandler);
			_rslLoader.contentLoaderInfo.removeEventListener(
				IOErrorEvent.IO_ERROR, rslLoaderIOErrorHandler);
			_rslLoader.contentLoaderInfo.removeEventListener(
				SecurityErrorEvent.SECURITY_ERROR, rslLoaderSecurityErrorHandler);

			init();
		}

		protected function rslLoaderSecurityErrorHandler(event:SecurityErrorEvent):void
		{
			// TODO
			trace("rslLoaderSecurityErrorHandler", event.text);
		}

		protected function rslLoaderIOErrorHandler(event:IOErrorEvent):void
		{
			// TODO
			trace("rslLoaderIOErrorHandler", event.text);
		}

		protected function init():void
		{
			// Create new Moto application
			var app:MotoApplication = new MotoApplication(this);

			// Event listeners
			app.addEventListener(MotoProgressEvent.MOTO_PROGRESS, appProgressHandler);
			app.addEventListener(MotoEvent.INITIALIZATION_COMPLETE, appInitializationCompleteHandler);
		}

		protected function appProgressHandler(event:Event):void
		{
			// Update application progress bar
			var motoProgressEvent:MotoProgressEvent = MotoProgressEvent(event)
			if (motoProgressEvent && _preloader)
			{
				_preloader.setProgress(motoProgressEvent.loaded, motoProgressEvent.total);
			}
		}

		protected function appInitializationCompleteHandler(event:Event):void
		{
			// Hide application preloader
			gotoAndPlay("hide");
		}

		public function showWebsite():void
		{
		}

	}
}
