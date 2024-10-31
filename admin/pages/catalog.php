<?php

use rentsyst\includes\Rentsyst_PluginSettings;

require_once WP_RENTSYST_PLUGIN_DIR . '/includes/Rentsyst_PluginSettings.php';
$service->add_my_setting();
?>

<div class="rentsyst-page-wrapper wrap base-sub-page">
    <h1 class="wp-heading-inline">Catalog options</h1>
    <div class="connect-status">
        <div class="ibox-content">
            <form action="<?= $selfUrl ?>" method="POST" class="form form-horizontal">
                <fieldset>
                    <input type="hidden" value="rentsyst" name="page">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Enable filter</label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>

                                    <input type="checkbox"
                                           name="enable_catalog_filter"
                                           value="1" <?= $settings['enable_catalog_filter'] ? 'checked' : '' ?>>
                                    Enable
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Filter position</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="filter_button_position">
                                <option <?= $settings['filter_button_position'] === 'right' ? 'selected' : ''; ?>
                                        value="right">
                                    Right
                                </option>
                                <option <?= $settings['filter_button_position'] === 'left' ? 'selected' : ''; ?>
                                        value="left">
                                    Left
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Accent Color</label>
                        <div class="col-sm-10">
                            <input type="color" name="filter_accent_color" class="form-control"
                                   value="<?= $settings['filter_accent_color']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Fixed position</label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>

                                    <input type="checkbox"
                                           name="<?= Rentsyst_PluginSettings::CATALOG_FILTER_ENABLE_FIX; ?>"
                                           value="1" <?= $settings[Rentsyst_PluginSettings::CATALOG_FILTER_ENABLE_FIX] ? 'checked' : '' ?>>
                                    Enable
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Template for catalog item</label>
                        <div class="col-sm-10">
			                <?php
			                wp_dropdown_pages(['name' => 'rentsyst_catalog_page_id', 'selected' => $settings['rentsyst_catalog_page_id'], 'show_option_none' => 'no selected']);
			                ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Template for vehicle single page</label>
                        <div class="col-sm-10">
			                <?php
			                wp_dropdown_pages(['name' => 'rentsyst_catalog_single_page_id', 'selected' => $settings['rentsyst_catalog_single_page_id'], 'show_option_none' => 'no selected']);
			                ?>
                        </div>
                    </div>


                    <button class="btn btn-primary btn-block">
                        Save
                    </button>
                </fieldset>
            </form>

        </div>
        </p>
    </div>
</div>
