package slots{
	import com.moto.template.common.utils.MotoUtils;
	import com.moto.template.common.view.components.loaders.ImageLoader;
	import com.moto.template.shell.model.vo.PropertyVO;
	import com.moto.template.shell.view.components.AbstractMotoSlot;
	import flash.display.MovieClip;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;

	public class TitleSlot extends AbstractMotoSlot
	{
		private var _tf:TextField;
		private var _tf2:TextField;
		private var _area:MovieClip;
		private var _alpha:Number = 0;
		private var _image:ImageLoader;
		
		public function TitleSlot()
		{
			super();

			// Get slot elements
			
			_area = area as MovieClip;
			_image = imageHolder.image as ImageLoader;
			_tf = textHolder1.clip.tf as TextField;
			_tf2 = textHolder2.clip.tf as TextField;
			_tf.autoSize = _tf2.autoSize = TextFieldAutoSize.LEFT;
			_tf.wordWrap = _tf2.wordWrap = false;
			_tf.multiline = _tf2.multiline = true;

			mouseChildren = false;
			buttonMode = true;
			hitArea = _area;
		}
		override public function updateProperty(property:PropertyVO):void
		{
			switch (property.propertyType)
			{
				case 1 :
					MotoUtils.setHTMLTextFromPropertyVO(_tf, property);
					setSize(Math.max(_tf.textWidth, _area.width), Math.max(_tf.height, _area.height));
					updateDimensions();
					break;

				case 2 :
					MotoUtils.setHTMLTextFromPropertyVO(_tf2, property);
					setSize(Math.max(_tf.textWidth, _area.width), Math.max(_tf.height, _area.height));
					updateDimensions();
					break;
					
				case 3 :
					_image.source = pathPrefix + property.value;
					break;

				case 4 :
					_image.y = Math.min((getDimensions().height - _image.height), Number(property.value));
					break;
					
				case 5 :
					_alpha = Number(property.value) / 100;
					_image.alpha = _alpha;
					break;
				case 6 :
					_image.x = Number(property.value);
					break;
			}
		}
		
		override public function setSize(newWidth:Number, newHeight:Number):void
		{
			super.setSize(newWidth, newHeight);
			newWidth = Math.max(newWidth, Math.round(_tf.width));
			newHeight = Math.max(newHeight, Math.max(_tf.height));

			_area.height = newHeight;
			_area.width = newWidth;
			
			//_tf.x = int((newWidth - _tf.width) / 2);
			_tf.y = int((newHeight - _tf.height) / 2);
			_tf2.x = int((_tf.width - _tf2.width) / 2);
			_tf2.y = int((newHeight - _tf2.height) / 2);
		}
		override public function getDimensions():Rectangle
		{
			return new Rectangle(0,0,_area.width,_area.height);
		}
	}
}