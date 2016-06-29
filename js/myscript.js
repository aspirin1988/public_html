	function max_height(){
		var maxheight=$(".mheight");
		var max=$("#gcamera").height();;
		/*
		maxheight.each(function(i, e){
			if ($(e).height()>max) max=$(e).height();
		});*/
		maxheight.height(max);
		
	}
	$(window).resize(function (){
		max_height();
	});

