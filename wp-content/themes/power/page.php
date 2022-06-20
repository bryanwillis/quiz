<?php get_header(); ?>
    <div class="main_box">  
         <div class="page_bg_banner" style="background: url('<?php echo get_template_directory_uri() ?>/images/feature.jpg')">
                <div class="headings">
                    <h2><?php the_title(); ?></h2>
                </div>
            </div> 
        <div class="container">
    <?php
    if(have_posts()):
        while (have_posts()):the_post();
            ?>
                <div class="inner_pages_content">
                    <?php the_content(); ?>
                </div>
                <?php
               endwhile;
               ?>
            </div>
        </div>
    <?php
endif;
get_footer();