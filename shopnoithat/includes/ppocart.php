<?php

define("PPOCART_MENU_NAME", "ppocart");
define("PPOCART_MENU_TITLE", "PPO Orders");

/**
 * Add actions
 */
add_action('admin_menu', 'add_ppocart_page');

function add_ppocart_page() {

    add_menu_page(__(PPOCART_MENU_TITLE), // Page title
            __(PPOCART_MENU_TITLE), // Menu title
            'manage_options', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            PPOCART_MENU_NAME, // menu id - Unique id of the menu
            'ppocart_settings_page', // render output function
            '', // URL icon, if empty default icon
            null // Menu position - integer, if null default last of menu list
    );
}
function ppocart_settings_page(){
    ?>
    <div class="wrap">
        <div class="opwrap" style="margin-top: 10px;" >
            <div class="icon32" id="icon-options-general"></div>
            <h2 class="wraphead"><?php echo PPOCART_MENU_TITLE; ?> settings</h2>
    <?php
    if (isset($_REQUEST['saved']))
        echo '<div id="message" class="updated fade"><p><strong>' . PPOCART_MENU_TITLE . ' settings saved.</strong></p></div>';
    ?>
            <form method="post">
                <h3>Orders Settings</h3>
                <table class="form-table">
                </table>
                <div class="submit">
                    <input name="save" type="submit" value="Save changes" class="button button-large button-primary" />
                    <input type="hidden" name="action" value="save" />
                </div>
            </form>
        </div>
    </div>
<?php
}