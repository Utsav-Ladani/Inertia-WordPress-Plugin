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

// Register custom endpoint for Inertia pages
function inertia_register_rewrite_rules() {
    add_rewrite_rule(
        'inertia/v2/?([^/]*)/?$',
        'index.php?pagename=inertia&inertia_page=$matches[1]',
        'top'
    );
    add_rewrite_tag('%inertia_page%', '([^&]+)');
}
add_action('init', __NAMESPACE__ . '\inertia_register_rewrite_rules');

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

                <h2>Sample Pages</h2>
                <ul>
                    <li><a href="/inertia/v2">Home</a></li>
                    <li><a href="/inertia/v2/posts">Posts</a></li>
                </ul>
            </div>
            <?php
        },
        'dashicons-admin-site-alt3',
    );
}
add_action('admin_menu', __NAMESPACE__ . '\inertia_create_menu');

// Add Inertia headers to all responses
function inertia_add_headers() {
    if (isset($_SERVER['HTTP_X_INERTIA'])) {
        add_filter(
            'wp_headers',
            function ($headers) {
                $headers['X-Inertia'] = 'true';
                $headers['Vary']      = 'X-Inertia';
                return $headers;
            }
        );
    }
}
add_action('init', __NAMESPACE__ . '\inertia_add_headers');

function inertia_enqueue_assets() {
    if (get_query_var('pagename') !== 'inertia') {
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
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\inertia_enqueue_assets');

function inertia_enqueue_styles() {
    wp_enqueue_style('inertia-styles', plugins_url('build/index.css', __FILE__));
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\inertia_enqueue_styles');


// Handle the custom endpoint
function inertia_template_redirect() {
    if (get_query_var('pagename') !== 'inertia') {
        return;
    }

    $page       = get_query_var('inertia_page') ?: 'home';
    $base_url   = home_url('inertia/v2');
    $asset_file = include plugin_dir_path(__FILE__) . 'build/index.asset.php';

    $page_data = PageFactory::create($page, $base_url, $asset_file['version']);
    $data      = [
        'component' => $page_data->getComponent(),
        'props'     => $page_data->getProps(),
        'url'       => $page_data->getUrl(),
        'version'   => $asset_file['version'],
    ];

    // If this is an XHR request, return JSON
    if (isset($_SERVER['HTTP_X_INERTIA'])) {
        wp_send_json($data);
        exit;
    }

    // For initial page load, render HTML
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Inertia App</title>
        <?php wp_head(); ?>
    </head>

    <body>
        <div id="inertia" data-page='<?php echo esc_attr(json_encode($data)); ?>'></div>
        <?php wp_footer(); ?>
    </body>

    </html>
    <?php
    exit;
}
add_action('template_redirect', __NAMESPACE__ . '\inertia_template_redirect');
