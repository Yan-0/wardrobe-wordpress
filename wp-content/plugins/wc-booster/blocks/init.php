<?php
/**
 * WC Booster Blocks
 * 
 * @since WC Booster 1.3
 */
if( !class_exists( 'WC_Booster_Blocks_Init' ) ){

    class WC_Booster_Blocks_Init extends WC_Booster_Helper{

        protected static $instance;
        
        public static function get_instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

    	public function __construct(){
            add_action( 'enqueue_block_assets', [ $this, 'block_assets' ] );
            add_action( 'rest_api_init', [ $this, 'register_rest_fields' ] );
    		add_filter( 'block_categories_all', [ $this, 'register_category' ], 9999, 1 );
            add_action( 'plugins_loaded', [ $this, 'include_files' ] );
    	}

        public function register_category( $categories ){
            # setup category array
            $category = [
                'slug' => 'wc-booster',
                'title' => __( 'WC Booster', 'wc-booster' ),
            ];

            # make a new category array and insert ours at position 1
            $new_categories = [];
            $new_categories[0] = $category;

            # rebuild cats array
            foreach ($categories as $category) {
                $new_categories[] = $category;
            }

            return $new_categories;
        }

        public function include_files(){

            require_once WC_Booster_Path . "blocks/base-block.php";
            
            $dir = WC_Booster_Path . "blocks/class/*.php";
            $dirs = array_filter( glob( $dir ), 'is_file' );
            foreach( $dirs as $dir ){
                require_once $dir;
            }
        }

        public function get_product_categories( $object, $field_name, $request ){

            $product_id = $object['id'];
            $categories = wp_get_post_terms( $product_id, 'product_cat' );
            $categories_data = [];
            
            if( !empty( $categories ) ){
                foreach( $categories as $category ){
                    $categories_data[] = [
                        'id'   => $category->term_id,
                        'name' => $category->name,
                    ];
                }
            }

            return $categories_data;
        }


        public function get_product_price($object, $field_name, $request) {

            $product_id = $object['id'];
            $product = wc_get_product($product_id);

            if ($product) {
                return $product->get_price_html(); 
            }

            return null;
        }

        public function register_rest_fields(){
            register_rest_field(
                'product',
                'wc_booster_product_categories',
                [
                    'get_callback' => [ $this, 'get_product_categories' ],
                    'update_callback' => null,
                    'schema' => null,
                ]
            );

            register_rest_field(
                'product',
                'wc_booster_product_price',
                [
                    'get_callback' => [ $this, 'get_product_price' ],
                    'update_callback' => null,
                    'schema' => null,
                ]
            );
        }

        /**
         * Register Style for banckend and frontend
         * dependencies { wp-editor }
         * @access public
         * @return void
         * @since 1.0.0
         */
        public function block_assets(){

            if (is_admin()) {
                $scripts = array(
                    array(
                        'handler' => 'wc-booster-fonts',
                        'style' => 'https://fonts.googleapis.com/css?family=' . join('|', self::get_fonts()) . '&display=swap',
                        'absolute' => true,
                        'minified' => true,
                    ),
                );

                self::enqueue($scripts);

                $size = get_intermediate_image_sizes();
                $size[] = 'full';

                $l10n = apply_filters('wc_booster_l10n', array(
                    'image_size' => $size,
                    'plugin_path' => self::get_plugin_directory_uri(),
                ));

                wp_localize_script('wc-booster-carousel-product-editor-script', 'WC_Booster_VAR', $l10n);
            }
        }
    }

    WC_Booster_Blocks_Init::get_instance();
}