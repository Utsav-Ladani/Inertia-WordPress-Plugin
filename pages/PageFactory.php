<?php

/**
 * Factory class for creating Inertia pages
 */

namespace Inertia\Pages;

/**
 * Factory class for creating Inertia pages
 */
class PageFactory {
    /**
     * Create a page instance
     *
     * @param string $page     Page identifier
     * @param string $base_url Base URL for all Inertia pages
     *
     * @return AbstractPage
     */
    public static function create( string $page, string $base_url ): AbstractPage {
        switch ($page) {
            case 'posts':
                return new PostsPage($base_url);
            default:
                return new HomePage($base_url);
        }
    }
}
