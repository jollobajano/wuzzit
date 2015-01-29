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

    $num_cols = 3;
    $md_col_class = "col-md-" . strval(12 / $num_cols);

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
        'size' => 'medium', //array(72,72),
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
    $count = 0;
    wz_head();
    
    foreach ( $images as $image ) {    
        $caption = $image->post_excerpt;
 
        $description = $image->post_content;
        if($description == '') $description = $image->post_title;
 
        $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
 
        // render your gallery here
        $image_tag = wp_get_attachment_image_src($image->ID, 'medium');
        $thumbnail_tag = wp_get_attachment_image_src($image->ID, array(72, 72));
	wz_img($image_tag[0], $thumbnail_tag[0]);
    }
    wz_foot();
}

  function wz_head() {
    $jssor_slider = plugins_url( 'js/jssor.slider.mini.js', __FILE__ );
    $slider_local = plugins_url( 'js/slider-local.js', __FILE__ );
    echo <<<DIV
    <div class="row">
      <div class="col-md-12">
    <!-- it works the same with all jquery version from 1.x to 2.x 
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    -->
    <!-- use jssor.slider.mini.js (40KB) instead for release -->
    <!-- jssor.slider.mini.js = (jssor.js + jssor.slider.js) -->
    <script type="text/javascript" src="$jssor_slider"></script>
    <script type="text/javascript" src="$slider_local"></script>
    <!-- Jssor Slider Begin -->
    <!-- You can move inline styles to css file or css block. -->
    <div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 800px;
        height: 650px; background: #191919; overflow: hidden;">

        <!-- Loading Screen -->
        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                background-color: #000000; top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
            <div style="position: absolute; display: block; background: url(../img/loading.gif) no-repeat center center;
                top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
        </div>

        <!-- Slides Container -->
        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 800px; height: 550px; overflow: hidden;">
DIV;
  }

  function wz_img($image, $thumbnail) {
    echo <<<DIV
            <div>
                <img u="image" src="$image" />
                <img u="thumb" src="$thumbnail" />
            </div>
DIV;
  }

  function wz_foot() {
    $arrows_png = plugins_url( 'img/a17.png', __FILE__ );
    echo <<<DIV
      </div>
        <!-- Arrow Navigator Skin Begin -->
        <style>
            /* jssor slider arrow navigator skin 05 css */
            /*
            .jssora05l              (normal)
            .jssora05r              (normal)
            .jssora05l:hover        (normal mouseover)
            .jssora05r:hover        (normal mouseover)
            .jssora05ldn            (mousedown)
            .jssora05rdn            (mousedown)
            */
            .jssora05l, .jssora05r, .jssora05ldn, .jssora05rdn
            {
		position: absolute;
            	cursor: pointer;
            	display: block;
                background: url($arrows_png) no-repeat;
                overflow:hidden;
            }
            .jssora05l { background-position: -10px -40px; }
            .jssora05r { background-position: -70px -40px; }
            .jssora05l:hover { background-position: -130px -40px; }
            .jssora05r:hover { background-position: -190px -40px; }
            .jssora05ldn { background-position: -250px -40px; }
            .jssora05rdn { background-position: -310px -40px; }
        </style>
        <!-- Arrow Left -->
        <span u="arrowleft" class="jssora05l" style="width: 40px; height: 40px; top: 275px; left: 8px;">
        </span>
        <!-- Arrow Right -->
        <span u="arrowright" class="jssora05r" style="width: 40px; height: 40px; top: 275px; right: 8px">
        </span>
        <!-- Arrow Navigator Skin End -->
        
        <!-- Thumbnail Navigator Skin Begin -->
        <div u="thumbnavigator" class="jssort01" style="position: absolute; width: 800px; height: 100px; left:0px; bottom: 0px;">
            <!-- Thumbnail Item Skin Begin -->
            <style>
                /* jssor slider thumbnail navigator skin 01 css */
                /*
                .jssort01 .p           (normal)
                .jssort01 .p:hover     (normal mouseover)
                .jssort01 .pav           (active)
                .jssort01 .pav:hover     (active mouseover)
                .jssort01 .pdn           (mousedown)
                */
                .jssort01 .w {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    width: 100%;
                    height: 100%;
                }

                .jssort01 .c {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    width: 68px;
                    height: 68px;
                    border: #000 2px solid;
                }

                .jssort01 .p:hover .c, .jssort01 .pav:hover .c, .jssort01 .pav .c {
                    background: url(img/t01.png) center center;
                    border-width: 0px;
                    top: 2px;
                    left: 2px;
                    width: 68px;
                    height: 68px;
                }

                .jssort01 .p:hover .c, .jssort01 .pav:hover .c {
                    top: 0px;
                    left: 0px;
                    width: 70px;
                    height: 70px;
                    border: #fff 1px solid;
                }
            </style>
            <div u="slides" style="cursor: move;">
                <div u="prototype" class="p" style="position: absolute; width: 72px; height: 72px; top: 0; left: 0;">
                    <div class=w><div u="thumbnailtemplate" style=" width: 100%; height: 100%; border: none;position:absolute; top: 0; left: 0;"></div></div>
                    <div class=c>
                    </div>
                </div>
            </div>
            <!-- Thumbnail Item Skin End -->
        </div>
        <!-- Thumbnail Navigator Skin End -->
        <a style="display: none" href="http://www.jssor.com">Image Slider</a>
    </div>
    <!-- Jssor Slider End -->
    <div style="margin-bottom: 2cm;"></div>
  </div>
</div>
DIV;
}
?>
