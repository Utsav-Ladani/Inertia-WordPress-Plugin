<?php

/**
 * Plugin Name: Inertia
 * Description: Inertia.js integration for WordPress
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Utsav Ladani
 * Author URI: https://github.com/Utsav-Ladani
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Inertia;

use Inertia\Pages\PageFactory;

// Prevent direct access
if (! defined('ABSPATH')) {
    exit;
}

// Autoload classes
function inertia_autoload($class) {
    $prefix   = __NAMESPACE__ . '\\';
    $base_dir = plugin_dir_path(__FILE__);

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file           = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        include $file;
    }
}
spl_autoload_register(__NAMESPACE__ . '\inertia_autoload');

register_activation_hook(__FILE__, 'flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

function inertia_create_menu() {
    add_menu_page(
        'Inertia',
        'Inertia',
        'manage_options',
        'inertia',
        function () {
            ?>
            <div class="wrap">
                <h1>Inertia</h1>
                <p>Inertia is a plugin that allows you to use Inertia.js with WordPress.</p>

                <div id="inertia" data-page='<?php echo esc_attr(wp_json_encode(inertia_get_data())); ?>'></div>
            </div>
            <?php
        },
        'dashicons-admin-site-alt3',
    );
}
add_action('admin_menu', __NAMESPACE__ . '\inertia_create_menu');

function inertia_enqueue_assets() {
    if (! is_inertia_page()) {
        return;
    }

    $asset_file = include plugin_dir_path(__FILE__) . 'build/index.asset.php';

    wp_enqueue_script(
        'inertia-scripts',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        $asset_file['version'],
        [
            'in_footer' => true,
        ]
    );

    wp_enqueue_style('inertia-styles', plugins_url('build/index.css', __FILE__));
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\inertia_enqueue_assets');

function inertia_handle_request() {
    if (
        ! is_inertia_page() ||
        !isset($_SERVER['HTTP_X_INERTIA'])
    ) {
        return;
    }

    inertia_set_headers();
    wp_send_json(inertia_get_data());
    wp_die();
}
add_action('admin_init', __NAMESPACE__ . '\inertia_handle_request');

function is_inertia_page() {
    return isset($_GET['page']) && $_GET['page'] === 'inertia';
}

function inertia_set_headers() {
    header('X-Inertia: true');
    header('Vary: X-Inertia');
}

function inertia_get_data() {
    $page       = isset($_GET['inertia_page']) ? $_GET['inertia_page'] : 'home';
    $base_url   = wp_make_link_relative(admin_url('admin.php?page=inertia'));

    $page_data  = PageFactory::create($page, $base_url);
    $asset_file = include plugin_dir_path(__FILE__) . 'build/index.asset.php';

    return [
        'component' => $page_data->getComponent(),
        'props'     => $page_data->getProps(),
        'url'       => $page_data->getUrl(),
        'version'   => $asset_file['version'],
    ];
}
