<?php

class Kanda_Fields {

    private $active;
    private $plugin = 'advanced-custom-fields-pro/acf.php';

    /**
     * Singleton.
     */
    static function get_instance() {
        static $instance = null;
        if ( $instance == null) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->check_activity();

        if( ! $this->active ) {
            return;
        }

        if( is_admin() ) {
            add_filter( 'acf/update_value/name=send_activation_email', array( $this, 'send_activation_email' ), 10, 3 );
        }

    }

    /**
     * Check acf plugin status
     */
    private function check_activity() {
        if( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $this->active = is_plugin_active( $this->plugin );
    }

    /**
     * Send / resend profile activation email
     *
     * @param $value
     * @param $post_id
     * @param $field
     * @return int
     */
    public function send_activation_email( $value, $post_id, $field ) {

        if( $value ) {

            $user_id = preg_replace('/[^0-9]/', '', $post_id);
            $user = get_user_by('id', (int)$user_id);

            if ( $user ) {

                $subject = kanda_get_theme_option( 'email_profile_activation' );
                $message = kanda_get_theme_option( 'email_profile_activation_title' );
                $variables = array( '{{LOGIN_URL}}' => sprintf( '<a href="%1$s">%1$s</a>', kanda_url_to( 'login' ) ) );

                $sent = kanda_mailer()->send_user_email( $user->user_email, $subject, $message, $variables );
                if( ! $sent ) {
                    kanda_logger()->log( sprintf( 'Error sending email to user for account activation notification. Details: user_id=%d', $user->ID ) );
                }
            }

            // Set back to 0 to give resend functionality
            $value = 0;

        }

        return $value;
    }

}

/**
 * Get fields helper instance
 *
 * @return Kanda_Fields
 */
function kanda_fields() {
    return Kanda_Fields::get_instance();
}

kanda_fields();