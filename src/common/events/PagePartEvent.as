package common.events 
{
	import flash.events.Event;
	
	/**
	 * ...
	 * @author 
	 */
	public class PagePartEvent extends Event 
	{
		static public const CHANGE:String = "changePage";
		public var label:String;
		
		public function PagePartEvent(type:String, label:String) 
		{ 
			super(CHANGE);
			this.label = label;
		} 
		
		public override function clone():Event 
		{ 
			return new PagePartEvent(type, label);
		} 
		
		public override function toString():String 
		{ 
			return formatToString("PagePartEvent", "label", "type", "bubbles", "cancelable", "eventPhase"); 
		}
		
	}
	
}