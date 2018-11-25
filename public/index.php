<?php require_once("../resources/config.php "); ?>

<?php include(TEMPLATE_FRONT . DS . "header.php")?>
/* you dont want your stop working because of a header mistake so always include (modular bro)*/
    <!-- Page Content -->
    <div class="container">

        <div class="row">

        <!-- category here -->
        <?php include(TEMPLATE_FRONT . DS . "side_nav.php")?>

            <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-12">
                        <!-- slider (Carouse)-->
                        <?php include(TEMPLATE_FRONT . DS . "slider.php")?>
                    </div>

                </div>

                <div class="row">
                    <!-- Single Thumbnail starting -->
                    
                    <?php get_products(); ?>
                    <!-- review can be added to thumbnail
                           <div class="ratings">
                                <p class="pull-right">15 reviews</p>
                                <p>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                </p>
                            </div>
                    -->
                    <!-- Single thumbnail end-->

                       <!-- beautiful msg
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <h4><a href="#">Like this template?</a>
                        </h4>
                        <p>If you like this template, then check out <a target="_blank" href="http://maxoffsky.com/code-blog/laravel-shop-tutorial-1-building-a-review-system/">this tutorial</a> on how to build a working review system for your online store!</p>
                        <a class="btn btn-primary" target="_blank" href="http://maxoffsky.com/code-blog/laravel-shop-tutorial-1-building-a-review-system/">View Tutorial</a>
                    </div>
                        -->

                </div> <!-- row ends here -->

            </div>

        </div>

    </div>
    <!-- /.container -->
<?php include(TEMPLATE_FRONT . DS . "footer.php")?>
