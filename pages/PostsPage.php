<?php

/**
 * Posts page component
 */

namespace Inertia\Pages;

/**
 * Posts page component
 */
class PostsPage extends AbstractPage {
    /**
     * Get the component name for this page
     *
     * @return string
     */
    public function getComponent(): string {
        return 'Posts';
    }

    /**
     * Get the props data for this page
     *
     * @return array
     */
    public function getProps(): array {
        $posts = get_posts(
            [
                'post_type' => 'post',
                'posts_per_page' => 10,
            ]
        );

        return [
            'posts' => $posts,
            'homeURL' => wp_make_link_relative($this->base_url),
        ];
    }

    /**
     * Get the URL for this page
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->base_url . '/posts';
    }
}
