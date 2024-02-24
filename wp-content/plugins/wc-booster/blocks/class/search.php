<?php
if( !class_exists( 'WC_Booster_Search_Block' ) ){

	class WC_Booster_Search_Block extends WC_Booster_Base_Block{

		public $slug = 'search';

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
			
				$padding = self::get_initial_responsive_props();
				if( isset( $attrs[ 'padding' ] ) ){
					$padding = self::get_dimension_props( [ 'padding-right', 'padding-left' ],
						$attrs[ 'padding' ]
					);
				}

				$margin = self::get_initial_responsive_props();
				if( isset( $attrs[ 'margin' ] ) ){
					$margin = self::get_dimension_props( 'margin',
						$attrs[ 'margin' ]
					);
				}

				$icon_size = self::get_initial_responsive_props();
				if( isset( $attrs[ 'iconSize' ] ) ){
					$icon_size = self::get_dimension_props( 'font-size',
						$attrs[ 'iconSize' ]
					);
				}

				$font_size = self::get_initial_responsive_props();
				if( isset( $attrs[ 'fontSize' ] ) ){
					$font_size = self::get_dimension_props( 'font-size',
						$attrs[ 'fontSize' ]
					);
				}

				$height = $line_height = self::get_initial_responsive_props();
				if( isset( $attrs[ 'height' ] ) ){
					$height = self::get_dimension_props( 'height',
						$attrs[ 'height' ]
					);
					$line_height = self::get_dimension_props( 'line-height',
						$attrs[ 'height' ]
					);
				}

				foreach( self::$devices as $device ){

					$styles = [
						[
							'selector' => '',
							'props' => array_merge( $margin[ $device ] )
						],
						[
							'selector' => 'form.wc-booster-search-form .select2-container--default .select2-selection--single .select2-selection__arrow',
							'props' => $icon_size[ $device ]
						],
						[
							'selector' => '.select2-container--default .select2-selection--single .select2-selection__placeholder',
							'props' => $font_size[ $device ]
						],
						[
							'selector' => [ '.wc-booster-search-form .select2-container', 'form.wc-booster-search-form .select2-container--default .select2-selection--single', '.select2-container--default .select2-selection--single .select2-selection__placeholder', 'form.wc-booster-search-form .select2-container--default .select2-selection--single .select2-selection__arrow' ],
							'props' => $height[ $device ]
						],
						[
							'selector' => 'form.wc-booster-search-form .select2-container--default .select2-selection--single .select2-selection__rendered',
							'props' => array_merge( $line_height[ $device ], $padding[ $device ] )
						],
						[
							'selector' => 'form.wc-booster-search-form .select2-container--default .select2-selection--single .select2-selection__arrow',
							'props' => $padding[ $device ]
						]
					];

					self::add_styles([
						'attrs' => $attrs,
						'css'   => $styles,
					], $device );
				}

				$desktop_css = [
					[
						'selector' => 'form.wc-booster-search-form .select2-container--default .select2-selection--single .select2-selection__arrow',
						'props' => array_merge(
							[ 
								'color' => 'color',
								'background-color' => 'bgColor',
								'border-left' => [
									'unit' => 'px',
									'value' => 0
								]
							]
						)
					],
					[
						'selector' => 'form.wc-booster-search-form .select2-container--default .select2-selection--single .select2-selection__arrow:after',
						'props' => [
							'color' => 'color'
						]
					]
				];

				self::add_styles( array(
					'attrs' => $attrs,
					'css'   => $desktop_css,
				));
			}
		}

		public function render( $attrs, $content, $block ){
		   
		    $post_id = isset( $block->context[ 'postId' ] ) ? $block->context[ 'postId' ] : '';
		   
	        ob_start();
	       	?>
	       	<div id="<?php echo esc_attr( $attrs[ 'block_id' ] ); ?>">
	       		<form class="wc-booster-search-form">
	    			<select data-placeholder="<?php echo esc_attr( $attrs[ 'placeholder' ] ); ?>" class="wc-booster-product" name="wc_booster_product"></select>
	    		</form>
	       	</div>
	       	<?php
	        return ob_get_clean();
		    
		}
	}

	WC_Booster_Search_Block::get_instance();
}