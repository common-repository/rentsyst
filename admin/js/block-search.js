( function( blocks, element, serverSideRender, blockEditor, component ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        ServerSideRender = serverSideRender;

	const searchIcon = wp.element.createElement('svg',
		{
			width: 20,
			height: 20,

		},
		wp.element.createElement( 'path',
			{
				d: "M7.1,2.5c-0.4,0-0.6,0.3-0.6,0.6s0.3,0.6,0.6,0.6c2.5,0,4.5,2,4.5,4.5c0,0.4,0.3,0.6,0.6,0.6s0.6-0.3,0.6-0.6\n" +
					"                C12.8,5.1,10.3,2.5,7.1,2.5z"
			}
		),
		wp.element.createElement( 'path',
			{
				d: "M19.5,18l-6-6c0.9-1.2,1.4-2.7,1.4-4.4c0-4-3.3-7.4-7.4-7.4c-4,0-7.4,3.3-7.4,7.4S3.6,15,7.6,15\n" +
					"                c1.7,0,3.2-0.6,4.5-1.5l6,6c0.2,0.2,0.5,0.3,0.7,0.3c0.2,0,0.5-0.1,0.7-0.3C20,19.1,20,18.4,19.5,18z M7.6,13.6\n" +
					"                c-3.3,0-5.9-2.6-5.9-5.9c0-3.2,2.6-5.9,5.9-5.9s5.9,2.6,5.9,5.9S10.9,13.6,7.6,13.6z"
			}
		),
	);

    registerBlockType( 'rentsyst/rentsyst-form-search', {
        title: 'Rentsyst search form',
        icon: searchIcon,
        category: 'widgets',

        edit: function( props ) {
			var orientation = props.attributes.orientation;
			var colorMain = props.attributes.colorMain;
			var colorSecond = props.attributes.colorSecond;
			var textColor = props.attributes.textColor;
			var bgColor = props.attributes.bgColor;

			function onChangeOrientation( newValue ) {
				props.setAttributes( { orientation: newValue } );
			}
			function onChangeColorMain( newValue ) {
				props.setAttributes( { colorMain: newValue.hex } );
			}
			function onChangeColorSecond( newValue ) {
				props.setAttributes( { colorSecond: newValue.hex } );
			}
			function onChangeTextColor( newValue ) {
				props.setAttributes( { textColor: newValue.hex } );
			}
			function onChangeBgColor( newValue ) {
				props.setAttributes( { bgColor: newValue.hex } );
			}

			return el(
                element.Fragment,
                null,
                el(
                    blockEditor.InspectorControls,
                    null,
                    el(

                        component.PanelBody,
                        {
                            title: 'Form search settings'
                        },
						el(
							component.BaseControl,
							{
								label: 'Orientation',
							},
							el(
								component.SelectControl,
								{
									value: orientation,
									help: 'Horizontal type available only if your page has enough width.',
									options: [
											{
												label: 'Vertical',
												value: 'vertical'
											},
											{
												label: 'Horizontal',
												value: 'horizontal'
											},
									],
									onChange: onChangeOrientation,
								}
							),
						),
						el(
							component.BaseControl,
							{
								label: 'Main color',
							},
							el(
								component.ColorPicker,
								{
									color: colorMain,
									onChangeComplete: onChangeColorMain,
								}
							),
						),
						el(
							component.BaseControl,
							{
								label: 'Second color',
							},
							el(
								component.ColorPicker,
								{
									color: colorSecond,
									onChangeComplete: onChangeColorSecond,
								}
							),
						),
						el(
							component.BaseControl,
							{
								label: 'Text color',
							},
							el(
								component.ColorPicker,
								{
									color: textColor,
									onChangeComplete: onChangeTextColor,
								}
							),
						),
						el(
							component.BaseControl,
							{
								label: 'Background color',
							},
							el(
								component.ColorPicker,
								{
									color: bgColor,
									onChangeComplete: onChangeBgColor,
								}
							),
						),
                    )
                ),
                el(
                	component.Disabled,
                    null,
                    el(
                    	ServerSideRender,
                    	{
                        	block: 'rentsyst/rentsyst-form-search',
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

