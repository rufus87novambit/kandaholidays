<?php
/**
 * Kanda Theme Mailer
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Kanda_Mailer {

    private $layout_path = '';

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
        $this->layout_path = trailingslashit( KANDA_THEME_PATH . 'views/email' );
    }

    public static function get_layout_path() {
        return trailingslashit( KANDA_THEME_PATH . 'views/email' );
    }

    /**
     * Get always present variables actual values for email
     *
     * @return array
     */
    private function get_email_constant_variables() {
        return array(
            '{{SITE_NAME}}' => sprintf( '<a href="%1$s">%2$s</a>', kanda_url_to( 'home' ), get_option( 'blogname' ) )
        );
    }

    /**
     * Get html email headers
     *
     * @param array $headers
     * @return array
     */
    private function get_html_email_headers( $headers = array() ) {

        $from = Kanda_Config::get( 'email_from' );
        if( ! $from ) {
            $site_name = strtolower($_SERVER['SERVER_NAME']);
            if (substr($site_name, 0, 4) == 'www.') {
                $site_name = substr($site_name, 4);
            }
            $from = 'noreply@' . $site_name;
        }

        return array_merge(
            array(
                'Content-Type: text/html; charset=UTF-8' . "\r\n",
                'From: ' . get_option( 'blogname' ) . ' <' . $from . '>' . "\r\n"
            ),
            $headers
        );

    }

    /**
     * Prepend website name to email subject
     *
     * @param $subject
     * @return string
     */
    private function normalize_email_subject( $subject, $variables ) {
        $subject = strtr( $subject, array_merge(
            $this->get_email_constant_variables(),
            $variables
        ) );

        return sprintf(
            '%1$s : %2$s',
            wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
            wp_specialchars_decode( $subject )
        );
    }

    /**
     * Replace variables in message content with their actual values
     *
     * @param $message
     * @param array $variables
     * @return string
     */
    private function normalize_email_content ( $message, $variables = array() ) {

        $message = strtr( apply_filters( 'the_content', $message ), array_merge(
            $this->get_email_constant_variables(),
            $variables
        ) );

        ob_start();
        include $this->layout_path . 'user.php';

        return ob_get_clean();
    }

    /**
     * Send email to developer
     *
     * @param $subject
     * @param $message
     * @param array $variables
     * @param array $headers
     * @return bool
     */
    public function send_developer_email( $subject, $message, $variables = array(), $headers = array() ) {

        $to = preg_replace('/\s+/', '', kanda_get_theme_option( 'debug_developer_email' ) );
        $subject = $this->normalize_email_subject( $subject, $variables );
        $message = $this->normalize_email_content( $message, $variables );
        $headers = $this->get_html_email_headers();

        return $to ? wp_mail( $to, $subject, $message, $headers ) : null;
    }

    /**
     * Send email to admin
     *
     * @param $subject
     * @param $message
     * @param array $variables
     * @param array $headers
     * @return bool|null|void
     */
    public function send_admin_email( $subject, $message, $variables = array(), $headers = array() ) {

        $to = get_option( 'admin_email' );
        $subject = $this->normalize_email_subject( $subject, $variables );
        $message = $this->normalize_email_content( $message, $variables );
        $headers = $this->get_html_email_headers();

        return wp_mail( $to, $subject, $message, $headers );

    }

    /**
     * Send email to user
     *
     * @param $user_id_email
     * @param $subject
     * @param $message
     * @param array $variables
     * @param array $headers
     * @return bool|null|void
     */
    public function send_user_email( $user_id_email, $subject, $message, $variables = array(), $headers = array() ) {

        if( ! $user_id_email ) {
            return;
        }

        if( is_numeric( $user_id_email ) ) {
            $user_data = get_userdata( $user_id_email );
            $user_id_email = $user_data->user_email;
        } elseif( filter_var( $user_id_email, FILTER_VALIDATE_EMAIL ) === false ) {
            $user_id_email = false;
        }

        if( $user_id_email ) {

            $subject = $this->normalize_email_subject( $subject, $variables );
            $message = $this->normalize_email_content( $message, $variables );
            $headers = $this->get_html_email_headers();

            return wp_mail( $user_id_email, $subject, $message, $headers );
        }

        return null;
    }

}

/**
 * Get mailer instance
 *
 * @return Kanda_Mailer
 */
function kanda_mailer() {
    return Kanda_Mailer::get_instance();
}