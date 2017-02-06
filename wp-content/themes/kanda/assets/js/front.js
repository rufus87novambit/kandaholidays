/**
 * Map
 *
 * 1. Variable definitions
 * 2. Background Slider
 * 3. Custom validation rules
 * 4. Login validation
 * 5. Register validation
 * 6. Forgot password validation
 * 7. Reset password validation
 */

(function($){

    $( document).on( 'ready', function() {

        /****************************************** 1. Variable definitions *****************************************/

        var
            kanda_slides = $('.slides'),
            kanda_form_login = $( 'form#form_login'),
            kanda_form_register = $( 'form#form_register'),
            kanda_form_forgot_password = $( 'form#form_forgot_password'),
            kanda_form_reset_password = $( 'form#form_reset_password'),
            kanda_form_validation_default_args = {
                onfocusout: function( element ) {
                    if( this.element( element ) ) {
                        $( element).removeClass('error')
                            .parents( '.input-holder' ).removeClass( 'has-error' )
                            .find( '.help-block').text( '' );
                    }
                },
                errorPlacement : function( error, element ) {
                    element.siblings( '.help-block').text( error.text() );
                    element.parents('.input-holder').addClass( 'has-error' );
                }
            };

        /****************************************** /end Variable definitions *****************************************/

        /******************************************* 2. Background Slider *******************************************/

        kanda_slides.superslides({
            play : 3000,
            animation : 'fade',
            pagination : false,
            hashchange: false
        });

        /****************************************** /end Background Slider ******************************************/

        /**************************************** 3. Custom validation rules ****************************************/

        /**
         * loginRegex validation
         */
        $.validator.addMethod( 'loginRegex' , function( value, element ) {
            return /^[a-z0-9\_\-]+$/i.test( value );
        } );

        /**
         * Phone number validation
         */
        $.validator.addMethod( 'phone_number', function( value, element ) {
            var regex_result = /^[\+:]*\d{9,}$/.test( value );
            if( $( element).hasClass( 'optional' ) ) {
                return value ? regex_result : true;
            }
            return regex_result;
        } );

        /*************************************** /end Custom validation rules ***************************************/

        /******************************************** 4. Login validation *******************************************/

        if( kanda_form_login.length ) {

            var kanda_form_login_validation_args = {
                rules : {
                    username : {
                        required : true,
                        loginRegex : true
                    },
                    password : {
                        required : true
                    }
                },
                messages : {
                    username : {
                        required : kanda.validation.form_login.username.required,
                        loginRegex : kanda.validation.form_login.username.loginRegex
                    },
                    password : {
                        required : kanda.validation.form_login.password.required
                    }
                }
            };
            kanda_form_login_validation_args = Object.assign(
                kanda_form_validation_default_args,
                kanda_form_login_validation_args
            );

            kanda_form_login.validate( kanda_form_login_validation_args );

            kanda_form_login.on( 'submit', function() {
                if ( ! $( this ).valid() ) {
                    return false;
                }
            } );
        }

        /******************************************* /end Login validation ******************************************/

        /******************************************* 5. Register validation *****************************************/

        if( kanda_form_register.length ) {

            var kanda_form_register_validation_args = {
                rules : {
                    'personal[username]' : {
                        required : true,
                        loginRegex : true,
                        rangelength : [ kanda.validation.data.username_min_length, kanda.validation.data.username_max_length ]
                    },
                    'personal[email]' : {
                        required : true,
                        email : true
                    },
                    'personal[password]' : {
                        required : true,
                        rangelength : [ kanda.validation.data.password_min_length, kanda.validation.data.password_max_length ]
                    },
                    'personal[confirm_password]' : {
                        required : true,
                        equalTo : '#password'
                    },
                    'personal[first_name]' : {
                        required : true
                    },
                    'personal[last_name]' : {
                        required : true
                    },
                    'personal[mobile]' : {
                        phone_number : true
                    },
                    'company[name]' : {
                        required : true
                    },
                    'company[license]' : {
                        required : true
                    },
                    'company[phone]' : {
                        phone_number : true
                    }
                },
                messages : {
                    'personal[username]' : {
                        required : kanda.validation.form_register.username.required,
                        loginRegex : kanda.validation.form_register.username.loginRegex,
                        rangelength : kanda.validation.form_register.username.rangelength
                    },
                    'personal[email]' : {
                        required : kanda.validation.form_register.email.required,
                        email : kanda.validation.form_register.email.email
                    },
                    'personal[password]' : {
                        required : kanda.validation.form_register.password.required,
                        rangelength : kanda.validation.form_register.password.rangelength
                    },
                    'personal[confirm_password]' : {
                        required : kanda.validation.form_register.confirm_password.required,
                        equalTo : kanda.validation.form_register.confirm_password.equalTo
                    },
                    'personal[first_name]' : {
                        required : kanda.validation.form_register.first_name.required
                    },
                    'personal[last_name]' : {
                        required : kanda.validation.form_register.last_name.required
                    },
                    'personal[mobile]' : {
                        phone_number : kanda.validation.form_register.mobile.phone_number
                    },
                    'company[name]' : {
                        required : kanda.validation.form_register.company_name.required
                    },
                    'company[license]' : {
                        required : kanda.validation.form_register.company_license.required
                    },
                    'company[phone]' : {
                        phone_number : kanda.validation.form_register.company_phone.phone_number
                    }
                }
            };
            kanda_form_register_validation_args = Object.assign(
                kanda_form_validation_default_args,
                kanda_form_register_validation_args
            );

            kanda_form_register.validate( kanda_form_register_validation_args );

            kanda_form_register.on( 'submit', function() {
                if ( ! $( this ).valid() ) {
                    return false;
                }
            } );
        }

        /****************************************** /end Register validation ****************************************/

        /*************************************** 6. Forgot password validation **************************************/

        if( kanda_form_forgot_password.length ) {

            var kanda_form_forgot_password_validation_args = {
                rules : {
                    username_email : {
                        required : true
                    }
                },
                messages : {
                    username_email : {
                        required : kanda.validation.form_forgot_password.username_email.required
                    }
                }
            };
            kanda_form_forgot_password_validation_args = Object.assign(
                kanda_form_validation_default_args,
                kanda_form_forgot_password_validation_args
            );

            kanda_form_forgot_password.validate( kanda_form_forgot_password_validation_args );

            kanda_form_forgot_password.on( 'submit', function() {
                if ( ! $( this ).valid() ) {
                    return false;
                }
            } );
        }

        /************************************** /end Forgot password validation *************************************/

        /**************************************** 7. Reset password validation **************************************/

        if( kanda_form_reset_password.length ) {

            var kanda_form_reset_password_validation_args = {
                rules : {
                    password : {
                        required : true,
                        rangelength : [ kanda.validation.data.password_min_length, kanda.validation.data.password_max_length ]
                    },
                    confirm_password : {
                        required : kanda.validation.form_reset_password.confirm_password.required,
                        equalTo  : '#password'
                    }
                },
                messages : {
                    password : {
                        required : kanda.validation.form_reset_password.password.required,
                        rangelength : kanda.validation.form_reset_password.password.rangelength
                    },
                    confirm_password : {
                        required : kanda.validation.form_reset_password.confirm_password.required,
                        equalTo  : kanda.validation.form_reset_password.confirm_password.equalTo
                    }
                }
            };
            kanda_form_reset_password_validation_args = Object.assign(
                kanda_form_validation_default_args,
                kanda_form_reset_password_validation_args
            );

            kanda_form_reset_password.validate( kanda_form_reset_password_validation_args );

            kanda_form_reset_password.on( 'submit', function() {
                if ( ! $( this ).valid() ) {
                    return false;
                }
            } );
        }

        /**************************************** 7. Reset password validation **************************************/

    } );

})(jQuery)
