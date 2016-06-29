package com.moto.template.common.view
{
	import com.moto.template.common.view.IProgressBar;

	import flash.display.MovieClip;

	/**
	 * The class which implements the abstract logic of progress bar.
	 *
	 * <p>The abstract logic of progress bar is implemented in this class.
	 * For effective use you should extend this class, override
	 * the <code>setProgress</code> method and implement
	 * the logic of progress display in it.</p>
	 */
	public class AbstractProgressBar extends MovieClip implements IProgressBar
	{
		//----------------------------------
		//  loaded
		//----------------------------------

		/**
		 * @private
		 * The backing variable for the property.
		 */
		private var _loaded:Number;

		/**
		 * Current value of progress.
		 *
		 * @return Number
		 */
		public function get loaded():Number
		{
			return _loaded;
		}

		/**
		 * @private
		 */
		public function set loaded(value:Number):void
		{
			setProgress(value, _total);
		}

		//----------------------------------
		//  total
		//----------------------------------

		/**
		 * @private
		 * The backing variable for the property.
		 */
		private var _total:Number;

		/**
		 * @inheritDoc
		 */
		public function get total():Number
		{
			return _total;
		}

		/**
		 * @private
		 */
		public function set total(value:Number):void
		{
			setProgress(_loaded, value);
		}

		/**
		 * @inheritDoc
		 */
		public function get percentLoaded():int
		{
			return Math.floor(_loaded * 100 / _total);
		}

		/**
		 * Сonstructor.
		 */
		public function AbstractProgressBar()
		{
			super();
		}

		/**
		 * @inheritDoc
		 */
		public function setProgress(value:Number, total:Number):void
		{
			_loaded = value;
			_total = total;
		}
	}
}