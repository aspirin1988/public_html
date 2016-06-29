package common 
{
	import com.asual.swfaddress.SWFAddress;
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.shell.model.vo.ModuleItemVO;
	import com.moto.template.shell.model.vo.PropertyVO;

	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;

	public class TemplateUtils1
	{
		
		public function TemplateUtils1() 
		{
			
		}
		
		static public function setTextFieldHeight(textField:TextField, multiline:Boolean = false, wordWrap:Boolean = false, autoSize:String = "left"):void
		{
			var tf:TextField = new TextField();
			tf.width = textField.width;
			tf.wordWrap = wordWrap;
			tf.multiline = multiline;
			tf.autoSize = autoSize;
			tf.defaultTextFormat = textField.getTextFormat();
			tf.htmlText = textField.htmlText;
			textField.height = tf.height;
		}
		
		static public function setTextFormat(textField:TextField, propertyVO:PropertyVO):void
		{
			var format:TextFormat = MotoUtils.getTextFormatFromXML(propertyVO.value);
			
			textField.defaultTextFormat = format;
			textField.setTextFormat(format);
			
			MotoUtils.setHTMLParametersFromPropertyVO(textField, propertyVO);
		}
		
		static public function setFont(textField:TextField, fontName:String, fontColor:int = -1):void
		{
			var tf:TextFormat = textField.getTextFormat();
			tf.font = fontName;
			if (fontColor > - 1)
			{
				tf.color = fontColor;
			}
			textField.defaultTextFormat = tf;
			textField.setTextFormat(tf);
		}
		
		static public function getItemIndex(array:Array, keyName:String, value:*):int
		{
			var i:uint = array.length;
			while (i--)
			{
				if (array[i][keyName] == value)
				{
					return i;
				}
			}
			
			return -1;
		}
		
		static public function checkAddress(itemAdress:String, idVO:String):Boolean
		{
			return (itemAdress == idVO || 
					itemAdress == idVO + "/" || 
					itemAdress == "/" + idVO || 
					itemAdress == "/" + idVO + "/");
		}
		
		static public function getDeepAddress(address:String, pageID:String):String
		{
			if (address.lastIndexOf("/") != address.length - 1)
			{
				address += "/";
			}
			
			return address + pageID;
		}
		
		static public function gotoDeepAddress(address:String, pageID:String):void
		{
			SWFAddress.setValue(getDeepAddress(address, pageID)/* + "/"*/);
		}
		
		static public function getItemIndexByID(dataProvider:Array, id:uint):int
		{
			var length:uint = dataProvider.length;
			for (var i:uint = 0; i < length; i++)
			{
				if (ModuleItemVO(dataProvider[i]).id == id)
				{
					return i;
				}
			}
			return -1;
		}
	}

}