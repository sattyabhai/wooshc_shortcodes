<?php
class Wooshc_Recent_Products_Widget extends WP_Widget {

// Constructor
public function __construct() {
    parent::__construct(
        'wooshc_recent_products_widget',
        'Recent Products',
        array(
            'description' => 'Display recent products in a widget.',
        )
    );
}

// Widget Content
public function widget($args, $instance) {
    echo $args['before_widget'];
    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];

    // Display recent products
    $wc_query = new WP_Query(array(
        'post_type'      => 'product', // Change this to your actual custom post type
        'posts_per_page' => $instance['number_of_products'],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    $button['add_to_cart_button'] = "yes";
    $button['add_to_cart_button_ajax'] = "yes";
    echo '<div class="product-widget">';

    if ($wc_query->have_posts()) {
        // while ($wc_query->have_posts()) {
        //     $wc_query->the_post();
            require WOOSHC_TEMPLATE_PATH."wooshc-products.php";
        // }
        wp_reset_postdata();
    } else {
        echo 'No recent products found.';
    }
    echo '</div>';

    echo $args['after_widget'];
}

// Widget Form
public function form($instance) {
    $title             = !empty($instance['title']) ? $instance['title'] : 'Recent Products';
    $number_of_products = !empty($instance['number_of_products']) ? $instance['number_of_products'] : 5;
    ?>

    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('number_of_products'); ?>">Number of Products to Display:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('number_of_products'); ?>" name="<?php echo $this->get_field_name('number_of_products'); ?>" type="number" min="1" value="<?php echo esc_attr($number_of_products); ?>">
    </p>

    <?php
}

// Widget Update
public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title']             = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
    $instance['number_of_products'] = (!empty($new_instance['number_of_products'])) ? absint($new_instance['number_of_products']) : 5;

    return $instance;
}
}


