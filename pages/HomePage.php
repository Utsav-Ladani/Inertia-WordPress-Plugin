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
                    'url'  => $this->base_url . '&inertia_page=posts',
                ],
            ],
            'user' => $this->getUser(),
        ];
    }

    /**
     * Get the user for this page
     *
     * @return array
     */
    public function getUser(): array {
        $user = wp_get_current_user();

        return [
            'id'    => $user->ID,
            'name'  => $user->display_name,
            'email' => $user->user_email,
            'avatar' => get_avatar_url($user->ID),
        ];
    }
}
