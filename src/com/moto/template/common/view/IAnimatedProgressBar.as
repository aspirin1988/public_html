package com.moto.template.common.view
{
	import com.moto.template.common.view.IProgressBar;

	/**
	 * The IAnimatedProgressBar interface is implemented by moto animated progress bars.
	 */
	public interface IAnimatedProgressBar extends IProgressBar
	{
		/**
		 * Show the progress bar.
		 */
		function show():void;

		/**
		 * Hide the progress bar.
		 */
		function hide():void;
	}
}
