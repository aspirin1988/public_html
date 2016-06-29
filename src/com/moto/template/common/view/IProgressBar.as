package com.moto.template.common.view
{
	/**
	 * The IProgressBar interface is implemented by moto progress bar.
	 */
	public interface IProgressBar
	{
		/**
		 * Set the values of progress.
		 *
		 * @param	value
		 * @param	total
		 */
		function setProgress(value:Number, total:Number):void;

		/**
		 * Current value of progress.
		 */
		function get loaded():Number;

		/**
		 * Total value of progress.
		 */
		function get total():Number;

		/**
		 * Percent value of the progress completeness.
		 */
		function get percentLoaded():int;
	}
}
