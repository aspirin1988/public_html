package common 
{
	import flash.events.Event;
	
	public class PreviewAnimationEvent extends Event 
	{
		static public const TYPE:String = "previewAnimationEventType";
		
		public var motoGroupID:String;
		public var status:Boolean;
		
		public function PreviewAnimationEvent(motoGroupID:String, status:Boolean) 
		{ 
			super(TYPE);
			this.motoGroupID = motoGroupID;
			this.status = status;
		} 
		
		public override function clone():Event 
		{ 
			return new PreviewAnimationEvent(motoGroupID, status);
		} 
		
		public override function toString():String 
		{ 
			return formatToString("PreviewAnimationEvent", "motoGroupID", "status", "type", "bubbles", "cancelable", "eventPhase"); 
		}
		
	}
	
}