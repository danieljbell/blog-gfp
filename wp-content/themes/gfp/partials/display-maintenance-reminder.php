<?php
  $deere_permalink = get_field('deere_permalink');
  $url = $deere_permalink . 'index.json';
  $getJSON = curl_init();
  curl_setopt($getJSON, CURLOPT_URL, $url);
  curl_setopt($getJSON, CURLOPT_HEADER, 0);
  curl_setopt($getJSON, CURLOPT_RETURNTRANSFER, 1);
  
  $returnDeereModel = json_decode(curl_exec($getJSON), true);

  $model_number = $returnDeereModel['Page']['analytics']['MetaData']['product-model-number'];
  $model_name = $returnDeereModel['Page']['analytics']['MetaData']['product-model-name'];
  $model_image = 'https://deere.com/' . $returnDeereModel['Page']['analytics']['MetaData']['product-image'];
?>  

<section <?php post_class(); ?>>
  <div class="site-width">

    <aside>
      <img class="model-image" src="<?php echo $model_image; ?>" alt="John Deere <?php echo $model_number . ' ' . $model_name; ?>">

    </aside>
      
    <article>
      <h1><?php echo get_the_title(); ?></h1>

      <?php
        $model_modifiers = get_field('model_modifers'); 
        echo '<select id="modelModifiers">';
          echo '<option selected disabled>Choose Different Model in this Series</option>';
        foreach ($model_modifiers as $post) {
          setup_postdata($post);
          global $post;
          $post_slug = $post->post_name;
          echo '<option value="' . $post_slug . '">' . str_replace('Maintenance Sheet', '', get_the_title()) . '</option>';
        }
        wp_reset_postdata();
        echo '</select>';
      ?>

      <section class="mar-y--most">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni possimus quasi nobis voluptates sint aliquid in quas rerum, optio quisquam recusandae ratione corporis suscipit quis!</p>
      </section>

      <section class="mar-y--most">
        <h2>Common Parts<span>for John Deere <?php echo $model_number . ' ' . $model_name; ?></span></h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut est soluta vitae ipsam facilis exercitationem, nulla ea. Illum possimus nisi suscipit, quae dolore blanditiis! Autem eligendi libero ullam, accusamus eius nihil facilis iure assumenda! Explicabo.</p>
        <?php if (have_rows('common_parts')) : ?>
          <table>
            <tr>
              <th>Part Type</th>
              <th>Part Number</th>
              <!-- <th>Price</th> -->
              <th width="130"></th>
            </tr>
        <?php while (have_rows('common_parts')) : the_row(); ?>
            <?php
              // $url = 'https://www.greenfarmparts.com/-p/' . get_sub_field('common_part_number') . '.htm';
              // $getHTML = curl_init();
              // curl_setopt($getHTML, CURLOPT_URL, $url);
              // curl_setopt($getHTML, CURLOPT_HEADER, 0);
              // curl_setopt($getHTML, CURLOPT_RETURNTRANSFER, 1);
              // $returnProductData = curl_exec($getHTML);

              // check for sold online or not
              if (!get_sub_field('not_sold')) {
                $available_online = '<button class="add-to-cart">Add to Cart</button>';
                $product_link = '<a href="https://www.greenfarmparts.com/-p/' . get_sub_field('common_part_number') . '.htm">' . get_sub_field('common_part_number') . '</a>';
              } else {
                $available_online = '<button class="disabled">Not Sold Online</button>';
                $product_link = get_sub_field('common_part_number');
              }

                // Add Serial Break on seperate line
                $common_part_description = get_sub_field('common_part_description');
                $common_part_description = explode('(', $common_part_description);
                if (count($common_part_description) > 1) {
                  $common_part_description = $common_part_description[0] . '<br><em>(' . $common_part_description[1] . '</em>';
                } else {
                  $common_part_description = get_sub_field('common_part_description');
                }
            ?>
            <tr>
              <td data-header="Part Type" data-product-image="https://greenfarmparts.com/v/vspfiles/photos/<?php echo get_sub_field('common_part_number'); ?>-2T.jpg">
                <?php echo $common_part_description; ?>    
              </td>
              <td data-header="Part Number"><?php echo $product_link; ?></td>
              <!-- <td data-header="Price">$9.99</td> -->
              <td><?php echo $available_online; ?></td>
            </tr>
            <?php if (have_rows('serial_breaks')) : while(have_rows('serial_breaks')) : the_row(); ?>
              <?php
                // Add Serial Break on seperate line
                $serial_break_common_part_description = get_sub_field('serial_break_common_part_description');
                $serial_break_common_part_description = explode('(', $serial_break_common_part_description);
              ?>
              <tr>
                <td data-header="Part Type"><?php echo $serial_break_common_part_description[0]; ?><br><em>(<?php echo $serial_break_common_part_description[1]; ?></em></td>
                <td data-header="Part Number"><a href="https://www.greenfarmparts.com/-p/<?php echo get_sub_field('serial_break_common_part_number'); ?>.htm"><?php echo get_sub_field('serial_break_common_part_number'); ?></a></td>
                <!-- <td data-header="Price">$9.99</td> -->
                <td>
                  <?php
                    if (!get_sub_field('not_sold')) {
                      echo '<button class="add-to-cart">Add to Cart</button>';
                    } else {
                      echo 'Not sold online';
                    }
                  ?>
                </td>
              </tr>
            <?php endwhile; endif; ?>
        <?php endwhile; ?>
          </table>
        <?php endif; ?>
      </section>

      <section class="mar-y--most">
        <h2>Hourly Parts<span>for John Deere <?php echo $model_number . ' ' . $model_name; ?></span></h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut est soluta vitae ipsam facilis exercitationem, nulla ea. Illum possimus nisi suscipit, quae dolore blanditiis! Autem eligendi libero ullam, accusamus eius nihil facilis iure assumenda! Explicabo.</p>
        <?php if (have_rows('hourly_parts')) : ?>
          <table>
            <tr>
              <th>Part Type</th>
              <th>Part Number</th>
              <th width="75">Quantity</th>
              <th>Hour Intervals</th>
              <th width="130"></th>
            </tr>
        <?php while (have_rows('hourly_parts')) : the_row(); ?>
            <?php
              // $url = 'https://www.greenfarmparts.com/-p/' . get_sub_field('hourly_part_number') . '.htm';
              // $getHTML = curl_init();
              // curl_setopt($getHTML, CURLOPT_URL, $url);
              // curl_setopt($getHTML, CURLOPT_HEADER, 0);
              // curl_setopt($getHTML, CURLOPT_RETURNTRANSFER, 1);
              // $returnProductData = curl_exec($getHTML);

              // check for sold online or not
              if (!get_sub_field('not_sold')) {
                $available_online = '<button class="add-to-cart">Add to Cart</button>';
                $product_link = '<a href="https://www.greenfarmparts.com/-p/' . get_sub_field('hourly_part_number') . '.htm">' . get_sub_field('hourly_part_number') . '</a>';
              } else {
                $available_online = '<button class="disabled">Not Sold Online</button>';
                $product_link = get_sub_field('hourly_part_number');
              }

                // Add Serial Break on seperate line
                $hourly_part_description = get_sub_field('hourly_part_description');
                $hourly_part_description = explode('(', $hourly_part_description);
                if (count($hourly_part_description) > 1) {
                  $hourly_part_description = $hourly_part_description[0] . '<br><em>(' . $hourly_part_description[1] . '</em>';
                } else {
                  $hourly_part_description = get_sub_field('hourly_part_description');
                }
                $interval_hour = get_sub_field('interval');
                $intervals = join("/", $interval_hour);
            ?>
            <tr>
              <td data-header="Part Type" data-product-image="https://greenfarmparts.com/v/vspfiles/photos/<?php echo get_sub_field('hourly_part_number'); ?>-2T.jpg">
                <?php echo $hourly_part_description; ?>    
              </td>
              <td data-header="Part Number"><?php echo $product_link; ?></td>
              <td data-header="Quantity"><?php echo get_sub_field('quantity'); ?></td>
              <td data-header="Hour Intervals"><?php echo $intervals; ?></td>
              <td><?php echo $available_online; ?></td>
            </tr>
            <?php if (have_rows('serial_breaks')) : while(have_rows('serial_breaks')) : the_row(); ?>
              <?php
                // Add Serial Break on seperate line
                $serial_break_hourly_part_description = get_sub_field('serial_break_hourly_part_description');
                $serial_break_hourly_part_description = explode('(', $serial_break_hourly_part_description);
              ?>
              <tr>
                <td data-header="Part Type"><?php echo $serial_break_hourly_part_description[0]; ?><br><em>(<?php echo $serial_break_hourly_part_description[1]; ?></em></td>
                <td data-header="Part Number"><a href="https://www.greenfarmparts.com/-p/<?php echo get_sub_field('serial_break_hourly_part_number'); ?>.htm"><?php echo get_sub_field('serial_break_common_part_number'); ?></a></td>
                <td data-header="Quantity">$9.99</td>
                <td data-header="Hour Intervals">$9.99</td>
                <td>
                  <?php
                    if (!get_sub_field('not_sold')) {
                      echo '<button class="add-to-cart">Add to Cart</button>';
                    } else {
                      echo 'Not sold online';
                    }
                  ?>
                </td>
              </tr>
            <?php endwhile; endif; ?>
        <?php endwhile; ?>
          </table>
        <?php endif; ?>
      </section>
      
      <section class="mar-y--most">
        <h2>As Needed Parts <span>for John Deere <?php echo $model_number . ' ' . $model_name; ?></span></h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut est soluta vitae ipsam facilis exercitationem, nulla ea. Illum possimus nisi suscipit, quae dolore blanditiis! Autem eligendi libero ullam, accusamus eius nihil facilis iure assumenda! Explicabo.</p>
        <?php if (have_rows('as_needed_parts')) : ?>
          <table>
            <tr>
              <th>Part Type</th>
              <th>Part Number</th>
              <!-- <th>Price</th> -->
              <th width="130"></th>
            </tr>
        <?php while (have_rows('as_needed_parts')) : the_row(); ?>
            <?php
              // $url = 'https://www.greenfarmparts.com/-p/' . get_sub_field('as_needed_part_number') . '.htm';
              // $getHTML = curl_init();
              // curl_setopt($getHTML, CURLOPT_URL, $url);
              // curl_setopt($getHTML, CURLOPT_HEADER, 0);
              // curl_setopt($getHTML, CURLOPT_RETURNTRANSFER, 1);
              // $returnProductData = curl_exec($getHTML);

              // check for sold online or not
              if (!get_sub_field('not_sold')) {
                $available_online = '<button class="add-to-cart">Add to Cart</button>';
                $product_link = '<a href="https://www.greenfarmparts.com/-p/' . get_sub_field('as_needed_part_number') . '.htm">' . get_sub_field('as_needed_part_number') . '</a>';
              } else {
                $available_online = '<button class="disabled">Not Sold Online</button>';
                $product_link = get_sub_field('as_needed_part_number');
              }

                // Add Serial Break on seperate line
                $as_needed_part_description = get_sub_field('as_needed_part_description');
                $as_needed_part_description = explode('(', $as_needed_part_description);
                if (count($as_needed_part_description) > 1) {
                  $as_needed_part_description = $as_needed_part_description[0] . '<br><em>(' . $as_needed_part_description[1] . '</em>';
                } else {
                  $as_needed_part_description = get_sub_field('as_needed_part_description');
                }
            ?>
            <tr>
              <td data-header="Part Type" data-product-image="https://greenfarmparts.com/v/vspfiles/photos/<?php echo get_sub_field('as_needed_part_number'); ?>-2T.jpg">
                <?php echo $as_needed_part_description; ?>    
              </td>
              <td data-header="Part Number"><?php echo $product_link; ?></td>
              <!-- <td data-header="Price">$9.99</td> -->
              <td><?php echo $available_online; ?></td>
            </tr>
            <?php if (have_rows('serial_breaks')) : while(have_rows('serial_breaks')) : the_row(); ?>
              <?php
                // Add Serial Break on seperate line
                $serial_break_as_needed_part_description = get_sub_field('serial_break_as_needed_part_description');
                $serial_break_as_needed_part_description = explode('(', $serial_break_as_needed_part_description);
              ?>
              <tr>
                <td data-header="Part Type"><?php echo $serial_break_as_needed_part_description[0]; ?><br><em>(<?php echo $serial_break_as_needed_part_description[1]; ?></em></td>
                <td data-header="Part Number"><a href="https://www.greenfarmparts.com/-p/<?php echo get_sub_field('serial_break_as_needed_part_number'); ?>.htm"><?php echo get_sub_field('serial_break_as_needed_part_number'); ?></a></td>
                <!-- <td data-header="Price">$9.99</td> -->
                <td>
                  <?php
                    if (!get_sub_field('not_sold')) {
                      echo '<button class="add-to-cart">Add to Cart</button>';
                    } else {
                      echo 'Not sold online';
                    }
                  ?>
                </td>
              </tr>
            <?php endwhile; endif; ?>
        <?php endwhile; ?>
          </table>
        <?php endif; ?>
      </section>

    </article>

  </div>
</section>


<div class="alert--add-to-cart">
  <div class="alert--header">
    <h4>Products in Cart</h4>
    <button class="alert--close" id="closeAlert">&times;</button>
  </div>
  <div class="alert--content">
    <ul class="alert--cart-list">
      <li class="alert--cart-item">
        <span class="alert--cart-part">
          <span class="alert--cart-part-type">productType</span>
          <span class="alert--cart-part-number">productCode</span>
        </span>
        <span>
          <label for="product_quantity">Qty: </label>
          <input type="number" name="product_quantity" min="1" max="50" value="1">
          <button class="alert--remove-item">&times;</button>
        </span>
      </li>
    </ul>
    <div class="has-text-center mar-t--more">
      <a id="submitCheckout" href="https://www.greenfarmparts.com/shoppingcart.asp?" class="btn-solid--brand">Checkout</a>
    </div>
  </div>
</div>