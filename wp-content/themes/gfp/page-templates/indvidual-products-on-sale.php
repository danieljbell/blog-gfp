<?php
/*
=========================================
Template Name: Indvidual Products On Sale
=========================================
*/
?>

<?php get_header(); ?>

  <section class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url(<?php echo get_stylesheet_directory_URI(); ?>/dist/img/hero--generic-<?php echo mt_rand(1,5);?>.jpg);">
    <div class="site-width">
      <h1><?php echo get_the_title(); ?></h1>
    </div>
  </section>

  <section class="pad-y--most">
    <div class="site-width">
      <ul class="grid--third">
        <?php while (have_rows('products')) : the_row(); ?>
          <?php
            $product_id = wc_get_product_id_by_sku(get_sub_field('part_sku'));
            if ($product_id) :
              $wc_product = wc_get_product($product_id);
?>
  <li class="products--item product-card--slim">
      <div class="products--image">
        <a href="<?php echo $wc_product->get_permalink(); ?>">
          <?php if ( has_post_thumbnail($wc_product->get_id()) ) : ?>
            <img src="https://res.cloudinary.com/greenfarmparts/image/upload/e_brightness:30,w_100,h_100,c_fill/<?php echo $wc_product->get_sku(); ?>-0.jpg" alt="">
          <?php else : ?>
            <img src="<?php echo wc_placeholder_img_src(); ?>" alt="Part Photo Coming Soon">
          <?php endif; ?>
        </a>
      </div>
      <div class="products--content">
        <a href="<?php echo $wc_product->get_permalink(); ?>">
          <h3><?php echo $wc_product->get_name(); ?></h3>
        </a>
        <?php if ($wc_product->get_sale_price()) : ?>
          <span class="price">
            <del><span class="woocommerce-Price-amount amount"><?php echo $wc_product->get_regular_price(); ?></span></del>
            <br>
            <ins><span class="woocommerce-Price-amount amount">See Price in Cart</span></ins>
          </span>
        <?php else : ?>
          <span class="price"><?php echo $wc_product->get_price_html(); ?></span>
        <?php endif; ?>
          <div class="products--actions">
            <button class="add-to-cart btn-solid--brand-two" value="<?php echo $wc_product->get_id(); ?>">Add to Cart</button>
          </div>
      </div>
  </li>
<?php
            endif;
          ?>
        <?php endwhile; ?>
      </ul>
    </div>
  </section>

<?php get_footer(); ?>