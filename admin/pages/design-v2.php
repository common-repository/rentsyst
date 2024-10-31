<?php
$service->add_my_setting();
?>

<noscript>You need to enable JavaScript to run this app.</noscript>
<div style="background: white; position: relative" id="rentsyst_frame"></div>
<script>
	window.rentsyst_settings = <?= $settings ?>;

	window.addEventListener( "load", ( function() {
		RentSystFrame.init( {
            container: document.getElementById("rentsyst_frame"),
			containerClassName: "wp-rentsyst-constructor"

		} )
	} ) )
</script>

<?php

