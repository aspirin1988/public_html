package common.events 
{
	import flash.events.Event;
	
	public class LightBoxEvent extends Event 
	{
		static public const TYPE:String = "ligthBoxEventType";
		
		public var motoGroupID:String;
		public var lastIndex:int;
		
		public var action:String;
		
		public function LightBoxEvent(motoGroupID:String, action:String, lastIndex:int = 0)
		{ 
			super(TYPE);
			
			this.motoGroupID = motoGroupID;
			this.action = action;
			this.lastIndex = lastIndex;
		} 
		
		public override function clone():Event 
		{ 
			return new LightBoxEvent(motoGroupID, action, lastIndex);
		} 
		
		public override function toString():String 
		{ 
			return formatToString("LightBoxEvent", "motoGroupID", "action", "type", "bubbles", "cancelable", "eventPhase"); 
		}
		
	}
	
}