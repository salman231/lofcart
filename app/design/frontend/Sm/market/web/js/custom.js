require([
    'jquery'
], function ($) {
    if ($(window).width() < 767) {
        $('.col-lg-8 .footer-block-title').click(function(e) {
            e.preventDefault();
            var $this = $(this);
    
            if ($this.next().hasClass('ft-menu-open')) {
                $this.next().removeClass('ft-menu-open');
                $this.next().slideUp(350);
                $(this).removeClass('rotate')
            } else {
                $this.parent().parent().parent().find('.footer-block-content').removeClass('ft-menu-open');
                $this.parent().parent().parent().find('.footer-block-title').removeClass('rotate');
                $this.parent().parent().parent().find('.footer-block-content').slideUp(500);
                $this.next().toggleClass('ft-menu-open');
                $(this).toggleClass('rotate')
                $this.next().slideToggle(500);
            }
        });
    }
	$(window).scroll(function() {    
	if ($(window).width() < 767) {
    var scroll = $(window).scrollTop();
    if (scroll >= 1) {
        $(".header-container.header-style-12").addClass("header-sticky");
    } else {
        $(".header-container.header-style-12").removeClass("header-sticky");
    }
	}
	
	if ($(window).width() < 767) {
        $('.sidebar-main .filter-title').click(function(e) {
           $(this).toggleClass('rotate')
		   $(this).next().toggleClass('ft-menu-open');
		   $(this).next().slideToggle(500);
        });
    }
    
    jQuery(window).scroll(function() {    
        var scroll = jQuery(window).scrollTop();
        if (scroll >= 10) {
            jQuery(".header-top").addClass("top-header-sticky");
        }else{
            jQuery(".header-top").removeClass("top-header-sticky");
        }
    });
    
    jQuery(window).scroll(function() {    
        var scroll = jQuery(window).scrollTop();
        if (scroll >= 100) {
            jQuery(".header-bottom").addClass("sticky");
        }else{
            jQuery(".header-bottom").removeClass("sticky");
        }
    });
    
    
var heights = jQuery("div.product_name").map(function(){
        return jQuery(this).height();
    }).get();
maxHeight = Math.max.apply(null, heights);
jQuery("div.product_name").css("height", maxHeight);

    
}); 
});

