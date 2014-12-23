<?php
/*
Plugin Name: Wuzzit
Plugin URI: https://github.com/jollobajano/wuzzit
Description: Custom galleries plugin ripped from http://shibashake.com/wordpress-theme/how-to-render-your-own-wordpress-photo-gallery
Version: 1.0
Author: Mats Nyberg or rather <a href="http://shibashake.com/wordpress-theme/author/shibashake">Shiba Shake</a>
Author URI: http://your-home-url
*/

remove_shortcode('gallery');
add_shortcode('gallery', 'parse_gallery_shortcode');



function parse_gallery_shortcode($atts) {
 
    global $post;
 
    if ( ! empty( $atts['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $atts['orderby'] ) )
            $atts['orderby'] = 'post__in';
        $atts['include'] = $atts['ids'];
    }
 
    extract(shortcode_atts(array(
        'orderby' => 'menu_order ASC, ID ASC',
        'include' => '',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'medium',
        'link' => 'file'
    ), $atts));
 
 
    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_mime_type' => 'image',
        'orderby' => $orderby
    );
 
    if ( !empty($include) )
        $args['include'] = $include;
    else {
        $args['post_parent'] = $id;
        $args['numberposts'] = -1;
    }
 
    $images = get_posts($args);

    echo '<div class="row"><!-- gallery -->';
    
    foreach ( $images as $image ) {    
        $caption = $image->post_excerpt;
 
        $description = $image->post_content;
        if($description == '') $description = $image->post_title;
 
        $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
 
        // render your gallery here
        echo '<div class="col-xs-6 col-md-4">';
        echo '<a href="#" class="thumbnail">';

        echo wp_get_attachment_image($image->ID, $size);
        
        echo '</a>';
        echo '</div>';
    }
    echo '</div><!-- /gallery -->';
}
?>
