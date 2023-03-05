<?php
if(!defined('UNAPPROVED')) define ('UNAPPROVED', 0);
if(!defined('APPROVED')) define ('APPROVED', 1);
if(!defined('CANCELLED')) define ('CANCELLED', 2);

if (!class_exists('WP_List_Table'))
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class WPOrders_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_list_order', //Singular label
            'plural' => 'wp_list_orders', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav($which) {
        if ($which == "top") {
            //The code that goes before the table is here
            //echo"Hello, I'm before the table";
        }
        if ($which == "bottom") {
            //The code that goes after the table is there
            //echo"Hi, I'm after the table";
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'col_orders_cb' => '<input type="checkbox" class="cb-orders-select-all" />',
            'col_orders_id' => __('ID', SHORT_NAME),
            'col_orders_customer_name' => __('Khách hàng', SHORT_NAME),
            'col_orders_amount' => __('Tổng tiền', SHORT_NAME),
            'col_orders_date' => __('Ngày', SHORT_NAME),
            'col_orders_options' => __('Tùy chọn', SHORT_NAME)
        );
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'col_orders_id' => array('ID', true),
            'col_orders_customer_name' => array('display_name', false),
            'col_orders_amount' => array('total_amount', false),
            'col_orders_date' => array('created_at', false),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        $screen = get_current_screen();
        $tblOrders = $wpdb->prefix . 'orders';

        $this->process_bulk_action();

        // Update status
        if (isset($_GET['action'])) {
            $act = $_GET['action'];
            $order_id = intval($_GET['order_id']);
            switch ($act) {
                case "approve":
                    $query = "UPDATE $tblOrders SET status = 1 WHERE ID = $order_id and status <> 1";
                    $wpdb->query($query);
                    break;
                case "unapprove":
                    $query = "UPDATE $tblOrders SET status = 0 WHERE ID = $order_id and status <> 0";
                    $wpdb->query($query);
                    break;
                case "cancel":
                    $query = "UPDATE $tblOrders SET status = 2 WHERE ID = $order_id and status <> 2";
                    $wpdb->query($query);
                    break;
                case "restore":
                    $query = "UPDATE $tblOrders SET status = 0 WHERE ID = $order_id and status <> 0";
                    $wpdb->query($query);
                    break;
                case "delete":
                    $query = "DELETE FROM $tblOrders WHERE ID = $order_id";
                    $wpdb->query($query);
                    break;
                default:
                    break;
            }
            header("location: ?page=nvt_orders");
            exit();
        }

        /* -- Preparing your query -- */
        $query = "SELECT $tblOrders.*, $wpdb->users.display_name FROM $tblOrders LEFT JOIN $wpdb->users ON $wpdb->users.ID = $tblOrders.customer_id ";

        $status = (isset($_GET['status'])) ? $_GET['status'] : null;
        if ($status == null) {
            $query .= "WHERE status = 0";
        } else if (in_array($status, array(APPROVED, CANCELLED))) {
            $query .= "WHERE status = $status";
        }
        // orders by product
        if(isset($_REQUEST['product_id']) and intval($_REQUEST['product_id']) > 0){
            $product_id = intval($_REQUEST['product_id']);
            if(strpos($query, "WHERE") !== FALSE){
//                $query .= " AND products REGEXP '.*{\"id\":\"$product_id\",.*'";
                $query .= " AND products REGEXP '.*{\"id\":$product_id,.*'";
            } else {
//                $query .= " WHERE products REGEXP '.*{\"id\":\"$product_id\",.*'";
                $query .= " WHERE products REGEXP '.*{\"id\":$product_id,.*'";
            }
        }
        
        // Search by keyword
        if(isset($_REQUEST['s']) and !empty($_REQUEST['s'])){
            $search_query = esc_sql($_REQUEST['s']);
            if(strpos($query, "WHERE") !== FALSE){
//                $query .= " AND customer_info REGEXP '.*{\"fullname\":\"$search_query\",.*'";
                $query .= " AND (customer_info LIKE '%$search_query%' OR ship_info LIKE '%$search_query%')";
            } else {
//                $query .= " WHERE customer_info REGEXP '.*{\"fullname\":\"$search_query\",.*'";
                $query .= " WHERE customer_info LIKE '%$search_query%' OR ship_info LIKE '%$search_query%'";
            }
        }

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ID';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 20;
        //Which page is this?
        //$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        $paged = $this->get_pagenum();
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if (!empty($records)) {
            foreach ($records as $rec) {

                //Open the line
                echo '<tr id="record_' . $rec->ID . '">';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;
                    
                    //links
                    $viewlink = '?page=nvt_orders&action=view-detail&order_id=' . (int) $rec->ID;
                    $approveLink = '?page=nvt_orders&action=approve&order_id=' . (int) $rec->ID;
                    $unapproveLink = '?page=nvt_orders&action=unapprove&order_id=' . (int) $rec->ID;
                    $cancelLink = '?page=nvt_orders&action=cancel&order_id=' . (int) $rec->ID;
                    $restoreLink = '?page=nvt_orders&action=restore&order_id=' . (int) $rec->ID;
                    $deleteLink = '?page=nvt_orders&action=delete&order_id=' . (int) $rec->ID;
//                    $printLink = get_page_link(get_option(SHORT_NAME . '_pagePrintOrdersID')) .'/?order_id=' . (int) $rec->ID;
                        
                    //Display the cell
                     
                    switch ($column_name) {
                        case "col_orders_cb": echo '<th ' . $attributes . '>' . $this->column_cb($rec) . '</th>';
                            break;
                        case "col_orders_id": echo '<td ' . $attributes . '>' . $rec->ID . '</td>';
                            break;
                        case "col_orders_customer_name": echo '<td ' . $attributes . '>' . ($rec->display_name ? $rec->display_name : "Guest") . '</td>';
                            break;
                        case "col_orders_amount": echo '<td ' . $attributes . '>' . number_format($rec->total_amount, 0, ',', '.') . ' đ</td>';
                            break;
                        case "col_orders_date": echo '<td ' . $attributes . '>' . $rec->created_at . '</td>';
                            break;
                        case "col_orders_options":
                            echo '<td ' . $attributes . '>';
//                            echo '<a href="' . $printLink . '" target="_blank">In hóa đơn</a> | ';
                            if ($rec->status == UNAPPROVED) {
                                echo '<a href="' . $viewlink . '">Xem</a> | <a href="' . $approveLink . '">Duyệt</a> | <a href="' . $cancelLink . '">Hủy</a>';
                            } else if ($rec->status == APPROVED) {
                                
                                echo '<a href="' . $viewlink . '">Xem</a> | <a href="' . $unapproveLink . '">Không duyệt</a> | <a href="' . $cancelLink . '">Hủy</a>';
                            } else if ($rec->status == CANCELLED) {
                                echo '<a href="' . $viewlink . '">Xem</a> | <a href="' . $approveLink . '">Duyệt</a> | <a href="' . $restoreLink . '">Phục hồi</a>| <a onclick="return confirm(\'Bạn có chắc chắn không?\')" href="' . $deleteLink . '">Xóa vĩnh viễn</a>';
                            }
                            echo '</td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }

    function get_bulk_actions() {
        $status = (isset($_GET['status'])) ? $_GET['status'] : UNAPPROVED;
        if ($status == APPROVED) {
            $actions = array(
                'unapprove' => __('Không duyệt', SHORT_NAME),
                'cancel' => __('Hủy', SHORT_NAME)
            );
        } elseif ($status == CANCELLED) {
            $actions = array(
                'approve' => __('Duyệt', SHORT_NAME),
                'restore' => __('Phục hồi', SHORT_NAME),
                'delete' => __('Xóa vĩnh viễn', SHORT_NAME)
            );
        } else {
            $actions = array(
                'approve' => __('Duyệt', SHORT_NAME),
                'cancel' => __('Hủy', SHORT_NAME)
            );
        }

        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;
        $tblOrders = $wpdb->prefix . 'orders';

        // security check!
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
            $nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];

            if (!wp_verify_nonce($nonce, $action))
                wp_die('Nope! Security check failed!');
        }

        $action = $this->current_action();
        $wp_list_orders = getRequest('wp_list_order');
        if(is_array($wp_list_orders)){
            switch ($action) {
                case "approve":
                    foreach ($wp_list_orders as $id) {
                        $query = "UPDATE $tblOrders SET status = 1 WHERE ID = $id and status <> 1";
                        $wpdb->query($query);
                    }
                    break;
                case "unapprove":
                    foreach ($wp_list_orders as $id) {
                        $query = "UPDATE $tblOrders SET status = 0 WHERE ID = $id and status <> 0";
                        $wpdb->query($query);
                    }
                    break;
                case "cancel":
                    foreach ($wp_list_orders as $id) {
                        $query = "UPDATE $tblOrders SET status = 2 WHERE ID = $id and status <> 2";
                        $wpdb->query($query);
                    }
                    break;
                case "restore":
                    foreach ($wp_list_orders as $id) {
                        $query = "UPDATE $tblOrders SET status = 0 WHERE ID = $id and status <> 0";
                        $wpdb->query($query);
                    }
                    break;
                case "delete":
                    foreach ($wp_list_orders as $id) {
                        $query = "DELETE FROM $tblOrders WHERE ID = $id";
                        $wpdb->query($query);
                    }
                    break;
                default:
                    break;
            }
        }

        return;
    }

    function column_default($item, $column_name) {
        return '';
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->ID);
    }

}

################################################################################
add_action('admin_print_footer_scripts', 'orders_bulk_actions_select_all', 99);

function orders_bulk_actions_select_all() {
    echo <<<HTML
<style type="text/css">
    #col_orders_cb{width: 30px;}
    #col_orders_id{width: 50px;}
</style>
<script type="text/javascript">/* <![CDATA[ */
jQuery(function($){
    $("input.cb-orders-select-all").click(function(){
        if($(this).is(':checked')){
            $("input[name='wp_list_order[]']").attr('checked', 'checked');
            $("input.cb-orders-select-all").attr('checked', 'checked');
        }else{
            $("input[name='wp_list_order[]']").removeAttr('checked');
            $("input.cb-orders-select-all").removeAttr('checked');
        }
    });
    $("form#ppo-orders-form").submit(function(){
        var str_query = $("#search-submit").prev().val().trim();
        if(str_query.length > 0){
            window.location = window.location.href + "&s=" + str_query;
            return false;
        }
    });
});
/* ]]> */
</script>
HTML;
}