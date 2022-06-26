<?php
//-----------------------------------
//       LOGO CUSTOMIZE
//-----------------------------------
add_theme_support( 'custom-logo' );
/*--------------------
          Navigations
----------------------*/
register_nav_menus(array(
   'primary'=>__( 'Header Menu' ),
   'footer'=>__('Footer Menu'),
));
/*--------------------
       SITE FILES
----------------------*/
function actionswing(){
    wp_enqueue_style( 'frontend',  PLUGIN_URL . '/quez_system/assets/css/frontend.css', __FILE__ , false );
    wp_enqueue_style('bootstrap-min', get_template_directory_uri(). '/assets/css/bootstrap.min.css');
    wp_enqueue_style('raleway', get_template_directory_uri(). '/fonts/raleway/stylesheet.css');
    wp_enqueue_style('open', get_template_directory_uri(). '/fonts/open/stylesheet.css');
    wp_enqueue_style('fontawesome-all-min', get_template_directory_uri(). '/fonsawesome/css/fontawesome-all.min.css');
    wp_enqueue_style('slick-theme', get_template_directory_uri(). '/assets/slick/slick-theme.css');
    wp_enqueue_style('slick', get_template_directory_uri(). '/assets/slick/slick.css');
    wp_enqueue_style('animated', get_template_directory_uri() . '/assets/css/animated.css', array());
    
    wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.3');
    
    wp_enqueue_style('responsive', get_template_directory_uri() . '/assets/css/responsive.css', array());

    wp_enqueue_script('bootstrap-min', get_template_directory_uri(). '/assets/js/bootstrap.min.js',array('jquery')); 
    wp_enqueue_script('slick', get_template_directory_uri(). '/assets/slick/slick.js',array('jquery')); 
    wp_enqueue_script('scrollreveal-min', get_template_directory_uri() . '/assets/js/scrollreveal.min.js', array('jquery'));
    wp_enqueue_script('wow-min', get_template_directory_uri() . '/assets/js/wow.min.js', array('jquery'));
    wp_enqueue_script('custom', get_template_directory_uri(). '/assets/js/custom.js',array('jquery'), '1.5'); 
    
}
add_action('wp_enqueue_scripts','actionswing');

add_theme_support( 'post-thumbnails', array( 'post', 'page', 'service' ) );

// See the __() WordPress function for valid values for $text_domain.
register_sidebar( array(
    'id'          => 'top-menu',
    'name'        => __( 'Top Menu', $text_domain ),
    'description' => __( 'This sidebar is located above the age logo.', $text_domain ),
) );
// add_action( 'widgets_init', function(){
//  register_widget( 'My_Widget' );
// });

function widgate_disclaimer(){
    if(function_exists('register_sidebar')){      //register sidebar
    register_sidebar(array(
        'name'=> 'ftr_logo',                     //name of sidebar
        'before_widget' => '<div class="ftr_logo">',
        'after_widget' => '</div>',
        'before_title'=>'<h2>',
        'after_title'=>'</h2>'
    ));
  }
  if(function_exists('register_sidebar')){      //register sidebar
    register_sidebar(array(
        'name'=> 'right_sidebar',                     //name of sidebar
        'before_widget' => '<div class="right_sidebar">',
        'after_widget' => '</div>',
        'before_title'=>'<h2>',
        'after_title'=>'</h2>'
    ));
  }
  
  if(function_exists('register_sidebar')){      //register sidebar
    register_sidebar(array(
        'name'=> 'foetr_widget_1',                     //name of sidebar
        'before_widget' => '<div class="ftr_main">',
        'after_widget' => '</div>',
        'before_title'=>'<h2>',
        'after_title'=>'</h2>'
    ));
  }
  if(function_exists('register_sidebar')){      //register sidebar
    register_sidebar(array(
        'name'=> 'foetr_widget_2',                     //name of sidebar
        'before_widget' => '<div class="ftr_resources">',
        'after_widget' => '</div>',
        'before_title'=>'<h2>',
        'after_title'=>'</h2>'
    ));
  }
  if(function_exists('register_sidebar')){      //register sidebar
    register_sidebar(array(
        'name'=> 'foetr_widget_3',                     //name of sidebar
        'before_widget' => '<div class="ftr_Concepts">',
        'after_widget' => '</div>',
        'before_title'=>'<h2>',
        'after_title'=>'</h2>'
    ));
  }
}
add_action('init','widgate_disclaimer');

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' ); //change page with your post type slug.
}

function  custom_excerpt_lenth(){
    return 50;
}
add_filter('excerpt_length','custom_excerpt_lenth');


function what_is_coching(){
    ?>
    <div class="disscuss_gcc wow fadeInUp" data-wow-delay="0.3s"  style="visibility: visible; -webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s; transition: 0.3s;">
        
        <a href="/what-is-coaching">
            <div class="team_box">
                <div class="gcc_team" style="background: url('<?php echo get_template_directory_uri() ?>/images/Who-is-GCC-For_one.jpg')">
                    <div class="box_colot">
                        <div class="text_box">
                            <h5>What is Coaching?</h5>
                        </div>
                    </div>
                </div>
            </div>
        </a>  
    
        <a href="/how-is-coaching-different-from-therapy-or-a-friend">
            <div class="team_box">
                <div class="gcc_team" style="background: url('<?php echo get_template_directory_uri() ?>/images/Who-is-GCC-For_two.jpg')">
                    <div class="box_colot">
                        <div class="text_box">
                            <h5>How is coaching different from therapy or a friend?</h5>
                        </div>
                    </div>
                </div>
            </div>  
        </a>
        
        <a href="/sponsorship-opportunities">
            <div class="team_box">
                <div class="gcc_team" style="background: url('<?php echo get_template_directory_uri() ?>/images/Who-is-GCC-For_three.jpg')">
                    <div class="box_colot">
                        <div class="text_box">
                            <h5>Sponsorship Opportunities</h5>
                        </div>
                    </div>
                </div>
            </div>
        </a>   
        
</div>
<?php
}
add_shortcode('show_by_shortcode_what_is_coching', 'what_is_coching');


function resources_type(){
    $doc_categories = get_terms( array(
    'taxonomy' => 'type',
    'hide_empty' => false,
) );
foreach($doc_categories as $doc_category) {
    $args = array(
	'post_type' => 'page',
        'posts_per_page' => -1,
	'tax_query' => array(
	    array(
	        'taxonomy' => 'type',
		    'field' => 'slug',
		    'terms' => array( $doc_category->slug )
            )
	)
    );  
    $docs = get_posts($args);
    ?>
        <div class="myservices wow fadeInUp" data-wow-delay="0.3s"  style="visibility: visible; -webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s; transition: 0.3s;">
    <?php
    foreach ($docs as $doc){
        ?>
            <a href="<?php echo $doc->guid; ?>">
            <div class="service_box">
                <div class="ser_info">
                    <h2 title="<?php echo $doc->post_title; ?>"><?php echo $doc->post_title; ?> </h2>
                    <div class="ser_icon">
                        <i class="fas fa-caret-down"></i>
                    </div>
                    <div class="ser_text">
                        <p><?php echo $doc->post_excerpt ?></p>
                    </div>
                    <div class="ser_read">
                        <button>LEAR MORE</button>
                    </div>
                </div>
            </div>
           </a>
        <?php
       }
    ?>
    </div>
    <?php
   }
}
add_shortcode('shpw_by_shortcode_resources_type', 'resources_type');

function testimonial_slider() {
    ?>
<div class="container">
<div class="user_info wow fadeInUp" style="visibility: visible; -webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s; transition: 0.3s;" data-wow-delay="0.3s">
<?php
    $user = array(
        'post_type'=> 'testimonial',
        'order'    => 'DESC',
        'posts_per_page' => 3,
       );
        $the_query = new WP_Query( $user );
        if($the_query->have_posts() ) :
        while ( $the_query->have_posts() ) : $the_query->the_post();
        ?>
        <?php the_content(); ?>
        <h4><?php the_title(); ?></h4>
      <?php
        endwhile;
        endif;
        ?>
</div>
    </div>
    <?php
}
add_shortcode('show_testimonial', 'testimonial_slider');


function Firststart(){
    $return = '<div classs="first_start">';
    return $return;
}
add_shortcode('Firststart', 'Firststart');

function Secondstart(){
    $return = '<button class="bext_button">Next</button>';
    return $return;
}
add_shortcode('Secondstart', 'Secondstart');

function Thirdstart(){
    $return = '</div>';
    return $return;
}
add_shortcode('Thirdstart', 'Thirdstart');


add_action( 'init', 'download_csv' );
function download_csv() {
    if(is_user_logged_in()){
        if(isset($_GET['download']) && $_GET['download']=='CSV'){
            $file = str_replace(' ', '-', 'My Personal Information');
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename='.$file.'.xls');
            $current_user = wp_get_current_user();
            $result = '';
            $result .='<table border="1">';
            $result .='<tr><th>ID</th><th>Name</th><th>Nic Name</th><th>Email</th></tr>';
            $result .='<tr><td>'.$current_user->ID.'</td><td>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</td><td>'.$current_user->display_name.'</td><td>'.$current_user->user_email.'</td></tr>';
            $result .='</table>';
            echo $result;
            die();
        }
    }
}

function passwordValidation(){
    $return = ' <div id="pswd_info">
                    <h4>Password must meet the following requirements:</h4>
                    <ul>
                        <li id="letter" class="invalid">At least <strong>one lowercase letter</strong></li>
                        <li id="capital" class="invalid">At least <strong>one uppercase letter</strong></li>
                        <li id="number" class="invalid">At least <strong>one number</strong></li>
                        <li id="length" class="invalid">At least <strong>8 characters</strong></li>
                    </ul>
                </div>';
    return $return;
}
add_shortcode('PasswordValidation', 'passwordValidation');


/**
 * Validate field Registration Code
 * @param string $key
 * @param attay  $array
 * @param array  $args
 */
function um_custom_validate_team_code( $key, $array, $args ) {
    global $wpdb;
    
    $table_team = $wpdb->prefix . "team";
    $data_team  = $wpdb->get_results("SELECT * FROM $table_team WHERE team_code='$args[$key]'");

    if ( empty($data_team) ) {
        UM()->form()->add_error( $key, __( 'This Registration Code does not exist', 'ultimate-member' ) );
    }
}
add_action( 'um_custom_field_validation_team_code', 'um_custom_validate_team_code', 30, 3 );