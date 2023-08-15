<?php

// if accessed directly, exit
if (!defined('ABSPATH')) {
    exit;
}

// Admin admin page as sub menu to WooCommerce
add_action('admin_menu', function () {

    add_submenu_page(
        'woocommerce',
        __('Mini Cart Upsells'),
        __('Mini Cart Upsells'),
        'manage_options',
        'sbwc-mini-cart-upsells',
        'sbwc_mini_cart_upsells_admin_page'
    );
});

// Admin page
function sbwc_mini_cart_upsells_admin_page()
{

    global $title;

    // get existing upsell product ids
    $upsell_product_ids = get_option('sbwc_mini_cart_upsells_product_ids');

    // get columns per row
    $columns_per_row = get_option('sbwc_mini_cart_upsells_columns_per_row'); ?>

    <div id="sbwc-mc-upsells">

        <h1 style="background: white; padding: 20px; margin-top: 0; margin-left: -19px; box-shadow: 0px 2px 4px lightgray;"><?php _e($title, 'default'); ?></h1>

        <!-- instructions -->
        <div class="sbwc-mc-upsells-instructions">
            <p>
                <b>
                    <i>
                        <?php _e('Add product ids to the field below. These products will be displayed as upsells in the mini cart. You can also set the amount of products to be displayed per row.', 'default'); ?>
                    </i>
                </b>
            </p>
        </div>

        <!-- products per row select - either 2 or 3 per row -->
        <div class="sbwc-mc-upsells-columns-per-row">

            <p>
                <label for="sbwc-mc-upsells-columns-per-row-select" style="width: 120px; display: inline-block;">
                    <b>
                        <i>
                            <?php _e('Products per row:', 'default'); ?>
                        </i>
                    </b>
                </label>

                <select id="sbwc-mc-upsells-columns-per-row-select">
                    <option value="2" <?php if ($columns_per_row == 2) echo 'selected'; ?>>
                        <?php _e('2', 'default'); ?>
                    </option>
                    <option value="3" <?php if ($columns_per_row == 3) echo 'selected'; ?>>
                        <?php _e('3', 'default'); ?>
                    </option>
                </select>
            </p>

        </div>

        <!-- product ids multiple select dropdown using select2 and woocommerce ajax product loader -->
        <div class="sbwc-mc-upsells-ids">

            <p>
                <label for="sbwc-mc-upsells-columns-per-row-select" style="width: 120px; display: inline-block;">
                    <b>
                        <i>
                            <?php _e('Product IDs:', 'default'); ?>
                        </i>
                    </b>
                </label>

                <?php
                // get current language from polylang if pll_current_language function exists, else default to english
                $curr_lang = function_exists('pll_current_language') ? pll_current_language() : 'en';
                ?>

                <select id="sbwc-mc-upsells-products-select" multiple required>
                    <?php
                    // get all products
                    $products = wc_get_products(array(
                        'limit'  => -1,
                        'status' => 'publish',
                        'lang'   => $curr_lang
                    ));

                    // loop through products
                    foreach ($products as $product) {

                        // get product id
                        $product_id = $product->get_id();

                        // get product name
                        $product_name = $product->get_name();
                    ?>
                        <option value="<?php echo $product_id; ?>" <?php if (in_array($product_id, $upsell_product_ids)) echo 'selected'; ?>>
                            <?php echo $product_name; ?>
                        </option>
                    <?php } ?>
                </select>
            </p>

        </div>

        <!-- save -->
        <div class="sbwc-mc-upsells-save">

            <p>
                <input type="submit" id="sbwc-mc-upsells-save-btn" class="button button-primary" value="<?php _e('Save Upsells', 'default'); ?>" style="width: 150px; text-align: center; position: relative; left: 124px;" />
            </p>

        </div>

        <?php
        // include select2 CSS and JS
        wp_enqueue_style('select2_mcus', SBWC_MINI_CART_UPSELLS_URL . 'assets/select2.css', array(), '4.1.0-rc.0', 'all');
        wp_enqueue_script('select2_mcus', SBWC_MINI_CART_UPSELLS_URL . 'assets/select2.js', array('jquery'), '4.1.0-rc.0', true);
        ?>

        <!-- select2 -->
        <script>
            window.onload = function() {

                $ = jQuery;

                $('#sbwc-mc-upsells-products-select').select2({
                    placeholder: '<?php _e('Select products', 'default'); ?>',
                });

                $('#sbwc-mc-upsells-save-btn').click(function() {

                    console.log('save');

                    // get product ids
                    var product_ids = $('#sbwc-mc-upsells-products-select').val();

                    // if product ids empty, show error and return
                    if (product_ids.length == 0) {
                        alert('<?php _e('Please select at least one product', 'default'); ?>');
                        return;
                    }

                    // get columns per row
                    var columns_per_row = $('#sbwc-mc-upsells-columns-per-row-select').val();

                    // ajax save
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'sbwc_mc_upsells_save',
                            product_ids: product_ids,
                            columns_per_row: columns_per_row
                        },
                        success: function(response) {
                            alert('<?php _e('Upsells saved successfully', 'default'); ?>');
                        }
                    });

                });
            }
        </script>

    </div>

<?php }


/**
 * Save upsells
 */
add_action('wp_ajax_sbwc_mc_upsells_save', function () {

    // get product ids from post
    $product_ids = $_POST['product_ids'];

    // get columns per row from post
    $columns_per_row = $_POST['columns_per_row'];

    // update product ids option
    update_option('sbwc_mini_cart_upsells_product_ids', $product_ids);

    // update columns per row option
    update_option('sbwc_mini_cart_upsells_columns_per_row', $columns_per_row);

    // return success
    wp_send_json_success();
});