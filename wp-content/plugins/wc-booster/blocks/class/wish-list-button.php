<?php 
if( !class_exists( 'WC_Booster_Wish_List_Button_Block' ) ){

	class WC_Booster_Wish_List_Button_Block extends WC_Booster_Base_Block{

		public $slug = 'wish-list-button';

	    protected static $instance;
	    protected static $attributes;
	    
		public $settings;
	    	    
	    public static function get_instance() {
	        if ( null === self::$instance ) {
	            self::$instance = new self();
	        }
	        return self::$instance;
	    }

		public function prepare_scripts(){ 

			$attrs = self::get_attrs_with_default( self::$attributes );
			$attrs[ 'block_id' ] = '';

			$right = $top = $iconSize = self::get_initial_responsive_props();
			if( isset( $attrs[ 'right' ] ) ){
				$right = self::get_dimension_props( 'right',
					$attrs[ 'right' ]
				);
			}

			if( isset( $attrs[ 'top' ] ) ){
				$top = self::get_dimension_props( 'top',
					$attrs[ 'top' ]
				);
			}

			if( isset( $attrs[ 'iconSize' ] ) ){
				$iconSize = self::get_dimension_props( 'font-size',
					$attrs[ 'iconSize' ]
				);
			}

			foreach( self::$devices as $device ){

				$styles = [
					[
						'selector' => '',
						'props' => array_merge( $top[ $device ], $right[ $device ] )
					],
					[
						'selector' => '.wc-booster-wishlist-button',
						'props' => $iconSize[ $device ]
					]
				];

				self::add_styles([
					'attrs' => $attrs,
					'wrapper_selector' => '.wc-booster-wishlist-button-wrapper',
					'css'   => $styles,
				], $device );
			}

			$desktop_css = [
				[
					'selector' => '',
					'props' => array_merge(
						[ 
							'position' => 'iconPosition',
						]
					)
				]
			];

			self::add_styles( array(
				'attrs' => $attrs,
				'wrapper_selector' => '.wc-booster-wishlist-button-wrapper',
				'css'   => $desktop_css,
			));
		}

		public function render( $attrs, $content, $block ) {

			// save attributes to print styles from prepare_scripts function
			self::$attributes = $attrs;
			
			$this->settings = WC_Booster_Settings::get_instance();
		    $page_id =$this->settings->get_field( 'wishlist_page_id' );
		    $product_id =  is_product()  ? wc_get_product()->get_id() : '' ;

		    $post_id = $block->context[ 'postId' ] ?? $product_id;
		    $post_type = get_post_type( $post_id );

		    if ( empty( $post_id ) || $post_type != 'product' ) {
		    	return;
		    }

		    $product = wc_get_product( $post_id );
		    $wishlist = [];

		    if( is_user_logged_in() ){
		        $user_id = get_current_user_id();
		        $wishlist = get_user_meta( $user_id, 'wc_booster_wishlist', true );
		    } elseif ( isset( $_COOKIE[ 'wc_booster_wishlist' ] ) ) {
		        $wishlist = unserialize( stripslashes( $_COOKIE[ 'wc_booster_wishlist' ] ), [ 'allowed_classes' => false ] );
		    }

		    $class = '';
		    $class = $wishlist_count = 0 ;
		    if( $wishlist ){
		    	$wishlist_count = count( $wishlist );
		    	foreach( $wishlist as $list ) {
			        if( $list[ 'product_id' ] == $post_id ){
			            $class = 'added';
			            break; 
			        }
			    }
		    }

		    ob_start();
		    ?>
				<div class="wc-booster-wishlist-button-wrapper">
		            <button data-item_id="<?php echo esc_attr( $product->get_id() ) ?>" data-count="<?php echo esc_attr( $wishlist_count ) ?>" class="wc-booster-wishlist-button <?php echo esc_attr( $class ) ?>" data-url="<?php echo esc_url( get_permalink( $page_id ) ); ?>">
		                <i class="fa-regular fa-heart " aria-hidden="true"></i>
		            </button>
		        </div>
		    <?php
		    return ob_get_clean();
		}
	}
	WC_Booster_Wish_List_Button_Block::get_instance();
}