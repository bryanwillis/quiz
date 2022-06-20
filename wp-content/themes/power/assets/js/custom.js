//-----------------------------------------------
//scroll buton bottm to top
//-----------------------------------------------
jQuery(document).ready(function(){
    jQuery('.site-manu ul li').hover(function(){
        jQuery(this).find('div.mn_services').stop().slideToggle("fast");
    });
    
    
    jQuery('.myservices').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true
    });
    
    
});

 wow = new WOW(
           {
            boxClass:     'wow',      // default
            animateClass: 'animated', // default
            offset:       0,          // default
            mobile:       true,       // default
            live:         true        // default
          }
        );
     wow.init();
 jQuery(document).ready(function(){
	// hide #back-top first
	jQuery("#back-top").hide();
	
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 350) {
				jQuery('#back-top').fadeIn();
			} else {
				jQuery('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery('#back-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
        });
});


//----------------------------------------------
//             Href scroll
//---------------------------------------------
     jQuery(document).ready(function(){
             jQuery('ul li a[href^="#"]').on('click',function(){
                var target=jQuery(this.getAttribute('href'));
                if(target.length){
                    jQuery('body,html').stop().animate({
                       scrollTop:target.offset().top  
                    },3000);
                }
           }); 
       }); 
       
//----------------------------------------------
//             Href scroll
//---------------------------------------------
       
   function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }


jQuery(document).ready(function(){
    jQuery(".seach-toggle").on('click', function(){
        jQuery(".search-box.now-visible").slideToggle('active');
    });
});