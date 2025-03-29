<?php

/**
 * Home page component
 */

namespace Inertia\Pages;

/**
 * Home page component
 */
class HomePage extends AbstractPage {
    /**
     * Get the component name for this page
     *
     * @return string
     */
    public function getComponent(): string {
        return 'Home';
    }

    /**
     * Get the props data for this page
     *
     * @return array
     */
    public function getProps(): array {
        return [
            'pages' => [
                [
                    'name' => 'Posts',
                    'url'  => wp_make_link_relative($this->base_url . '/posts'),
                ],
            ],
        ];
    }
}
