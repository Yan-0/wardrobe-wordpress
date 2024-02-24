<?php 
if( !class_exists( 'WC_Booster_Carousel_Product_Block' ) ){

	class WC_Booster_Carousel_Product_Block extends WC_Booster_Base_Block{

		public $slug = 'carousel-product';

	    protected static $instance;

        public static function get_instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function prepare_scripts(){

      	    if( count( $this->blocks ) > 0 ){

      	    	wp_enqueue_script( 'slick', WC_Booster_Url . 'assets/vendors/slick/slick.min.js', [ 'jquery' ], '1.8.1' );
      	    	wp_enqueue_style( 'slick', WC_Booster_Url . 'assets/vendors/slick/slick.min.css' );

	    		foreach( $this->blocks as $block ){

	    			$attrs = self::get_attrs_with_default( $block[ 'attrs' ] );
	    			
	    			# Typography for title, content on mobile
	    			$heading_typo = self::get_initial_responsive_props();
	    			if( isset( $attrs[ 'headingTypo' ] ) ){
	    				$heading_typo = self::get_typography_props(  $attrs[ 'headingTypo' ] );
	    			}

	    			$title_typo = self::get_initial_responsive_props();
	    			if( isset( $attrs[ 'titleTypo' ] ) ){
	    				$title_typo = self::get_typography_props(  $attrs[ 'titleTypo' ] );
	    			}

	    			$meta_typo = self::get_initial_responsive_props();
	    			if( isset( $attrs[ 'metaTypo' ] ) ){	
	    				$meta_typo = self::get_typography_props(  $attrs[ 'metaTypo' ] );
	    			}

	    			$price_typo = self::get_initial_responsive_props();
	    			if( isset( $attrs[ 'priceTypo' ] ) ){	
	    				$price_typo = self::get_typography_props(  $attrs[ 'priceTypo' ] );
	    			}

	    			$padding = self::get_dimension_props( 'padding', $attrs[ 'padding' ] );
	    			
	    			$productMargin = self::get_dimension_props( 'margin', $attrs[ 'productMargin' ] );

	    			foreach( [ 'mobile', 'tablet', 'desktop' ] as $device ){
	    				$css = array(
	    					array(
	    						'selector' => '.wc-booster-carousel-product-title',
	    						'props'    => $heading_typo[ $device ]
	    					),
	    					array(
	    						'selector' => '.wc-booster-carousel-product-post-title a',
	    						'props'    => $title_typo[ $device ]
	    					),
	    					array(
	    						'selector' => '.meta-content a',
	    						'props'    => $meta_typo[ $device ]
	    					),
	    					array(
	    						'selector' => '.wc-booster-carousel-product-price',
	    						'props'    => $price_typo[ $device ]
	    					),
	    					array(
	    						'selector' => '',
	    						'props' => $padding[ $device ],
	    					),
	    					array(
	    						'selector' => '.wc-booster-carousel-product-inner-wrapper',
	    						'props' => $productMargin[ $device ],
	    					)
	    				);

	    				self::add_styles( array(
	    					'attrs' => $attrs,
	    					'css'   => $css,
	    				), $device );
	    			}

	    			$dynamic_css = array(
	    				array(
	    					'selector' => '.wc-booster-carousel-product-meta-wrapper a',
	    					'props' => array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-carousel-product-inner-wrapper',
	    					'props' => array(
	    						'background-color' => 'productBgColor'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-carousel-product-post-title a',
	    					'props'    =>  array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-carousel-product-title',
	    					'props'    =>  array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-carousel-product-body-inner del',
	    					'props'    =>  array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-carousel-product-body-inner ins',
	    					'props'    =>  array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-carousel-product-post-title:hover a',
	    					'props'    => array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '.meta-content a',
	    					'props'    => array(
	    						'color' => 'color'
	    					)
	    				),
	    				array(
	    					'selector' => '',
	    					'props'    => array(
	    						'background-color' => 'bgColor'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-slider-arrow',
	    					'props'    => array(
	    						'background-color' => 'arrowBgColor'
	    					)
	    				),
	    				array(
	    					'selector' => '.wc-booster-slider-arrow',
	    					'props'    => array(
	    						'color' => 'arrowColor'
	    					)
	    				)
	    			);

	    			self::add_styles( array(
	    				'attrs' => $attrs,
	    				'css'   => $dynamic_css,
	    			));

	    			$query = $this->get_query( $attrs );
	    			$slidesToShow = $query->post_count > $attrs[ 'slidesToShow' ] ? $attrs[ 'slidesToShow' ] : $query->post_count;
	    			ob_start();
	    			?>
	    			
	    			var params = {
	    				slidesToShow: <?php echo esc_attr( $slidesToShow ) ?>,
	    				slidesToScroll: 1,
	    				autoplay: false,
	    				infinite: true,
	    				arrows: true,
	    				dots: false,
	    				prevArrow: '<button type="button" class="wc-booster-prev-arrow wc-booster-slider-arrow"><i class="fa fa-angle-left"></i></button>',
	    				nextArrow: '<button type="button" class="wc-booster-next-arrow wc-booster-slider-arrow"><i class="fa fa-angle-right"></i></button>',
	    				responsive: [
	    					{
	    						breakpoint: 767,
	    						settings: {
	    							slidesToShow: 1
	    						}
	    					}
	    				]
	    			};
	    			jQuery('#<?php echo esc_attr( $attrs[ 'block_id' ] ); ?> .wc-booster-carousel-product-init').slick( params );

	    			<?php
	    			$js = ob_get_clean();
	    			self::add_scripts( $js );
	    		}
      	    }
    	}

    	public function get_query( $attrs ){

    		$args = array(
    			'post_type'   => 'product',
    			'post_status' => 'publish',
    			'ignore_sticky_posts' => true,
    			'posts_per_page' => $attrs[ 'postsToShow' ],
    			'order' => $attrs[ 'order' ],
    			'orderby' => $attrs[ 'orderBy' ],
    			'tax_query' => array(),
    		);
    		
    		if( isset( $attrs[ 'categories' ] ) && ! empty( $attrs[ 'categories' ] ) ){
    			$args['tax_query'][] = array(
    				'taxonomy' => 'product_cat',
    				'field'    => 'id',
    				'terms'    => $attrs[ 'categories' ],
    			);
    		}
    		
    		$query = new WP_Query( $args );

    		return $query;
    	}

    		   /**
		* Returns attributes for this Block
		*
		* @access public
		* @since 1.0.0
		* @return array
		*/
		protected function get_attrs(){
			return array(

				# Hidden setting
				'block_id' => array(
					'type' => 'string',
				),
				'title' => array(
					'type' => 'string',
					'default' => 'Carousel Product'
 				),
				'alignment' => array( 
					'type' => 'string',
					'default' => 'center'
				),
				# Post Setting
				'postsToShow'     => array(
					'type'    => 'number',
					'default' => 5,
				),
				'order'           => array(
					'type'    => 'string',
					'default' => 'desc',
				),
				'orderBy'         => array(
					'type'    => 'string',
					'default' => 'date',
				),
				'categories' => array(
					'type' => 'string',
				),
				'headingTypo' => array(
					'type' => 'object',
					'default' => array(
						'fontFamily' => 'Roboto',
						'fontSize'   => array(
							'units' => array( 'px', 'em', 'rem' ),
							'activeUnit' => 'px',
							'values' => array(
								'desktop' => 20,
								'tablet'  => 20,
								'mobile'  => 20
							)
						),
						'textTransform' => 'uppercase',
						'fontWeight' => 700,
						'lineHeight' => array(
							'activeUnit' => '',
							'units'      => array( '' ),
							'values'     => array(
								'desktop' => '1.2',
								'tablet'  => '1.2',
								'mobile'  => '1.2'
							)
						)
					)
				),
				'titleTypo' => array(
					'type' => 'object',
					'default' => array(
						'fontFamily' => 'Roboto',
						'fontSize'   => array(
							'units' => array( 'px', 'em', 'rem' ),
							'activeUnit' => 'px',
							'values' => array(
								'desktop' => 20,
								'tablet'  => 20,
								'mobile'  => 20
							)
						),
						'fontWeight' => 500,
						'lineHeight' => array(
							'activeUnit' => 'px',
							'units'      => array( 'px' ),
							'values'     => array(
								'desktop' => '28',
								'tablet'  => '28',
								'mobile'  => '28'
							)
						)
					)
				),
				'metaTypo' => array(
					'type' => 'object',
					'default' => array(
						'fontFamily' => 'Roboto',
						'fontSize'   => array(
							'units' => array( 'px', 'em', 'rem' ),
							'activeUnit' => 'px',
							'values' => array(
								'desktop' => 12,
								'tablet'  => 12,
								'mobile'  => 12
							)
						),
						'fontWeight' => 400,
						'lineHeight' => array(
							'activeUnit' => '',
							'units'      => array( '' ),
							'values'     => array(
								'desktop' => '1.2',
								'tablet'  => '1.2',
								'mobile'  => '1.2'
							)
						)
					)
				),
				'priceTypo' => array(
					'type' => 'object',
					'default' => array(
						'fontFamily' => 'Roboto',
						'fontSize'   => array(
							'units' => array( 'px', 'em', 'rem' ),
							'activeUnit' => 'px',
							'values' => array(
								'desktop' => 17,
								'tablet'  => 17,
								'mobile'  => 17
							)
						),
						'fontWeight' => 400,
						'lineHeight' => array(
							'activeUnit' => '',
							'units'      => array( '' ),
							'values'     => array(
								'desktop' => '1.2',
								'tablet'  => '1.2',
								'mobile'  => '1.2'
							)
						)
					)
				),
				'padding' => array(
					'type' => 'object',
					'default' => array(
						'activeUnit'   => 'px',
						'isLinkActive' => true,
						'properties'   => array( 'top', 'right', 'bottom', 'left' ),
						'responsiveViews' => array( 'desktop', 'tablet', 'mobile' ),
						'units' => array( 'px', 'rem' ),
						'values' => array(
							'desktop' => array( 20, 20, 20, 20 ),
							'tablet' => array( 20, 20, 20, 20 ),
							'mobile' => array( 20, 20, 20, 20 ),
						)  
					)
				),
				'productMargin' => array(
					'type' => 'object',
					'default' => array(
						'activeUnit'   => 'px',
						'isLinkActive' => true,
						'properties'   => array( 'top', 'right', 'bottom', 'left' ),
						'responsiveViews' => array( 'desktop', 'tablet', 'mobile' ),
						'units' => array( 'px', 'rem' ),
						'values' => array(
							'desktop' => array( 0, 15, 0, 15 ),
							'tablet' => array( 0, 15, 0, 15 ),
							'mobile' => array( 0, 15, 0, 15 ),
						)  
					)
				),

				'enableTitle' => array(
					'type' => 'boolean',
					'default' => true
				),
				'enableCategory' => array(
					'type' => 'boolean',
					'default' => true
				),
				'enablePrice' => array(
					'type' => 'boolean',
					'default' => true
				),
				'imageSize' => array(
					'type'    => 'string',
					'default' => 'full'
				),
				'color' => array(
					'type'    => 'string',
					'default' => '#000000'
				),
				'productBgColor' => array(
					'type'    => 'string',
					'default' => '#ffffff'
				),
				'bgColor' => array(
					'type'    => 'string',
					'default' => '#ffffff'
				),
				'arrowBgColor' => array(
					'type'    => 'string',
					'default' => '#000000'
				),
				'arrowColor' => array(
					'type'    => 'string',
					'default' => '#ffffff'
				),
				'slidesToShow' => array(
					'type' => 'number',
					'default' => 3
				)
			);
		}

    	public static function make_category_arr( $_cat ){
    		$cat = false;
    		if( $_cat ){
    			$cat = array(
    				'name' => $_cat->name,
    				'link' => get_category_link( $_cat->term_id )
    			);
    		}

    		return $cat;
    	}

		public function render( $attrs, $content ){
			
			$block_content = '';
			
			$query = $this->get_query( $attrs );

			if( $query->have_posts() ):
				ob_start();

				if( isset( $attrs[ 'categories' ] ) ){
					$_cat = get_term( absint( $attrs['categories'] ), 'product_cat' );
					$cat = self::make_category_arr( $_cat );
					
				}
				
				$section_cls = 'wc-booster-carousel-product-wrapper wc-booster-align-' . esc_attr( $attrs[ 'alignment' ] );
				
				?>
				<section id="<?php echo esc_attr( $attrs[ 'block_id' ] ); ?>" class="<?php echo esc_attr( $section_cls ); ?>">
					<div class="wc-booster-carousel-product-overlay"></div>
					<h2 class="wc-booster-carousel-product-title"><?php echo esc_html( $attrs[ 'title' ] ); ?></h2>
					<div class="wc-booster-carousel-product-init">
				    <?php while( $query->have_posts() ): 
				    	$query->the_post(); 

				    	$id  = get_the_ID();
				    	$src = '';

				    	$product = wc_get_product( $id );

				    	if( !isset( $attrs[ 'categories' ] ) ){
				    		$_cat = get_the_terms( $id, 'product_cat' );
				    		$cat = self::make_category_arr( $_cat[ 0 ] );
				    	}

			            if( has_post_thumbnail( ) ){
			            	$src = get_the_post_thumbnail_url( $id, $attrs[ 'imageSize' ] );
			            }

				    ?>
			            <div class="wc-booster-carousel-product-card-wrapper">
				            <div class="wc-booster-carousel-product-inner-wrapper">
				            	<div>
				            	    <div 
				            	    	style="background-image: url(<?php echo esc_url( $src ); ?>)" 
				            	    	class="wc-booster-carousel-product-card-image">                                      
				            	    </div>
				            	</div>

				                <div class="wc-booster-carousel-product-card">
				                    <div class="wc-booster-carousel-product-body">
				                    	<div class="wc-booster-carousel-product-body-inner">
				                    		<?php if( $attrs[ 'enableCategory' ] ): ?>
				                    	        <div class="wc-booster-carousel-product-post-cat meta-content">
				                    	            <?php if (!empty($cat)) : ?>
                                                <a href="<?php echo esc_url( $cat[ 'link' ] ); ?>">
                                                    <span><?php echo esc_html( $cat[ 'name' ] ); ?></span>
                                                </a>
                                            <?php endif; ?>
				                    	        </div>
				                    	    <?php endif; ?>

			                            	<?php if( $attrs[ 'enableTitle' ] ): ?>
			    	                            <h2 class="wc-booster-carousel-product-post-title">
			    	                                <a href="<?php the_permalink(); ?>">
			    	                                    <?php the_title(); ?>
			    	                                </a>
			    	                            </h2>
			                            	<?php endif; ?>
			                            	<?php if( $attrs[ 'enablePrice' ] ): ?>
				                            	<div class="wc-booster-carousel-product-price">
				                            		<?php echo $product->get_price_html(); ?>
				                            	</div>
				                    	    <?php endif; ?>
										<?php ?>
					                	</div>
				                    </div>
				                </div>
				            </div>
			            </div>                  
					    <?php endwhile; ?>   	
					</div>
				</section>
				<?php
				wp_reset_postdata();
				$block_content = ob_get_clean();
			endif; 

			return $block_content;
		}

	}

	WC_Booster_Carousel_Product_Block::get_instance();

}