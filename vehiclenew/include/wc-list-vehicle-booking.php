<?php
/*
 * WP List Table
*/
if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class wp_custom_list_table extends \WP_List_Table {

    function __construct() {
        parent::__construct( array(
            'singular' => 'Booking Request',
            'plural'   => 'Booking Requests',
            'ajax'     => false
        ) );
    }

    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items() {
        _e( 'No Booking Request Found', 'ancineha' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'first_name':
                return $item->first_name;

            case 'last_name':
                return $item->last_name;

            case 'email':
                return $item->email;

            case 'phone':
                return $item->phone;

            case 'vehicle_type':
                     $term = get_term_by( 'id', $item->vehicle_type, 'vehicle_type' ); 
                return $term->name;

            case 'vehicle':
                   $post   = get_post( $item->vehicle );
                return $post->post_title;

            case 'vehicle_price':
                return $item->vehicle_price;

            case 'status':
                 if($item->status == 0){
                    $status = 'Pending';
                 }else{
                    $status = 'Published';
                 }
                return $status;

            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'first_name'      => __( 'First Name', 'corpus' ),
            'last_name'      => __( 'Last Name', 'corpus' ),
            'email'      => __( 'Email', 'corpus' ),
            'phone'      => __( 'Phone', 'corpus' ),
            'vehicle_type'      => __( 'Vehicle Type', 'corpus' ),
            'vehicle'      => __( 'Vehicle', 'corpus' ),
            'vehicle_price'      => __( 'Vehicle Price', 'corpus' ),
            'status'      => __( 'Status', 'corpus' ),

        );

        return $columns;
    }

    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_name( $item ) {

        $actions           = array();
        $actions['edit']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=orders-page&action=edit&id=' . $item->id ), $item->id, __( 'Edit this item', 'corpus' ), __( 'Edit', 'corpus' ) );
        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=orders-page&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'corpus' ), __( 'Delete', 'corpus' ) );

        return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=orders-page&action=view&id=' . $item->id ), $item->name, $this->row_actions( $actions ) );
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'first_name' => array( 'first_name', true ),
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Move to Trash', 'corpus' ),
        );
        return $actions;
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%d" />', $item->id
        );
    }

    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        $status_links   = array();
        $base_link      = admin_url( 'admin.php?page=sample-page' );

        foreach ($this->counts as $key => $value) {
            $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
            $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
        }

        return $status_links;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {

        $columns               = $this->get_columns();
        $hidden                = array( );
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 10;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

        $this->items  = orders_get_all_Order( $args );

        $this->set_pagination_args( array(
            'total_items' => orders_get_Order_count(),
            'per_page'    => $per_page
        ) );
    }
}
/**
 * Get all Order
 *
 * @param $args array
 *
 * @return array
 */
function orders_get_all_Order( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'first_name',
        'order'      => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'Order-all';
    $items     = wp_cache_get( $cache_key, 'corpus' );

    if ( false === $items ) {
        if(isset($_POST['s'])){
            /*echo 'SELECT * FROM `wp_orders` WHERE `name` LIKE "%'.$_POST['s'].'%"  ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] ;*/
         $items = $wpdb->get_results( 'SELECT * FROM `wp_vehicle_booking` WHERE `first_name` LIKE "%'.$_POST['s'].'%"  ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );
        }else{
             $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'vehicle_booking ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );
        }
        wp_cache_set( $cache_key, $items, 'corpus' );
    }

    return $items;
}

/**
 * Fetch all Order from database
 *
 * @return array
 */
function orders_get_Order_count() {
    global $wpdb;
    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'vehicle_booking' );
}

/**
 * Fetch a single Order from database
 *
 * @param int   $id
 *
 * @return array
 */
function orders_get_Order( $id = 0 ) {
    global $wpdb;
    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'vehicle_booking WHERE id = %d', $id ) );
}

// Render your admin menu outside the class
function WBU_adminMenu()
{
    add_menu_page( 'Booking Request', 'Booking Request', 'edit_theme_options', 'render_admin_page', 'render_admin_page');
  //  add_submenu_page( 'render_admin_page', 'Import Album', 'Import Albums',    'manage_options', 'import_albums_plugin');
  //  add_submenu_page( 'render_admin_page', 'Import Album', 'Import Albums', 'manage_options', 'import-album_page', 'import_albums_plugin');

}
// Create your menu outside the class
add_action('admin_menu','WBU_adminMenu');
function render_admin_page(){
?>
<div class="wrap">
    <h2><?php _e( 'Booking Request', 'corpus' ); ?> <a href="<?php echo admin_url( 'admin.php?page=orders-page&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'corpus' ); ?></a></h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $list_table = new wp_custom_list_table();
        $list_table->prepare_items();
        $list_table->search_box( 'search', 'search_id' );
        $list_table->display();
        ?>
    </form>
</div>
<?php }  //END render_admin_page