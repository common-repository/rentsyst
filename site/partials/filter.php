<?php

use rentsyst\includes\Rentsyst_PluginSettings;
use rentsyst\includes\RS_Company;

$rs_filter = $_GET['rentsyst'] ?? null;
?>
<aside id="rentsyst-filter" class="aside <?= $fixPosition ? 'rentsyst-enable-fix-position' : ''; ?>">
    <div class="filters js_drop-list">
        <form action="" method="GET" id="js_filter-form">

            <div class="rentsyst-wrap-filter-options">

            <input type="hidden" name="page_id" value="<?= get_option(Rentsyst_PluginSettings::CATALOG_PAGE_ID) ?>" >
            <div class="filter js_filter price-filter <?= $rs_filter && !isset($rs_filter['price']) ? '' : 'is-open'; ?>">
                <a href="#" class="filter__title js_filter-caret" title="Price per day">Price per day
                    <i class="fico fico-plus"></i> <i class="fico fico-down-arrow" style="transform: rotate(-180deg);"></i>
                </a>
                <div class="filter__dropdown js_list">
                    <ul class="filter__list">
                        <li class="filter__item">
                            <input type="hidden" name="rentsyst[price]" id="price" readonly="readonly">
                            <div class="range-out"><span class="range-out__min"><?= $rangePrice['min']; ?></span><?= RS_Company::getInstance()->getCurrency(); ?> <span class="range-out__max"><?= $rangePrice['max']; ?></span></div>
                            <div style="width: 96%" data-min="<?= $rangePrice['min']; ?>" data-max="<?= $rangePrice['max']; ?>" data-valuestart="<?= $rangePrice['start']; ?>" data-valueend="<?= $rangePrice['end']; ?>" data-input="#price" class="js_range-input"></div>
                        </li>
                    </ul>
                </div>
            </div>

            <?php foreach($filters as $key => $filter) { ?>
                <?php $checkedElements = $rs_filter[$key] ?? null; ?>
                <div class="filter js_filter <?= $checkedElements && count($checkedElements) ? 'is-open' : ''; ?>"><a href="#"
                                                         class="filter__title js_filter-caret"
                                                         title="<?= $filterNames[$key] ?? $key ?>"><?= $filterNames[$key] ?? $key ?> <i
                                class="fico fico-plus"></i> <i class="fico fico-down-arrow" style="transform: rotate(-180deg);"></i></a>
                    <div class="filter__dropdown js_list">

                        <ul class="filter__list">
                            <?php foreach($filter as $name => $title) { ?>
	                            <?php if(!$title) continue ?>
                                <?php $id = $key . '_' . $name; ?>
                            <li class="filter__item">
                                <input <?=  isset($checkedElements[$name]) ? 'checked' : ''; ?> type="checkbox" name="<?= 'rentsyst[' . $key . ']' . '[' . $name . ']'; ?>" id="<?= $id; ?>">
                                <label class="label label--car" for="<?= $id; ?>">
                                    <?= $title; ?>
                                </label>
                            </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>

            <?php } ?>
            </div>

            <a href="<?= get_permalink(get_option(Rentsyst_PluginSettings::CATALOG_PAGE_ID)); ?>" class="rentsyst-filter-reset">
                <?= __('Reset Filters', 'rentsyst'); ?>
            </a>
            <button class="rentsyst-filter-submit"><?= __('Submit', 'rentsyst') ?></button>
        </form>
    </div>
</aside>


<style>
    .rentsyst-filter-submit {
        background: <?= $accentColor; ?>
    }
    .filters .filter.is-open .filter__title, .filters .filter.is-open .filter__title .fico {
        color: <?= $accentColor; ?>
    }
    .filters .filter__item .label::after {
        border-left: 3px solid <?= $accentColor; ?>;
        border-bottom: 3px solid <?= $accentColor; ?>;
    }
    .filters .filter__show-link {
        color: <?= $accentColor; ?>
    }
    .ui-slider .ui-slider-range {
        background: <?= $accentColor; ?>
    }

    .ui-button, .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
        border: 2px solid <?= $accentColor; ?>;
    }
    .rentsyst-wrap-filters .ui-button:focus, .ui-button:hover {
        background: <?= $accentColor; ?>
    }

    .rentsyst-wrap-filters .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active {
        background: <?= $accentColor; ?>;
    }

    .pagination {
        clear: both;
        display: inline-block;
        position: relative;
        font-size: 15px;
        font-weight: 700;
        line-height: 100%;
        margin-bottom: 15px;
        padding: 0;
        text-transform: uppercase;
    }

    .pagination span, .pagination a {
        background: #fff;
        border: 1px solid #ddd;
        color: #aaa;
        display: block;
        float: left;
        font-size: 15px;
        font-weight: 400;
        margin: 2px 5px 2px 0;
        padding: 9px 12px 8px;
        text-decoration: none;
        width: auto;
    }

    .pagination .current,
    .pagination a:hover {
        background: <?= $accentColor; ?>;
        border: 1px solid <?= $accentColor; ?>;
        color: #fff !important;
    }

    .pagination a,
    .pagination a:visited {
        color: #aaa;
    }
    .pagination a:hover {
        color: <?= $accentColor; ?>;
        opacity: 1;
        text-decoration: none;
        -webkit-transition: color .25s, background .25s, opacity .25s;
        -moz-transition: color .25s, background .25s, opacity .25s;
        -ms-transition: color .25s, background .25s, opacity .25s;
        -o-transition: color .25s, background .25s, opacity .25s;
        transition: color .25s, background .25s, opacity .25s;
        -webkit-tap-highlight-color:  rgba(255, 255, 255, 0);
    }
</style>
