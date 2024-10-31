<?php
$service->add_my_setting();
?>

<div class="wrap rentsyst-page-wrapper base-sub-page">
    <h1 class="wp-heading-inline">Connect Account</h1>
    <div class="connect-status" data-info="<?= json_encode(get_option('rentsyst_access_token')); ?>">
        <hr>
        <p <?= $authInfo ? 'style="display: none"' : ''; ?> id="status-no-connect">For connect acount click next button
            <input type="submit" data-url="<?= $connectorUrl ?>" id="connect-rentsyst" class="button action"
                   value="Connect"></p>
        <div id="status-connect" <?= ! $authInfo ? 'style="display: none"' : ''; ?>>
        <p>You already connect
            <input type="submit" data-url="<?= $disconnectorUrl ?>" id="disconnect-rentsyst" class="button action"
                   value="Disconnect">
        </p>
        <hr>
        <p>
            Last settings synchronize: <span class="ab-icon setting-sync-time"><?= $lastTimeSyncSettings; ?></span> <input type="submit" data-url="<?= $synchronizeSettingsUrl ?>" id="synchronize-settings-rentsyst" class="button action" value="Synchronize settings">
        </p>
        </div>
        <hr>
		<?php if ( $vehicleCatalogEnable ) { ?>
            <p id="status-upload-vehicles" style="display: <?= $authInfo ? 'display-block' : 'none' ?>;">
                Last vehicle synchronize: <span class="ab-icon vehicle-sync-time"><?= $lastTimeSync; ?></span>
                <input type="submit" data-auto-sync="<?= ( ! $lastTimeSync && $authInfo ) ? 1 : '' ?>" data-url="<?= $syncVehicleUrl; ?>" id="upload-vehicle" class="button action"
                       value="Synchronize Fleet">
            </p>
            <hr>
		<?php } ?>
        <p class="static-button-for-booking">
        <div class="ibox-content" style="display: <?= $authInfo ? 'inherit' : 'none' ?>;">
            <form class="form form-horizontal" id="booking_form">
                <fieldset>
                    <input type="hidden" value="rentsyst" name="page">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Enable the catalog of vehicles</label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>

                                    <input type="checkbox"
                                           name="add_catalog_vehicles"
                                           value="1" <?= $settings['add_catalog_vehicles'] ? 'checked' : '' ?>>
                                    Add catalog vehicles
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Individual page</label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>

                                    <input data-show-subsettings="add-booking-in-menu-wraper" type="checkbox"
                                           name="enable_booking_page"
                                           value="1" <?= $settings['enable_booking_page'] ? 'checked' : '' ?>>
                                    Add individual page for booking
                                </label>
                            </div>
                            <div id="add-booking-in-menu-wraper"
                                 style="color: gray; margin-left: 20px;"
                                 class="checkbox subsetting-wraper <?= $settings['enable_booking_page'] ? 'open' : 'close'; ?>">

                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Privacy policy page</label>
                        <div class="col-sm-10">
                            <?php
                            wp_dropdown_pages(['name' => 'privacy_policy_page_id', 'selected' => $settings['privacy_policy_page_id'], 'show_option_none' => 'no selected']);
                            ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirmation page</label>
                        <div class="col-sm-10">
                            <?php
                            wp_dropdown_pages(['name' => 'confirmation_page_id', 'selected' => $settings['confirmation_page_id']]);
                            ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Buttons for booking</label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input data-show-subsettings="booking-button-settings" type="checkbox"
                                           name="enable_booking_button"
                                           value="1" <?= $settings['enable_booking_button'] ? 'checked' : ''; ?>>
                                    Create a button on the pages
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="booking-button-settings"
                         class="subsetting-wraper <?= $settings['enable_booking_button'] ? 'open' : 'close'; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Button Text</label>
                            <div class="col-sm-10">
                                <input type="text" name="button_text" class="form-control"
                                       value="<?= $settings['button_text']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Location of the button</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="button_position">
                                    <option <?= $settings['button_position'] === 'bottom right' ? 'selected' : ''; ?>
                                            value="bottom right">
                                        Lower right corner
                                    </option>
                                    <option <?= $settings['button_position'] === 'top right' ? 'selected' : ''; ?>
                                            value="top right">
                                        Upper right corner
                                    </option>
                                    <option <?= $settings['button_position'] === 'bottom left' ? 'selected' : ''; ?>
                                            value="bottom left">
                                        Lower left corner
                                    </option>
                                    <option <?= $settings['button_position'] === 'top left' ? 'selected' : ''; ?>
                                            value="top left">
                                        Upper left corner
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Button Color</label>
                            <div class="col-sm-10">
                                <input type="color" name="button_color" class="form-control"
                                       value="<?= $settings['button_color']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Animation</label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="button_animation"
                                               value="1" <?= $settings['button_animation'] ? 'checked' : ''; ?>>
                                        Show animation
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Location of the panel</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="form_position">
                                    <option <?= $settings['form_position'] === 'left' ? 'selected' : ''; ?>
                                            value="left">
                                        On the left
                                    </option>
                                    <option <?= $settings['form_position'] === 'right' ? 'selected' : ''; ?>
                                            value="right">
                                        On the right
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Other</label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label>

                                        <input data-show-subsettings="add-booking-in-menu-wraper" type="checkbox"
                                               name="enable_design_v2"
                                               value="1" <?= $settings['enable_design_v2'] ? 'checked' : '' ?>>
                                        Enable design builder 2.0 (recommend)
                                    </label>
                                </div>
                                <div id="add-booking-in-menu-wraper"
                                     style="color: gray; margin-left: 20px;"
                                     class="checkbox subsetting-wraper <?= $settings['enable_design_v2'] ? 'open' : 'close'; ?>">

                                </div>
                            </div>
                        </div>

                    <button class="btn btn-primary btn-block">
                        Save
                    </button>
                </fieldset>
            </form>

            <a id="rentsyst_booking_button" class="RentsystButton <?= $settings['button_position']; ?>" href="#"
               style="z-index: 10000; display:<?= $settings['enable_booking_button'] ? 'block' : 'none'; ?>">
                <div class="RentsystButtonBackground"
                     style="background-color: <?= $settings['button_color']; ?>;"></div>
                <div class="RentsystButtonWave"
                     style="border-color: <?= $settings['button_color']; ?>; color: <?= $settings['button_color']; ?>; display:  <?= $settings['button_animation'] ? 'block' : 'none'; ?>;"></div>
                <div class="RentsystButtonText"><?= $settings['button_text']; ?></div>
                <div class="RentsystButtonIcon"></div>
            </a>
        </div>
        </p>
    </div>
    <div class="rentsyst-loading hidden">
        <div class="img-loader"></div>
        <div class="loader-message">Loading data</div>
    </div>
