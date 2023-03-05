<?php

if (!function_exists('ppo_find_layersliders')) {

    function ppo_find_layersliders($names_only = false) {
        // Get WPDB Object
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "layerslider";

        // Get sliders
        $sliders = $wpdb->get_results("SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC LIMIT 100");

        if (empty($sliders))
            return;

        if ($names_only) {
            $new = array();
            foreach ($sliders as $key => $item) {
                if (empty($item->name))
                    $item->name = __("(Unnamed Slider)", "ppo_framework");
                $new[$item->name] = $item->id;
            }

            return $new;
        }

        return $sliders;
    }

}

/**************************/
/* Include LayerSlider WP */
/**************************/
if (is_admin()) {
    add_action('init', 'ppo_include_layerslider', 45);
} else {
    add_action('wp', 'ppo_include_layerslider', 45);
}

function ppo_include_layerslider() {
    // Path for LayerSlider WP main PHP file
    $layerslider = get_template_directory() . '/config-layerslider/LayerSlider/layerslider.php';
    $themeNice = SHORT_NAME;

    // Check if the file is available and the user didnt activate the layerslide plugin to prevent warnings
    if (file_exists($layerslider)) {
        if (function_exists('layerslider')) { //layerslider plugin is active
            if (get_option("{$themeNice}_layerslider_activated", '0') == '0') {

                // Save a flag that it is activated, so this won't run again
                update_option("{$themeNice}_layerslider_activated", '1');
            }
        } else { //not active, use theme version instead
            // Include the file
            include $layerslider;

            $GLOBALS['lsPluginPath'] = get_template_directory_uri() . '/config-layerslider/LayerSlider/';
            $GLOBALS['lsAutoUpdateBox'] = false;

            // Activate the plugin if necessary
            if (get_option("{$themeNice}_layerslider_activated", '0') == '0') {

                // Run activation script
                layerslider_activation_scripts();

                // Save a flag that it is activated, so this won't run again
                update_option("{$themeNice}_layerslider_activated", '1');
            }
        }
    }
}
