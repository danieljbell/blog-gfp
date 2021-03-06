<?php 
/*
=============================
Template Name: Model Selector
=============================
*/
?>

<?php get_header(); ?>

<section class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url(<?php echo get_stylesheet_directory_URI(); ?>/dist/img/hero--generic-<?php echo mt_rand(1,5);?>.jpg);">
  <div class="site-width">
    <h1>Choose Your Model</h1>
  </div>
</section>

<section>
  <div class="site-width">
    <div class="all-models-container">
      <div>
        <section id="equipment-container--lawn" class="mar-b--most equipment-container equipment-container--lawn-garden">
          <div class="box--with-header">
            <header>
              <h2>Lawn &amp; Garden</h2>
            </header>
            <?php
              format_equipment_menu('lawn-garden', 'Lawn Tractors', 'lawn-tractors', 'lawn_tractors');
              format_equipment_menu('lawn-garden', 'Zero Turns', 'zero-turns', 'zero_turns');
              format_equipment_menu('lawn-garden', 'Compact Tractors', 'compact-tractors', 'compact_tractors');
              format_equipment_menu('lawn-garden', 'Gators', 'gators', 'gators');
              format_equipment_menu('lawn-garden', 'Walk Behind', 'walk-behind-mowers', 'walk_behind_mowers');
              format_equipment_menu('lawn-garden', 'Hand Held Equipment', 'hand-held-equipment', 'hand_held_equipment');
            ?>
          </div>
        </section>
        <section id="equipment-container--agriculture" class="mar-b--most equipment-container equipment-container--agriculture">
          <div class="box--with-header">
            <header>
              <h2>Agriculture</h2>
            </header>            
            <?php
              format_equipment_menu('agriculture', 'Sprayers', 'sprayers', 'sprayers');
              format_equipment_menu('agriculture', 'Tractors', 'tractors', 'tractors');
              format_equipment_menu('agriculture', 'Combines', 'combines', 'combines');
              format_equipment_menu('agriculture', 'Balers', 'balers', 'balers');
              format_equipment_menu('agriculture', 'Loaders', 'loaders', 'loaders');
              format_equipment_menu('agriculture', 'Windrowers', 'windrowers', 'windrowers');
            ?>
          </div>
        </section>
        <section id="equipment-container--landscapers" class="mar-b--most equipment-container equipment-container--landscapers">
          <div class="box--with-header">
            <header>
              <h2>Landscapers</h2>
            </header>
            <?php
              format_equipment_menu('landscapers', 'Zero Turns', 'zero-turn-mowers', 'zero_turn_mowers');
              format_equipment_menu('landscapers', 'Front Mowers', 'front-mowers', 'front_mowers');
              format_equipment_menu('landscapers', 'Quik-Traks', 'quik-traks', 'quik_traks');
              format_equipment_menu('landscapers', 'Walk Behind', 'walk-behind-mowers', 'walk_behind_mowers');
              format_equipment_menu('landscapers', 'Wide Area', 'wide-area-mowers', 'wide_area_mowers');
            ?>
          </div>
        </section>
        <section id="equipment-container--golf" class="equipment-container equipment-container--golf">
          <div class="box--with-header">
            <header>
              <h2>Golf</h2>
            </header>
            <?php
              format_equipment_menu('golf', 'Aerators', 'aeration', 'aeration');
              format_equipment_menu('golf', 'Greens Mowers', 'greens-mowers', 'greens_mowers');
              format_equipment_menu('golf', 'Fairway Mowers', 'fairway-mowers', 'fairway_mowers');
              format_equipment_menu('golf', 'Rough & Trim Mowers', 'rough-trim-mowers', 'rough_trim_mowers');
              format_equipment_menu('golf', 'Turf Sprayers', 'turf-sprayers', 'turf_sprayers');
            ?>
          </div>
        </section>
      </div>
      <aside>
        <div class="sticky-elements">
          <div class="box--with-header">
            <header>Filters</header>
            <div class="form-group mar-b">
              <label for="search_model" class="visually-hidden">Filter Page By Model Number</label>
              <input type="text" name="search_model" id="search_model" placeholder="Filter Page By Model Number">
            </div>
            <ul style="list-style-type: none;">
              <li>
                <input type="checkbox" id="lawn-garden" checked>
                <label for="lawn-garden">Lawn &amp; Garden</label>
              </li>
              <li>
                <input type="checkbox" id="agriculture" checked>
                <label for="agriculture">Agriculture</label>
              </li>
              <li>
                <input type="checkbox" id="landscapers" checked>
                <label for="landscapers">Landscapers</label>
              </li>
              <li>
                <input type="checkbox" id="golf" checked>
                <label for="golf">Golf</label>
              </li>
            </ul>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php get_footer(); ?>

<?php 
function format_equipment_menu($parent, $pretty_name, $slug, $equip_var) {
    $equipment_query = new WP_Query(array(
      'post_type' => 'post',
      'category' => 'maintenance-reminder',
      'order' => 'ASC',
      'orderby' => 'title',
      'posts_per_page' => -1,
      'tag_slug__and' => [$parent, $slug]
    ));
    $posts = [];
    if ($equipment_query->have_posts()) : while ($equipment_query->have_posts()) : $equipment_query->the_post();
      $name = get_the_title();
      $name = str_replace("John Deere ", "", $name);
      $name = str_replace(" Maintenance Guide", "", $name);
      array_push($posts, array(
        'name' => $name,
        'link' => get_the_permalink()
      ));
    endwhile; endif; wp_reset_query();
    echo '<div class="industry-vertical--section mar-b" id="' . $parent . '-' . $slug . '">';
      echo '<div class="industry-vertical--links">';
        echo '<ul class="accordian">';
          echo '<li class="accordian--item">';
            echo '<button class="accordian--title"><img src="' . get_stylesheet_directory_URI() . '/dist/img/' . $parent . '-' . $slug . '.jpg" alt="' . $pretty_name . '">' . $pretty_name . '</button>';
            echo '<div class="accordian--content">';
              echo '<ul class="single--part-fitment-list">';
                foreach ($posts as $key => $post) {
                  $name = $post['name'];
                  $name = explode(' ', $name);
                  echo '<li class="single--part-fitment-item" data-model-number="' . $post['name'] . '"><a href="' . $post['link'] . '">' . $name[0] . '</a></li>';
                }
              echo '</ul>';
            echo '</div>';
          echo '</li>';
        echo '</ul>';
      echo '</div>';
    echo '</div>';
  }