<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see   https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

$allProductTags = wp_get_post_terms($product->get_id(), 'product_tag');

$part_replaces = get_post_meta($product->get_id(), 'replaces');

if ( (!empty( $tabs )) || $allProductTags || (count($part_replaces[0]) > 0)) : 
?>

  <div class="woocommerce-tabs wc-tabs-wrapper">
    <ul class="tabs wc-tabs" role="tablist">
      <?php if ($allProductTags) {
        echo '<li class="part_fitment_tab" id="tab-title-part_fitment" role="tab" aria-controls="tab-part_fitment"><a href="#tab-part_fitment">Part Fitment</a></li>';
      } ?>
      <?php foreach ( $tabs as $key => $tab ) : ?>
        <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
          <a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( ucwords($tab['title']) ), $key ); ?></a>
        </li>
      <?php endforeach; ?>
      <?php if ($part_replaces[0] && (count($part_replaces[0]) > 0)) {
        echo '<li class="part_replaces_tab" id="tab-title-part_replaces" role="tab" aria-controls="tab-part_replaces"><a href="#tab-part_replaces">Part Replaces</a></li>';
      } ?>
    </ul>
    <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--part_fitment panel entry-content wc-tab" id="tab-part_fitment" role="tabpanel" aria-labelledby="tab-title-part_fitment" style="display: block;">
      <?php do_action( 'woocommerce_template_single_meta' ); ?>
    </div>
    <?php foreach ( $tabs as $key => $tab ) : ?>
      <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
        <?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?>
      </div>
    <?php endforeach; ?>
    <?php if ($part_replaces[0] && (count($part_replaces[0]) > 0)) : ?>
      <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--part_replaces panel entry-content wc-tab" id="tab-part_replaces" role="tabpanel" aria-labelledby="tab-title-part_replaces" style="display: block;">
        <ul class="pad-x">
          <?php foreach ($part_replaces[0] as $key => $part) : ?>
            <?php
              $replaced_part = wc_get_product($part);
              echo '<li><a href="' . $replaced_part->get_permalink() . '">' . $replaced_part->get_name() . '</a></li>';
            ?>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>
