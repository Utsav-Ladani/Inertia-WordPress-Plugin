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
        return [
            'posts'   => $this->getPosts(),
            'homeURL' => $this->base_url,
        ];
    }

    /**
     * Get the URL for this page
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->base_url . '&inertia_page=posts';
    }

    /**
     * Get the posts for this page
     *
     * @return array
     */
    public function getPosts() {
        $raw_posts = get_posts(
            [
                'post_type' => 'post',
                'posts_per_page' => 10,
            ]
        );

        return array_map(function ($post) {
            return [
                'id'    => $post->ID,
                'title' => $post->post_title,
                'date'  => $post->post_date,
            ];
        }, $raw_posts);
    }
}
