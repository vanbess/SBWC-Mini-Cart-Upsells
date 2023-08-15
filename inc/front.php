<?php
// if accessed directly, exit
if (!defined('ABSPATH')) {
    exit;
}

// Hook to after mini cart
add_action('woocommerce_after_mini_cart', function () {

    // get existing upsell product ids
    $upsell_product_ids = get_option('sbwc_mini_cart_upsells_product_ids');

    // get columns per row
    $columns_per_row = get_option('sbwc_mini_cart_upsells_columns_per_row');

    // if no upsell product ids, return
    if (empty($upsell_product_ids)) return;

    // upsell container title 
?>

    <div id="sbwc-mc-upsells-main-cont pb-5">

        <div class="sbwc-mc-upsells-title" style="margin-top: 30px; margin-bottom: 20px;">
            <h3 style="text-align: center;">
                <b>
                    <i>
                        <?php pll_e('You may also like'); ?>
                    </i>
                </b>
            </h3>
        </div>

        <?php

        // Set min width of mini cart dropdown to 45rem if 2 columns per row, else 60rem
        if ($columns_per_row == 2) : ?>
            <style>
                .mini-basket-dropdown .dropdown-box {
                    width: 45rem;
                }

                .mini-basket-dropdown.offcanvas-type .dropdown-box {
                    right: -45rem;
                }
            </style>

            <script>
                window.onload = function() {
                    $ = jQuery;

                    // .cart-toggle on click
                    $('.cart-dropdown .cart-toggle').click(function() {
                        $('.cart-popup').css('right', '0');
                        $('.cart-overlay').show();
                    });

                    // .btn-close on click
                    $('.cart-popup .btn-close').click(function() {
                        $('.cart-popup').css('right', '-45rem');
                        $('.cart-overlay').hide();
                    });

                    // .btn-close on click
                    $('.cart-overlay').click(function() {
                        $('.cart-popup').css('right', '-45rem');
                        $('.cart-overlay').hide();
                    });
                }
            </script>
        <?php else : ?>
            <style>
                .mini-basket-dropdown .dropdown-box {
                    width: 68rem;
                }

                .mini-basket-dropdown.offcanvas-type .dropdown-box {
                    right: -68rem;
                }
            </style>

            <script>
                window.onload = function() {
                    $ = jQuery;

                    // .cart-toggle on click
                    $('.cart-dropdown .cart-toggle').click(function() {
                        $('.cart-popup').css('right', '0');
                        $('.cart-overlay').show();
                    });

                    // .btn-close on click
                    $('.cart-popup .btn-close').click(function() {
                        $('.cart-popup').css('right', '-68rem');
                        $('.cart-overlay').hide();
                    });

                    // .btn-close on click
                    $('.cart-overlay').click(function() {
                        $('.cart-popup').css('right', '-68rem');
                        $('.cart-overlay').hide();
                    });
                }
            </script>
            <?php endif;

        // counter
        $counter = 0;

        // loop to get product objects and display in mini cart as per columns per row
        foreach ($upsell_product_ids as $key => $product_id) {

            // get product object
            $product = wc_get_product($product_id);

            // get product name
            $product_name = $product->get_name();

            // get product image
            $product_image = $product->get_image();

            // if counter is equal to columns per row, reset counter and start new row
            if ($counter % $columns_per_row == 0) { ?>

                <!-- row -->
                <div class="sbwc-mc-upsells-row">
                <?php } ?>

                <!-- product cont -->
                <div class="sbwc-mc-upsells-product" style="width: calc(100% / <?php echo $columns_per_row; ?>);">

                    <!-- image -->
                    <div class="sbwc-mc-upsells-product-image"><?php echo $product_image; ?></div>

                    <!-- name -->
                    <div class="sbwc-mc-upsells-product-name"><?php echo $product_name; ?></div>

                    <!-- rating -->
                    <div class="sbwc-mc-upsells-product-rating mt-3"><?php echo wc_get_rating_html($product->get_average_rating()); ?></div>

                    <!-- add to cart -->
                    <div class="sbwc-mc-upsells-product-add-to-cart"><?php echo do_shortcode('[add_to_cart id="' . $product_id . '"]'); ?></div>

                </div>

                <?php
                // if counter is equal to columns per row, reset counter and start new row
                if ($counter % $columns_per_row == 0) { ?>
                </div>
        <?php }

                // increment counter
                $counter++;
            } ?>

    </div>

    <style>
        .sbwc-mc-upsells-product {
            display: block;
            float: left;
            text-align: center;
        }

        .sbwc-mc-upsells-product-image {
            padding: 5px;
            margin-bottom: 10px;
        }

        .sbwc-mc-upsells-product-add-to-cart>p>a {
            display: block;
        }

        .sbwc-mc-upsells-product-add-to-cart>p {
            border: none !important;
        }

        .sbwc-mc-upsells-product-add-to-cart>p>span {
            display: block;
            margin-bottom: 15px;
        }

        .sbwc-mc-upsells-product-add-to-cart>p>a {
            text-align: center !important;
        }

        .mini-basket-dropdown.offcanvas-type .dropdown-box {
            height: 100vh;
            overflow-x: hidden;
        }
    </style>

<?php });
