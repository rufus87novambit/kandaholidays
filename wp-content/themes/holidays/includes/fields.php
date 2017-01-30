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

        if( ! $this->active ) return;

        add_action( 'init', array( $this, 'add_option_pages' ), 100 );

        if( is_admin() ) {
            add_filter('acf/update_value/name=send_activation_email', array($this, 'send_activation_email'), 10, 3);
        }

    }

    /**
     * Check acf plugin status
     */
    private function check_activity() {
        if( ! function_exists( 'is_plugin_active' ) ) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $this->active = is_plugin_active( $this->plugin );
    }

    /**
     * Add option pages
     */
    public function add_option_pages() {

        $general_options = array(
            'page_title' => esc_html__( 'Kanda Settings', 'kanda' ),
            'menu_title' => esc_html__( 'Settings', 'kanda' ),
            'capability' => 'edit_posts',
            'icon_url'   => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMzAwLjAwMDAwMHB0IiBoZWlnaHQ9IjMwMC4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDMwMC4wMDAwMDAgMzAwLjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjEzLCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxNQo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwzMDAuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNMTI5MCAyOTg2IGMtMzUzIC01NSAtNjg3IC0yMzUgLTkxMyAtNDkyIC0zMjYgLTM3NCAtNDUwIC04ODEgLTMzMQotMTM1OCA2NiAtMjY1IDE5NCAtNDg4IDM5OCAtNjkyIDIyMiAtMjIxIDQ1OCAtMzQ5IDc2NiAtNDE1IDE1MSAtMzMgNDM4IC0zMwo1ODcgLTEgNjE5IDEzNCAxMDg5IDYyMyAxMTg4IDEyMzcgMjAgMTIxIDE5IDM1NyAtMSA0NzYgLTU1IDMxOSAtMTk5IDU5NQotNDI5IDgyNCAtMjA2IDIwNSAtNTAwIDM1OSAtNzgwIDQxMSAtMTE5IDIxIC0zNzUgMjcgLTQ4NSAxMHogbTQ0NyAtMjM1IGM1MTYKLTEwMCA5MTcgLTUwMSAxMDIwIC0xMDE4IDIzIC0xMTggMjMgLTM1NSAtMSAtNDc2IC00NyAtMjQyIC0xNTQgLTQ0MyAtMzM2Ci02MzAgLTI2MyAtMjcyIC01NTAgLTM5NyAtOTEzIC0zOTcgLTM2NyAwIC02NDIgMTE0IC05MDIgMzc1IC0yMDAgMTk5IC0zMTUKNDEzIC0zNjAgNjY5IC0yMCAxMTUgLTIwIDMzNyAwIDQ1MiA0NiAyNjIgMTc3IDUwMCAzODQgNjk2IDE4NCAxNzYgNDAzIDI5MAo2MzcgMzMyIDExOSAyMiAzNTIgMjAgNDcxIC0zeiIvPgo8cGF0aCBkPSJNODM4IDE1MTAgbDIgLTgzMCAxMTAgMCAxMTAgMCAwIDI4OSAwIDI5MCAxMzAgMTIzIGM3MiA2OCAxMzYgMTIzCjE0MiAxMjMgNiAwIDE0MiAtMTg2IDMwMiAtNDEyIGwyODkgLTQxMyAxNDQgMCBjNzkgMCAxNDMgMSAxNDMgMyAwIDEgLTE2MgoyMjMgLTM2MCA0OTIgLTE5OCAyNzAgLTM1OSA0OTIgLTM1OSA0OTUgMCAzIDE1NSAxNTQgMzQzIDMzNSBsMzQ0IDMzMCAtMTQ1IDMKLTE0NCAzIC00MTIgLTQwNyAtNDEyIC00MDYgLTMgNDA2IC0yIDQwNiAtMTEzIDAgLTExMiAwIDMgLTgzMHoiLz4KPC9nPgo8L3N2Zz4K'
        );


        acf_add_options_page( $general_options );

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

            $status = false;
            if ($user) {
                $status = Kanda_Mailer()->send_user_email($user->user_email, 'Profile Activated', 'Your profile is activated');
            }

            // Set back to 0 to give resend functionality
            $value = 0;

        }

        return $value;
    }

}

Kanda_Fields::get_instance();