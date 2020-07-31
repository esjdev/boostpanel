<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p>2Checkout</p>
                <h2><?= sprintf(lang("starting_payment"), '2Checkout'); ?></h2>
            </div>

            <?= form_open('twocheckout/success', ['id' => 'payment-form']); ?>
            <input id="token" name="token" type="hidden" value="">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="payment-errors alert alert-icon alert-danger alert-dismissible d-none">
                            <i class="fe fe-alert-triangle mr-2" aria-hidden="true"></i>
                            <span class="payment-errors-message"></span>
                        </div>

                        <div class="form-group">
                            <label class="form-label text-dark font-weight-bold"><?= lang('user_information'); ?></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="<?= dataUser(logged(), 'name'); ?>" required autofocus readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" id="email" placeholder="<?= logged(); ?>" required autofocus readonly>
                        </div>
                        <div class="bg-other-blue p-2 rounded text-white mb-3">
                            <div class="form-group">
                                <label class="form-label font-weight-bold">
                                    <?= lang('input_card_number'); ?>
                                </label>
                                <input type="number" class="form-control" id="card_num" placeholder="<?= lang('input_card_number'); ?>" autocomplete="off" required>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold"><?= lang('input_expiry_date'); ?></label>
                                        <div class="input-group">
                                            <input type="number" id="exp_month" class="form-control" placeholder="MM" required>
                                            <input type="number" id="exp_year" class="form-control" placeholder="YY" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold">CVV</label>
                                        <input type="number" id="cvv" class="form-control" placeholder="CVV" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class="text-dark"><?= lang('billing_details'); ?></h5>
                        <div class="form-group">
                            <select name="country" id="country" class="form-control">
                                <option value="0"><?= lang("select_country_twocheckout"); ?></option>
                                <option value="AFG">Afghanistan</option>
                                <option value="ALA">Åland Islands</option>
                                <option value="ALB">Albania</option>
                                <option value="DZA">Algeria</option>
                                <option value="ASM">American Samoa</option>
                                <option value="AND">Andorra</option>
                                <option value="AGO">Angola</option>
                                <option value="AIA">Anguilla</option>
                                <option value="ATA">Antarctica</option>
                                <option value="ATG">Antigua and Barbuda</option>
                                <option value="ARG">Argentina</option>
                                <option value="ARM">Armenia</option>
                                <option value="ABW">Aruba</option>
                                <option value="AUS">Australia</option>
                                <option value="AUT">Austria</option>
                                <option value="AZE">Azerbaijan</option>
                                <option value="BHS">Bahamas</option>
                                <option value="BHR">Bahrain</option>
                                <option value="BGD">Bangladesh</option>
                                <option value="BRB">Barbados</option>
                                <option value="BLR">Belarus</option>
                                <option value="BEL">Belgium</option>
                                <option value="BLZ">Belize</option>
                                <option value="BEN">Benin</option>
                                <option value="BMU">Bermuda</option>
                                <option value="BTN">Bhutan</option>
                                <option value="BOL">Bolivia, Plurinational State of</option>
                                <option value="BES">Bonaire, Sint Eustatius and Saba</option>
                                <option value="BIH">Bosnia and Herzegovina</option>
                                <option value="BWA">Botswana</option>
                                <option value="BVT">Bouvet Island</option>
                                <option value="BRA">Brazil</option>
                                <option value="IOT">British Indian Ocean Territory</option>
                                <option value="BRN">Brunei Darussalam</option>
                                <option value="BGR">Bulgaria</option>
                                <option value="BFA">Burkina Faso</option>
                                <option value="BDI">Burundi</option>
                                <option value="KHM">Cambodia</option>
                                <option value="CMR">Cameroon</option>
                                <option value="CAN">Canada</option>
                                <option value="CPV">Cape Verde</option>
                                <option value="CYM">Cayman Islands</option>
                                <option value="CAF">Central African Republic</option>
                                <option value="TCD">Chad</option>
                                <option value="CHL">Chile</option>
                                <option value="CHN">China</option>
                                <option value="CXR">Christmas Island</option>
                                <option value="CCK">Cocos (Keeling) Islands</option>
                                <option value="COL">Colombia</option>
                                <option value="COM">Comoros</option>
                                <option value="COG">Congo</option>
                                <option value="COD">Congo, the Democratic Republic of the</option>
                                <option value="COK">Cook Islands</option>
                                <option value="CRI">Costa Rica</option>
                                <option value="CIV">Côte d'Ivoire</option>
                                <option value="HRV">Croatia</option>
                                <option value="CUB">Cuba</option>
                                <option value="CUW">Curaçao</option>
                                <option value="CYP">Cyprus</option>
                                <option value="CZE">Czech Republic</option>
                                <option value="DNK">Denmark</option>
                                <option value="DJI">Djibouti</option>
                                <option value="DMA">Dominica</option>
                                <option value="DOM">Dominican Republic</option>
                                <option value="ECU">Ecuador</option>
                                <option value="EGY">Egypt</option>
                                <option value="SLV">El Salvador</option>
                                <option value="GNQ">Equatorial Guinea</option>
                                <option value="ERI">Eritrea</option>
                                <option value="EST">Estonia</option>
                                <option value="ETH">Ethiopia</option>
                                <option value="FLK">Falkland Islands (Malvinas)</option>
                                <option value="FRO">Faroe Islands</option>
                                <option value="FJI">Fiji</option>
                                <option value="FIN">Finland</option>
                                <option value="FRA">France</option>
                                <option value="GUF">French Guiana</option>
                                <option value="PYF">French Polynesia</option>
                                <option value="ATF">French Southern Territories</option>
                                <option value="GAB">Gabon</option>
                                <option value="GMB">Gambia</option>
                                <option value="GEO">Georgia</option>
                                <option value="DEU">Germany</option>
                                <option value="GHA">Ghana</option>
                                <option value="GIB">Gibraltar</option>
                                <option value="GRC">Greece</option>
                                <option value="GRL">Greenland</option>
                                <option value="GRD">Grenada</option>
                                <option value="GLP">Guadeloupe</option>
                                <option value="GUM">Guam</option>
                                <option value="GTM">Guatemala</option>
                                <option value="GGY">Guernsey</option>
                                <option value="GIN">Guinea</option>
                                <option value="GNB">Guinea-Bissau</option>
                                <option value="GUY">Guyana</option>
                                <option value="HTI">Haiti</option>
                                <option value="HMD">Heard Island and McDonald Islands</option>
                                <option value="VAT">Holy See (Vatican City State)</option>
                                <option value="HND">Honduras</option>
                                <option value="HKG">Hong Kong</option>
                                <option value="HUN">Hungary</option>
                                <option value="ISL">Iceland</option>
                                <option value="IND">India</option>
                                <option value="IDN">Indonesia</option>
                                <option value="IRN">Iran, Islamic Republic of</option>
                                <option value="IRQ">Iraq</option>
                                <option value="IRL">Ireland</option>
                                <option value="IMN">Isle of Man</option>
                                <option value="ISR">Israel</option>
                                <option value="ITA">Italy</option>
                                <option value="JAM">Jamaica</option>
                                <option value="JPN">Japan</option>
                                <option value="JEY">Jersey</option>
                                <option value="JOR">Jordan</option>
                                <option value="KAZ">Kazakhstan</option>
                                <option value="KEN">Kenya</option>
                                <option value="KIR">Kiribati</option>
                                <option value="PRK">Korea, Democratic People's Republic of</option>
                                <option value="KOR">Korea, Republic of</option>
                                <option value="KWT">Kuwait</option>
                                <option value="KGZ">Kyrgyzstan</option>
                                <option value="LAO">Lao People's Democratic Republic</option>
                                <option value="LVA">Latvia</option>
                                <option value="LBN">Lebanon</option>
                                <option value="LSO">Lesotho</option>
                                <option value="LBR">Liberia</option>
                                <option value="LBY">Libya</option>
                                <option value="LIE">Liechtenstein</option>
                                <option value="LTU">Lithuania</option>
                                <option value="LUX">Luxembourg</option>
                                <option value="MAC">Macao</option>
                                <option value="MKD">Macedonia, the former Yugoslav Republic of</option>
                                <option value="MDG">Madagascar</option>
                                <option value="MWI">Malawi</option>
                                <option value="MYS">Malaysia</option>
                                <option value="MDV">Maldives</option>
                                <option value="MLI">Mali</option>
                                <option value="MLT">Malta</option>
                                <option value="MHL">Marshall Islands</option>
                                <option value="MTQ">Martinique</option>
                                <option value="MRT">Mauritania</option>
                                <option value="MUS">Mauritius</option>
                                <option value="MYT">Mayotte</option>
                                <option value="MEX">Mexico</option>
                                <option value="FSM">Micronesia, Federated States of</option>
                                <option value="MDA">Moldova, Republic of</option>
                                <option value="MCO">Monaco</option>
                                <option value="MNG">Mongolia</option>
                                <option value="MNE">Montenegro</option>
                                <option value="MSR">Montserrat</option>
                                <option value="MAR">Morocco</option>
                                <option value="MOZ">Mozambique</option>
                                <option value="MMR">Myanmar</option>
                                <option value="NAM">Namibia</option>
                                <option value="NRU">Nauru</option>
                                <option value="NPL">Nepal</option>
                                <option value="NLD">Netherlands</option>
                                <option value="NCL">New Caledonia</option>
                                <option value="NZL">New Zealand</option>
                                <option value="NIC">Nicaragua</option>
                                <option value="NER">Niger</option>
                                <option value="NGA">Nigeria</option>
                                <option value="NIU">Niue</option>
                                <option value="NFK">Norfolk Island</option>
                                <option value="MNP">Northern Mariana Islands</option>
                                <option value="NOR">Norway</option>
                                <option value="OMN">Oman</option>
                                <option value="PAK">Pakistan</option>
                                <option value="PLW">Palau</option>
                                <option value="PSE">Palestinian Territory, Occupied</option>
                                <option value="PAN">Panama</option>
                                <option value="PNG">Papua New Guinea</option>
                                <option value="PRY">Paraguay</option>
                                <option value="PER">Peru</option>
                                <option value="PHL">Philippines</option>
                                <option value="PCN">Pitcairn</option>
                                <option value="POL">Poland</option>
                                <option value="PRT">Portugal</option>
                                <option value="PRI">Puerto Rico</option>
                                <option value="QAT">Qatar</option>
                                <option value="REU">Réunion</option>
                                <option value="ROU">Romania</option>
                                <option value="RUS">Russian Federation</option>
                                <option value="RWA">Rwanda</option>
                                <option value="BLM">Saint Barthélemy</option>
                                <option value="SHN">Saint Helena, Ascension and Tristan da Cunha</option>
                                <option value="KNA">Saint Kitts and Nevis</option>
                                <option value="LCA">Saint Lucia</option>
                                <option value="MAF">Saint Martin (French part)</option>
                                <option value="SPM">Saint Pierre and Miquelon</option>
                                <option value="VCT">Saint Vincent and the Grenadines</option>
                                <option value="WSM">Samoa</option>
                                <option value="SMR">San Marino</option>
                                <option value="STP">Sao Tome and Principe</option>
                                <option value="SAU">Saudi Arabia</option>
                                <option value="SEN">Senegal</option>
                                <option value="SRB">Serbia</option>
                                <option value="SYC">Seychelles</option>
                                <option value="SLE">Sierra Leone</option>
                                <option value="SGP">Singapore</option>
                                <option value="SXM">Sint Maarten (Dutch part)</option>
                                <option value="SVK">Slovakia</option>
                                <option value="SVN">Slovenia</option>
                                <option value="SLB">Solomon Islands</option>
                                <option value="SOM">Somalia</option>
                                <option value="ZAF">South Africa</option>
                                <option value="SGS">South Georgia and the South Sandwich Islands</option>
                                <option value="SSD">South Sudan</option>
                                <option value="ESP">Spain</option>
                                <option value="LKA">Sri Lanka</option>
                                <option value="SDN">Sudan</option>
                                <option value="SUR">Suriname</option>
                                <option value="SJM">Svalbard and Jan Mayen</option>
                                <option value="SWZ">Swaziland</option>
                                <option value="SWE">Sweden</option>
                                <option value="CHE">Switzerland</option>
                                <option value="SYR">Syrian Arab Republic</option>
                                <option value="TWN">Taiwan, Province of China</option>
                                <option value="TJK">Tajikistan</option>
                                <option value="TZA">Tanzania, United Republic of</option>
                                <option value="THA">Thailand</option>
                                <option value="TLS">Timor-Leste</option>
                                <option value="TGO">Togo</option>
                                <option value="TKL">Tokelau</option>
                                <option value="TON">Tonga</option>
                                <option value="TTO">Trinidad and Tobago</option>
                                <option value="TUN">Tunisia</option>
                                <option value="TUR">Turkey</option>
                                <option value="TKM">Turkmenistan</option>
                                <option value="TCA">Turks and Caicos Islands</option>
                                <option value="TUV">Tuvalu</option>
                                <option value="UGA">Uganda</option>
                                <option value="UKR">Ukraine</option>
                                <option value="ARE">United Arab Emirates</option>
                                <option value="GBR">United Kingdom</option>
                                <option value="USA">United States</option>
                                <option value="UMI">United States Minor Outlying Islands</option>
                                <option value="URY">Uruguay</option>
                                <option value="UZB">Uzbekistan</option>
                                <option value="VUT">Vanuatu</option>
                                <option value="VEN">Venezuela, Bolivarian Republic of</option>
                                <option value="VNM">Viet Nam</option>
                                <option value="VGB">Virgin Islands, British</option>
                                <option value="VIR">Virgin Islands, U.S.</option>
                                <option value="WLF">Wallis and Futuna</option>
                                <option value="ESH">Western Sahara</option>
                                <option value="YEM">Yemen</option>
                                <option value="ZMB">Zambia</option>
                                <option value="ZWE">Zimbabwe</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address" placeholder="<?= lang('input_address'); ?>" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city" placeholder="<?= lang('input_city'); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="state" placeholder="<?= lang('input_state'); ?>" size="4" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="zipcode" placeholder="<?= lang('input_zip_code'); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="btnSubmit" class="btn btn-green btn-lg btn-block border-0 font-weight-bold" value="<?= lang('pay'); ?> <?= configs('currency_symbol', 'value'); ?><?= currency_format(session('twocheckout_amount')); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script>
    // Called when token created successfully.
    var successCallback = function(data) {
        var myForm = document.getElementById('payment-form');

        // Set the token as the value for the token input
        myForm.token.value = data.response.token.token;

        // Form submission
        myForm.submit();
    };

    // Called when token creation fails.
    var errorCallback = function(data) {
        if (data.errorCode === 200) {
            tokenRequest();
        } else {
            Swal.fire({
                title: 'Error',
                text: data.errorMsg,
                icon: 'error'
            });
            $('#btnSubmit').val('<?= lang('pay'); ?> <?= configs('currency_symbol', 'value'); ?><?= currency_format(session('twocheckout_amount')); ?>').removeAttr('disabled');
        }
    };

    var tokenRequest = function() {
        // Setup token request arguments
        var args = {
            sellerId: "<?= config_payment('2checkout_seller_id', 'value'); ?>",
            publishableKey: "<?= config_payment('2checkout_publishable_key', 'value'); ?>",
            ccNo: $("#card_num").val(),
            cvv: $("#cvv").val(),
            expMonth: $("#exp_month").val(),
            expYear: $("#exp_year").val()
        };

        if ($("#country").val() != 0) {
            // Make the token request
            TCO.requestToken(successCallback, errorCallback, args);
        } else {
            Swal.fire({
                title: 'Error',
                text: '<?= lang('select_country_twocheckout'); ?>',
                icon: 'error'
            });
            $('#btnSubmit').val('<?= lang('pay'); ?> <?= configs('currency_symbol', 'value'); ?><?= currency_format(session('twocheckout_amount')); ?>').removeAttr('disabled');
        }
    };

    $(document).ready(function() {
        // Pull in the public encryption key for our environment
        TCO.loadPubKey("<?= config_payment('2checkout_environment', 'value') == 'Sandbox' ? 'sandbox' : 'production'; ?>");

        $("#payment-form").submit(function(e) {
            $('#btnSubmit').val('<?= lang('loading'); ?> ...').attr("disabled", "disabled");
            // Call our token request function
            tokenRequest();
            // Prevent form from submitting
            return false;
        });
    });
</script>