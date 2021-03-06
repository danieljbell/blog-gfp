<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// print_r($order);

?>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/gfp-letterhead.jpg" alt="letterhead background" class="order-letterhead print-only">


<?php
  function sv_wc_pip_add_order_delivery_shipping( $shipping, $document_type, $order ) {
    // if you want to only add this to invoices, you can add another check for document type
    // if ( 'invoice' !== $document_type ) { return $shipping; }
    // bail if Order Delivery plugin is not active
    if ( ! function_exists( 'WC_OD' ) ) {
      return $shipping;
    }
    $order_id      = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;
    $delivery_date = get_post_meta( $order_id, '_delivery_date', true );
    if ( $delivery_date ) {
      $delivery_date_i18n = wc_od_localize_date( $delivery_date );
      $shipping .= '<p>We will try our best to deliver your order on: ' . $delivery_date_i18n . '</p>';
    }
    return $shipping;
  }
  echo add_filter( 'wc_pip_document_shipping_method', 'sv_wc_pip_add_order_delivery_shipping', 10, 3 );
?>

<div class="order-status">
  
  <div class="order-status-details--content">
    <h1><?php _e( 'Order #' . $order->get_order_number(), 'woocommerce' ); ?></h1>
    <p class="order-date">Order Date: <?php echo wc_format_datetime( $order->get_date_created() ); ?></p>
    <p class="order-status">Order Status: <?php echo wc_get_order_status_name( $order->get_status() ); ?></p>
  </div>
  <div class="order-status-details--actions box--with-header has-text-center">
    <h3 class="mar-b">Have A Question On Your Order?</h3>
    <button class="btn-solid--brand-two launchModal" data-modal-launch="send-order-comment" data-order-number="<?php echo $order->get_order_number(); ?>">Ask Us!</button>
  </div>

</div>

<?php if ( $notes = $order->get_customer_order_notes() ) : ?>
  <div class="no-print">
    <h2><?php echo count($notes); ?> Order Updates</h2>
    <ol class="woocommerce-OrderUpdates commentlist notes">
      <?php foreach ( $notes as $note ) : ?>
      <li class="woocommerce-OrderUpdate comment note">
        <div class="woocommerce-OrderUpdate-inner comment_container">
          <div class="woocommerce-OrderUpdate-text comment-text">
            <div class="woocommerce-OrderUpdate-description description">
              <?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
            </div>
            <?php echo get_avatar($note->comment_author_email, 50, null, $note->comment_author); ?>
            <p class="woocommerce-OrderUpdate-meta meta"><?php echo $note->comment_author; ?><br><?php echo date_i18n( __( 'M. dS h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>
