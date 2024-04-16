<?php 
    // print_r($button);
    // echo "<br>";
    while ( $wc_query->have_posts() ) : $wc_query->the_post();
?>
    <div class="product-row">
<?php 
            $product_id = get_the_ID();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'medium' );
            $_product = wc_get_product( $product_id );
            $product_name = $_product->get_name();
            $product_stock_status = $_product->get_stock_status();
            $product_stock = $_product->get_stock_quantity();
            $price_html = $_product->get_price_html();
            $product_sku = $_product->get_sku();
            $product_type = $_product->get_type();
            $button_class ="button ";
            $button_url = "";
            $product_url = get_permalink($product_id);
            $stock_html = "";
            switch($product_type)
            {
                case "simple":  $button_text = "Add to Cart";
                                $button_url = "?add-to-cart=".$product_id;
                                $button_class.= "add_to_cart_button ";
                                break;
                
                case "variable":  $button_text = "Select Option";
                                  $button_url = get_permalink($product_id);
                                  break;

                case "external":  $button_text = "Buy On Site";
                                break;

                case "grouped":  $button_text = "View Products";
                                $button_url = get_permalink($product_id);
                                break;
            }
            $button_class.= "product_type_".$product_type;
            if($button['add_to_cart_button_ajax'] == 'yes')
            {
                $button_class.= " ajax_add_to_cart";
            }
            if($_product->get_manage_stock())
            {
                $stock_html = ($product_stock_status == 'instock'? $product_stock:'');
            }
?>      
         <div class="product-column col-left">
            <a href="<?php echo $product_url; ?>">
                <img src="<?php echo $image[0]; ?>">
            </a>
        </div>
        <div class="product-column col-right">
        <div class="content-area">

            <a class="product-title" href="<?php echo $product_url; ?>"><?php echo $product_name; ?></a>
            <div class="product-sku"><span class="h-title"> SKU: </span> <span class="h-content"><?php echo $product_sku; ?> </span></div>
            <div class="product-stock"> 
                <span class="h-title"> Quantity: </span> <span class="h-content"> <?php echo $stock_html; ?> </span>
            </div> 
            <div class="product-price">  <span class="h-title">Price: </span> <span class="h-content"> <?php echo $price_html; ?></span> </div>
            <a href="<?php echo $button_url; ?>" class="<?php echo $button_class; ?>" data-product_id="<?php echo $product_id; ?>" data-product_sku="<?php echo $product_sku; ?>"><?php echo $button_text; ?></a>
            </div>

        </div>
    </div>
<?php
    endwhile;

?>
