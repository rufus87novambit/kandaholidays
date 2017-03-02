<form class="form-block" id="hotel_search_form" method="post">

    <?php $city = isset( $city ) ? $city : 'AUH'; ?>
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'SELECT DESTINATION', 'kanda' ); ?></legend>
        <ul class="block-sm-3 clearfix">
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="AUH" <?php checked( 'AUH', $city ); ?>>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Abu Dhabi', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="AJMA" <?php checked( 'AJMA', $city ); ?>>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Ajman', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="ALAZ" <?php checked( 'ALAZ', $city ); ?>>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Al Ain', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="DXB" <?php checked( 'DXB', $city ); ?>>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Dubai', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="FUJA" <?php checked( 'FUJA', $city ); ?>>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php esc_html_e( 'Fujairah', 'kanda' ); ?></span>
                </label>
            </li>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="RASK" <?php checked( 'RASK', $city ); ?>>
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

                <?php $hotel_name = isset( $hotel_name ) ? $hotel_name : ''; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></label>
                    <div class="col-lg-7">
                        <input type="text" name="hotel_name" class="form-control" value="<?php echo $hotel_name; ?>">
                    </div>
                </div>

                <?php $star_rating = isset( $star_rating ) ? $star_rating : 3; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Rating', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <select class="rating" name="star_rating">
                            <option value="" <?php selected( "", $star_rating ); ?>></option>
                            <option value="2" <?php selected( 2, $star_rating ); ?>></option>
                            <option value="2" <?php selected( 2, $star_rating ); ?>></option>
                            <option value="3" <?php selected( 3, $star_rating ); ?>></option>
                            <option value="4" <?php selected( 4, $star_rating ); ?>></option>
                            <option value="5" <?php selected( 5, $star_rating ); ?>></option>
                        </select>
                    </div>
                </div>

                <?php $include_on_request = isset( $include_on_request ) ? $include_on_request : 0; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Hotels In Request', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="include_on_request">
                            <option value="1" <?php selected( 1, $include_on_request ); ?>><?php esc_html_e( 'Available & On Request', 'kanda' ); ?></option>
                            <option value="0" <?php selected( 0, $include_on_request ); ?>><?php esc_html_e( 'Only Available', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>

                <?php $nationality = isset( $nationality ) ? $nationality : 'AM'; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Nationality', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="nationality">
                            <option value="AF" <?php selected( "AF", $nationality ); ?>>Afghanistan</option>
                            <option value="AX" <?php selected( "AX", $nationality ); ?>>Åland Islands</option>
                            <option value="AL" <?php selected( "AL", $nationality ); ?>>Albania</option>
                            <option value="DZ" <?php selected( "DZ", $nationality ); ?>>Algeria</option>
                            <option value="AS" <?php selected( "AS", $nationality ); ?>>American Samoa</option>
                            <option value="AD" <?php selected( "AD", $nationality ); ?>>Andorra</option>
                            <option value="AO" <?php selected( "AO", $nationality ); ?>>Angola</option>
                            <option value="AI" <?php selected( "AI", $nationality ); ?>>Anguilla</option>
                            <option value="AQ" <?php selected( "AQ", $nationality ); ?>>Antarctica</option>
                            <option value="AG" <?php selected( "AG", $nationality ); ?>>Antigua and Barbuda</option>
                            <option value="AR" <?php selected( "AR", $nationality ); ?>>Argentina</option>
                            <option value="AM" <?php selected( "AM", $nationality ); ?>>Armenia</option>
                            <option value="AW" <?php selected( "AW", $nationality ); ?>>Aruba</option>
                            <option value="AU" <?php selected( "AU", $nationality ); ?>>Australia</option>
                            <option value="AT" <?php selected( "AT", $nationality ); ?>>Austria</option>
                            <option value="AZ" <?php selected( "AZ", $nationality ); ?>>Azerbaijan</option>
                            <option value="BS" <?php selected( "BS", $nationality ); ?>>Bahamas</option>
                            <option value="BH" <?php selected( "BH", $nationality ); ?>>Bahrain</option>
                            <option value="BD" <?php selected( "BD", $nationality ); ?>>Bangladesh</option>
                            <option value="BB" <?php selected( "BB", $nationality ); ?>>Barbados</option>
                            <option value="BY" <?php selected( "BY", $nationality ); ?>>Belarus</option>
                            <option value="BE" <?php selected( "BE", $nationality ); ?>>Belgium</option>
                            <option value="BZ" <?php selected( "BZ", $nationality ); ?>>Belize</option>
                            <option value="BJ" <?php selected( "BJ", $nationality ); ?>>Benin</option>
                            <option value="BM" <?php selected( "BM", $nationality ); ?>>Bermuda</option>
                            <option value="BT" <?php selected( "BT", $nationality ); ?>>Bhutan</option>
                            <option value="BO" <?php selected( "BO", $nationality ); ?>>Bolivia, Plurinational State of</option>
                            <option value="BQ" <?php selected( "BQ", $nationality ); ?>>Bonaire, Sint Eustatius and Saba</option>
                            <option value="BA" <?php selected( "BA", $nationality ); ?>>Bosnia and Herzegovina</option>
                            <option value="BW" <?php selected( "BW", $nationality ); ?>>Botswana</option>
                            <option value="BV" <?php selected( "BV", $nationality ); ?>>Bouvet Island</option>
                            <option value="BR" <?php selected( "BR", $nationality ); ?>>Brazil</option>
                            <option value="IO" <?php selected( "IO", $nationality ); ?>>British Indian Ocean Territory</option>
                            <option value="BN" <?php selected( "BN", $nationality ); ?>>Brunei Darussalam</option>
                            <option value="BG" <?php selected( "BG", $nationality ); ?>>Bulgaria</option>
                            <option value="BF" <?php selected( "BF", $nationality ); ?>>Burkina Faso</option>
                            <option value="BI" <?php selected( "BI", $nationality ); ?>>Burundi</option>
                            <option value="KH" <?php selected( "KH", $nationality ); ?>>Cambodia</option>
                            <option value="CM" <?php selected( "CM", $nationality ); ?>>Cameroon</option>
                            <option value="CA" <?php selected( "CA", $nationality ); ?>>Canada</option>
                            <option value="CV" <?php selected( "CV", $nationality ); ?>>Cape Verde</option>
                            <option value="KY" <?php selected( "KY", $nationality ); ?>>Cayman Islands</option>
                            <option value="CF" <?php selected( "CF", $nationality ); ?>>Central African Republic</option>
                            <option value="TD" <?php selected( "TD", $nationality ); ?>>Chad</option>
                            <option value="CL" <?php selected( "CL", $nationality ); ?>>Chile</option>
                            <option value="CN" <?php selected( "CN", $nationality ); ?>>China</option>
                            <option value="CX" <?php selected( "CX", $nationality ); ?>>Christmas Island</option>
                            <option value="CC" <?php selected( "CC", $nationality ); ?>>Cocos (Keeling) Islands</option>
                            <option value="CO" <?php selected( "CO", $nationality ); ?>>Colombia</option>
                            <option value="KM" <?php selected( "KM", $nationality ); ?>>Comoros</option>
                            <option value="CG" <?php selected( "CG", $nationality ); ?>>Congo</option>
                            <option value="CD" <?php selected( "CD", $nationality ); ?>>Congo, the Democratic Republic of the</option>
                            <option value="CK" <?php selected( "CK", $nationality ); ?>>Cook Islands</option>
                            <option value="CR" <?php selected( "CR", $nationality ); ?>>Costa Rica</option>
                            <option value="HR" <?php selected( "HR", $nationality ); ?>>Côte d'Ivoire</option>
                            <option value="HR" <?php selected( "HR", $nationality ); ?>>Croatia</option>
                            <option value="CU" <?php selected( "CU", $nationality ); ?>>Cuba</option>
                            <option value="CW" <?php selected( "CW", $nationality ); ?>>Curaçao</option>
                            <option value="CY" <?php selected( "CY", $nationality ); ?>>Cyprus</option>
                            <option value="CZ" <?php selected( "CZ", $nationality ); ?>>Czech Republic</option>
                            <option value="DK" <?php selected( "DK", $nationality ); ?>>Denmark</option>
                            <option value="DJ" <?php selected( "DJ", $nationality ); ?>>Djibouti</option>
                            <option value="DM" <?php selected( "DM", $nationality ); ?>>Dominica</option>
                            <option value="DO" <?php selected( "DO", $nationality ); ?>>Dominican Republic</option>
                            <option value="EC" <?php selected( "EC", $nationality ); ?>>Ecuador</option>
                            <option value="EG" <?php selected( "EG", $nationality ); ?>>Egypt</option>
                            <option value="SV" <?php selected( "SV", $nationality ); ?>>El Salvador</option>
                            <option value="GQ" <?php selected( "GQ", $nationality ); ?>>Equatorial Guinea</option>
                            <option value="ER" <?php selected( "ER", $nationality ); ?>>Eritrea</option>
                            <option value="EE" <?php selected( "EE", $nationality ); ?>>Estonia</option>
                            <option value="ET" <?php selected( "ET", $nationality ); ?>>Ethiopia</option>
                            <option value="FK" <?php selected( "FK", $nationality ); ?>>Falkland Islands (Malvinas)</option>
                            <option value="FO" <?php selected( "FO", $nationality ); ?>>Faroe Islands</option>
                            <option value="FJ" <?php selected( "FJ", $nationality ); ?>>Fiji</option>
                            <option value="FI" <?php selected( "FI", $nationality ); ?>>Finland</option>
                            <option value="FR" <?php selected( "FR", $nationality ); ?>>France</option>
                            <option value="GF" <?php selected( "GF", $nationality ); ?>>French Guiana</option>
                            <option value="PF" <?php selected( "PF", $nationality ); ?>>French Polynesia</option>
                            <option value="TF" <?php selected( "TF", $nationality ); ?>>French Southern Territories</option>
                            <option value="GA" <?php selected( "GA", $nationality ); ?>>Gabon</option>
                            <option value="GM" <?php selected( "GM", $nationality ); ?>>Gambia</option>
                            <option value="GE" <?php selected( "GE", $nationality ); ?>>Georgia</option>
                            <option value="DE" <?php selected( "DE", $nationality ); ?>>Germany</option>
                            <option value="GH" <?php selected( "GH", $nationality ); ?>>Ghana</option>
                            <option value="GI" <?php selected( "GI", $nationality ); ?>>Gibraltar</option>
                            <option value="GR" <?php selected( "GR", $nationality ); ?>>Greece</option>
                            <option value="GL" <?php selected( "GL", $nationality ); ?>>Greenland</option>
                            <option value="GD" <?php selected( "GD", $nationality ); ?>>Grenada</option>
                            <option value="GP" <?php selected( "GP", $nationality ); ?>>Guadeloupe</option>
                            <option value="GU" <?php selected( "GU", $nationality ); ?>>Guam</option>
                            <option value="GT" <?php selected( "GT", $nationality ); ?>>Guatemala</option>
                            <option value="GG" <?php selected( "GG", $nationality ); ?>>Guernsey</option>
                            <option value="GN" <?php selected( "GN", $nationality ); ?>>Guinea</option>
                            <option value="GW" <?php selected( "GW", $nationality ); ?>>Guinea-Bissau</option>
                            <option value="GY" <?php selected( "GY", $nationality ); ?>>Guyana</option>
                            <option value="HT" <?php selected( "HT", $nationality ); ?>>Haiti</option>
                            <option value="HM" <?php selected( "HM", $nationality ); ?>>Heard Island and McDonald Islands</option>
                            <option value="VA" <?php selected( "VA", $nationality ); ?>>Holy See (Vatican City State)</option>
                            <option value="HN" <?php selected( "HN", $nationality ); ?>>Honduras</option>
                            <option value="HK" <?php selected( "HK", $nationality ); ?>>Hong Kong</option>
                            <option value="HU" <?php selected( "HU", $nationality ); ?>>Hungary</option>
                            <option value="IS" <?php selected( "IS", $nationality ); ?>>Iceland</option>
                            <option value="IN" <?php selected( "IN", $nationality ); ?>>India</option>
                            <option value="ID" <?php selected( "ID", $nationality ); ?>>Indonesia</option>
                            <option value="IR" <?php selected( "IR", $nationality ); ?>>Iran, Islamic Republic of</option>
                            <option value="IQ" <?php selected( "IQ", $nationality ); ?>>Iraq</option>
                            <option value="IE" <?php selected( "IE", $nationality ); ?>>Ireland</option>
                            <option value="IM" <?php selected( "IM", $nationality ); ?>>Isle of Man</option>
                            <option value="IL" <?php selected( "IL", $nationality ); ?>>Israel</option>
                            <option value="IT" <?php selected( "IT", $nationality ); ?>>Italy</option>
                            <option value="JM" <?php selected( "JM", $nationality ); ?>>Jamaica</option>
                            <option value="JP" <?php selected( "JP", $nationality ); ?>>Japan</option>
                            <option value="JE" <?php selected( "JE", $nationality ); ?>>Jersey</option>
                            <option value="JO" <?php selected( "JO", $nationality ); ?>>Jordan</option>
                            <option value="KZ" <?php selected( "KZ", $nationality ); ?>>Kazakhstan</option>
                            <option value="KE" <?php selected( "KE", $nationality ); ?>>Kenya</option>
                            <option value="KI" <?php selected( "KI", $nationality ); ?>>Kiribati</option>
                            <option value="KP" <?php selected( "KP", $nationality ); ?>>Korea, Democratic People's Republic of</option>
                            <option value="KR" <?php selected( "KR", $nationality ); ?>>Korea, Republic of</option>
                            <option value="KW" <?php selected( "KW", $nationality ); ?>>Kuwait</option>
                            <option value="KG" <?php selected( "KG", $nationality ); ?>>Kyrgyzstan</option>
                            <option value="LA" <?php selected( "LA", $nationality ); ?>>Lao People's Democratic Republic</option>
                            <option value="LV" <?php selected( "LV", $nationality ); ?>>Latvia</option>
                            <option value="LB" <?php selected( "LB", $nationality ); ?>>Lebanon</option>
                            <option value="LS" <?php selected( "LS", $nationality ); ?>>Lesotho</option>
                            <option value="LR" <?php selected( "LR", $nationality ); ?>>Liberia</option>
                            <option value="LY" <?php selected( "LY", $nationality ); ?>>Libya</option>
                            <option value="LI" <?php selected( "LI", $nationality ); ?>>Liechtenstein</option>
                            <option value="LT" <?php selected( "LT", $nationality ); ?>>Lithuania</option>
                            <option value="LU" <?php selected( "LU", $nationality ); ?>>Luxembourg</option>
                            <option value="MO" <?php selected( "MO", $nationality ); ?>>Macao</option>
                            <option value="MK" <?php selected( "MK", $nationality ); ?>>Macedonia, the former Yugoslav Republic of</option>
                            <option value="MG" <?php selected( "MG", $nationality ); ?>>Madagascar</option>
                            <option value="MW" <?php selected( "MW", $nationality ); ?>>Malawi</option>
                            <option value="MY" <?php selected( "MY", $nationality ); ?>>Malaysia</option>
                            <option value="MV" <?php selected( "MV", $nationality ); ?>>Maldives</option>
                            <option value="ML" <?php selected( "ML", $nationality ); ?>>Mali</option>
                            <option value="MT" <?php selected( "MT", $nationality ); ?>>Malta</option>
                            <option value="MH" <?php selected( "MH", $nationality ); ?>>Marshall Islands</option>
                            <option value="MQ" <?php selected( "MQ", $nationality ); ?>>Martinique</option>
                            <option value="MR" <?php selected( "MR", $nationality ); ?>>Mauritania</option>
                            <option value="MU" <?php selected( "MU", $nationality ); ?>>Mauritius</option>
                            <option value="YT" <?php selected( "YT", $nationality ); ?>>Mayotte</option>
                            <option value="MX" <?php selected( "MX", $nationality ); ?>>Mexico</option>
                            <option value="FM" <?php selected( "FM", $nationality ); ?>>Micronesia, Federated States of</option>
                            <option value="MD" <?php selected( "MD", $nationality ); ?>>Moldova, Republic of</option>
                            <option value="MC" <?php selected( "MC", $nationality ); ?>>Monaco</option>
                            <option value="MN" <?php selected( "MN", $nationality ); ?>>Mongolia</option>
                            <option value="ME" <?php selected( "ME", $nationality ); ?>>Montenegro</option>
                            <option value="MS" <?php selected( "MS", $nationality ); ?>>Montserrat</option>
                            <option value="MA" <?php selected( "MA", $nationality ); ?>>Morocco</option>
                            <option value="MZ" <?php selected( "MZ", $nationality ); ?>>Mozambique</option>
                            <option value="MM" <?php selected( "MM", $nationality ); ?>>Myanmar</option>
                            <option value="NA" <?php selected( "NA", $nationality ); ?>>Namibia</option>
                            <option value="NR" <?php selected( "NR", $nationality ); ?>>Nauru</option>
                            <option value="NP" <?php selected( "NP", $nationality ); ?>>Nepal</option>
                            <option value="NL" <?php selected( "NL", $nationality ); ?>>Netherlands</option>
                            <option value="NC" <?php selected( "NC", $nationality ); ?>>New Caledonia</option>
                            <option value="NZ" <?php selected( "NZ", $nationality ); ?>>New Zealand</option>
                            <option value="NI" <?php selected( "NI", $nationality ); ?>>Nicaragua</option>
                            <option value="NE" <?php selected( "NE", $nationality ); ?>>Niger</option>
                            <option value="NG" <?php selected( "NG", $nationality ); ?>>Nigeria</option>
                            <option value="NU" <?php selected( "NU", $nationality ); ?>>Niue</option>
                            <option value="NF" <?php selected( "NF", $nationality ); ?>>Norfolk Island</option>
                            <option value="MP" <?php selected( "MP", $nationality ); ?>>Northern Mariana Islands</option>
                            <option value="NO" <?php selected( "NO", $nationality ); ?>>Norway</option>
                            <option value="OM" <?php selected( "OM", $nationality ); ?>>Oman</option>
                            <option value="PK" <?php selected( "PK", $nationality ); ?>>Pakistan</option>
                            <option value="PW" <?php selected( "PW", $nationality ); ?>>Palau</option>
                            <option value="PS" <?php selected( "PS", $nationality ); ?>>Palestinian Territory, Occupied</option>
                            <option value="PA" <?php selected( "PA", $nationality ); ?>>Panama</option>
                            <option value="PG" <?php selected( "PG", $nationality ); ?>>Papua New Guinea</option>
                            <option value="PY" <?php selected( "PY", $nationality ); ?>>Paraguay</option>
                            <option value="PE" <?php selected( "PE", $nationality ); ?>>Peru</option>
                            <option value="PH" <?php selected( "PH", $nationality ); ?>>Philippines</option>
                            <option value="PN" <?php selected( "PN", $nationality ); ?>>Pitcairn</option>
                            <option value="PL" <?php selected( "PL", $nationality ); ?>>Poland</option>
                            <option value="PT" <?php selected( "PT", $nationality ); ?>>Portugal</option>
                            <option value="PR" <?php selected( "PR", $nationality ); ?>>Puerto Rico</option>
                            <option value="QA" <?php selected( "QA", $nationality ); ?>>Qatar</option>
                            <option value="RE" <?php selected( "RE", $nationality ); ?>>Réunion</option>
                            <option value="RO" <?php selected( "RO", $nationality ); ?>>Romania</option>
                            <option value="RU" <?php selected( "RU", $nationality ); ?>>Russian Federation</option>
                            <option value="RW" <?php selected( "RW", $nationality ); ?>>Rwanda</option>
                            <option value="BL" <?php selected( "BL", $nationality ); ?>>Saint Barthélemy</option>
                            <option value="SH" <?php selected( "SH", $nationality ); ?>>Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value="KN" <?php selected( "KN", $nationality ); ?>>Saint Kitts and Nevis</option>
                            <option value="LC" <?php selected( "LC", $nationality ); ?>>Saint Lucia</option>
                            <option value="MF" <?php selected( "MF", $nationality ); ?>>Saint Martin (French part)</option>
                            <option value="PM" <?php selected( "PM", $nationality ); ?>>Saint Pierre and Miquelon</option>
                            <option value="VC" <?php selected( "VC", $nationality ); ?>>Saint Vincent and the Grenadines</option>
                            <option value="WS" <?php selected( "WS", $nationality ); ?>>Samoa</option>
                            <option value="SM" <?php selected( "SM", $nationality ); ?>>San Marino</option>
                            <option value="ST" <?php selected( "ST", $nationality ); ?>>Sao Tome and Principe</option>
                            <option value="SA" <?php selected( "SA", $nationality ); ?>>Saudi Arabia</option>
                            <option value="SN" <?php selected( "SN", $nationality ); ?>>Senegal</option>
                            <option value="RS" <?php selected( "RS", $nationality ); ?>>Serbia</option>
                            <option value="SC" <?php selected( "SC", $nationality ); ?>>Seychelles</option>
                            <option value="SL" <?php selected( "SL", $nationality ); ?>>Sierra Leone</option>
                            <option value="SG" <?php selected( "SG", $nationality ); ?>>Singapore</option>
                            <option value="SX" <?php selected( "SX", $nationality ); ?>>Sint Maarten (Dutch part)</option>
                            <option value="SK" <?php selected( "SK", $nationality ); ?>>Slovakia</option>
                            <option value="SI" <?php selected( "SI", $nationality ); ?>>Slovenia</option>
                            <option value="SB" <?php selected( "SB", $nationality ); ?>>Solomon Islands</option>
                            <option value="SO" <?php selected( "SO", $nationality ); ?>>Somalia</option>
                            <option value="ZA" <?php selected( "ZA", $nationality ); ?>>South Africa</option>
                            <option value="GS" <?php selected( "GS", $nationality ); ?>>South Georgia and the South Sandwich Islands</option>
                            <option value="SS" <?php selected( "SS", $nationality ); ?>>South Sudan</option>
                            <option value="ES" <?php selected( "ES", $nationality ); ?>>Spain</option>
                            <option value="LK" <?php selected( "LK", $nationality ); ?>>Sri Lanka</option>
                            <option value="SD" <?php selected( "SD", $nationality ); ?>>Sudan</option>
                            <option value="SR" <?php selected( "SR", $nationality ); ?>>Suriname</option>
                            <option value="SJ" <?php selected( "SJ", $nationality ); ?>>Svalbard and Jan Mayen</option>
                            <option value="SZ" <?php selected( "SZ", $nationality ); ?>>Swaziland</option>
                            <option value="SE" <?php selected( "SE", $nationality ); ?>>Sweden</option>
                            <option value="CH" <?php selected( "CH", $nationality ); ?>>Switzerland</option>
                            <option value="SY" <?php selected( "SY", $nationality ); ?>>Syrian Arab Republic</option>
                            <option value="TJ" <?php selected( "TJ", $nationality ); ?>>Taiwan, Province of China</option>
                            <option value="TJ" <?php selected( "TJ", $nationality ); ?>>Tajikistan</option>
                            <option value="TZ" <?php selected( "TZ", $nationality ); ?>>Tanzania, United Republic of</option>
                            <option value="TH" <?php selected( "TH", $nationality ); ?>>Thailand</option>
                            <option value="TL" <?php selected( "TL", $nationality ); ?>>Timor-Leste</option>
                            <option value="TG" <?php selected( "TG", $nationality ); ?>>Togo</option>
                            <option value="TO" <?php selected( "TO", $nationality ); ?>>Tokelau</option>
                            <option value="TO" <?php selected( "TO", $nationality ); ?>>Tonga</option>
                            <option value="TT" <?php selected( "TT", $nationality ); ?>>Trinidad and Tobago</option>
                            <option value="TN" <?php selected( "TN", $nationality ); ?>>Tunisia</option>
                            <option value="TR" <?php selected( "TR", $nationality ); ?>>Turkey</option>
                            <option value="TM" <?php selected( "TM", $nationality ); ?>>Turkmenistan</option>
                            <option value="TC" <?php selected( "TC", $nationality ); ?>>Turks and Caicos Islands</option>
                            <option value="TV" <?php selected( "TV", $nationality ); ?>>Tuvalu</option>
                            <option value="UG" <?php selected( "UG", $nationality ); ?>>Uganda</option>
                            <option value="UA" <?php selected( "UA", $nationality ); ?>>Ukraine</option>
                            <option value="AE" <?php selected( "AE", $nationality ); ?>>United Arab Emirates</option>
                            <option value="GB" <?php selected( "GB", $nationality ); ?>>United Kingdom</option>
                            <option value="US" <?php selected( "US", $nationality ); ?>>United States</option>
                            <option value="UM" <?php selected( "UM", $nationality ); ?>>United States Minor Outlying Islands</option>
                            <option value="UY" <?php selected( "UY", $nationality ); ?>>Uruguay</option>
                            <option value="UZ" <?php selected( "UZ", $nationality ); ?>>Uzbekistan</option>
                            <option value="VU" <?php selected( "VU", $nationality ); ?>>Vanuatu</option>
                            <option value="VE" <?php selected( "VE", $nationality ); ?>>Venezuela, Bolivarian Republic of</option>
                            <option value="VN" <?php selected( "VN", $nationality ); ?>>Viet Nam</option>
                            <option value="VG" <?php selected( "VG", $nationality ); ?>>Virgin Islands, British</option>
                            <option value="WF" <?php selected( "WF", $nationality ); ?>>Virgin Islands, U.S.</option>
                            <option value="WF" <?php selected( "WF", $nationality ); ?>>Wallis and Futuna</option>
                            <option value="EH" <?php selected( "EH", $nationality ); ?>>Western Sahara</option>
                            <option value="YE" <?php selected( "YE", $nationality ); ?>>Yemen</option>
                            <option value="ZM" <?php selected( "ZM", $nationality ); ?>>Zambia</option>
                            <option value="ZW" <?php selected( "ZW", $nationality ); ?>>Zimbabwe</option>
                        </select>
                    </div>
                </div>

                <?php $currency = isset( $currency ) ? $currency : 'USD'; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Currency', 'kanda' ); ?></label>
                    <?php if( $currencies = kanda_get_theme_option( 'exchange_active_currencies' ) ) { ?>
                        <div class="select-wrap col-lg-7">
                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="currency">
                                <?php foreach( $currencies as $curr ) { ?>
                                    <option value="<?php echo $curr; ?>" <?php selected( $curr, $currency ); ?>><?php echo $curr; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-6">
                <legend><?php esc_html_e( 'SELECT YOUR TRAVEL DATES', 'kanda' ); ?></legend>

                <?php $start_date = isset( $start_date ) ? $start_date : ''; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Check In date', 'kanda' ); ?></label>
                    <div class="calendar-field col-lg-7">
                        <input type="text" name="start_date" class="form-control datepicker-start-date" value="<?php echo $start_date; ?>">
                    </div>
                </div>

                <?php $end_date = isset( $end_date ) ? $end_date : ''; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Check Out date', 'kanda' ); ?></label>
                    <div class="calendar-field col-lg-7">
                        <input type="text" name="end_date" class="form-control datepicker-end-date" value="<?php echo $end_date; ?>">
                    </div>
                </div>

                <?php $nights_count = isset( $nights_count ) ? $nights_count : '1'; ?>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Number Of Nights', 'kanda' ); ?></label>
                    <div class="select-wrap col-lg-7">
                        <input id="nights_count" name="nights_count" type="number" class="form-control -sm" min="1" value="<?php echo $nights_count; ?>">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldset row clearfix">
        <div class="col-sm-6">
            <legend><?php esc_html_e( 'SELECT YOUR ROOM/S', 'kanda' ); ?></legend>

            <?php $rooms_count = isset( $rooms_count ) ? $rooms_count : 1; ?>
            <div class="form-group row clearfix">
                <label class="form-label col-lg-5"><?php esc_html_e( 'How Many Rooms Do You Require?', 'kanda' ); ?></label>
                <div class="select-wrap col-lg-7">
                    <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="rooms_count" id="rooms_count">
                        <option value="1" <?php selected( 1, $rooms_count ); ?>>1</option>
                        <option value="2" <?php selected( 2, $rooms_count ); ?>>2</option>
                        <option value="3" <?php selected( 3, $rooms_count ); ?>>3</option>
                        <option value="4" <?php selected( 4, $rooms_count ); ?>>4</option>
                        <option value="5" <?php selected( 5, $rooms_count ); ?>>5</option>
                        <option value="6" <?php selected( 6, $rooms_count ); ?>>6</option>
                        <option value="7" <?php selected( 7, $rooms_count ); ?>>7</option>
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
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="room_occupants[1][adults]">
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
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?> children-presence" name="room_occupants[1][child]">
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