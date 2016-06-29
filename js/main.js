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
	
		$(".parallax-container").parallax({});
		
		$(".f__box").fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			type:'ajax'
		});
		  
		$('.full-width').horizontalNav({});


	$('.m_comments').click(function(){
		$(".m_comments").fancybox({});
	});
	$(".f_swf").fancybox({
		'width'				: '75%',
		'height'			: '75%',
        'autoScale'     	: false,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});


	});