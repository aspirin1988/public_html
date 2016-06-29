package com.moto.template.common.view
{
	import com.moto.template.common.view.AbstractProgressBar;
	import com.moto.template.common.view.IAnimatedProgressBar;

	import flash.display.DisplayObjectContainer;
	import flash.display.MovieClip;

	/**
	 * The class which implements an animated progress bar.
	 *
	 * <p>For effective use you should extend this class and override methods:
	 * <code>setProgress, show, hide</code> with calling by the super method.</p>
	 *
	 * <p>In this class the <code>setProgress</code> method is implemented for
	 * moving the cursor on the timeline to the <code>percentCompleted</code> value.</p>
	 *
	 * <p>If you are using an instance of the <code>AnimatedProgressBar</code>
	 * class on the timeline with animation, e.g. for gallery thumbnails, it is
	 * better to put it in another MovieClip. In this case an instance of the
	 * <code>AnimatedProgressBar</code> class won't be created again when playing
	 * animation on the timeline cyclically</p>
	 */
	public class AnimatedProgressBar extends AbstractProgressBar implements IAnimatedProgressBar
	{
		private var _parent:DisplayObjectContainer;

		private var _depth:int = 0;

		/**
		 * Constructor.
		 */
		public function AnimatedProgressBar()
		{
			super();
		}

		/**
		 * Showing progress bar.
		 */
		public function show():void
		{
			if (!parent && _parent)
			{
				_parent.addChildAt(this, _depth);
			}
		}

		/**
		 * Hiding progress bar.
		 */
		public function hide():void
		{
			if (parent)
			{
				_parent = parent;
				_depth = _parent.getChildIndex(this);
				_parent.removeChild(this);
			}
		}

		/**
		 * @inheritDoc
		 */
		override public function setProgress(value:Number, total:Number):void
		{
			super.setProgress(value, total);
			gotoAndStop(percentLoaded);
		}
	}
}