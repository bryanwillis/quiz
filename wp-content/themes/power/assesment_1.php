<?php
/*
 * Template Name: Assesment Development
 */
?>
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
.wrapper {
    width: 100%;
    height: 100%;
}
.main_box.quiz_page {

    /*display: flex;*/
    align-items: center;

}
.request_exam a {

    display: inline-block;
    font-size: 16px;
    color: #000;

}
.request_exam {

    text-align: center;

}
</style>
    <div class="main_box quiz_page">
        <div class="container">
    <?php
    if(have_posts()):
        while (have_posts()):the_post();
            ?>
                <div class="inner_pages_content">
                    <?php echo do_shortcode('[my_exmeShow_by_shortcode_updated]'); ?>
                </div>
                <?php
               endwhile;
               ?>
            </div>
        </div>
    <?php
endif;
get_footer();