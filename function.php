<?php

// Add this code in your function.php file
/********
*
*  Step 1
*
******/

// Add search form shortcode used in function.php
//Ajax filter Search
function my_ajax_filter_search_shortcode() {

	my_ajax_filter_search_scripts(); // Added here

    ob_start(); ?>
    <!-- FORM CODE WILL GOES HERE -->
	<div id="my-ajax-filter-search">
        <form action="" method="get">
            <input type="text" name="search" id="search" value="" placeholder="Search Here..">
            <input type="submit" id="submit" name="submit" value="Search">
        </form>
        <ul id="ajax_filter_search_results"></ul>
    </div>
    
    <?php
    return ob_get_clean();
}

add_shortcode ('my_ajax_filter_search', 'my_ajax_filter_search_shortcode');

/********
*
*  Step 2 Call Ajax Filter File in function.php
*
******/

function my_ajax_filter_search_scripts() {
    wp_enqueue_script( 'my_ajax_filter_search', get_stylesheet_directory_uri(). '/script.js', array(), '1.0', true );
    wp_add_inline_script( 'my_ajax_filter_search', 'const ajax_info = '. json_encode( array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) ), 'before' );
}


/********
*
*  Step 3 Get Search Data from our post type.
*
******/
 
add_action('wp_ajax_my_ajax_filter_search', 'my_ajax_filter_search_callback');
add_action('wp_ajax_nopriv_my_ajax_filter_search', 'my_ajax_filter_search_callback');
 
function my_ajax_filter_search_callback() {
 
    header("Content-Type: application/json"); 
 
    $meta_query = array('relation' => 'AND');
 
    $tax_query = array();
 
    $args = array(
        'post_type' => 'movie',   // Use Post type here
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
        'tax_query' => $tax_query
    );
 
    if(isset($_GET['search'])) {
        $search = sanitize_text_field( $_GET['search'] );
        $search_query = new WP_Query( array(
            'post_type' => 'movie',
            'posts_per_page' => -1,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
            's' => $search
        ) );
    } else {
        $search_query = new WP_Query( $args );
    }
 
    if ( $search_query->have_posts() ) {
 
        $result = array();
 
        while ( $search_query->have_posts() ) {
            $search_query->the_post();
 
            $cats = strip_tags( get_the_category_list(", ") );
            $result[] = array(
                "id" => get_the_ID(),
                "title" => get_the_title(),
                "content" => get_the_content(),
                "permalink" => get_permalink(),
                "year" => get_field('year'),
                "poster" => wp_get_attachment_url(get_post_thumbnail_id($post->ID),'full')
            );
        }
        wp_reset_query();
 
        echo json_encode($result);
 
    } else {
        // no posts found
    }
    wp_die();
}
