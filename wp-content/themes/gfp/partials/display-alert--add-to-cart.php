<?php
  $cart = WC()->instance()->cart;
  $totals = $cart->get_totals();
  $cart_line_items = $cart->get_cart();
  $item_count = 0;
  $amount_left = 75 - $totals['subtotal'];
  $percent_to_free = ($totals['subtotal'] / 75) * 100;
  foreach ($cart_line_items as $key => $line_item) {
    $item_count = $item_count + $line_item['quantity'];
  }
?>

<div class="drawer drawer--add-to-cart" role="alert">
  <button class="close-drawer btn-solid--brand-two"><span>&times;</span> Hide Cart</button>
  <?php if (($item_count > 1) || ($item_count === 0)) : ?>
    <h3 class="drawer--header"><span class="item-count"><?php echo $item_count; ?> Items in your Cart</span><br /><span class="cart-subtotal">Cart Subtotal: <span class="subtotal-amount">$<?php echo number_format($cart->get_totals()['subtotal'],2,'.',','); ?></span></span></h3>
  <?php else : ?>
    <h3 class="drawer--header"><span class="item-count"><?php echo $item_count; ?> Item in your Cart</span><br /><span class="cart-subtotal">Cart Subtotal: <span class="subtotal-amount">$<?php echo number_format($cart->get_totals()['subtotal'],2,'.',','); ?></span></span></h3>
  <?php endif; ?>

  <ul class="drawer--items-list">
    <?php 
      foreach ($cart_line_items as $line_item) :
        $line_item_details = $line_item['data'];
        $permalink = $line_item_details->get_permalink();
        $id = $line_item_details->get_id();
        $sku = strtoupper($line_item_details->get_sku());
        $qty = $line_item['quantity'];
        $name = $line_item_details->get_name();
        $product_brands = get_terms('pa_brand');
        foreach ($product_brands as $key => $brand) {
          $name = str_replace($brand->name . ' ', '', $name);
        }
        $name = str_replace($sku, '', $name);
        $price = number_format($line_item_details->get_regular_price(),2,'.',',');
        $sale_price = number_format($line_item_details->get_sale_price(),2,'.',',');
       ?>
      <li class="drawer--item" data-product-id="<?php echo $id; ?>" data-product-key="<?php echo $line_item['key']; ?>">
        <div class="drawer-item-action">
          <button class="drawer-remove-item">&times;</button>
        </div>
        <div class="drawer-item-image">
          <a href="<?php echo $permalink; ?>">
            <?php if (has_post_thumbnail($id)) : ?>

              <img src="<?php echo 'https://res.cloudinary.com/greenfarmparts/image/fetch/w_100,h_100,c_pad/' . str_replace('gfp.local', 'greenfarmparts.com', wp_get_attachment_image_url($line_item_details->get_image_id(), 'thumb')); ?>" alt="<?php echo $line_item_details->get_name(); ?>">
            <?php else :  ?>
              <img src="<?php echo get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg' ?>" alt="No Part Image">
            <?php endif; ?>
          </a>
        </div>
        <div class="drawer-item-content">
          <p class="drawer-item-title"><a href="<?php echo $permalink; ?>"><?php echo $name; ?></a></p>
          <?php if ($sale_price) : ?>
            <p class="drawer-item-price"><span class="drawer-item-sku"><?php echo $sku; ?></span> - <del>$<?php echo $price; ?></del> <span class="drawer-item-sale-price">$<?php echo $sale_price; ?> each</span></p>
          <?php else : ?>
            <p class="drawer-item-price"><span class="drawer-item-sku"><?php echo $sku; ?></span> - $<?php echo $price; ?> each</p>
          <?php endif; ?>
          <label for="" class="drawer-item-label">Quantity:</label>
          <input type="number" class="drawer-item-input" min="1" step="1" value="<?php echo $qty; ?>">
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <p class="has-text-center mar-y--more"><a href="/cart/" class="btn-solid--brand">Checkout</a></p>

  <?php
    $upsell_parts = new WP_Query(array(
      'post_type' 		=> 'product',
      'meta_key'			=> 'upsell_part',
      'meta_value' 		=> 'yes'
    ));
    if ($upsell_parts->have_posts()) : 
      echo '<div class="box--with-header" style="background-color: #fff;">';
        echo '<header>Don\'t forget!</header>';
        echo '<ul class="upsellAddToCart">';
          while ($upsell_parts->have_posts()) : 
            $upsell_parts->the_post();
            $product = wc_get_product($post->ID);
            ?>
              <li class="card card--upsell-product">
                <div class="card-upsell-product--container">
                  <div class="card-upsell-product--image">
                    <?php if ( has_post_thumbnail() ) : ?>
                      <a href="<?php echo $product->get_permalink(); ?>" title="<?php echo $product->get_permalink(); ?>">
                        <img src="<?php echo 'https://res.cloudinary.com/greenfarmparts/image/fetch/w_100,h_100,c_pad/' . str_replace('gfp.local', 'greenfarmparts.com', wp_get_attachment_image_url($product->get_image_id(), 'thumb')); ?>" alt="<?php echo $product->get_name(); ?>">
                      </a>
                    <?php else : ?>
                      <img src="<?php echo wc_placeholder_img_src(); ?>" alt="Part Photo Coming Soon">
                    <?php endif; ?>
                  </div>
                  <div class="card-upsell-product--content">
                    <p class="mar-b"><?php echo get_the_title(); ?><br>$<?php echo $product->get_price(); ?></p>
                    <button class="add-to-cart btn-solid--brand-two" value="<?php echo $post->ID; ?>">Add to Cart</button>
                  </div>
                </div>
              </li>
            <?php
          endwhile;
        echo '</ul>';
      echo '</div>';
    endif;
    wp_reset_postdata();
  ?>
</div>