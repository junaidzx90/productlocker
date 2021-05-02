<?php
// vehica_car
add_action("add_meta_boxes",function(){
    add_meta_box("lc4pay_require_product","Locked document upload","lc4pay_require_product_cb",['post','page','vehica_car'],"advanced","high");
});

function lc4pay_require_product_cb($post){
    if( $image = wp_get_attachment_image_src( $post->ID ) ) {
 
        echo '<a href="#" class="junu-upl"><img src="' . $image[0] . '" /></a>
              <a href="#" class="junu-rmv">Remove image</a>
              <input type="hidden" name="junu-img" value="' . $post->ID . '">';
     
    } else {
     
        echo '<a href="#" class="junu-upl">Upload documents</a>
              <a href="#" class="junu-rmv" style="display:none">Remove image</a>
              <input type="hidden" name="junu-img" value="">';
     
    }
    ?>
    <div class="generate_link">
        <input type="text" placeholder="Redirect url">
        <span class="generatelink">Generate Link</span>
        <br>
        <br>
        
        <div class="wppayform_shortcode">
            <input type="text" placeholder="Payform shortcode">
        </div>
    </div>
    <br>
    <br>
    <br>
    <span>📄documents-1.pdf</span><br>
    <span>📁documents-2.zip</span>
    <?php
    echo get_post_meta( $post->ID, "lc4pay_require_product",true);
    wp_nonce_field( "nonce_values", "lc4pay_nonce");
}

add_action( 'save_post',function( $post_id ) {
    if(isset($_POST['lc4pay_require_product'])){
        if(wp_verify_nonce( $_POST["lc4pay_nonce"], "nonce_values")){
            update_post_meta( $post_id, "lc4pay_require_product", $_POST['lc4pay_require_product'] );
        }
    }
});