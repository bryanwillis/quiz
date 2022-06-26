//-----------------------------------------------
//scroll buton bottm to top
//-----------------------------------------------
jQuery(document).ready(function(){
    
    jQuery(".bext_button").on('click', function(){
            var team_name = jQuery("#team_name-866").val();
            if(team_name==''){
                jQuery(".um-field-team_name").addClass('error');
                return false;
            }else{
                jQuery(".um-field-team_name").removeClass('error');
            }
            
            var mailformat = /^([a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var username = jQuery("#username-866").val();
            if(!mailformat.test(username)){
                jQuery(".um-field-username").addClass('error');
                return false;
            }else{
                jQuery(".um-field-username").removeClass('error');
            }

            var first_name = jQuery("#first_name-866").val();
            if(first_name==''){
                jQuery(".um-field-first_name").addClass('error');
                return false;
            }else{
                jQuery(".um-field-first_name").removeClass('error');
            }

            var last_name = jQuery("#last_name-866").val();
            if(last_name==''){
                jQuery(".um-field-last_name").addClass('error');
                return false;
            }else{
                jQuery(".um-field-last_name").removeClass('error');
            }
            

            var passwordRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
            var user_password = jQuery("#user_password-866").val();
            if(!passwordRegex.test(user_password)){
                jQuery("#um_field_866_user_password").addClass('error');
                return false;
            }else{
                jQuery("#um_field_866_user_password").removeClass('error');
            }

            

            var confirm_user_password = jQuery("#confirm_user_password-866").val();
            if(confirm_user_password!=user_password){
                jQuery("#um_field_866_confirm_user_password").addClass('error');
                return false;
            }else{
                jQuery("#um_field_866_confirm_user_password").removeClass('error');
            }

            var terms = jQuery("#um_field_866_terms .um-field-checkbox");
            if(!terms.hasClass( "active" )){
                jQuery("#um_field_866_terms").addClass('error');
                return false;
            }else{
                jQuery("#um_field_866_terms").removeClass('error');
            }
        jQuery(".um-field-Country").css({display: 'block'});
        jQuery(".um-field-your_culture").css({display: 'block'});
//        jQuery(".um-form .um-field-user_gender").css({display: 'block !important'});
        jQuery(".um-field-user_age").css({display: 'block'});
        jQuery(".um-field-share_informations").css({display: 'block'});
        jQuery(".um-field-Industry").css({display: 'block'});
//        jQuery(".um-field-team_name").css({display: 'block'});

        jQuery(".um-field-gender_f").css({display: 'block'});
        jQuery(".um-register .um-col-alt").css({display: 'block'});
        

        jQuery("._um_row_1").css({display: 'none'});

        jQuery(".um-field-team_name").css({display: 'none'});
        jQuery(".um-field-username").css({display: 'none'});
        jQuery(".um-field-user_login").css({display: 'none'});
        jQuery(".um-field-first_name").css({display: 'none'});
        jQuery(".um-field-last_name").css({display: 'none'});
        jQuery(".um-field-user_email").css({display: 'none'});
        jQuery(".um-field-user_password").css({display: 'none'});
        jQuery(".um-field-terms").css({display: 'none'});
        jQuery(".um-field-um_block_866_20").css({display: 'none'});        
        jQuery(".bext_button").css({display: 'none'});
        return false;
    });
    
    
//    jQuery('input[type=radio][name=share_informations[]]').change(function() {
//        console.log('yes');
//    });

    jQuery('input').on('click', function() {
        var share_informations = jQuery("input[name='share_informations[]']:checked").val();
        if (share_informations == "I agree to share my information") {
            jQuery(".um-field-Country").css({display: 'block'});
            jQuery(".um-field-your_culture").css({display: 'block'});
            jQuery(".um-field-gender_f").css({display: 'block'});
            jQuery(".um-field-user_age").css({display: 'block'});
            jQuery(".um-field-Industry").css({display: 'block'});
            //jQuery(".um-field-team_name").css({display: 'block'});

        }else if (share_informations == "I don’t agree to share my information") {
            jQuery(".um-field-Country").css({display: 'none'});
            jQuery(".um-field-your_culture").css({display: 'none'});
            jQuery(".um-field-gender_f").css({display: 'none'});
            jQuery(".um-field-user_age").css({display: 'none'});
            jQuery(".um-field-Industry").css({display: 'none'});
            /*jQuery(".um-field-team_name").css({display: 'none'});*/
        }
    });
    
    jQuery("#Industry").on('change', function(){
        var Industry = jQuery(this).val();
        if(Industry=='Other'){
            jQuery(".um-field-other_industry").css({display: 'block'});
        }else{
            jQuery(".um-field-other_industry").css({display: 'none'});
        }
    });
    
    jQuery("#Country").on('change', function(){
        var country = jQuery(this).val();
        if(country=='Other'){
            jQuery(".um-field-other_country").css({display: 'block'});
        }else{
            jQuery(".um-field-other_country").css({display: 'none'});
        }
    });
    
    jQuery("#your_culture").on('change', function(){
        var country = jQuery(this).val();
        if(country=='Other'){
            jQuery(".um-field-your_other_culture").css({display: 'block'});
        }else{
            jQuery(".um-field-your_other_culture").css({display: 'none'});
        }
    });
    
    jQuery(".um-register form").on('submit', function(){
        var Industry = jQuery("#Industry").val();
        if(Industry=='Other'){
            var other_industry = jQuery("#other_industry").val();
            if(other_industry==''){
                jQuery(".um-field-other_industry").addClass('error');
                return false;
            }else{
                jQuery(".um-field-other_industry").removeClass('error');
            }
        }
        var country = jQuery("#Country").val();
        if(country=='Other'){
            var other_country = jQuery("#other_country").val();
            if(other_country==''){
                jQuery(".um-field-other_country").addClass('error');
                return false;
            }else{
                jQuery(".um-field-other_country").removeClass('error');
            }
        }
        var your_culture = jQuery("#your_culture").val();
        if(your_culture=='Other'){
            var your_other_culture = jQuery("#your_other_culture").val();
            if(your_other_culture==''){
                jQuery(".um-field-your_other_culture").addClass('error');
                return false;
            }else{
                jQuery(".um-field-your_other_culture").removeClass('error');
            }
        }
    });
    
    jQuery('.site-manu ul li').hover(function(){
        jQuery(this).find('div.mn_services').stop().slideToggle("fast");
    });
    
    var w_height = jQuery(window).height();
    jQuery(".quiz_page").css({height:w_height+"px"});
    
    
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

jQuery(document).ready(function() {

    jQuery('#user_password-866').keyup(function() {
        var pswd = jQuery(this).val();
        //validate the length
        if ( pswd.length < 8 ) {
            jQuery('#length').removeClass('valid').addClass('invalid');
        } else {
            jQuery('#length').removeClass('invalid').addClass('valid');
        }
        //validate letter
        if ( pswd.match(/[a-z]/) ) {
            jQuery('#letter').removeClass('invalid').addClass('valid');
        } else {
            jQuery('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            jQuery('#capital').removeClass('invalid').addClass('valid');
        } else {
            jQuery('#capital').removeClass('valid').addClass('invalid');
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            jQuery('#number').removeClass('invalid').addClass('valid');
        } else {
            jQuery('#number').removeClass('valid').addClass('invalid');
        }
        }).focus(function() {
            jQuery('#pswd_info').show();
        }).blur(function() {
            jQuery('#pswd_info').hide();
    });

});