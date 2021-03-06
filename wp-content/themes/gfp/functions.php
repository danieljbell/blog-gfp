<?php

add_action( 'login_enqueue_scripts', 'enqueue_my_script' );

function enqueue_my_script( $page ) {
    wp_enqueue_script( 'my-script', get_stylesheet_directory_URI() . '/dist/js/login.js', null, null, true );
}

/*
==============================
ADD GLOBAL CSS TO PAGE
==============================
*/
function enqueue_global_css() {
  wp_enqueue_style('global', get_stylesheet_directory_URI() . '/dist/css/global.css', array(), '1.0.67');
}
add_action('wp_enqueue_scripts', 'enqueue_global_css');

/*
==============================
ADD GLOBAL JS TO PAGE
==============================
*/
function enqueue_global_js() {
  wp_enqueue_script('global', get_stylesheet_directory_URI() . '/dist/js/global.js', array(), '1.0.67', true);

  // if (is_page_template( 'page-templates/check-order-status.php' ) || is_account_page()) {
    $translation_array = array(
      'ajax_url'   => admin_url( 'admin-ajax.php' ),
      'nonce'  => wp_create_nonce( 'nonce_name' )
    );
    wp_localize_script( 'global', 'ajax_order_tracking', $translation_array );
  // }

    if (is_page_template( 'page-templates/admin-phone-order.php' )) {
      wp_enqueue_script('admin-phone-order', get_stylesheet_directory_URI() . '/dist/js/admin-phone-order.js', array(), '1.0.2', true);
    }
  
}
add_action('wp_enqueue_scripts', 'enqueue_global_js');


/*
=========================
CHANGE REDIRECT LOCATION
=========================
*/
function my_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
        if (in_array('administrator', $user->roles) || in_array('shop_manager', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to = site_url() . '/wp-admin/admin.php?page=wc-reports';
        }
    }

    return $redirect_to;
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


/*
==========================================
CREATING ADMIN NAV MENUS
==========================================
*/
register_nav_menus( array(
  'eyebrow' => __( 'Eyebrow' ),
  // 'shop-by-part' => __( 'Shop By Part' ),
  // 'shop-by-equipment' => __( 'Shop By Equipment' ),
  'homepage-promoted-categories' => __( 'Homepage Promoted Categories' )
) );


/*
==========================================
HIDE ADMIN BAR
==========================================
*/
// add_filter('show_admin_bar', '__return_false');


/*
=========================
ADDING POST FORMATS
=========================
*/
add_theme_support( 'post-formats', array( 'video' ) );

/*
==========================================
ADDS POST THUMBNAILS
==========================================
*/
add_theme_support( 'post-thumbnails' );


/*
=========================
OPTIONS PAGES
=========================
*/
if( function_exists('acf_add_options_page') ) {
  
  // acf_add_options_page(array(
  //   'page_title'  => 'Global Blog Settings',
  //   'menu_title'  => 'Blog Settings',
  //   'menu_slug'   => 'global-blog-settings',
  //   'capability'  => 'edit_posts',
  //   'parent_slug' => 'edit.php',
  //   'redirect'    => false
  // ));

  acf_add_options_page(array(
    'page_title'  => 'Global Shop Settings',
    'menu_title'  => 'Shop Settings',
    'menu_slug'   => 'global-shop-settings',
    'capability'  => 'edit_posts',
    'parent_slug' => 'edit.php?post_type=shop_order',
    'redirect'    => false
  ));
  
}


/*
==========================================
REMOVE WP EMOJICONS
==========================================
*/
function disable_wp_emojicons() {
  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );


/*
==============================
REMOVE WOOCOMMERCE STYLESHEETS
==============================
*/
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/*
=====================================
CHANGE PLACEHOLDER IMAGE FOR PRODUCTS
=====================================
*/
add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

function custom_woocommerce_placeholder_img_src( $src ) {
    // $upload_dir = wp_upload_dir();
    // $uploads = untrailingslashit( $upload_dir['baseurl'] );
    // replace with path to your image
    $src = get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg';
     
    return $src;
}

/*
================================
CHANGE THE BREADCRUMB SEPARATOR
================================
*/
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
function wcc_change_breadcrumb_delimiter( $defaults ) {
  // Change the breadcrumb delimeter from '/' to '>'
  $defaults['delimiter'] = '<span class="breadcrumb-delimiter">&gt;</span>';
  return $defaults;
}


add_action( 'woocommerce_template_single_title', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_template_single_rating', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_template_single_price', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_template_single_excerpt', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_template_single_meta', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_template_single_sharing', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_template_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_output_product_data_tabs', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_template_loop_add_to_cart', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_template_loop_product_link_close', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_template_loop_product_thumbnail', 'woocommerce_template_loop_product_thumbnail', 5 );
add_action( 'woocommerce_cart_totals', 'woocommerce_cart_totals', 10 );
add_action( 'woocommerce_checkout_login_form', 'woocommerce_checkout_login_form', 10 );
add_action( 'woocommerce_checkout_coupon_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_catalog_ordering', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_order_review', 'woocommerce_order_review', 10 );
add_action( 'woocommerce_checkout_payment', 'woocommerce_checkout_payment', 20 );
// add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
// add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );



/*
==================================================
Removing the content editor for product pages
==================================================
*/
add_action( 'init', function() {
    remove_post_type_support( 'product', 'editor' );
}, 99);

// function remove_metaboxes() {
//      remove_meta_box( 'postcustom' , 'product' , 'normal' );
//      remove_meta_box( 'postexcerpt' , 'product' , 'normal' );
//      remove_meta_box( 'commentsdiv' , 'product' , 'normal' );
//      remove_meta_box( 'tagsdiv-product_tag' , 'product' , 'normal' );
// }
// add_action( 'add_meta_boxes' , 'remove_metaboxes', 50 );

// add_action( 'add_meta_boxes' , 'remove_metaboxes', 11 );
// Move Yoast to bottom
function yoasttobottom() {
  return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');


function formatCartItems($response) {
  global $wpdb;
  $lineItems = array();

  foreach ($response as $key => $line_item) {
    $line_item_details = $line_item['data'];
    $name = $line_item_details->get_name();
    $product_brands = get_terms('pa_brand');
    $qty_increment = $wpdb->get_row( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = " . $line_item_details->get_id() . " AND meta_key = 'qty_increment'" );
    if ($qty_increment && $qty_increment->meta_value) {
      $inc = $qty_increment->meta_value;
    } else {
      $inc = 1;
    }
    if ($product_brands) {
      foreach ($product_brands as $key => $brand) {
        $name = str_replace($brand->name . ' ', '', $name);
      }
    }
    if (has_post_thumbnail($line_item_details->get_id())) :
      $thumb = '<img src="https://res.cloudinary.com/greenfarmparts/image/upload/e_brightness:30,w_100,h_100,c_fill/' . $line_item_details->get_sku() . '-0.jpg" alt="' . $line_item_details->get_name() . '">';
    else :
      $thumb = '<img src="' . get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg" alt="No Part Image">';
    endif;
    $singleLineItem = array(
      'productName'         => $name,
      'productID'           => $line_item_details->get_id(),
      'productKey'          => $line_item['key'],
      'productSku'          => $line_item_details->get_sku(),
      'productQty'          => $line_item['quantity'],
      'productQtyInc'       => $inc,
      'productRegularPrice' => number_format($line_item_details->get_regular_price(), 2, '.', ','),
      'productSalePrice'    => number_format($line_item_details->get_sale_price(), 2, '.', ','),
      'productImg'          => $thumb,
      'productPermalink'    => $line_item_details->get_permalink()
    );
    array_push($lineItems, $singleLineItem);

    // number_format((float)$wc_product->get_price(), 2, '.', '');
  }
  return $lineItems;
}

function add_item_to_cart_with_qty() {
  check_ajax_referer( 'nonce_name' );
  $sku = $_POST['sku'];
  $qty = $_POST['qty'];
  $wc_product_id = wc_get_product_id_by_sku($sku);
  $wc_product = wc_get_product($wc_product_id);
  if ($wc_product) {
    $cart = WC()->instance()->cart;
    $cart->add_to_cart($wc_product_id, $qty);
    
    if (has_post_thumbnail($wc_product->get_id())) :
      $thumb = '<img src="https://res.cloudinary.com/greenfarmparts/image/upload/e_brightness:30,w_100,h_100,c_fill/' . $wc_product->get_sku() . '-0.jpg" alt="' . $wc_product->get_name() . '">';
    else :
      $thumb = '<img src="' . get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg" alt="No Part Image">';
    endif;

    wp_send_json(array(
      'id' => $wc_product->get_id(),
      'name' => $wc_product->get_name(),
      'link' => $wc_product->get_permalink(),
      'img' => $thumb,
      'price' => $wc_product->get_price()
    ));
  } else {
    wp_send_json(array(
      'status' => 'failed'
    ));
  }
}

function add_multiple_items() {
  check_ajax_referer( 'nonce_name' );
  $items = $_POST['items'];
  $cart = WC()->instance()->cart;
  foreach ($items as $key => $item) {
    $cart->add_to_cart($item['id'], $item['qty']);
  }
  wp_send_json(array(
    'success' => true
  ));
}


function get_product_prices() {
  // check_ajax_referer( 'nonce_name' );
  $part = $_POST['parts'];
  $wc_product_id = wc_get_product_id_by_sku($part);
  if ($wc_product_id) {
    $wc_product = wc_get_product($wc_product_id);
    wp_send_json(array(
      'id' => $wc_product->get_id(),
      'sku' => $wc_product->get_sku(),
      'regular_price' => $wc_product->get_regular_price(),
    ));
  } else {
    wp_send_json(array(
      'id' => '',
      'sku' => $part,
      'regular_price' => '-'
    ));
  }
}

function get_product_info() {
  // check_ajax_referer( 'nonce_name' );
  $sku = $_POST['sku'];
  $wc_product_id = wc_get_product_id_by_sku($sku);
  $wc_product = wc_get_product($wc_product_id);
  if ($wc_product) {
    if (has_post_thumbnail($wc_product->get_id())) :
      $thumb = '<img src="https://res.cloudinary.com/greenfarmparts/image/upload/e_brightness:30,w_100,h_100,c_fill/' . $wc_product->get_sku() . '-0.jpg" alt="' . $wc_product->get_name() . '">';
    else :
      $thumb = '<img src="' . get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg" alt="No Part Image">';
    endif;
    $response = array(
      'id'    => $wc_product_id,
      'name' => $wc_product->get_name(),
      'link' => $wc_product->get_permalink(),
      'img' => $thumb,
      'price' => $wc_product->get_price()
    );
  } else {
    $response = array(
      'price' => '&ndash;'
    );
  }
  wp_send_json($response);
}

function get_cart() {
  check_ajax_referer( 'nonce_name' );
  $cart = WC()->instance()->cart;
  $response = $cart->get_cart();
  // wp_send_json($response);
  wp_send_json(array(
    'totals' => $cart->get_totals(),
    'lineItems' => formatCartItems($response)
  ));
}


function remove_item_from_cart() {
  // check_ajax_referer( 'nonce_name' );
  $cart = WC()->instance()->cart;
  $id = $_POST['product_id'];
  $key = $_POST['product_key'];
  $cart_item_id = $cart->find_product_in_cart($key);
  if ($cart_item_id) {
    $cart->set_quantity($cart_item_id, 0);
    // wp_send_json();
    wp_send_json(array(
      'subtotal' => $cart->get_totals()[subtotal],
      'lineItems' => formatCartItems($cart->get_cart())
    ));
  } 
  
}

function add_item_to_cart() {
  // check_ajax_referer( 'nonce_name' );
  global $wpdb;
  $cart = WC()->instance()->cart;
  $id = $_POST['product_id'];
  $qty_increment = $wpdb->get_row( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = " . $id . " AND meta_key = 'qty_increment'" );
  if ($qty_increment && $qty_increment->meta_value) {
    $cart->add_to_cart($id, $qty_increment->meta_value);
  } else {
    $cart->add_to_cart($id, 1);
  }
  $response = $cart->get_cart();
  // wp_send_json(wc_get_product($id));
  wp_send_json(array(
    'subtotal' => number_format($cart->get_totals()['subtotal'],2,'.',','),
    'lineItems' => formatCartItems($cart->get_cart())
  ));
}

function increment_item_in_cart() {
  // check_ajax_referer( 'nonce_name' );
  $cart = WC()->instance()->cart;
  $id = $_POST['product_id'];
  $key = $_POST['product_key'];
  $qty = $_POST['qty'];
  $cart_item_id = $cart->find_product_in_cart($key);
  $cart->set_quantity($cart_item_id, $qty);
  wp_send_json(array(
    'subtotal' => $cart->get_totals()[subtotal],
    'lineItems' => formatCartItems($cart->get_cart())
  ));
}

function get_product_details() {
  $cart = WC()->instance()->cart;
  $cart_line_items = $cart->get_cart();
  $product_id = $_POST['product_id'];
  $product_details = array();
  foreach ($cart_line_items as $line_item) :
    $line_item_details = $line_item[data];
    $permalink = $line_item_details->get_permalink();
    $id = $line_item_details->get_id();
    $sku = strtoupper($line_item_details->get_sku());
    $name = $line_item_details->get_name();
    $name = str_replace('John Deere ', '', $name);
    $name = str_replace('Green Farm Parts ', '', $name);
    $name = str_replace('Frontier ', '', $name);
    $name = str_replace('A&I ', '', $name);
    $name = str_replace('Stens ', '', $name);
    $name = str_replace('Sunbelt ', '', $name);
    $name = str_replace('ZGlide Suspension ', '', $name);
    $name = str_replace('Honda ', '', $name);
    $name = str_replace($sku, '', $name);
    $price = $line_item_details->get_regular_price();
    $sale_price = $line_item_details->get_sale_price();
    
    $single_product_details = array(
      'id'          => $id,
      'permalink'   => $permalink,
      'name'        => trim($name),
      'sku'         => $sku,
      'price'       => $price,
      'salePrice'   => $sale_price,
      'quantity'    => $line_item[quantity]
    );

    array_push($product_details, $single_product_details);
  endforeach;
  echo json_encode($product_details);
}

function get_orders() {
  check_ajax_referer( 'nonce_name' );
  $email_address = $_POST['email_address'];
  $zipcode = $_POST['zipcode'];
  
  if (!$email_address && !$zipcode) {
    echo json_encode(array(
      'status'      => 'error',
      'messages'    => array(
        'email'     => 'Please provide an email address',
        'zipcode'   => 'Please provide a shipping zipcode'
      )
    ));
    die();
  }

  if (!$email_address) {
    echo json_encode(array(
      'status'      => 'error',
      'messages'    => array(
        'email'     => 'Please provide an email address',
      )
    ));
    die();
  }

  if (!$zipcode) {
    echo json_encode(array(
      'status'      => 'error',
      'messages'    => array(
        'zipcode'   => 'Please provide a shipping zipcode',
      )
    ));
    die();
  }

  $supplied_user = get_user_by('email', $email_address);
  if ($supplied_user) {
    $WC_user = new WC_Customer($supplied_user->ID);
    if ($zipcode === $WC_user->get_shipping_postcode()) {
      $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $supplied_user->data->ID,
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses() ),
      ) );
      foreach ($customer_orders as $k => $customer_order) {
        $sequential_order = get_post_meta($customer_order->ID, '_order_number_formatted');
        $customer_orders[$k]->fancy = $sequential_order[0];
        // $customer_orders['asdfasdf'] = $customer_orders[$k]->post_status;
      }
      echo json_encode(array(
        'status'        => 'success',
        'email_address' => $email_address,
        'zipcode'       => $zipcode,
        'orders'        => $customer_orders,
        'user'          => array(
          'display_name'    => $WC_user->get_display_name(),
          'phone_number'    => $WC_user->get_billing_phone(),
          'email_address'   => $WC_user->get_email()
        )
      ));
    } else {
      echo json_encode(array(
        'status'      => 'error',
        'messages'    => array(
          'zipcode'   => 'Sorry, the email & shipping zipcode provided don\'t match any orders',
        )
      ));
    }
    die();
  } else {
    echo json_encode(array(
      'status'      => 'error',
      'messages'    => array(
        'zipcode'   => 'Sorry, the email & shipping zipcode provided don\'t match any orders',
      )
    ));
    die();
  }

  
}

function get_order_details() {
  check_ajax_referer( 'nonce_name' );
  $orderID = $_POST['orderID'];
  $order = wc_get_order( $orderID );
  $order_items = $order->get_items();
  $order_details = [];
  
  foreach ( $order_items as $item_id => $item ) {
    $product = $item->get_product();
    $qty = $item->get_quantity();
    $name = $item->get_name();
    $image = $product->get_image(array(100,100));
    $link = $product->get_permalink();
    $subtotal = $item->get_subtotal();
    $total = $item->get_total();
    $unit_price = $subtotal / $qty;
    array_push($order_details,array(
      "qty"         => $qty,
      "name"        => $name,
      "image"       => $image,
      "link"        => $link,
      "subtotal"    => $subtotal,
      "total"       => $total,
      "unit_price"  => $unit_price
    ));
  }

  echo json_encode($order_details);
  die();
}

function get_order_notes() {
  check_ajax_referer( 'nonce_name' );
  $order_id = $_POST['orderID'];
  $order = wc_get_order( $order_id );
  $order_notes = $order->get_customer_order_notes();
  $scrubbed_note = [];

  foreach ($order_notes as $note) {
    array_push($scrubbed_note, array(
      'commentAuthor'     => $note->comment_author,
      'commentAuthorImg'  => get_avatar($note->comment_author_email, 50, null, $note->comment_author),
      'commentDate'       => $note->comment_date_gmt,
      'commentContent'    => $note->comment_content
    ));
  }

  echo json_encode($scrubbed_note);
  die();
}

function find_user_by_email() {
  check_ajax_referer( 'nonce_name' );
  $email = $_POST['email_address'];
  $user = get_users(array(
    'search' => $email,
    'fields' => ['ID', 'user_email', 'display_name']
  ));
  if ($user) {
    $customer = new WC_Customer( $user[0]->ID );
    
    wp_send_json(array(
      'id'   => $customer->get_id(),
      'name' => $customer->get_display_name(),
      'billing' => $customer->get_billing(),
      'shipping' => $customer->get_shipping()
    ));
  } else {
    wp_send_json(array(
      'success' => false,
      'message' => 'No User Found! Check the Email Address'
    ));
  }
}

function draft_order() {
  check_ajax_referer( 'nonce_name' );
  global $woocommerce;
  global $wpdb;

  

  /*
  =========================
  GET LAST ORDER NUMBER
  =========================
  */

  $last_order_query = $wpdb->get_results( 
    "
    SELECT ID, post_title, wp_postmeta.meta_value 
    FROM $wpdb->posts wp_posts
    INNER JOIN $wpdb->post_meta wp_postmeta
    ON wp_posts.ID = wp_postmeta.post_id
    WHERE post_type = 'shop_order' 
    AND wp_postmeta.meta_key = '_order_number_formatted'
    ORDER BY ID DESC
    LIMIT 1
    "

  );
  $last_order = $last_order_query[0]->meta_value;
  $last_order = str_replace('GFP-', '', $last_order);
  $last_order++;

  // get customer data
  $customer = new WC_Customer($_POST['customer']);
  $customer_id = $customer->get_id();
  $billing_address = array(
    'first_name' => $customer->get_billing_first_name(),
    'last_name'  => $customer->get_billing_last_name(),
    'company'    => $customer->get_billing_company(),
    'email'      => $customer->get_billing_email(),
    'phone'      => $customer->get_billing_phone(),
    'address_1'  => $customer->get_billing_address_1(),
    'address_2'  => $customer->get_billing_address_2(),
    'city'       => $customer->get_billing_city(),
    'state'      => $customer->get_billing_state(),
    'postcode'   => $customer->get_billing_postcode(),
    'country'    => $customer->get_billing_country()
  );
  $shipping_address = array(
    'first_name' => $customer->get_shipping_first_name(),
    'last_name'  => $customer->get_shipping_last_name(),
    'company'    => $customer->get_shipping_company(),
    'address_1'  => $customer->get_shipping_address_1(),
    'address_2'  => $customer->get_shipping_address_2(),
    'city'       => $customer->get_shipping_city(),
    'state'      => $customer->get_shipping_state(),
    'postcode'   => $customer->get_shipping_postcode(),
    'country'    => $customer->get_shipping_country()
  );


  $order = wc_create_order();
  $line_items = $_POST['lineItems'];
  foreach ($line_items as $key => $item) {
    $order->add_product( get_product( $item['id'] ), $item['qty'] );
  }
  $order->set_address( $billing_address, 'billing' );
  $order->set_address( $shipping_address, 'shipping' );
  $order->set_customer_id( $_POST['customer'] );
  $order->update_meta_data('_order_number', $last_order);
  $order->update_meta_data('_order_number_formatted', 'GFP-' . $last_order);
  $order->update_meta_data('_order_number_meta', 'a:3:{s:6:"prefix";s:4:"GFP-";s:6:"suffix";s:0:"";s:6:"length";s:1:"4";}');
  $order->calculate_totals();
  $order->update_status("Completed", 'Imported order', TRUE);

  $order_details = wc_get_order($last_order_query[0]->ID + 1);
  $login = wp_nonce_url( add_query_arg( array(
      'action'  => 'switch_to_user',
      'user_id' => $customer_id,
      'nr'      => 1,
    ), wp_login_url() ), "switch_to_user_{$customer_id}" );

  wp_send_json(array(
    'id' => $order_details->get_id(),
    'order_key' => $order_details->get_order_key(),
    'login' => $login
  ));
}

function create_customer() {
  check_ajax_referer( 'nonce_name' );

  
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email_address = $_POST['email_address'];

  $user = get_users(array(
    'search' => $email_address,
    'fields' => ['ID', 'user_email', 'display_name']
  ));
  if ($user) {
    $customer = new WC_Customer( $user[0]->ID );
    wp_send_json(array(
      'returning' => true,
      'first' => $first_name,
      'last' => $last_name,
      'email' => $email_address,
      'customer' => $customer->get_id()
    ));
  } else {
    $customer = wc_create_new_customer($email_address, $email_address, 'N2OIN13nslss');
    wp_send_json(array(
      'returning' => false,
      'first' => $first_name,
      'last' => $last_name,
      'email' => $email_address,
      'customer' => $customer
    ));
  }


}

function add_coupon() {
  $cart = WC()->instance()->cart;
  $coupon = $_POST['coupon'];
  $cart->apply_coupon($coupon);
  wp_send_json($cart);
}

function getInventory() {
  check_ajax_referer( 'nonce_name' );
  $part_number = $_POST['partNumber'];
  
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://scorecard.reynoldsfarmequipment.com/cdk/parts/" . $part_number,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Content-Type: application/json",
      "X-Requested-With: XMLHttpRequest"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl); 

  wp_send_json(array(
    'partNumber' => $part_number,
    'data' => json_decode($response)
  ));
}


function filter_model_cat() {
  check_ajax_referer( 'nonce_name' );

  $primary_id = $_POST['primaryID'];
  $filter_id = $_POST['filterID'];

  $query = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'order' => 'ASC',
    'orderby' => 'menu_order',
    'tax_query' => array(
      'relation' => 'AND',
      array(
        'taxonomy' => 'product_cat',
        'field'    => 'id',
        'terms'    => $primary_id
      ),
      array(
        'taxonomy' => 'product_cat',
        'field'    => 'id',
        'terms'    => $filter_id
      ),
    )
  ));

  $results = [];
  foreach ($query->posts as $item) {
    $thumb = get_the_post_thumbnail_url($item->ID, 'full');
    if (!$thumb) {
      $thumb = get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg';
    }

    $thumb = str_replace('gfp.local', 'greenfarmparts.com', $thumb);

    $thumb = 'https://res.cloudinary.com/greenfarmparts/image/fetch/w_75,h_75,c_fill/' . $thumb;

    array_push($results, array(
      'id'    => $item->ID,
      'sku' => get_post_meta($item->ID, '_sku')[0],
      'url'    => $item->guid,
      'title' => $item->post_title,
      'price' => get_post_meta($item->ID, '_regular_price')[0],
      'thumb' => $thumb
    ));
  }

  wp_send_json(array(
    'chach' => true,
    'name' => 'Daniel',
    'primary' => $primary_id,
    'filter' => $filter_id,
    'results' => $results
  ));
}


add_action('wp_ajax_filter_model_cat', 'filter_model_cat');
add_action('wp_ajax_nopriv_filter_model_cat', 'filter_model_cat');
add_action('wp_ajax_find_user_by_email', 'find_user_by_email');
add_action('wp_ajax_draft_order', 'draft_order');
add_action('wp_ajax_create_customer', 'create_customer');
add_action('wp_ajax_get_product_prices', 'get_product_prices');
add_action('wp_ajax_nopriv_get_product_prices', 'get_product_prices');
add_action('wp_ajax_get_product_info', 'get_product_info');
add_action('wp_ajax_nopriv_get_product_info', 'get_product_info');
add_action('wp_ajax_add_multiple_items', 'add_multiple_items');
add_action('wp_ajax_nopriv_add_multiple_items', 'add_multiple_items');
add_action('wp_ajax_add_item_to_cart_with_qty', 'add_item_to_cart_with_qty');
add_action('wp_ajax_nopriv_add_item_to_cart_with_qty', 'add_item_to_cart_with_qty');
add_action('wp_ajax_get_cart', 'get_cart');
add_action('wp_ajax_nopriv_get_cart', 'get_cart');
add_action('wp_ajax_remove_item_from_cart', 'remove_item_from_cart');
add_action('wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart');
add_action('wp_ajax_add_item_to_cart', 'add_item_to_cart');
add_action('wp_ajax_nopriv_add_item_to_cart', 'add_item_to_cart');
add_action('wp_ajax_increment_item_in_cart', 'increment_item_in_cart');
add_action('wp_ajax_nopriv_increment_item_in_cart', 'increment_item_in_cart');
add_action('wp_ajax_get_product_details', 'get_product_details');
add_action('wp_ajax_nopriv_get_product_details', 'get_product_details');
add_action('wp_ajax_get_orders', 'get_orders');
add_action('wp_ajax_nopriv_get_orders', 'get_orders');
add_action('wp_ajax_get_order_details', 'get_order_details');
add_action('wp_ajax_nopriv_get_order_details', 'get_order_details');
add_action('wp_ajax_get_order_notes', 'get_order_notes');
add_action('wp_ajax_nopriv_get_order_notes', 'get_order_notes');
add_action('wp_ajax_send_order_comment', 'send_order_comment');
add_action('wp_ajax_nopriv_send_order_comment', 'send_order_comment');
add_action('wp_ajax_add_coupon', 'add_coupon');
add_action('wp_ajax_nopriv_add_coupon', 'add_coupon');
add_action('wp_ajax_getInventory', 'getInventory');
add_action('wp_ajax_nopriv_getInventory', 'getInventory');



/*
========================================
ALLOW THEME TO INTERACT WITH WOOCOMMERCE
========================================
*/
function mytheme_add_woocommerce_support() {
  add_theme_support( 'woocommerce' );
  // add_theme_support( 'wc-product-gallery-zoom' );
  // add_theme_support( 'wc-product-gallery-lightbox' );
  // add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );





if ( function_exists('register_sidebar') ) {
  register_sidebar(array(
      'name' => 'Product Filters',
      'id' => 'product_filters',
    ));

  register_sidebar(
    array(
    'name'  => 'Product Categories',
    'id'    => 'product_categories',
    )
  );

  register_sidebar(
    array(
    'name'  => 'Product Recommendations',
    'id'    => 'product_recommendations',
    )
  );

}







/*
===========================================================================
SEARCH JSON FOR WP-API
@Link - https://benrobertson.io/wordpress/wordpress-custom-search-endpoint
===========================================================================
*/

/**
 * Register our custom route.
 */
function gfp_register_search_route() {
    register_rest_route('gfp/v1', '/search', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'gfp_ajax_search',
        'args' => gfp_get_search_args()
    ]);
}
add_action( 'rest_api_init', 'gfp_register_search_route');

/**
 * Define the arguments our endpoint receives.
 */
function gfp_get_search_args() {
    $args = [];
    $args['s'] = [
       'description' => esc_html__( 'The search term.', 'gfp' ),
       'type'        => 'string',
   ];

   return $args;
}

/**
 * Use the request data to find the posts we
 * are looking for and prepare them for use
 * on the front end.
 */
function gfp_ajax_search( $request ) {
    $posts = [];
    // $results = [array(
    //   'term' => $request['s']
    // )];
    $results = [];
    // check for a search term
    if ( isset($request['s']) ) :

      if (!$_GET['per_page']) {
        $post_count = 10;
      } else {
        $post_count = $_GET['per_page'];
      }
      
      // get posts
      $posts = get_posts([
        'posts_per_page' => $post_count,
        'post_type' => 'post',
        's' => $request['s'],
      ]);

      $products = get_posts([
        'posts_per_page' => $post_count,
        'post_type' => 'product',
        's' => $request['s'],
      ]);

      $categories = get_categories(array(
        'taxonomy'      => 'product_cat',
        'name__like'    => $request['s'],
        'number'        => $post_count,
        'hide_empty'    => false
      ));
      
      
      // set up the data I want to return
      if ($posts) :
        foreach($posts as $post):
          $cat = get_the_category($post->ID);
          $slug = $cat[0]->slug;
          $title = $post->post_title;
          if ($slug === 'maintenance-reminder') {
            $title = $post->post_title;
            $title = str_replace('John Deere ', "", $title);
            $title = str_replace(' Maintenance Guide', "", $title);
            if (has_post_thumbnail($post->ID)) {
              $image = get_the_post_thumbnail($post->ID);
            } else {
              $image = '<img src="' . get_stylesheet_directory_URI() . '/dist/img/default-model-image.png" alt="Model Image Coming Soon">';
            }
          }
          $results[] = [
            'title' => $title,
            'link' => get_permalink( $post->ID ),
            'image' => $image,
            'type' => ($slug === 'maintenance-reminder' ? 'model' : $post->post_type)
          ];
        endforeach;
      endif;

      if ($products) :
        foreach($products as $product):
          $product = new WC_product($product->ID);
          $attachmentIds = $product->get_gallery_image_ids();
          $imgURL = wp_get_attachment_url( $attachmentId[0] );
          $results[] = [
            'title' => $product->get_name(),
            'link' => $product->get_permalink(),
            'type' => 'product',
            'image' => $product->get_image('thumbnail')
          ];
        endforeach;
      endif;
      
      if ($categories) :
        foreach($categories as $cat):
          $name = $cat->category_nicename;
          $name = explode('-', $name);
          $name = implode(' ', $name);
          $thumb_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
          $image = wp_get_attachment_url( $thumb_id );
          if (!$image) {
            $image = get_stylesheet_directory_URI() . '/dist/img/partPicComingSoon.jpg';
          }
          $results[] = [
            'title' => ucwords($name),
            'link' => get_tag_link($cat->term_id),
            'type' => 'category',
            'image' => $image
          ];
        endforeach;
      endif;

      wp_send_json($results);

    endif;

    if( empty($results) ) :
        return new WP_Error( 'front_end_ajax_search', 'No results');
    endif;

    echo json_encode($results);
    die();
}


/*
=================================
CREATE CUSTOM FIELD FOR NLA PARTS
=================================
*/
function create_nla_parts() {
  global $woocommerce, $post;
  woocommerce_wp_checkbox(array( 
    'id'            => 'nla_part', 
    'wrapper_class' => 'show_if_simple', 
    'label'         => __('Part Is NLA', 'woocommerce' )
  ));
}
add_action( 'woocommerce_product_options_general_product_data', 'create_nla_parts' );

/*
===============================
SAVE CUSTOM FIELD FOR NLA PARTS
===============================
*/
function save_nla_parts( $post_id ) {
 $woocommerce_checkbox = isset( $_POST['nla_part'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, 'nla_part', $woocommerce_checkbox );
}
add_action( 'woocommerce_process_product_meta', 'save_nla_parts' );

/*
===================================
CREATE CUSTOM FIELD FOR UPSELL PART
===================================
*/
function create_upsell_parts() {
  global $woocommerce, $post;
  woocommerce_wp_checkbox(array( 
    'id'            => 'upsell_part', 
    'wrapper_class' => 'show_if_simple', 
    'label'         => __('Add to Upsell Parts', 'woocommerce' )
  ));
}
add_action( 'woocommerce_product_options_general_product_data', 'create_upsell_parts' );

/*
=================================
SAVE CUSTOM FIELD FOR UPSELL PART
=================================
*/
function save_upsell_parts( $post_id ) {
 $woocommerce_checkbox = isset( $_POST['upsell_part'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, 'upsell_part', $woocommerce_checkbox );
}
add_action( 'woocommerce_process_product_meta', 'save_upsell_parts' );

/*
===================================
CREATE CUSTOM FIELD FOR VENDOR PART
===================================
*/
function create_vendor_parts() {
  global $woocommerce, $post;
  woocommerce_wp_checkbox(array( 
    'id'            => 'vendor_part', 
    'wrapper_class' => 'show_if_simple', 
    'label'         => __('Is Vendor Part?', 'woocommerce' )
  ));
}
add_action( 'woocommerce_product_options_general_product_data', 'create_vendor_parts' );

/*
=================================
SAVE CUSTOM FIELD FOR VENDOR PART
=================================
*/
function save_vendor_parts( $post_id ) {
 $woocommerce_checkbox = isset( $_POST['vendor_part'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, 'vendor_part', $woocommerce_checkbox );
}
add_action( 'woocommerce_process_product_meta', 'save_vendor_parts' );

/*
====================================
CREATE CUSTOM FIELD FOR VINTAGE PART
====================================
*/
function create_vintage_parts() {
  global $woocommerce, $post;
  woocommerce_wp_checkbox(array( 
    'id'            => 'vintage_part', 
    'wrapper_class' => 'show_if_simple', 
    'label'         => __('Is Vintage Part?', 'woocommerce' )
  ));
}
add_action( 'woocommerce_product_options_general_product_data', 'create_vintage_parts' );

/*
==================================
SAVE CUSTOM FIELD FOR VINTAGE PART
==================================
*/
function save_vintage_parts( $post_id ) {
 $woocommerce_checkbox = isset( $_POST['vintage_part'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, 'vintage_part', $woocommerce_checkbox );
}
add_action( 'woocommerce_process_product_meta', 'save_vintage_parts' );

/*
============================================
CREATE CUSTOM FIELD FOR PRODUCT ALTERNATIVES
============================================
*/
function create_product_alternative_field() {
 $args = array(
 'id' => 'product_alternatives',
 'label' => 'Product Alternatives',
 'desc_tip' => true,
 'description' => 'Separate SKUs by | with no spaces Ex: ABC123|DEF456|GHI789',
 );
 woocommerce_wp_text_input( $args );
}
add_action( 'woocommerce_product_options_related', 'create_product_alternative_field' );

/*
==========================================
SAVE CUSTOM FIELD FOR PRODUCT ALTERNATIVES
==========================================
*/
function save_product_alternatives( $post_id ) {
 $product = wc_get_product( $post_id );
 $title = isset( $_POST['product_alternatives'] ) ? $_POST['product_alternatives'] : '';
 $product->update_meta_data( 'product_alternatives', sanitize_text_field( $title ) );
 $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_product_alternatives' );

/*
==========================================
CREATE CUSTOM FIELD FOR QUANTITY INCREMENT
==========================================
*/
function create_product_qty_increment_field() {
 $args = array(
 'id' => 'qty_increment',
 'label' => 'Quantity Increment',
 'desc_tip' => true,
 'description' => 'Number to display as quantity',
 );
 woocommerce_wp_text_input( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'create_product_qty_increment_field' );

/*
==========================================
SAVE CUSTOM FIELD FOR PRODUCT ALTERNATIVES
==========================================
*/
function save_product_qty_increment( $post_id ) {
 $product = wc_get_product( $post_id );
 $title = isset( $_POST['qty_increment'] ) ? $_POST['qty_increment'] : '';
 $product->update_meta_data( 'qty_increment', sanitize_text_field( $title ) );
 $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_product_qty_increment' );


/*
====================================
CREATE CUSTOM FIELD FOR PRODUCT SUBS
====================================
*/
function create_product_subs_field() {
 $args = array(
 'id' => 'product_subs',
 'label' => 'Product Subs',
 'desc_tip' => true,
 'description' => 'Separate SKUs by | with no spaces Ex: ABC123|DEF456|GHI789',
 );
 woocommerce_wp_text_input( $args );
}
add_action( 'woocommerce_product_options_related', 'create_product_subs_field' );

/*
==================================
SAVE CUSTOM FIELD FOR PRODUCT SUBS
==================================
*/
function save_product_subs( $post_id ) {
 $product = wc_get_product( $post_id );
 $title = isset( $_POST['product_subs'] ) ? $_POST['product_subs'] : '';
 $product->update_meta_data( 'product_subs', sanitize_text_field( $title ) );
 $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_product_subs' );



/*
=================================================
CHANGE DEFAULT HEADING TO H3 FOR PRODUCT LISTINGS
=================================================
*/
remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
function abChangeProductsTitle() {
    echo '<h3 class="woocommerce-loop-product_title">' . get_the_title() . '</h3>';
}


/*
=========================
SEND COMMENT ON ORDER
=========================
*/
function send_order_comment() {
  check_ajax_referer( 'nonce_name' );
  // get all vars from the POST
  $contact_preference = $_POST['contact_preference'];
  $customer_name = $_POST['customer_name'];
  $email_address = $_POST['email_address'];
  $phone_number = $_POST['phone_number'];
  $message = $_POST['message'];
  $order_number = $_POST['order_number'];
  $redirect_location = $_POST['redirect_location'];

  $order_id = wc_seq_order_number_pro()->find_order_by_order_number( $order_number );

  $order = wc_get_order( $order_id );
  $order->add_order_note( $message );

  // FORMAT THE MESSAGE TO PASS TO FLOCK
  if ($contact_preference === 'phone') {
    $message = '<strong>' . $customer_name . ' asked:</strong><br/><em>' . $message . '</em><br/>' . 'Please contact ' . $customer_name . ' via ' . $contact_preference . ' at ' . $phone_number . '.';
  } else {
    $message = '<strong>' . $customer_name . ' asked:</strong><br/><em>' . $message . '</em><br/>' . 'Please contact ' . $customer_name . ' via ' . $contact_preference . ' at <a href=\"mailto:' . $email_address . '\">' . $email_address . '</a>.';
  }

  // PASS CUSTOMER NOTIFICATION TO FLOCK
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.flock.com/hooks/sendMessage/5188ba60-d5c2-40f2-9624-16ca3bdb17d5",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n\t\"attachments\": [{\n        \t\"views\": {\n        \t\"flockml\": \"<flockml>" . $message . "</flockml>\"\n    \t},\n    \t\"buttons\": [{\n    \t\t\"name\": \"Open Order\",\n    \t\t\"icon\": \"https://www.greenfarmparts.com/v/vspfiles/templates/gfp-test/img/GFP-logo.svg\",\n    \t\t\"action\": {\n    \t\t\t\"type\": \"openBrowser\",\n    \t\t\t\"url\": \"" . site_url() . "/wp-admin/post.php?post=" . $order_id . "&action=edit\"\n    \t\t}\n    \t}]\n    }]\n}",
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Content-Type: application/json",
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl); 

  echo json_encode(array(
    'status'               => 'success',
    'contact_preference'   => $contact_preference,
    'name'                 => $customer_name,
    'email_address'        => $email_address,
    'phone_number'         => $phone_number
  ));
  die();

}



if ( ! function_exists( 'is_woocommerce_activated' ) ) {
  function is_woocommerce_activated() {
    if ( class_exists( 'woocommerce' ) ) {
      function remove_output_structured_data() { 
        remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); // Frontend pages 
        remove_action( 'woocommerce_email_order_details', array( WC()->structured_data, 'output_email_structured_data' ), 30 ); // Emails 
      } 
      add_action( 'init', 'remove_output_structured_data' );
    }
  }
}  





add_action( 'woocommerce_email_header', 'email_header_before', 1, 2 );
function email_header_before( $email_heading, $email ){
    $GLOBALS['email'] = $email;
}



/*
=========================
ALLOW SVG UPLOADS
@LINK - https://codepen.io/chriscoyier/post/wordpress-4-7-1-svg-upload
=========================
*/
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );






add_filter( 'woocommerce_get_sections_products' , 'shop_language_tab' );
function shop_language_tab( $settings_tab ){
     $settings_tab['shop_language'] = __( 'Shop Language' );
     return $settings_tab;
}

add_filter( 'woocommerce_get_settings_products' , 'shop_language_get_settings' , 10, 2 );
function shop_language_get_settings( $settings, $current_section ) {
         $custom_settings = array();
         if( 'shop_language' == $current_section ) {
              $custom_settings =  array(
                array(
                  'name' => __( 'Order Complete' ),
                  'type' => 'title',
                  'desc' => __( 'Text displayed to customers when order completed.' ),
                  'id'   => 'order_complete' 
                 ),
                array(
                  'name' => __( 'Order Review' ),
                  'type' => 'textarea',
                  'desc' => __( 'Message to display in the order review box'),
                  'desc_tip' => true,
                  'id'  => 'order_review'
                ),
                array(
                  'name' => __( 'Tracking Information' ),
                  'type' => 'textarea',
                  'desc' => __( 'Message to display in the tracking information box'),
                  'desc_tip' => true,
                  'id'  => 'tracking_information'
                ),
                array(
                  'type' => 'sectionend',
                  'id' => 'order_complete'
                ),
            );
         return $custom_settings;
       } else {
          return $settings;
       }
}

function wcpp_custom_style() {?>
  <style>
    textarea[name="order_review"],
    textarea[name="tracking_information"] {
      width: 100% !important;
      min-height: 100px;
    }
  </style>
<?php
}
add_action('admin_head', 'wcpp_custom_style');




/*
=========================
ADD EXCERPTS FOR PAGES
=========================
*/
add_post_type_support( 'page', 'excerpt' );


/* 
====================================================================
ADD TAX EXEMPT CAPABILITY
@LINK - https://trackitweb.com/tax-exempt-customers-for-woocommerce/
====================================================================
*/
  if (is_user_logged_in() && !is_admin()) {
    add_filter( 'wp_enqueue_scripts', 'make_customer_tax_exempt' );
  }
  function make_customer_tax_exempt() {
    global $WC;
    $tax_exempt = current_user_can( 'tax_exempt');
    WC()->customer->set_is_vat_exempt( $tax_exempt );
  }
 // This ends the tax-exempt section.


 /*
 ==============================
REMOVE wc-cart-fragmanets
 ==============================
 */
/** remove from homepage */
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
function dequeue_woocommerce_cart_fragments() {
  if (is_front_page()) wp_dequeue_script('wc-cart-fragments'); 
}


/*
====================
REMOVE HEARTBEAT
====================
*/
add_action( 'init', 'stop_heartbeat', 1 );
function stop_heartbeat() {
wp_deregister_script('heartbeat');
}


/*
==============================
REMOVE PHONE NUMBER VALIDATION
==============================
*/
add_filter( 'woocommerce_checkout_fields', 'remove_phone_validation' );
function remove_phone_validation( $woo_checkout_fields_array ) {
	unset( $woo_checkout_fields_array['billing']['billing_phone']['validate'] );
	return $woo_checkout_fields_array;
}