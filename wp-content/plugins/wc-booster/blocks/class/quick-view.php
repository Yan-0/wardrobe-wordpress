<?php 
if( !class_exists( 'WC_Booster_Quick_View_Block' ) ){

	class WC_Booster_Quick_View_Block extends WC_Booster_Base_Block{

		public $slug = 'quick-view';

	    protected static $instance;

	    protected static $attributes;
	    
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

			foreach( self::$devices as $device ){

				$styles = [
					[
						'selector' => '',
						'props' => array_merge( $top[ $device ], $right[ $device ] )
					]
				];

				self::add_styles([
					'attrs' => $attrs,
					'wrapper_selector' => '.wc-booster-quick-view',
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
				'wrapper_selector' => '.wc-booster-quick-view',
				'css'   => $desktop_css,
			));
		}

		public function render( $attrs, $content, $block ){
		    
		    global $product;
		    // save attributes to print styles from prepare_scripts function
			self::$attributes = $attrs;

		    $post_id = isset( $block->context[ 'postId' ] ) ? $block->context[ 'postId' ] : '';
		    $product = wc_get_product( $post_id );
		   
	        ob_start();
	        WC_Booster_Quick_View::get_instance()->render();
	        return ob_get_clean();
		    
		}
	}

	WC_Booster_Quick_View_Block::get_instance();
}