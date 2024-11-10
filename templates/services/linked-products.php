<?php
// Linked products display for single service page
$linked_products = get_field('linked_products');

if ($linked_products) : ?>
    <div class="linked-products">
        <h3><?php _e('Related Products', 'media-wolf'); ?></h3>
        <ul class="product-list">
            <?php foreach ($linked_products as $product_id) :
                $product = wc_get_product($product_id); ?>
                <li>
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                        <?php echo esc_html($product->get_name()); ?>
                    </a> - <?php echo $product->get_price_html(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>