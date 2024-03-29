<?php
/*
* Template Name: Order Page Template
*/

defined( 'ABSPATH' ) || exit;

global $woocommerce, $user_id;  

if (!class_exists('WooCommerce') || !get_current_user_id()) {
    return;
};

$user_id = get_current_user_id();

//$customer = wp_get_current_user();
$posts_per_page = 20; 
// Get all customer orders
$customer__all_orders = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
    'numberposts' => -1,
    'meta_key' => '_customer_user',
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_value' => $user_id ,
    'post_type' => wc_get_order_types(),
    'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
)));
$paged = isset($_REQUEST['order_page']) ? $_REQUEST['order_page'] : 1;
$total_records = count($customer__all_orders);
$total_pages = ceil($total_records / $posts_per_page);


$customer_orders = get_posts(array(
    'meta_key' => '_customer_user',
    'order' => 'DESC',
    'meta_value' => $user_id ,
    'post_type' => wc_get_order_types(),
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
));
?>


<?php if (!empty($customer_orders)) : ?>

    <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
        <thead>
            <tr>
                <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                    <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ( $customer_orders as $customer_order ) {
                $order      = wc_get_order( $customer_order ); 
                $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                ?>
                <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
                    <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                        <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                            <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                                <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                            <?php elseif ( 'order-number' === $column_id ) : ?>
                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                                    <?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
                                </a>

                            <?php elseif ( 'order-date' === $column_id ) : ?>
                                <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

                            <?php elseif ( 'order-status' === $column_id ) : ?>
                                <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

                            <?php elseif ( 'order-total' === $column_id ) : ?>
                                <?php
                                /* translators: 1: formatted order total 2: total order items */
                                echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
                                ?>

                            <?php elseif ( 'order-actions' === $column_id ) : ?>
                                <?php
                                $actions = wc_get_account_orders_actions( $order );

                                if ( ! empty( $actions ) ) {
                                    foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                        echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                                    }
                                }
                                ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="pagination">
    <?php
        $args = array(
            'base' => '%_%',
            'format' => '?order_page=%#%',
            'total' => $total_pages,
            'current' => $paged,
            'show_all' => False,
            'end_size' => 5,
            'mid_size' => 5,
            'prev_next' => True,
            'prev_text' => __('&laquo; Previous'),
            'next_text' => __('Next &raquo;'),
            'type' => 'plain',
            'add_args' => False,
            'add_fragment' => ''
        );
        echo paginate_links($args);
    ?>
</div>
<?php else : ?>
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
        <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
        <?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
    </div>
<?php endif; ?>