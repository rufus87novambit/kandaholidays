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
     * Get html email header
     *
     * @return string
     */
    // todo --> replace img src
    private function get_html_email_header() {
        return
            '<html>
                <body style="background:#fff;">
                    <table>
                        <tr>
                            <td>
                                <img style="width:1000px;" src="http://kandaclub.com/wp-content/themes/kanda/holidays/header.jpg" alt="header" />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left:50px;padding-right:50px;font-size:14px">';


    }

    /**
     * Get html email footer
     * @return string
     */
    // todo --> replace img src
    private function get_html_email_footer() {
        return
            '</td>
                        </tr>
                        <tr>
                            <td>
                                <img style="width:1000px;" src="http://kandaclub.com/wp-content/themes/kanda/holidays/footer.jpg" alt="header" />
                            </td>
                        </tr>
                    </table>
                </body>
            </html>';
    }

    /**
     * Get always present variables actual values for email
     *
     * @return array
     */
    private function get_email_constant_variables() {
        return array(
            '%SITE_NAME%' => sprintf( '<a href="%1$s">%2$s</a>', esc_url( home_url( '/' ) ), get_option( 'blogname' ) )
        );
    }

    /**
     * Get html email headers
     *
     * @param array $headers
     * @return array
     */
    private function get_html_email_headers( $headers = array() ) {

        $site_name = strtolower( $_SERVER['SERVER_NAME'] );
        if ( substr( $site_name, 0, 4 ) == 'www.' ) {
            $site_name = substr( $site_name, 4 );
        }
        $from = 'noreply@' . $site_name;

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
    private function normalize_email_subject( $subject ) {
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

        $header = $this->get_html_email_header();
        $message = strtr( apply_filters( 'the_content', $message ), array_merge(
            $this->get_email_constant_variables(),
            $variables
        ) );
        $footer = $this->get_html_email_footer();

        return $header . $message . $footer;
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

        $to = KH_Config::get( 'developer_email' );
        $subject = $this->normalize_email_subject( $subject );
        $message = $this->normalize_email_content( $message, $variables );
        $headers = $this->get_html_email_headers();


        return $to ? wp_mail( $to, $subject, $message, $headers ) : null;
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

        if( ! $user_id_email ) return;

        if( is_numeric( $user_id_email ) ) {
            $user_data = get_userdata( $user_id_email );
            $user_id_email = $user_data->user_email;
        } elseif( filter_var( $user_id_email, FILTER_VALIDATE_EMAIL ) === false ) {
            $user_id_email = false;
        }

        if( $user_id_email ) {

            $subject = $this->normalize_email_subject( $subject );
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