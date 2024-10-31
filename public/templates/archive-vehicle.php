<?php

require_once WP_RENTSYST_PLUGIN_DIR . '/includes/Rentsyst_CatalogFilter.php';

?>
    <div id="rentsyst_page">
            <div class="flex">
                <div class="calculate__content">
                    <div class="results">
                        <div class="results__head">
                            <h3 class="results__title">Results <span
                                        class="results__count"><?= $found_posts ?></span></h3>
                        </div>
                        <div class="rentsyst-flex rentsyst-container-catalog">
                            <?php if(Rentsyst_CatalogFilter::getPosition() === 'left') { ?>
                            <div class="rentsyst-wrap-filters left">
		                        <?= Rentsyst_CatalogFilter::display(); ?>
                            </div>
                            <?php } ?>
                            <div style="width: 100%;" class="results__items js_car-results">
								<?php

								if ( have_posts() ) {
									while ( have_posts() ) {
										the_post();
										rentsyst_get_template_part( 'content', 'vehicle' );
									}
								}

								?>
                                <div class="pagination">
		                            <?php
		                            echo paginate_links( apply_filters( 'rentsyst_pagination_args', array(

			                            'prev_text' => '&larr;',
			                            'next_text' => '&rarr;',

		                            ) ) );
		                            ?>
                                </div>
                            </div>
	                        <?php if(Rentsyst_CatalogFilter::getPosition() === 'right') { ?>
                                <div class="rentsyst-wrap-filters right">
			                        <?= Rentsyst_CatalogFilter::display(); ?>
                                </div>
	                        <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
    </div>

<?php
