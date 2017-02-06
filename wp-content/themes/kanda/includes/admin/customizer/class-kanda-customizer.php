<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Kanda_Customizer {

    protected static $sections_path = 'sections';

    /**
     * Register panels, appropriate sections and single sections
     *
     * @param array $panels
     */
    public static function register( $panels = array() ) {

        $theme_name = kanda_get_theme_name();
        $customizer_defaults = kanda_get_theme_defaults();
        $kanda_customizer_defaults = $customizer_defaults[ $theme_name ];

        $sections_path = trailingslashit( KANDA_CUSTOMIZER_PATH . self::$sections_path );

        /**
         * Add Config
         */
        self::add_config( kanda_get_theme_name(), array(
            'capability'    => 'edit_theme_options',
            'option_type'   => 'theme_mod',
        ) );

        /**
         * Scan and register single sections
         */
        $sections = glob( $sections_path . '*.php', GLOB_BRACE );
        foreach( $sections as $section ) {
            include_once( $section );
        }


        foreach( $panels as $panel_id => $args ) {
            self::add_panel( $panel_id, $args );

            $panel_sections_path = trailingslashit( $sections_path . $panel_id );
            if( is_dir( $panel_sections_path ) ) {
                $panel_sections = glob( $panel_sections_path . '/*.php', GLOB_BRACE );
                foreach ( $panel_sections as $panel_section ) {
                    include_once( $panel_section );
                }
            }
        }
    }

    /**
     * Create a new panel
     *
     * @param   string      the ID for this panel
     * @param   array       the panel arguments
     */
    public static function add_panel( $id = '', $args = array() ) {
        if ( class_exists( 'Kirki' ) ) {
            Kirki::add_panel( $id, $args );
        }
    }

    /**
     * Create a new section
     *
     * @param   string      the ID for this section
     * @param   array       the section arguments
     */
    public static function add_section( $id, $args ) {
        if ( class_exists( 'Kirki' ) ) {
            Kirki::add_section( $id, $args );
        }
    }

    /**
     * Sets the configuration options.
     *
     * @param    string    $config_id    The configuration ID
     * @param    array     $args         The configuration arguments
     */
    public static function add_config( $config_id, $args = array() ) {
        if ( class_exists( 'Kirki' ) ) {
            Kirki::add_config( $config_id, $args );
            return;
        }
    }

    /**
     * Create a new field
     *
     * @param    string    $config_id    The configuration ID
     * @param    array     $args         The field's arguments
     */
    public static function add_field( $config_id, $args ) {
        if ( class_exists( 'Kirki' ) ) {
            Kirki::add_field( $config_id, $args );
        }
    }

}