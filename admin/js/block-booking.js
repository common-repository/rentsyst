( function( blocks, element, serverSideRender, blockEditor, component ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        ServerSideRender = serverSideRender;

	const bookingIcon = wp.element.createElement('svg',
		{
			width: 20,
			height: 20,

		},
		wp.element.createElement( 'path',
			{
				class: 'st0',
				d: "M17.9,1.7c0.2,0,0.4,0.2,0.4,0.4v4.1c-1.4,0.7-2.4,2.1-2.4,3.8c0,1.7,1,3.1,2.4,3.8V18c0,0.2-0.2,0.4-0.4,0.4\n" +
					"                H2.1c-0.2,0-0.4-0.2-0.4-0.4v-4c1.6-0.6,2.8-2.2,2.8-4c0-1.8-1.2-3.4-2.8-4V2.1c0-0.2,0.2-0.4,0.4-0.4H17.9 M17.9,0.1H2.1\n" +
					"                c-1.1,0-2,0.9-2,2v5.3l0.2,0c1.5,0,2.6,1.2,2.6,2.6s-1.2,2.6-2.6,2.6l-0.2,0V18c0,1.1,0.9,2,2,2h15.8c1.1,0,2-0.9,2-2v-5.4\n" +
					"                c-1.4-0.1-2.4-1.2-2.4-2.6c0-1.4,1.1-2.5,2.4-2.6V2.1C19.8,1,18.9,0.1,17.9,0.1z"
			}
		),
		wp.element.createElement( 'path',
			{
				d: "M13.2,6.4H6.8C6.3,6.4,5.9,6,5.9,5.5v0c0-0.5,0.4-0.8,0.8-0.8h6.5c0.5,0,0.8,0.4,0.8,0.8v0\n" +
					"        C14.1,6,13.7,6.4,13.2,6.4z"
			}
		),
		wp.element.createElement( 'path',
			{
				d: "M13.2,15H6.8c-0.5,0-0.8-0.4-0.8-0.8v0c0-0.5,0.4-0.8,0.8-0.8h6.5c0.5,0,0.8,0.4,0.8,0.8v0\n" +
					"        C14.1,14.7,13.7,15,13.2,15z"
			}
		),
		wp.element.createElement( 'path',
			{
				d: "M12,10.6H8c-0.5,0-0.8-0.4-0.8-0.8v0c0-0.5,0.4-0.8,0.8-0.8H12c0.5,0,0.8,0.4,0.8,0.8v0\n" +
					"        C12.9,10.2,12.5,10.6,12,10.6z"
			}
		)
	);

    registerBlockType( 'rentsyst/rentsyst-booking', {
        title: 'Rentsyst booking',
        icon: bookingIcon,
        category: 'widgets',

        edit: function( props ) {
			return el(
                element.Fragment,
                null,
                el(
                	component.Disabled,
                    null,
                    el(
                    	ServerSideRender,
                    	{
                        	block: 'rentsyst/rentsyst-booking',
                        	attributes: props.attributes,
                    	},
					)
                )
            );
        },

    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.serverSideRender,
    window.wp.blockEditor,
    window.wp.components
) );

