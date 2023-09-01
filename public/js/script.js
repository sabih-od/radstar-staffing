"use strict";



/* ==== Jquery Functions ==== */
(function($) {
	
	
	/* ==== Testimonials Slider ==== */	
  	$(".testimonialsList").owlCarousel({      
	   loop:true,
		margin:30,
		nav:false,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:false
			},
			768:{
				items:1,
				nav:false
			},
			1170:{
				items:2,
				nav:false,
				loop:true
			}
		}
  	});
	
	/* ==== employerList Slider ==== */	
  	  function owlSlider(){
		var owl = $('.employerList'),
			dataGroupItem = owl.data('group-item'),
			owlItem = owl.find(".item-child").length;
		
			var groupNumber = owlItem / dataGroupItem;
			for(var i = 0; i < groupNumber; i++) {
			 owl.append('<div class="item-' + (i + 1) + '"></div>');
			  
			 var item = owl.children('.item-child');
			  var flag = 0;
			  item.each(function(){
				$(this).appendTo('.employerList .item-' + (i + 1));
				if(flag == (dataGroupItem - 1)) {
				  return false;
				}
				flag++;
			  });
			}
		
		owl.owlCarousel({
			loop:true,
			margin:15,
			responsiveClass:true,
			responsive:{
				0:{
					items: 1
				},
				767:{
				  items: 2
				},
				1170:{
					items: 5
				  }
			}
		});
	  }
	  
	  owlSlider();





	
	
	/* ==== Revolution Slider ==== */
	if($('.tp-banner').length > 0){
		$('.tp-banner').show().revolution({
			delay:6000,
	        startheight:550,
	        startwidth: 1140,
	        hideThumbs: 1000,
	        navigationType: 'none',
	        touchenabled: 'on',
	        onHoverStop: 'on',
	        navOffsetHorizontal: 0,
	        navOffsetVertical: 0,
	        dottedOverlay: 'none',
	        fullWidth: 'on'
		});
	}
	
	
	//Top search bar open/close
    if (!$('.srchbox').hasClass("searchStayOpen")) {
        $("#jbsearch").click(function() {
            $(".srchbox").addClass("openSearch");
            $(".additional_fields").slideDown();
        });


        $(".srchbox").click(function(e) {
            e.stopPropagation();
        });
    }
	
})(jQuery);