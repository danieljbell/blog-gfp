<?php
/*
=================================
Template Name: Check Order Status
=================================
*/
?>

<?php get_header(); ?>

<section class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url(<?php echo get_stylesheet_directory_URI(); ?>/dist/img/hero--generic-<?php echo mt_rand(1,5);?>.jpg);">
  <div class="site-width">
    <h1><?php echo get_the_title(); ?></h1>
    <h2>Check your order status</h2>
  </div>
</section>

<section class="pad-y--most woocommerce-view-order">
  <div class="site-width">
    <p class="mar-b--most has-text-center">The order tracking results only works for customers who have created an account, guest check out orders will not populate.</p>

    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" id="order_tracking_form">
      <label for="email_address">Email Address:</label>
      <input type="text" name="email_address" id="email_address" value="<?php echo $_GET['email_address']; ?>">
      <label for="zipcode">Shipping Zipcode:</label>
      <input type="text" name="zipcode" id="zipcode" value="<?php echo $_GET['zipcode']; ?>">
      <input type="submit" name="submit" value="Submit" class="btn-solid--brand-two">
      <input type="hidden" name="action" value="order_tracking">
    </form>

    <section class="order-results--container visually-hidden">
      <ul class="order-results--list"></ul>
      <div class="order-results--details"></div>
    </section>    
    
  </div>
</section>

<?php get_template_part('partials/modals/display', 'order-comment'); ?>

<?php get_footer(); ?>