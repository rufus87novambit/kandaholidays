<?php
/**
 * Kanda Theme customizer helper
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

final class Kanda_Customizer {

    /**
     * Holds customizer configurations
     * @var array
     */
    private $configs = array();

    /**
     * Holds customizer panels
     * @var array
     */
    private $panels = array();

    /**
     * Holds customizer sections
     * @var array
     */
    private $sections = array();

    /**
     * Holds customizer fields
     * @var array
     */
    private $fields = array();

    /**
     * Holds sections directory name for scanning
     * @var string
     */
    private $sections_path = 'sections';

    /**
     * Whether customizer has already processed
     * @var bool
     */
    private $processed = false;

    /**
     * Holds option name
     * @var string
     */
    private $theme_name = '';

    /**
     * Holds default values
     * @var mixed|string
     */
    public $defaults = '';

    /**
     * Holds customizer data
     * @var array
     */
    private $data = array();

    private $is_customising;

    /**
     * Get class instance
     *
     * @return Kanda_Customizer
     */
    public static function get_instance() {
        static $instance;
        if ( $instance == null) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->sections_path = trailingslashit( KANDA_CUSTOMIZER_PATH . $this->sections_path );
        $this->theme_name = kanda_get_theme_name();
        $this->defaults = include_once ( KANDA_CUSTOMIZER_PATH . 'defaults.php' );
        $this->is_customising = $should_include = (
            ( is_admin() && 'customize.php' == basename( $_SERVER['PHP_SELF'] ) )
            ||
            ( isset( $_REQUEST['wp_customize'] ) && 'on' == $_REQUEST['wp_customize'] )
            ||
            ( ! empty( $_GET['customize_changeset_uuid'] ) || ! empty( $_POST['customize_changeset_uuid'] ) )
        );

        $this->add_config( $this->theme_name, array(
            'capability'    => 'edit_theme_options',
            'option_type'   => 'theme_mod',
        ) );
    }

    /**
     * Add panels
     *
     * @param array $panels
     * @return $this
     */
    public function add_panels( $panels = array() ) {
        $panels = (array) $panels;
        foreach( $panels as $panel_id => $args ) {
            $this->add_panel( $panel_id, $args );
        }

        return $this;
    }

    /**
     * Add a single panel
     *
     * @param $panel_id
     * @param $args
     * @return $this
     */
    public function add_panel( $panel_id, $args = array() ) {
        $this->panels[ $panel_id ] = $args;
        $this->include_sections( $panel_id );
        return $this;
    }

    /**
     * Add configs
     *
     * @param array $configs
     * @return $this
     */
    public function add_configs( $configs = array() ) {
        foreach( $configs as $config_id => $args ) {
            $this->add_config( $config_id, $args );
        }
        return $this;
    }

    /**
     * Add a single config
     *
     * @param $config_id
     * @param array $args
     * @return $this
     */
    public function add_config( $config_id, $args = array() ) {
        $this->configs[ $config_id ] = $args;
        return $this;
    }

    /**
     * Add sections
     *
     * @param array $sections
     * @return $this
     */
    public function add_sections( $sections = array() ) {
        foreach( $sections as $section_id => $args ) {
            $this->add_section( $section_id, $args );
        }
        return $this;
    }

    /**
     * Add a single section
     *
     * @param $section_id
     * @param $args
     * @return $this
     */
    public function add_section( $section_id, $args ) {
        $this->sections[ $section_id ] = $args;
        return $this;
    }

    /**
     * Add fields
     *
     * @param array $fields
     * @return $this
     */
    public function add_fields( $fields = array() ) {
        foreach( $fields as $field_id => $args ) {
            $this->add_field( $field_id, $args );
        }
        return $this;
    }

    /**
     * Add a single field
     *
     * @param $field_id
     * @param array $args
     * @return $this
     */
    public function add_field( $field_id, $args = array() ) {
        $this->fields[ $field_id ] = $args;
        return $this;
    }

    /**
     * Register single panel sections
     *
     * @param $panel_id
     */
    private function include_sections( $panel_id = false ) {
        $sections_path = trailingslashit( $this->sections_path . $panel_id );

        $files = glob( $sections_path . '*.php', GLOB_BRACE );
        foreach( $files as $file ) {
            $section_data = include_once( $file );

            if( $section_data ) {
                $section = isset( $section_data['section'] ) ? $section_data['section'] : false;
                $fields = isset( $section_data['fields'] ) ? $section_data['fields'] : array();

                $this->add_section( $section['id'], $section['args']);
                $this->add_fields( $fields );
            }
        }
    }

    /**
     * Hook into before registering
     */
    private function before_run() {
        $this->include_sections();

        do_action( 'kanda/customizer/before_run', $this );
    }

    /**
     * Hook into after registering
     */
    private function after_run() {
        do_action( 'kanda/customizer/after_run', $this );
    }

    /**
     * Process registration
     */
    public function run() {
        /**
         * We do not need to process anything in case of class absence or if ir already processed
         */
        if ( ! class_exists( 'Kirki' ) || $this->processed ) {
            return;
        }

        /**
         * add possiblity to hook into
         */
        $this->before_run();

        /**
         * Add configs
         */
        foreach( $this->configs as $config_id => $config_args ) {
            Kirki::add_config( $config_id, $config_args );
        }

        /**
         * Add panels
         */
        foreach( $this->panels as $panel_id => $panel_args ) {
            Kirki::add_panel( $panel_id, $panel_args );
        }

        /**
         * Add panel sections
         */
        foreach( $this->sections as $section_id => $section_args ) {
            Kirki::add_section( $section_id, $section_args );
        }

        /**
         * Add fields
         */
        foreach( $this->fields as $field_id => $field_args ) {
            Kirki::add_field( $field_id, $field_args );
        }

        /**
         * add possiblity to hook into
         */
        $this->after_run();

        /**
         * Let instance to know that it already processed
         */
        $this->processed = true;
    }

    /**
     * Get defauls values
     *
     * @return mixed|string
     */
    public function get_defaults() {
        return $this->defaults;
    }
    /**
     * Get customizer value
     *
     * @param $option_name
     * @return null
     */
    public function get_option( $option_name ) {

        if( $this->is_customising || empty( $this->data ) ) {
            $this->data = get_theme_mod( $this->theme_name );
        }

        $value = null;
        if( isset( $this->data[ $option_name ] ) ) {
            $value = $this->data[ $option_name ];
        } elseif( isset( $this->defaults[ $option_name ] ) ) {
            $value = $this->defaults[ $option_name ];
        }

        return $value;
    }

}

/**
 * Get theme name
 *
 * @return string
 */
function kanda_get_theme_name() {
    return 'kanda_theme';
}

/**
 * Get customizer instance
 *
 * @return Kanda_Customizer
 */
function kanda_customizer() {
    return Kanda_Customizer::get_instance();
}

/**
 * Get theme option
 *
 * @param $option_name
 * @return null
 */
function kanda_get_theme_option( $option_name ) {
    return kanda_customizer()->get_option( $option_name );
}

/**
 * Get customizer default values
 *
 * @return mixed
 */
function kanda_get_customizer_defaults() {
    return kanda_customizer()->get_defaults();
}