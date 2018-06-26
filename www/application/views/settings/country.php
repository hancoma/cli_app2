<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>
    <div class="settings_content" id="settings_country_control">
        <div class="title_text text_grey"><?=c2ms('country');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_wrap">
                <div class="settings_content_main_value">
                    <div id="countryInitFormWrap" class="settings_content_form_wrap">
                        <div class="inline_clear">
                            <div class="form-header">
                                <div class="inline_left">
                                    <p class="country_sub_title"><?=c2ms('country_sub_title');?></p>
                                </div>
                                <div class="inline_right">
                                    <input type="checkbox" class="btn_switch btn_switch" id="countryControl"<?php if($selected_domain_info['country_access'] == 'On'):?> checked="checked"<?php endif?>>
                                </div>
                            </div>
                            <!-- Switch OFF일때만 보임 -->
                            <div id="countryOffDescription" class="country-off-description inline_clear">
                                <p><?=c2ms('country_description_1').' - '.c2ms('country_description_1_1');?></p>
                                <p><?=c2ms('country_description_2').' - '.c2ms('country_description_2_1');?></p>
                            </div>

                            <!-- Switch ON일때만 보임 -->
                            <div id="setCountryArea" class="set_country_wrap inline_clear">
                                <div class="country_top_wrap">
                                    <div class="form-input-group country_radio_group">
                                        <input type="radio" id="country_block" name="country_block_white" value="black"<?php if($selected_domain_info['country_access_type'] == 'Black'):?> checked="checked"<?php endif?>>
                                        <label for="country_block"><?php echo c2ms('country_switch_on_description_1') . " - " . c2ms('country_switch_on_description_1_1');?></label>
                                    </div>
                                    <div class="form-input-group country_radio_group">
                                        <input type="radio" id="country_white" name="country_block_white" value="white"<?php if($selected_domain_info['country_access_type'] == 'White'):?> checked="checked"<?php endif?>>
                                        <label for="country_white"><?php echo c2ms('country_switch_on_description_2') . " - " . c2ms('country_switch_on_description_2_1');?></label>
                                    </div>
                                    <div>
                                        <div id="selectCountriesWrap" class="inline_left">
                                            <select name="country_code[]" id="countriesSelect" class="form-control custom_select select2-hidden-accessible">
                                                <option value="none" disabled="" selected=""><?=c2ms('ph_select_country');?></option>
                                                <option value="AF" country="Afghanistan">Afghanistan</option>
                                                <option value="AX" country="Aland Islands">Aland Islands</option>
                                                <option value="AL" country="Albania">Albania</option>
                                                <option value="DZ" country="Algeria">Algeria</option>
                                                <option value="AS" country="American Samoa">American Samoa</option>
                                                <option value="AD" country="Andorra">Andorra</option>
                                                <option value="AO" country="Angola">Angola</option>
                                                <option value="AI" country="Anguilla">Anguilla</option>
                                                <option value="AQ" country="Antarctica">Antarctica</option>
                                                <option value="AG" country="Antigua and Barbuda">Antigua and Barbuda</option>
                                                <option value="AR" country="Argentina">Argentina</option>
                                                <option value="AM" country="Armenia">Armenia</option>
                                                <option value="AW" country="Aruba">Aruba</option>
                                                <option value="AU" country="Australia">Australia</option>
                                                <option value="AT" country="Austria">Austria</option>
                                                <option value="AZ" country="Azerbaijan">Azerbaijan</option>
                                                <option value="BS" country="Bahamas">Bahamas</option>
                                                <option value="BH" country="Bahrain">Bahrain</option>
                                                <option value="BD" country="Bangladesh">Bangladesh</option>
                                                <option value="BB" country="Barbados">Barbados</option>
                                                <option value="BY" country="Belarus">Belarus</option>
                                                <option value="BE" country="Belgium">Belgium</option>
                                                <option value="BZ" country="Belize">Belize</option>
                                                <option value="BJ" country="Benin">Benin</option>
                                                <option value="BM" country="Bermuda">Bermuda</option>
                                                <option value="BT" country="Bhutan">Bhutan</option>
                                                <option value="BO" country="Bolivia">Bolivia</option>
                                                <option value="BQ" country="Bonaire, Saint Eustatius and Saba">Bonaire, Saint Eustatius and Saba</option>
                                                <option value="BA" country="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                <option value="BW" country="Botswana">Botswana</option>
                                                <option value="BV" country="Bouvet Island">Bouvet Island</option>
                                                <option value="BR" country="Brazil">Brazil</option>
                                                <option value="IO" country="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                <option value="VG" country="British Virgin Islands">British Virgin Islands</option>
                                                <option value="BN" country="Brunei">Brunei</option>
                                                <option value="BG" country="Bulgaria">Bulgaria</option>
                                                <option value="BF" country="Burkina Faso">Burkina Faso</option>
                                                <option value="BI" country="Burundi">Burundi</option>
                                                <option value="KH" country="Cambodia">Cambodia</option>
                                                <option value="CM" country="Cameroon">Cameroon</option>
                                                <option value="CA" country="Canada">Canada</option>
                                                <option value="CV" country="Cape Verde">Cape Verde</option>
                                                <option value="KY" country="Cayman Islands">Cayman Islands</option>
                                                <option value="CF" country="Central African Republic">Central African Republic</option>
                                                <option value="TD" country="Chad">Chad</option>
                                                <option value="CL" country="Chile">Chile</option>
                                                <option value="CN" country="China">China</option>
                                                <option value="CX" country="Christmas Island">Christmas Island</option>
                                                <option value="CC" country="Cocos Islands">Cocos Islands</option>
                                                <option value="CO" country="Colombia">Colombia</option>
                                                <option value="KM" country="Comoros">Comoros</option>
                                                <option value="CK" country="Cook Islands">Cook Islands</option>
                                                <option value="CR" country="Costa Rica">Costa Rica</option>
                                                <option value="HR" country="Croatia">Croatia</option>
                                                <option value="CU" country="Cuba">Cuba</option>
                                                <option value="CW" country="Curacao">Curacao</option>
                                                <option value="CY" country="Cyprus">Cyprus</option>
                                                <option value="CZ" country="Czech Republic">Czech Republic</option>
                                                <option value="CD" country="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
                                                <option value="DK" country="Denmark">Denmark</option>
                                                <option value="DJ" country="Djibouti">Djibouti</option>
                                                <option value="DM" country="Dominica">Dominica</option>
                                                <option value="DO" country="Dominican Republic">Dominican Republic</option>
                                                <option value="TL" country="East Timor">East Timor</option>
                                                <option value="EC" country="Ecuador">Ecuador</option>
                                                <option value="EG" country="Egypt">Egypt</option>
                                                <option value="SV" country="El Salvador">El Salvador</option>
                                                <option value="GQ" country="Equatorial Guinea">Equatorial Guinea</option>
                                                <option value="ER" country="Eritrea">Eritrea</option>
                                                <option value="EE" country="Estonia">Estonia</option>
                                                <option value="ET" country="Ethiopia">Ethiopia</option>
                                                <option value="FK" country="Falkland Islands">Falkland Islands</option>
                                                <option value="FO" country="Faroe Islands">Faroe Islands</option>
                                                <option value="FJ" country="Fiji">Fiji</option>
                                                <option value="FI" country="Finland">Finland</option>
                                                <option value="FR" country="France">France</option>
                                                <option value="GF" country="French Guiana">French Guiana</option>
                                                <option value="PF" country="French Polynesia">French Polynesia</option>
                                                <option value="TF" country="French Southern Territories">French Southern Territories</option>
                                                <option value="GA" country="Gabon">Gabon</option>
                                                <option value="GM" country="Gambia">Gambia</option>
                                                <option value="GE" country="Georgia">Georgia</option>
                                                <option value="DE" country="Germany">Germany</option>
                                                <option value="GH" country="Ghana">Ghana</option>
                                                <option value="GI" country="Gibraltar">Gibraltar</option>
                                                <option value="GR" country="Greece">Greece</option>
                                                <option value="GL" country="Greenland">Greenland</option>
                                                <option value="GD" country="Grenada">Grenada</option>
                                                <option value="GP" country="Guadeloupe">Guadeloupe</option>
                                                <option value="GU" country="Guam">Guam</option>
                                                <option value="GT" country="Guatemala">Guatemala</option>
                                                <option value="GG" country="Guernsey">Guernsey</option>
                                                <option value="GN" country="Guinea">Guinea</option>
                                                <option value="GW" country="Guinea-Bissau">Guinea-Bissau</option>
                                                <option value="GY" country="Guyana">Guyana</option>
                                                <option value="HT" country="Haiti">Haiti</option>
                                                <option value="HM" country="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                                <option value="HN" country="Honduras">Honduras</option>
                                                <option value="HK" country="Hong Kong">Hong Kong</option>
                                                <option value="HU" country="Hungary">Hungary</option>
                                                <option value="IS" country="Iceland">Iceland</option>
                                                <option value="IN" country="India">India</option>
                                                <option value="ID" country="Indonesia">Indonesia</option>
                                                <option value="IR" country="Iran">Iran</option>
                                                <option value="IQ" country="Iraq">Iraq</option>
                                                <option value="IE" country="Ireland">Ireland</option>
                                                <option value="IM" country="Isle of Man">Isle of Man</option>
                                                <option value="IL" country="Israel">Israel</option>
                                                <option value="IT" country="Italy">Italy</option>
                                                <option value="CI" country="Ivory Coast">Ivory Coast</option>
                                                <option value="JM" country="Jamaica">Jamaica</option>
                                                <option value="JP" country="Japan">Japan</option>
                                                <option value="JE" country="Jersey">Jersey</option>
                                                <option value="JO" country="Jordan">Jordan</option>
                                                <option value="KZ" country="Kazakhstan">Kazakhstan</option>
                                                <option value="KE" country="Kenya">Kenya</option>
                                                <option value="KI" country="Kiribati">Kiribati</option>
                                                <option value="KR" country="Korea">Korea</option>
                                                <option value="XK" country="Kosovo">Kosovo</option>
                                                <option value="KW" country="Kuwait">Kuwait</option>
                                                <option value="KG" country="Kyrgyzstan">Kyrgyzstan</option>
                                                <option value="LA" country="Laos">Laos</option>
                                                <option value="LV" country="Latvia">Latvia</option>
                                                <option value="LB" country="Lebanon">Lebanon</option>
                                                <option value="LS" country="Lesotho">Lesotho</option>
                                                <option value="LR" country="Liberia">Liberia</option>
                                                <option value="LY" country="Libya">Libya</option>
                                                <option value="LI" country="Liechtenstein">Liechtenstein</option>
                                                <option value="LT" country="Lithuania">Lithuania</option>
                                                <option value="LU" country="Luxembourg">Luxembourg</option>
                                                <option value="MO" country="Macao">Macao</option>
                                                <option value="MK" country="Macedonia">Macedonia</option>
                                                <option value="MG" country="Madagascar">Madagascar</option>
                                                <option value="MW" country="Malawi">Malawi</option>
                                                <option value="MY" country="Malaysia">Malaysia</option>
                                                <option value="MV" country="Maldives">Maldives</option>
                                                <option value="ML" country="Mali">Mali</option>
                                                <option value="MT" country="Malta">Malta</option>
                                                <option value="MH" country="Marshall Islands">Marshall Islands</option>
                                                <option value="MQ" country="Martinique">Martinique</option>
                                                <option value="MR" country="Mauritania">Mauritania</option>
                                                <option value="MU" country="Mauritius">Mauritius</option>
                                                <option value="YT" country="Mayotte">Mayotte</option>
                                                <option value="MX" country="Mexico">Mexico</option>
                                                <option value="FM" country="Micronesia">Micronesia</option>
                                                <option value="MD" country="Moldova">Moldova</option>
                                                <option value="MC" country="Monaco">Monaco</option>
                                                <option value="MN" country="Mongolia">Mongolia</option>
                                                <option value="ME" country="Montenegro">Montenegro</option>
                                                <option value="MS" country="Montserrat">Montserrat</option>
                                                <option value="MA" country="Morocco">Morocco</option>
                                                <option value="MZ" country="Mozambique">Mozambique</option>
                                                <option value="MM" country="Myanmar">Myanmar</option>
                                                <option value="NA" country="Namibia">Namibia</option>
                                                <option value="NR" country="Nauru">Nauru</option>
                                                <option value="NP" country="Nepal">Nepal</option>
                                                <option value="NL" country="Netherlands">Netherlands</option>
                                                <option value="NC" country="New Caledonia">New Caledonia</option>
                                                <option value="NZ" country="New Zealand">New Zealand</option>
                                                <option value="NI" country="Nicaragua">Nicaragua</option>
                                                <option value="NE" country="Niger">Niger</option>
                                                <option value="NG" country="Nigeria">Nigeria</option>
                                                <option value="NU" country="Niue">Niue</option>
                                                <option value="NF" country="Norfolk Island">Norfolk Island</option>
                                                <option value="KP" country="North Korea">North Korea</option>
                                                <option value="MP" country="Northern Mariana Islands">Northern Mariana Islands</option>
                                                <option value="NO" country="Norway">Norway</option>
                                                <option value="OM" country="Oman">Oman</option>
                                                <option value="PK" country="Pakistan">Pakistan</option>
                                                <option value="PW" country="Palau">Palau</option>
                                                <option value="PS" country="Palestinian Territory">Palestinian Territory</option>
                                                <option value="PA" country="Panama">Panama</option>
                                                <option value="PG" country="Papua New Guinea">Papua New Guinea</option>
                                                <option value="PY" country="Paraguay">Paraguay</option>
                                                <option value="PE" country="Peru">Peru</option>
                                                <option value="PH" country="Philippines">Philippines</option>
                                                <option value="PN" country="Pitcairn">Pitcairn</option>
                                                <option value="PL" country="Poland">Poland</option>
                                                <option value="PT" country="Portugal">Portugal</option>
                                                <option value="PR" country="Puerto Rico">Puerto Rico</option>
                                                <option value="QA" country="Qatar">Qatar</option>
                                                <option value="CG" country="Republic of the Congo">Republic of the Congo</option>
                                                <option value="RE" country="Reunion">Reunion</option>
                                                <option value="RO" country="Romania">Romania</option>
                                                <option value="RU" country="Russia">Russia</option>
                                                <option value="RW" country="Rwanda">Rwanda</option>
                                                <option value="BL" country="Saint Barthelemy">Saint Barthelemy</option>
                                                <option value="SH" country="Saint Helena">Saint Helena</option>
                                                <option value="KN" country="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                <option value="LC" country="Saint Lucia">Saint Lucia</option>
                                                <option value="MF" country="Saint Martin">Saint Martin</option>
                                                <option value="PM" country="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                <option value="VC" country="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                                <option value="WS" country="Samoa">Samoa</option>
                                                <option value="SM" country="San Marino">San Marino</option>
                                                <option value="ST" country="Sao Tome and Principe">Sao Tome and Principe</option>
                                                <option value="SA" country="Saudi Arabia">Saudi Arabia</option>
                                                <option value="SN" country="Senegal">Senegal</option>
                                                <option value="RS" country="Serbia">Serbia</option>
                                                <option value="SC" country="Seychelles">Seychelles</option>
                                                <option value="SL" country="Sierra Leone">Sierra Leone</option>
                                                <option value="SG" country="Singapore">Singapore</option>
                                                <option value="SX" country="Sint Maarten">Sint Maarten</option>
                                                <option value="SK" country="Slovakia">Slovakia</option>
                                                <option value="SI" country="Slovenia">Slovenia</option>
                                                <option value="SB" country="Solomon Islands">Solomon Islands</option>
                                                <option value="SO" country="Somalia">Somalia</option>
                                                <option value="ZA" country="South Africa">South Africa</option>
                                                <option value="GS" country="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                                <option value="SS" country="South Sudan">South Sudan</option>
                                                <option value="ES" country="Spain">Spain</option>
                                                <option value="LK" country="Sri Lanka">Sri Lanka</option>
                                                <option value="SD" country="Sudan">Sudan</option>
                                                <option value="SR" country="Suriname">Suriname</option>
                                                <option value="SJ" country="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                <option value="SZ" country="Swaziland">Swaziland</option>
                                                <option value="SE" country="Sweden">Sweden</option>
                                                <option value="CH" country="Switzerland">Switzerland</option>
                                                <option value="SY" country="Syria">Syria</option>
                                                <option value="TW" country="Taiwan">Taiwan</option>
                                                <option value="TJ" country="Tajikistan">Tajikistan</option>
                                                <option value="TZ" country="Tanzania">Tanzania</option>
                                                <option value="TH" country="Thailand">Thailand</option>
                                                <option value="TG" country="Togo">Togo</option>
                                                <option value="TK" country="Tokelau">Tokelau</option>
                                                <option value="TO" country="Tonga">Tonga</option>
                                                <option value="TT" country="Trinidad and Tobago">Trinidad and Tobago</option>
                                                <option value="TN" country="Tunisia">Tunisia</option>
                                                <option value="TR" country="Turkey">Turkey</option>
                                                <option value="TM" country="Turkmenistan">Turkmenistan</option>
                                                <option value="TC" country="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                <option value="TV" country="Tuvalu">Tuvalu</option>
                                                <option value="VI" country="U.S. Virgin Islands">U.S. Virgin Islands</option>
                                                <option value="UG" country="Uganda">Uganda</option>
                                                <option value="UA" country="Ukraine">Ukraine</option>
                                                <option value="AE" country="United Arab Emirates">United Arab Emirates</option>
                                                <option value="GB" country="United Kingdom">United Kingdom</option>
                                                <option value="US" country="United States">United States</option>
                                                <option value="UM" country="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                <option value="UY" country="Uruguay">Uruguay</option>
                                                <option value="UZ" country="Uzbekistan">Uzbekistan</option>
                                                <option value="VU" country="Vanuatu">Vanuatu</option>
                                                <option value="VA" country="Vatican">Vatican</option>
                                                <option value="VE" country="Venezuela">Venezuela</option>
                                                <option value="VN" country="Vietnam">Vietnam</option>
                                                <option value="WF" country="Wallis and Futuna">Wallis and Futuna</option>
                                                <option value="EH" country="Western Sahara">Western Sahara</option>
                                                <option value="YE" country="Yemen">Yemen</option>
                                                <option value="ZM" country="Zambia">Zambia</option>
                                                <option value="ZW" country="Zimbabwe">Zimbabwe</option>
                                            </select>
                                        </div>
                                        <div class="form-group country_select_description inline_left">
                                            <button class="btn btn-gray btn-sm add_button" data-control-type="blacklist" type="button"><?=c2ms('add_record');?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tagit_wrapper inline_clear">
                                    <ul id="tagList" class="set_tagit_ip"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>