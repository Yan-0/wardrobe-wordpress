<?php 
if( !class_exists( 'WC_Booster_Wish_List_Item_Block' ) ){

	class WC_Booster_Wish_List_Item_Block extends WC_Booster_Base_Block{

		public $slug = 'wish-list-item';
		public $settings;

	    protected static $instance;
	    
	    public static function get_instance() {
	        if ( null === self::$instance ) {
	            self::$instance = new self();
	        }
	        return self::$instance;
	    }

	    /**
		* Generate & Print Frontend Styles
		* Called in wp_head hook
		* @access public
		* @since 1.0.0
		* @return null
		*/
		public function prepare_scripts(){
			
			foreach( $this->blocks as $block ){

				$attrs = self::get_attrs_with_default( $block[ 'attrs' ] );
				
				$icon_size = self::get_initial_responsive_props();
				if( isset( $attrs[ 'iconSize' ] ) ){
					$icon_size = self::get_dimension_props( 'font-size',
						$attrs[ 'iconSize' ]
					);
				}

				$desktop_css = [
					[
						'selector' => '.wc-booster-wishlist-menu i',
						'props' => array_merge(
							[ 
								'color' => 'color'
							], 
							$icon_size[ 'desktop' ],
						)
					]

				];

				self::add_styles( array(
					'attrs' => $attrs,
					'css'   => $desktop_css,
				));
			}
		}

		public function render( $attrs, $content, $block ) {
		    $this->settings = WC_Booster_Settings::get_instance();
		    $page_id =$this->settings->get_field( 'wishlist_page_id' );
		    
		    $wishlist_instance = WC_Booster_Wishlist::get_instance();
		    $wishlisted_items  = $wishlist_instance->get_wishlist();
		    $wishlist_count	   = count( $wishlisted_items) ;
		    ob_start();
		    ?>

		        <div class="wc-booster-wishlist-item-wrapper" id="<?php echo  esc_attr( $attrs[ 'block_id' ] ); ?>">
		            <a href="<?php echo esc_url( get_permalink( $page_id ) ); ?>" class="wc-booster-wishlist-menu">
		                <i class="fa-regular fa-heart" aria-hidden="true"></i>
		            </a>
		            <?php if ( $attrs[ 'enableCount' ]  ): ?>
		            	<span class="wishlist-count"><?php echo esc_html( $wishlist_count ); ?> </span>
		            <?php endif; ?>
		        </div>

		    <?php
		    return ob_get_clean();
		}

	}

	WC_Booster_Wish_List_Item_Block::get_instance();
}