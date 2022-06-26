<!DOCTYPE html>
<html <?php language_attributes() ?>>
    <head>
        <meta charset="<?php bloginfo('charset') ?>">
        <meta name="viewport" content="width=device-width">
        <title><?php 
            if (function_exists('is_tag') && is_tag()) {
            single_tag_title('Tag Archive for &quot;'); echo '&quot; - ';
            } elseif (is_archive()) {
            wp_title(''); echo ' Archive - ';
            } elseif (is_search()) {
            echo 'Search for &quot;'.wp_specialchars($s).'&quot; - ';
            } elseif (!(is_404()) && (is_single()) || (is_page())) {
            wp_title(''); echo ' - ';
            } elseif (is_404()) {
            echo 'Not Found - ';
            }
            if (is_home()) {
            bloginfo('name'); echo ' Home '; bloginfo('description');
            } else {
            bloginfo('name');
            }
            if ($paged > 1) {
            echo ' - page '. $paged;
            } 
            ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div class="wrapper">
            <div class="container">
                <!-- <div class="header">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="site-manu">
                                <ul>
                                    <li><a href="/">Home</a></li>
                                    <li><a href="#">Services</a>
                                       <?php
                                            $args = array(
                                                'theme_location' => 'mnu_services',
                                                'container'       => 'div',
                                                'container_class' => 'mn_services', 
                                                'menu_class'      => 'mn_services',
                                                'items_wrap' => '<ul class="services-menu">%3$s</ul>',
                                                'menu'=> 8
                                            );
                                        ?>
                                       <?php wp_nav_menu($args); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="site-logo">
                                <?php
                                        if ( function_exists( 'the_custom_logo' ) ) {
                                                the_custom_logo();
                                           }
                                   ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="site-manu">
                                <ul>
                                    <li><a href="#">Resources</a>
                                    <?php
                                            $args = array(
                                                'theme_location' => 'mnu_services',
                                                'container'       => 'div',
                                                'container_class' => 'mn_services', 
                                                'menu_class'      => 'mn_services',
                                                'items_wrap' => '<ul class="services-menu">%3$s</ul>',
                                                'menu'=> 7
                                            );
                                        ?>
                                       <?php wp_nav_menu($args); ?>
                                    </li>
                                    <li><a href="/about">About Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            