<?php get_header(); ?>
<?php
if(have_posts()):
    while (have_posts()):the_post();
        ?>
        <div class="main_box_image">
            <div class="container">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
        <div class="main_box">  
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="inner_page_text">
                            
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="inner_page_image">
                            <?php the_post_thumbnail(); ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
     <?php
    endwhile;
endif;

get_footer();