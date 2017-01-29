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
    private function get_html_email_headers( $headers = array() ) {

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
     * @param $content
     * @return string
     */
    private function normalize_email_content( $content ) {
        return '<p>' . implode( '</p><p>', array_filter( explode( "\n", $content ) ) ) . '</p>';
    }

    /**
     * Prepend website name to email subject
     *
     * @param $subject
     * @return string
     */
    private function normalize_email_subject( $subject ) {
        return sprintf( '%1$s - %2$s', get_bloginfo( 'sitename' ), $subject );
    }

    /**
     * Send email to developer
     *
     * @param $subject
     * @param $message
     * @param array $headers
     * @return bool
     */
    public function send_developer_email( $subject, $message, $headers = array() ) {

        $to = KH_Config::get( 'developer_email' );
        $subject = $this->normalize_email_subject( $subject );
        $message = $this->normalize_email_content( $message );
        $headers = $this->get_html_email_headers();

        return wp_mail( $to, $subject, $message, $headers );
    }

    /**
     * Send email to user
     *
     * @param $user_id_email
     * @param $subject
     * @param $message
     * @param array $headers
     * @return bool|null|void
     */
    public function send_user_email( $user_id_email, $subject, $message, $headers = array() ) {

        if( ! $user_id_email ) return;

        if( is_numeric( $user_id_email ) ) {
            $user_data = get_userdata( $user_id_email );
            $user_id_email = $user_data->user_email;
        } elseif( filter_var( $user_id_email, FILTER_VALIDATE_EMAIL ) === false ) {
            $user_id_email = false;
        }

        if( $user_id_email ) {

            $subject = $this->normalize_email_subject( $subject );
            $message = $this->normalize_email_content( $message );
            $headers = $this->get_html_email_headers();

            return wp_mail( $user_id_email, $subject, $message, $headers );
        }

        return null;
    }

}

function Kanda_Mailer() {
    return Kanda_Mailer::get_instance();
}