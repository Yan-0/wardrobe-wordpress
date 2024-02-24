<?php
/**
 * Helper class for overall plugin
 *
 * @since 1.0.0
 * @package WC Booster
 */

if( !class_exists( 'WC_Booster_Helper' ) ):
	class WC_Booster_Helper{

		/**
		 * Plugin fonts | Google Fonts
		 *
		 * @var array
		 */
		protected static $default_fonts = array(
			'Alegreya Sans'    => 'Alegreya+Sans:300,400,500,700,800,900',
			'Fjalla One'       => 'Fjalla+One',
			'Lato'             => 'Lato:300,400,700,900',
			'Montserrat'       => 'Montserrat:300,400,500,600,700,900',
			'Open Sans'        => 'Open+Sans:300,400,600,700,800',
			'Oswald'           => 'Oswald:300,400,500,600,700',
			'PT Sans Narrow'   => 'PT+Sans+Narrow',
			'Playfair Display' => 'Playfair+Display:400,700,900',
			'Raleway'          => 'Raleway:300,400,500,600,700,800,900',
			'Roboto'           => 'Roboto:300,400,500,700,900',
		);

		/**
		 * retrieve fonts
		 * @since 1.0.8
		 */
		public static function get_fonts(){
			return apply_filters( 'wc-booster-default-fonts', self::$default_fonts );
		}

		/**
		 * Retrives plugin directory uri
		 *
		 * @since 1.0.0
		 */
		public static function get_plugin_directory_uri(){
			return esc_url( plugins_url( '/', WC_Booster_File ) );
		}

				/**
		 * Enqueue scripts or styles
		 *
		 * @since 1.0.0
		 */
		public static function enqueue( $scripts, $uri=false ){

		    # Do not enqueue anything if no array is supplied.
		    if( ! is_array( $scripts ) ) return;

		    if(! $uri){
		    	$uri = self::get_plugin_directory_uri();
		    }
		    
		    foreach ( $scripts as $script ) {

		        # Do not try to enqueue anything if handler is not supplied.
		        if( ! isset( $script[ 'handler' ] ) )
		            continue;

		        $version = null;
		        if( isset( $script[ 'version' ] ) ){
		            $version = $script[ 'version' ];
		        }

		        $minified = isset( $script[ 'minified' ] ) ? $script[ 'minified' ] : true;
		        # Enqueue each vendor's style
		        if( isset( $script[ 'style' ] ) ){

					$path = $uri .  $script[ 'style' ];
					if( isset( $script[ 'absolute' ] ) ){
		                $path = $script[ 'style' ];
		            }
		           
		            $dependency = array();
		            if( isset( $script[ 'dependency' ] ) ){
		                $dependency = $script[ 'dependency' ];
		            }

	            	if( $minified ){
	            		$path = str_replace( '.css', '.min.css', $path );
	            	}
	           
		            wp_enqueue_style( $script[ 'handler' ], $path, $dependency, $version );
		        }

		        # Enqueue each vendor's script
		        if( isset( $script[ 'script' ] ) ){

		        	if( $script[ 'script' ] === true || $script[ 'script' ] === 1 ){
		        		wp_enqueue_script( $script[ 'handler' ] );
		        	}else{

			            $prefix = 'wc-booster';

			        	$path = '';
			        	if( isset( $script[ 'script' ] ) ){
							$path = $uri .  $script[ 'script' ];
			        	}

			            if( isset( $script[ 'absolute' ] ) ){
			                $path = $script[ 'script' ];
			            }

			            $dependency = array( 'jquery' );
			            if( isset( $script[ 'dependency' ] ) ){
			                $dependency = $script[ 'dependency' ];
			            }

			            $in_footer = true;

			            if( isset( $script[ 'in_footer' ] ) ){
			            	$in_footer = $script[ 'in_footer' ];
			            }

			            if( $minified ){
			            	$path = str_replace( '.js', '.min.js', $path );
			            }
			            wp_enqueue_script( $prefix . $script[ 'handler' ], $path, $dependency, $version, $in_footer );
		        	}
		        }
		    }
		}
	}
endif;