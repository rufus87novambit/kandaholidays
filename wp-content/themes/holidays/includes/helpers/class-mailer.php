<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Kanda_Mailer {

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
     * Get html email headers
     *
     * @param array $headers
     * @return array
     */
    private function kanda_get_html_email_headers( $headers = array() ) {

        $headers = array_merge( array(
            'Content-Type' => 'Content-Type: text/html; charset=UTF-8'
        ), $headers );

        $headers['From'] = sprintf(
            'From: %1$s <%2$s@%3$s>',
            'My Name',
            'noreply',
            strtr( get_bloginfo( 'siteurl' ), array ('http://' => '', 'https://' => '' ) )
        );

        return array_values( $headers );
    }

    /**
     * Replace line breaks with paragraph tags
     *
     * @param $string
     * @return string
     */
    private function kanda_normalize_email_content( $string ) {
        return '<p>' . implode( '</p><p>', array_filter( explode( "\n", $string ) ) ) . '</p>';
    }

    /**
     * Send email to developer
     *
     * @param $subject
     * @param $message
     */
    public function kanda_send_developer_email( $subject, $message, $headers = array() ) {

        $to = KH_Config::get( 'developer_email' );
        $subject = sprintf( '%1$s - %2$s', get_bloginfo( 'sitename' ), $subject );
        $message = kanda_normalize_email_content( $message );
        $headers = kanda_get_html_email_headers();

        wp_mail( $to, $subject, $message, $headers );
    }

}

function Kanda_Mailer() {
    return Kanda_Mailer::get_instance();
}