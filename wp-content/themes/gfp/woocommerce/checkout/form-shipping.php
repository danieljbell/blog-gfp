<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
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
 * @version 3.0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields">
  <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
    
    <div class="shipping-details--container">
      <h3 id="ship-to-different-address">Shipping Details</h3>

      <div class="shipping_address">

        <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

        <div class="woocommerce-shipping-fields__field-wrapper">
          <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox" style="display: inline-block; margin-bottom: 1rem;">
            <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="ship_to_different_address" value="1" />
            <span style="font-size: 0.9em;"><?php _e( 'Need to ship to a different address?', 'woocommerce' ); ?></span>
          </label>

          <fieldset id="diff_shipping_address" style="border: 0;" disabled>
            <?php
              $fields = $checkout->get_checkout_fields( 'shipping' );

              foreach ( $fields as $key => $field ) {
                if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
                  $field['country'] = $checkout->get_value( $field['country_field'] );
                }
                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
              }
            ?>            
          </fieldset>

        </div>

        <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

      </div>
    </div>

  <?php endif; ?>
</div>