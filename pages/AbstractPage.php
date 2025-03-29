<?php

/**
 * Abstract base class for Inertia pages
 */

namespace Inertia\Pages;

/**
 * Abstract base class for Inertia pages
 */
abstract class AbstractPage {
    /**
     * Base URL for all Inertia pages
     *
     * @var string
     */
    protected string $base_url;

    /**
     * Constructor
     *
     * @param string $base_url Base URL for all Inertia pages
     */
    public function __construct( string $base_url ) {
        $this->base_url = $base_url;
    }

    /**
     * Get the URL for this page
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->base_url;
    }

    /**
     * Get the component name for this page
     *
     * @return string
     */
    abstract public function getComponent(): string;

    /**
     * Get the props data for this page
     *
     * @return array
     */
    abstract public function getProps(): array;
}
