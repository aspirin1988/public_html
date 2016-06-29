(function($) {
$(function() {
var tabs=$('div.box');
	$('ul.tabs').each(function(i) {
		var storage = localStorage.getItem('tab'+i);
		if (storage) $(this).find('li').eq(storage).addClass('current').siblings().removeClass('current');
			tabs.hide().eq(storage).show();
	})

	$('ul.tabs').delegate('li:not(.current)', 'click', function() {
		$(this).addClass('current').siblings().removeClass('current');
		var t=$(this).index('.ttabs');
			tabs.hide();
			tabs.eq(t).stop().fadeIn(150);
		var ulIndex = $('ul.tabs').index($(this).parents('ul.tabs'));
		localStorage.removeItem('tab'+ulIndex);
		localStorage.setItem('tab'+ulIndex, $(this).index());
	})

})
})(jQuery)