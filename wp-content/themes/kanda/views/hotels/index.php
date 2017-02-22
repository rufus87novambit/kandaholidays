<form class="form-block" id="hotel_search_form" method="post">
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'SELECT DESTINATION', 'kanda' ); ?></legend>
        <ul class="block-sm-3 clearfix">
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="AUH" checked>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Abu Dhabi', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="AJMA">
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Ajman', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="ALAZ">
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Al Ain', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="DXB">
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Dubai', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="FUJA">
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Fujairah', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="RASK">
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Ras Al Khaimah', 'kanda' ); ?></span>
                </label>
            </li>
        </ul>
    </fieldset>
    <fieldset class="fieldset clearfix sep-btm">
        <div class="row">
            <div class="col-sm-6">
                <legend><?php esc_html_e( 'SEARCH CRITERIA', 'kanda' ); ?></legend>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></label>
                    <div class="col-lg-7">
                        <input type="text" name="hotel_name" class="form-control">
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Rating', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <select class="rating" name="hotel_rating">
                            <option value=""></option>
                            <option value="2"></option>
                            <option value="2"></option>
                            <option value="3" selected></option>
                            <option value="4"></option>
                            <option value="5"></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Hotels In Request', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <select class="custom-select" name="include_on_request">
                            <option value="Y"><?php esc_html_e( 'Available & On Request', 'kanda' ); ?></option>
                            <option value="N" selected="selected"><?php esc_html_e( 'Only Available', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Nationality', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <select class="custom-select" name="nationality">
                            <option value="AF">Afghanistan</option>
                            <option value="AX">Åland Islands</option>
                            <option value="AL">Albania</option>
                            <option value="DZ">Algeria</option>
                            <option value="AS">American Samoa</option>
                            <option value="AD">Andorra</option>
                            <option value="AO">Angola</option>
                            <option value="AI">Anguilla</option>
                            <option value="AQ">Antarctica</option>
                            <option value="AG">Antigua and Barbuda</option>
                            <option value="AR">Argentina</option>
                            <option value="AM" selected="selected">Armenia</option>
                            <option value="AW">Aruba</option>
                            <option value="AU">Australia</option>
                            <option value="AT">Austria</option>
                            <option value="AZ">Azerbaijan</option>
                            <option value="BS">Bahamas</option>
                            <option value="BH">Bahrain</option>
                            <option value="BD">Bangladesh</option>
                            <option value="BB">Barbados</option>
                            <option value="BY">Belarus</option>
                            <option value="BE">Belgium</option>
                            <option value="BZ">Belize</option>
                            <option value="BJ">Benin</option>
                            <option value="BM">Bermuda</option>
                            <option value="BT">Bhutan</option>
                            <option value="BO">Bolivia, Plurinational State of</option>
                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                            <option value="BA">Bosnia and Herzegovina</option>
                            <option value="BW">Botswana</option>
                            <option value="BV">Bouvet Island</option>
                            <option value="BR">Brazil</option>
                            <option value="IO">British Indian Ocean Territory</option>
                            <option value="BN">Brunei Darussalam</option>
                            <option value="BG">Bulgaria</option>
                            <option value="BF">Burkina Faso</option>
                            <option value="BI">Burundi</option>
                            <option value="KH">Cambodia</option>
                            <option value="CM">Cameroon</option>
                            <option value="CA">Canada</option>
                            <option value="CV">Cape Verde</option>
                            <option value="KY">Cayman Islands</option>
                            <option value="CF">Central African Republic</option>
                            <option value="TD">Chad</option>
                            <option value="CL">Chile</option>
                            <option value="CN">China</option>
                            <option value="CX">Christmas Island</option>
                            <option value="CC">Cocos (Keeling) Islands</option>
                            <option value="CO">Colombia</option>
                            <option value="KM">Comoros</option>
                            <option value="CG">Congo</option>
                            <option value="CD">Congo, the Democratic Republic of the</option>
                            <option value="CK">Cook Islands</option>
                            <option value="CR">Costa Rica</option>
                            <option value="CI">Côte d'Ivoire</option>
                            <option value="HR">Croatia</option>
                            <option value="CU">Cuba</option>
                            <option value="CW">Curaçao</option>
                            <option value="CY">Cyprus</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="DK">Denmark</option>
                            <option value="DJ">Djibouti</option>
                            <option value="DM">Dominica</option>
                            <option value="DO">Dominican Republic</option>
                            <option value="EC">Ecuador</option>
                            <option value="EG">Egypt</option>
                            <option value="SV">El Salvador</option>
                            <option value="GQ">Equatorial Guinea</option>
                            <option value="ER">Eritrea</option>
                            <option value="EE">Estonia</option>
                            <option value="ET">Ethiopia</option>
                            <option value="FK">Falkland Islands (Malvinas)</option>
                            <option value="FO">Faroe Islands</option>
                            <option value="FJ">Fiji</option>
                            <option value="FI">Finland</option>
                            <option value="FR">France</option>
                            <option value="GF">French Guiana</option>
                            <option value="PF">French Polynesia</option>
                            <option value="TF">French Southern Territories</option>
                            <option value="GA">Gabon</option>
                            <option value="GM">Gambia</option>
                            <option value="GE">Georgia</option>
                            <option value="DE">Germany</option>
                            <option value="GH">Ghana</option>
                            <option value="GI">Gibraltar</option>
                            <option value="GR">Greece</option>
                            <option value="GL">Greenland</option>
                            <option value="GD">Grenada</option>
                            <option value="GP">Guadeloupe</option>
                            <option value="GU">Guam</option>
                            <option value="GT">Guatemala</option>
                            <option value="GG">Guernsey</option>
                            <option value="GN">Guinea</option>
                            <option value="GW">Guinea-Bissau</option>
                            <option value="GY">Guyana</option>
                            <option value="HT">Haiti</option>
                            <option value="HM">Heard Island and McDonald Islands</option>
                            <option value="VA">Holy See (Vatican City State)</option>
                            <option value="HN">Honduras</option>
                            <option value="HK">Hong Kong</option>
                            <option value="HU">Hungary</option>
                            <option value="IS">Iceland</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IR">Iran, Islamic Republic of</option>
                            <option value="IQ">Iraq</option>
                            <option value="IE">Ireland</option>
                            <option value="IM">Isle of Man</option>
                            <option value="IL">Israel</option>
                            <option value="IT">Italy</option>
                            <option value="JM">Jamaica</option>
                            <option value="JP">Japan</option>
                            <option value="JE">Jersey</option>
                            <option value="JO">Jordan</option>
                            <option value="KZ">Kazakhstan</option>
                            <option value="KE">Kenya</option>
                            <option value="KI">Kiribati</option>
                            <option value="KP">Korea, Democratic People's Republic of</option>
                            <option value="KR">Korea, Republic of</option>
                            <option value="KW">Kuwait</option>
                            <option value="KG">Kyrgyzstan</option>
                            <option value="LA">Lao People's Democratic Republic</option>
                            <option value="LV">Latvia</option>
                            <option value="LB">Lebanon</option>
                            <option value="LS">Lesotho</option>
                            <option value="LR">Liberia</option>
                            <option value="LY">Libya</option>
                            <option value="LI">Liechtenstein</option>
                            <option value="LT">Lithuania</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MO">Macao</option>
                            <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                            <option value="MG">Madagascar</option>
                            <option value="MW">Malawi</option>
                            <option value="MY">Malaysia</option>
                            <option value="MV">Maldives</option>
                            <option value="ML">Mali</option>
                            <option value="MT">Malta</option>
                            <option value="MH">Marshall Islands</option>
                            <option value="MQ">Martinique</option>
                            <option value="MR">Mauritania</option>
                            <option value="MU">Mauritius</option>
                            <option value="YT">Mayotte</option>
                            <option value="MX">Mexico</option>
                            <option value="FM">Micronesia, Federated States of</option>
                            <option value="MD">Moldova, Republic of</option>
                            <option value="MC">Monaco</option>
                            <option value="MN">Mongolia</option>
                            <option value="ME">Montenegro</option>
                            <option value="MS">Montserrat</option>
                            <option value="MA">Morocco</option>
                            <option value="MZ">Mozambique</option>
                            <option value="MM">Myanmar</option>
                            <option value="NA">Namibia</option>
                            <option value="NR">Nauru</option>
                            <option value="NP">Nepal</option>
                            <option value="NL">Netherlands</option>
                            <option value="NC">New Caledonia</option>
                            <option value="NZ">New Zealand</option>
                            <option value="NI">Nicaragua</option>
                            <option value="NE">Niger</option>
                            <option value="NG">Nigeria</option>
                            <option value="NU">Niue</option>
                            <option value="NF">Norfolk Island</option>
                            <option value="MP">Northern Mariana Islands</option>
                            <option value="NO">Norway</option>
                            <option value="OM">Oman</option>
                            <option value="PK">Pakistan</option>
                            <option value="PW">Palau</option>
                            <option value="PS">Palestinian Territory, Occupied</option>
                            <option value="PA">Panama</option>
                            <option value="PG">Papua New Guinea</option>
                            <option value="PY">Paraguay</option>
                            <option value="PE">Peru</option>
                            <option value="PH">Philippines</option>
                            <option value="PN">Pitcairn</option>
                            <option value="PL">Poland</option>
                            <option value="PT">Portugal</option>
                            <option value="PR">Puerto Rico</option>
                            <option value="QA">Qatar</option>
                            <option value="RE">Réunion</option>
                            <option value="RO">Romania</option>
                            <option value="RU">Russian Federation</option>
                            <option value="RW">Rwanda</option>
                            <option value="BL">Saint Barthélemy</option>
                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value="KN">Saint Kitts and Nevis</option>
                            <option value="LC">Saint Lucia</option>
                            <option value="MF">Saint Martin (French part)</option>
                            <option value="PM">Saint Pierre and Miquelon</option>
                            <option value="VC">Saint Vincent and the Grenadines</option>
                            <option value="WS">Samoa</option>
                            <option value="SM">San Marino</option>
                            <option value="ST">Sao Tome and Principe</option>
                            <option value="SA">Saudi Arabia</option>
                            <option value="SN">Senegal</option>
                            <option value="RS">Serbia</option>
                            <option value="SC">Seychelles</option>
                            <option value="SL">Sierra Leone</option>
                            <option value="SG">Singapore</option>
                            <option value="SX">Sint Maarten (Dutch part)</option>
                            <option value="SK">Slovakia</option>
                            <option value="SI">Slovenia</option>
                            <option value="SB">Solomon Islands</option>
                            <option value="SO">Somalia</option>
                            <option value="ZA">South Africa</option>
                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                            <option value="SS">South Sudan</option>
                            <option value="ES">Spain</option>
                            <option value="LK">Sri Lanka</option>
                            <option value="SD">Sudan</option>
                            <option value="SR">Suriname</option>
                            <option value="SJ">Svalbard and Jan Mayen</option>
                            <option value="SZ">Swaziland</option>
                            <option value="SE">Sweden</option>
                            <option value="CH">Switzerland</option>
                            <option value="SY">Syrian Arab Republic</option>
                            <option value="TW">Taiwan, Province of China</option>
                            <option value="TJ">Tajikistan</option>
                            <option value="TZ">Tanzania, United Republic of</option>
                            <option value="TH">Thailand</option>
                            <option value="TL">Timor-Leste</option>
                            <option value="TG">Togo</option>
                            <option value="TK">Tokelau</option>
                            <option value="TO">Tonga</option>
                            <option value="TT">Trinidad and Tobago</option>
                            <option value="TN">Tunisia</option>
                            <option value="TR">Turkey</option>
                            <option value="TM">Turkmenistan</option>
                            <option value="TC">Turks and Caicos Islands</option>
                            <option value="TV">Tuvalu</option>
                            <option value="UG">Uganda</option>
                            <option value="UA">Ukraine</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="GB">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="UM">United States Minor Outlying Islands</option>
                            <option value="UY">Uruguay</option>
                            <option value="UZ">Uzbekistan</option>
                            <option value="VU">Vanuatu</option>
                            <option value="VE">Venezuela, Bolivarian Republic of</option>
                            <option value="VN">Viet Nam</option>
                            <option value="VG">Virgin Islands, British</option>
                            <option value="VI">Virgin Islands, U.S.</option>
                            <option value="WF">Wallis and Futuna</option>
                            <option value="EH">Western Sahara</option>
                            <option value="YE">Yemen</option>
                            <option value="ZM">Zambia</option>
                            <option value="ZW">Zimbabwe</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Currency', 'kanda' ); ?></label>
                    <?php if( $currencies = kanda_get_theme_option( 'exchange_active_currencies' ) ) { ?>
                        <div class="select-wrap col-lg-7">
                            <select class="custom-select" name="currency">
                                <?php foreach( $currencies as $currency ) { ?>
                                    <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-6">
                <legend><?php esc_html_e( 'SELECT YOUR TRAVEL DATES', 'kanda' ); ?></legend>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Check In date', 'kanda' ); ?></label>
                    <div class="calendar-field col-lg-7">
                        <input type="text" name="check_in" class="form-control datepicker-checkin">
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Check Out date', 'kanda' ); ?></label>
                    <div class="calendar-field col-lg-7">
                        <input type="text" name="check_out" class="form-control datepicker-checkout">
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Number Of Nights', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <input id="nights_count" name="nights_count" type="number" class="form-control -sm" min="1" value="1">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldset row clearfix">
        <div class="col-sm-6">
            <legend><?php esc_html_e( 'SELECT YOUR ROOM/S', 'kanda' ); ?></legend>
            <div class="form-group row clearfix">
                <label class="form-label col-lg-5"><?php esc_html_e( 'How Many Rooms Do You Require?', 'kanda' ); ?></label>
                <div class="select-wrap col-lg-7">
                    <select class="custom-select" name="rooms_count" id="rooms_count">
                        <option selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="fieldset row">
        <div class="col-sm-6 occupants occupants-cloneable" data-index="1">
            <div class="box">
                <legend><?php printf( __( 'ROOM <span>%d</span> OCCUPANTS', 'kanda' ), 1 ); ?></legend>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Adults', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <select class="custom-select" name="room_occupants[1][adults]">
                            <option value="1"><?php esc_html_e( '1 Adult - Single', 'kanda' ); ?></option>
                            <option value="2"><?php esc_html_e( '2 Adults - Double', 'kanda' ); ?></option>
                            <option value="3"><?php esc_html_e( '3 Adults - Triple', 'kanda' ); ?></option>
                            <option value="4"><?php esc_html_e( '4 Adults - Quad', 'kanda' ); ?></option>
                            <option value="5"><?php esc_html_e( '5 Adults', 'kanda' ); ?></option>
                            <option value="6"><?php esc_html_e( '6 Adults', 'kanda' ); ?></option>
                            <option value="7"><?php esc_html_e( '7 Adults', 'kanda' ); ?></option>
                            <option value="8"><?php esc_html_e( '8 Adults', 'kanda' ); ?></option>
                            <option value="9"><?php esc_html_e( '9 Adults', 'kanda' ); ?></option>
                            <option value="10"><?php esc_html_e( '10 Adults', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Children', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <select class="custom-select children-presence" name="room_occupants[1][child]">
                            <option value="0"><?php esc_html_e( 'Without children', 'kanda' ); ?></option>
                            <option value="1"><?php esc_html_e( '1 Child', 'kanda' ); ?></option>
                            <option value="2"><?php esc_html_e( '2 Children', 'kanda' ); ?></option>
                            <option value="3"><?php esc_html_e( '3 Children', 'kanda' ); ?></option>
                            <option value="4"><?php esc_html_e( '4 Children', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row clearfix text-center children-age-box hidden">
                    <small class="form-text text-muted"><?php esc_html_e( 'Please Specify Ages Of Children', 'kanda' ); ?></small>
                    <div class="children-ages"></div>
                </div>
            </div>
        </div>

    </fieldset>

    <footer class="form-footer clearfix">
        <input type="hidden" name="security" value="<?php echo wp_create_nonce( 'kanda-hotel-search' ); ?>" />
        <input type="submit" name="kanda_search" value="Search hotel" class="btn -primary pull-right">
    </footer>
</form>