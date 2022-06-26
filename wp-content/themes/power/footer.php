<div class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="ftr_logos">
                    <?php Dynamic_sidebar('ftr_logo'); ?> 
                </div>
              </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="newsletter">
                    <h2>Register for Updates:</h2>
                     <?php echo do_shortcode('[show_newslatters]'); ?> 
                </div>
               
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="social_meda">
                    <h2>Connect with Us:</h2>
                     <?php echo do_shortcode('[social_links]'); ?> 
                </div>
               
            </div>
        </div>
    </div>
</div>
    <div class="copy_right">
        <div class="container">
            <center><p>&COPY;2019 Global Coach Center</p></center>
        </div>
    </div>
        <p id="back-top">
            <a href="#top"><i class="fas fa-chevron-up"></i></a>
        </p>
       </div>
    <?php wp_footer(); ?>
   </body>
</html>