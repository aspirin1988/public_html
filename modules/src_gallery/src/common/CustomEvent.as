package common 
{
	import flash.events.Event;
	
	/**
	 * ...
	 * @author 
	 */
	public class CustomEvent extends Event 
	{
		public var propertyVO;
		public var propertyVO2;
		public static const SEND_TEXT:String = 'sendText';
		
		public function CustomEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false) 
		{ 
			super(type, bubbles, cancelable);
			
		} 
		
		public override function clone():Event 
		{ 
			return new CustomEvent(type, bubbles, cancelable);
		} 
		
		public override function toString():String 
		{ 
			return formatToString("CustomEvent", "type", "bubbles", "cancelable", "eventPhase"); 
		}
		
	}
	
}