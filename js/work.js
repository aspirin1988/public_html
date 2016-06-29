$(document).ready(function(){
		$('#main-menu').smartmenus({
			mainMenuSubOffsetX: -1,
			mainMenuSubOffsetY: 4,
			subMenusSubOffsetX: 6,
			subMenusSubOffsetY: -6,
			subIndicatorsText:'',
		});
		
		$("a.f_box").fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600, 
			'speedOut'		:	200, 
			'overlayShow'	:	false
		});
		$('.full-width').horizontalNav({});
		
		
		$(".f__box").fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			type:'ajax'
		});
		$(".parallax-container").parallax({});
		
		var max=0;
		var _e=$(".pp-card h3").each(function(i, element){
			if ($(element).height()>max) max=$(element).height();
		});
		_e.height(max);
		
		
		var p_list=$('.p-list');

	p_list.each(function(indx, element){
		var _id=$(element).attr('id');
		//alert('#'+_id);
		$('#'+_id).carouFredSel({
		auto: false, 
		prev:('#'+_id+'c .a_left'),
		next:('#'+_id+'c .a_right'), 
		items: {
			height:280,
			visible : {min: 2,max: 6},
			},
			cookie:true,
			height:300,
			width:'100%',
			responsive: true, 
			easing:'easeOutQuart',
			scroll: 1,
			mousewheel: false,
			swipe: {onMouse: true, onTouch: true}
		});	
		//alert(_id);
		
		
		//heights.push();
	});
	
		
		
		});