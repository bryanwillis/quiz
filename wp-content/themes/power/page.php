<?php get_header(); ?>
<style>
.header {
    display: none;
}
.site-footer{
    display: none;
}
.copy_right {
    display: none;
}
</style>
    <div class="main_box">  
         
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