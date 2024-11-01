<?php
    /* Entry Functions */
    register_activation_hook(WP_PLUGIN_DIR."/tracker-wp/trackerwp.php",'tkwp_install');

    register_deactivation_hook(WP_PLUGIN_DIR."/tracker-wp/trackerwp.php",'tkwp_deactivation');

    register_uninstall_hook(WP_PLUGIN_DIR."/tracker-wp/trackerwp.php",'tkwp_desistalar');


    add_action("plugins_loaded","tkwp_activar");//cuando esten cargados los plugins
    add_action("admin_menu","tkwp_menus");//cuando pase el hook admin menu

    //Carga de scripts y css
    add_action('wp_enqueue_scripts', 'tkwp_loadCssClient');
    add_action('admin_enqueue_scripts', 'tkwp_loadCssAdmin');
?>