<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-12">
    <!-- Main Content -->
    <main class="row">
        <!-- Slider -->
        <div class="col-12 px-0">
            <div id="slider" class="carousel slide w-100" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#slider" data-slide-to="0" class="active"></li>
                    <li data-target="#slider" data-slide-to="1"></li>
                    <li data-target="#slider" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img src="<?php echo SITE_IMAGES_URL;?>slider-1.jpg" class="slider-img">
                    </div>
                    <div class="carousel-item">
                        <img src="<?php echo SITE_IMAGES_URL;?>slider-2.jpg" class="slider-img">
                    </div>
                    <div class="carousel-item">
                        <img src="<?php echo SITE_IMAGES_URL;?>slider-3.jpg" class="slider-img">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <!-- Slider -->

        <!-- Featured Products -->
        <div class="col-12">
            <div class="row">
                <div class="col-12 py-3">
                    <div class="row">
                        <div class="col-12 text-center text-uppercase">
                            <h2>Featured Products</h2>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Product -->

                        <?php  
                        foreach ($feature_product as $key => $feature_produc) {  
                            $product_img = explode('|', $feature_produc['product_images']);
                            $profile_url = product_url($feature_produc['product_name'],$feature_produc['id']);
                        ?>
                            <div class="col-lg-3 col-sm-6 my-3">
                            <div class="col-12 bg-white text-center h-100 product-item">
                                <div class="row h-100">
                                    <div class="col-12 p-0 mb-3">
                                        <a href="<?php echo $profile_url; ?>">
                                            <img src="<?php echo SITE_IMAGES_URL.$product_img[0] ?>" class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <a href="<?php echo $profile_url; ?>" class="product-name">
                                           <?php echo $feature_produc['product_name']; ?>
                                        </a>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <span class="product-price-old">
                                           <i class="fa fa-inr"></i> <?php echo $feature_produc['display_cost']; ?>
                                        </span>
                                        <br>
                                        <span class="product-price">
                                           <i class="fa fa-inr"></i> <?php echo $feature_produc['product_cost']; ?>
                                        </span>
                                    </div>
                                    <div class="col-12 mb-3 align-self-end">
                                        <button class="btn btn-outline-dark addToCart" data-product_details='<?php echo json_encode($feature_produc); ?>' type="button"><i class="fas fa-cart-plus mr-2"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- Product -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Featured Products -->

        <div class="col-12">
            <hr>
        </div>

        <!-- Latest Product -->
        <div class="col-12">
            <div class="row">
                <div class="col-12 py-3">
                    <div class="row">
                        <div class="col-12 text-center text-uppercase">
                            <h2>Latest Products</h2>
                        </div>
                    </div>
                    <div class="row">

                        <!-- Product -->
                        <?php  foreach ($latest_product as $key => $latest_produc) { 
                            $product_img = explode('|', $latest_produc['product_images']);  
                            $profile_url = product_url($latest_produc['product_name'],$latest_produc['id']);
                        ?>
                            <div class="col-lg-3 col-sm-6 my-3">
                                <div class="col-12 bg-white text-center h-100 product-item">
                                    <span class="new">New</span>
                                    <div class="row h-100">
                                        <div class="col-12 p-0 mb-3">
                                            <a href="<?php echo $profile_url; ?>">
                                                <img src="<?php echo SITE_IMAGES_URL.$product_img[0];?>" class="img-fluid">
                                            </a>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <a href="<?php echo $profile_url; ?>" class="product-name">
                                                <?php echo $latest_produc['product_name'] ?>
                                            </a>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <span class="product-price-old">
                                                <i class="fa fa-inr"></i> <?php echo $latest_produc['display_cost'] ?>
                                            </span>
                                            <br>
                                            <span class="product-price">
                                                <i class="fa fa-inr"></i> <?php echo $latest_produc['display_cost'] ?>
                                            </span>
                                        </div>
                                        <div class="col-12 mb-3 align-self-end">
                                            <button class="btn btn-outline-dark addToCart" data-product_details='<?php echo json_encode($latest_produc); ?>' type="button"><i class="fas fa-cart-plus mr-2"></i>Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <!-- Product -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Latest Products -->

        <div class="col-12">
            <hr>
        </div>

        <!-- Top Selling Products -->

    </main>
    
</div>