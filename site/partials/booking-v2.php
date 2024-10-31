<?php
?>
<noscript>You need to enable JavaScript to run this app.</noscript>
<div style="position: relative" id="rentsyst_frame"></div>

<?php
$script = <<<JS

window.rentsyst_settings = $settings;

	window.addEventListener( "load", ( function() {
		RentSystFrame.init( { 
		container: document.getElementById("rentsyst_frame"),
		containerClassName: "wp-rentsyst-constructor"
		} )
	} ) )
JS;

wp_add_inline_script( 'rentsyst_bundle_front', $script, 'before');
