import AlpusTypographyControl, { alpusGenerateTypographyCSS } from '../controls/typography';
import AlpusStyleOptionsControl, { alpusGenerateStyleOptionsCSS, alpusGenerateStyleOptionsClass } from '../controls/style-options';
import AlpusDynamicContentControl from '../controls/dynamic-content';
/**
 * 02. Alpus Button
 * 
 * @since 1.2.0
 */
( function( wpI18n, wpBlocks, wpBlockEditor, wpComponents ) {
    "use strict";

    var __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        UnitControl = wp.components.__experimentalUnitControl,
        RichText = wpBlockEditor.RichText,
        TextControl = wpComponents.TextControl,
        ToggleControl = wpComponents.ToggleControl,
        SelectControl = wpComponents.SelectControl,
        PanelBody = wpComponents.PanelBody,
        useEffect = wp.element.useEffect,
        useState = wp.element.useState;

    const AlpusButton = function( { attributes, setAttributes } ) {
        const [buttonUrl, setButtonUrl] = useState( attributes.link );

        let buttonCls = 'btn',
            font_settings = Object.assign( {}, attributes.font_settings ),
            style_options = Object.assign( {}, attributes.style_options ),
            responsiveCls = alpusGenerateStyleOptionsClass( style_options ),
            selectorCls = 'btn-wrapper-' + Math.ceil( Math.random() * 10000 ),
            additionalCls = attributes.className ? ' ' + attributes.className : '',
            dynamic_content = Object.assign( {}, attributes.dynamic_content );

        /* start type builder */
        let content_type = document.getElementById( 'content_type' );
        if ( typeof content_type == 'undefined' ) {
            content_type = false;
        } else if ( content_type ) {
            content_type = content_type.value;
        }
        let content_type_value = '';
        if ( content_type ) {
            content_type_value = document.getElementById( 'content_type_' + content_type );
            if ( content_type_value ) {
                content_type_value = content_type_value.value;
            }
        }
        /* end type builder */
        useEffect(
            () => {
                let field_name = '';
                if ( attributes.dynamic_content && attributes.dynamic_content.source ) {
                    if ( 'post' == attributes.dynamic_content.source ) {
                        field_name = attributes.dynamic_content.post_info;
                    } else {
                        field_name = attributes.dynamic_content[attributes.dynamic_content.source];
                    }
                    jQuery.ajax( {
                        url: alpus_core_vars.ajax_url,
                        data: {
                            action: 'alpus_dynamic_tags_get_value',
                            nonce: alpus_core_vars.nonce,
                            content_type: content_type ? content_type : 'post',
                            content_type_value: content_type ? content_type_value : alpus_block_vars.edit_post_id,
                            source: attributes.dynamic_content.source,
                            field_name: field_name
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function( res ) {
                            if ( res && res.success ) {
                                if ( res.data ) {
                                    setButtonUrl( res.data );
                                } else if ( attributes.dynamic_content && attributes.dynamic_content.fallback ) {
                                    setButtonUrl( attributes.dynamic_content.fallback );
                                }
                            }
                        }
                    } );
                }
            },
            [attributes.link_source, attributes.dynamic_content && attributes.dynamic_content.source, attributes.dynamic_content && attributes.dynamic_content.post_info, attributes.dynamic_content && attributes.dynamic_content.metabox, attributes.dynamic_content && attributes.dynamic_content.acf, attributes.dynamic_content && attributes.dynamic_content.meta, attributes.dynamic_content && attributes.dynamic_content.tax],
        );

        buttonCls += additionalCls;

        if ( attributes.button_expand ) {
            buttonCls += ' btn-block';
        }
        if ( attributes.button_type ) {
            buttonCls += ' ' + attributes.button_type;
        }
        if ( 'btn-gradient' !== attributes.button_type && attributes.button_skin ) {
            buttonCls += ' ' + attributes.button_skin;
        } else if ( 'btn-gradient' === attributes.button_type && attributes.button_gradient_skin ) {
            buttonCls += ' ' + attributes.button_gradient_skin;
        }

        if ( attributes.button_text_hover_effect ) {
            buttonCls += ' btn-text-hover-effect ' + attributes.button_text_hover_effect;
        }

        if ( attributes.button_size ) {
            buttonCls += ' ' + attributes.button_size;
        }
        if ( 'btn-link' !== attributes.button_type && 'btn-outline' !== attributes.button_type && attributes.shadow ) {
            buttonCls += ' ' + attributes.shadow;
        }
        if ( 'btn-link' !== attributes.button_type && attributes.button_border ) {
            buttonCls += ' ' + attributes.button_border;
        }
        if ( attributes.show_icon ) {
            if ( 'before' === attributes.icon_pos ) {
                buttonCls += ' btn-icon-left';
            }
            if ( 'after' === attributes.icon_pos ) {
                buttonCls += ' btn-icon-right';
            }
            if ( attributes.icon_hover_effect ) {
                buttonCls += ' ' + attributes.icon_hover_effect;
            }
            if ( '' !== attributes.icon_hover_effect && 'btn-reveal' !== attributes.icon_hover_effect && attributes.icon_hover_effect_infinite ) {
                buttonCls += ' btn-infinite';
            }
        }

        buttonCls += ' ' + responsiveCls;

        let buttonStyle = '';

        buttonStyle += alpusGenerateTypographyCSS( font_settings, selectorCls + ' .btn' ) +
            alpusGenerateStyleOptionsCSS( style_options, selectorCls + ' .btn' );
        if ( attributes.button_align_selector ) {
            buttonStyle += '.' + selectorCls + '{ text-align:' + attributes.button_align_selector + '; }';
        }
        if ( attributes.show_icon && attributes.icon_space_selector ) {
            buttonStyle += '.button-label + i, i + .button-label{ margin-left:' + attributes.icon_space_selector + 'px; }'
        }
        if ( attributes.icon_size_selector ) {
            buttonStyle += '.' + selectorCls + ' i{ font-size:' + attributes.icon_size_selector + '; }';
        }

        return (
            <>
                <InspectorControls key="inspector">

                    <TextControl
                        label={ __( 'Text', 'alpus-core' ) }
                        value={ attributes.label }
                        onChange={ ( value ) => { setAttributes( { label: value } ); } }
                        placeholder={ __( 'Click here', 'alpus-core' ) }
                        help={ __( 'Type text that will be shown on button.', 'alpus-core' ) }
                    />

                    <SelectControl
                        label={ __( 'Link Source', 'alpus-core' ) }
                        value={ attributes.link_source }
                        options={ [{ label: __( 'Custom Link', 'alpus-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpus-core' ), value: 'dynamic' }] }
                        onChange={ ( value ) => { setAttributes( { link_source: value } ); } }
                    />

                    { 'dynamic' == attributes.link_source && (
                        <AlpusDynamicContentControl
                            label={ __( 'Dynamic Content', 'alpus-core' ) }
                            value={ dynamic_content }
                            options={ { field_type: 'link', content_type: content_type, content_type_value: content_type_value } }
                            onChange={ ( value ) => { setAttributes( { dynamic_content: value } ); } }
                        />
                    ) }

                    { !attributes.link_source && (
                        <TextControl
                            label={ __( 'Button URL', 'alpus-core' ) }
                            value={ attributes.link }
                            onChange={ ( value ) => { setAttributes( { link: value } ); setButtonUrl( value ); } }
                            placeholder={ __( 'Paste url or type', 'alpus-core' ) }
                            help={ __( 'Input URL where you will move when button is clicked.', 'alpus-core' ) }
                        />
                    ) }

                    <ToggleControl
                        label={ __( 'Expand', 'alpus-core' ) }
                        checked={ attributes.button_expand }
                        onChange={ ( value ) => { setAttributes( { button_expand: value } ); } }
                        help={ __( 'Controls button\'s alignment. Choose from Left, Center, Right.', 'alpus-core' ) }
                    />

                    { !attributes.button_expand && (
                        <SelectControl
                            label={ __( 'Alignments', 'alpus-core' ) }
                            value={ attributes.button_align_selector }
                            options={ [
                                { label: __( 'Left', 'alpus-core' ), value: 'left' },
                                { label: __( 'Center', 'alpus-core' ), value: 'center' },
                                { label: __( 'Right', 'alpus-core' ), value: 'right' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { button_align_selector: value } ); } }
                        />
                    ) }

                    <SelectControl
                        label={ __( 'Type', 'alpus-core' ) }
                        value={ attributes.button_type }
                        options={ [
                            { label: __( 'Default', 'alpus-core' ), value: '' },
                            { label: __( 'Gradient', 'alpus-core' ), value: 'btn-gradient' },
                            { label: __( 'Outline', 'alpus-core' ), value: 'btn-outline' },
                            { label: __( 'Link', 'alpus-core' ), value: 'btn-link' }
                        ] }
                        onChange={ ( value ) => { setAttributes( { button_type: value } ); } }
                    />

                    { 'btn-gradient' !== attributes.button_type && (
                        <SelectControl
                            label={ __( 'Skin', 'alpus-core' ) }
                            value={ attributes.button_skin }
                            options={ [
                                { label: __( 'Default', 'alpus-core' ), value: '' },
                                { label: __( 'Primary', 'alpus-core' ), value: 'btn-primary' },
                                { label: __( 'Secondary', 'alpus-core' ), value: 'btn-secondary' },
                                { label: __( 'Warning', 'alpus-core' ), value: 'btn-warning' },
                                { label: __( 'Danger', 'alpus-core' ), value: 'btn-danger' },
                                { label: __( 'Success', 'alpus-core' ), value: 'btn-success' },
                                { label: __( 'Dark', 'alpus-core' ), value: 'btn-dark' },
                                { label: __( 'White', 'alpus-core' ), value: 'btn-white' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { button_skin: value } ); } }
                        />
                    ) }

                    { 'btn-gradient' === attributes.button_type && (
                        <SelectControl
                            label={ __( 'Gradient Skin', 'alpus-core' ) }
                            value={ attributes.button_gradient_skin }
                            options={ [
                                { label: __( 'None', 'alpus-core' ), value: '' },
                                { label: __( 'Default', 'alpus-core' ), value: 'btn-gra-default' },
                                { label: __( 'Blue', 'alpus-core' ), value: 'btn-gra-blue' },
                                { label: __( 'Orange', 'alpus-core' ), value: 'btn-gra-orange' },
                                { label: __( 'Pink', 'alpus-core' ), value: 'btn-gra-pink' },
                                { label: __( 'Green', 'alpus-core' ), value: 'btn-gra-green' },
                                { label: __( 'Dark', 'alpus-core' ), value: 'btn-gra-dark' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { button_gradient_skin: value } ); } }
                        />
                    ) }

                    <SelectControl
                        label={ __( 'Text Hover Effect', 'alpus-core' ) }
                        value={ attributes.button_text_hover_effect }
                        options={ [
                            { label: __( 'No Effect', 'alpus-core' ), value: '' },
                            { label: __( 'Switch Left', 'alpus-core' ), value: 'btn-text-switch-left' },
                            // { label: __( 'Switch Up', 'alpus-core' ), value: 'btn-text-switch-up' },
                            { label: __( 'Marquee Left', 'alpus-core' ), value: 'btn-text-marquee-left' }
                            // { label: __( 'Marquee Up', 'alpus-core' ), value: 'btn-text-marquee-up' },
                            // { label: __( 'Marquee Down', 'alpus-core' ), value: 'btn-text-marquee-down' }
                        ] }
                        onChange={ ( value ) => { setAttributes( { button_text_hover_effect: value } ); } }
                    />

                    <SelectControl
                        label={ __( 'Size', 'alpus-core' ) }
                        value={ attributes.button_size }
                        options={ [
                            { label: __( 'Default', 'alpus-core' ), value: '' },
                            { label: __( 'Small', 'alpus-core' ), value: 'btn-sm' },
                            { label: __( 'Medium', 'alpus-core' ), value: 'btn-md' },
                            { label: __( 'Large', 'alpus-core' ), value: 'btn-lg' },
                            { label: __( 'Extra Large', 'alpus-core' ), value: 'btn-xl' }
                        ] }
                        onChange={ ( value ) => { setAttributes( { button_size: value } ); } }
                    />

                    { 'btn-link' !== attributes.button_type && 'btn-outline' !== attributes.button_type && (
                        <SelectControl
                            label={ __( 'Box Shadow', 'alpus-core' ) }
                            value={ attributes.shadow }
                            options={ [
                                { label: __( 'None', 'alpus-core' ), value: '' },
                                { label: __( 'Shadow 1', 'alpus-core' ), value: 'btn-shadow-sm' },
                                { label: __( 'Shadow 2', 'alpus-core' ), value: 'btn-shadow' },
                                { label: __( 'Shadow 3', 'alpus-core' ), value: 'btn-shadow-lg' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { shadow: value } ); } }
                        />
                    ) }

                    { 'btn-link' === attributes.button_type && (
                        <SelectControl
                            label={ __( 'Hover Underline', 'alpus-core' ) }
                            value={ attributes.link_hover_type }
                            options={ [
                                { label: __( 'None', 'alpus-core' ), value: '' },
                                { label: __( 'Underline1', 'alpus-core' ), value: 'btn-underline sm' },
                                { label: __( 'Underline2', 'alpus-core' ), value: 'btn-underline' },
                                { label: __( 'Underline3', 'alpus-core' ), value: 'btn-underline lg' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { link_hover_type: value } ); } }
                        />
                    ) }

                    { 'btn-link' !== attributes.button_type && (
                        <SelectControl
                            label={ __( 'Border Style', 'alpus-core' ) }
                            value={ attributes.button_border }
                            options={ [
                                { label: __( 'Square', 'alpus-core' ), value: '' },
                                { label: __( 'Rounded', 'alpus-core' ), value: 'btn-rounded' },
                                { label: __( 'Ellipse', 'alpus-core' ), value: 'btn-ellipse' },
                                { label: __( '50%', 'alpus-core' ), value: 'btn-circle' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { button_border: value } ); } }
                        />
                    ) }

                    <ToggleControl
                        label={ __( 'Show Icon?', 'alpus-core' ) }
                        checked={ attributes.show_icon }
                        onChange={ ( value ) => { setAttributes( { show_icon: value } ); } }
                        help={ __( 'Allows to show icon before or after button text.', 'alpus-core' ) }
                    />

                    <PanelBody>

                        { attributes.show_icon && (
                            <TextControl
                                label={ __( 'Icon', 'alpus-core' ) }
                                value={ attributes.icon }
                                onChange={ ( value ) => { setAttributes( { icon: value } ); } }
                                placeholder={ __( 'Type the icon class name', 'alpus-core' ) }
                                help={ __( 'Please check this link to see icons which Alpus supports.', 'alpus-core' ) }
                            />
                        ) }

                        { attributes.show_icon && (
                            <TextControl
                                label={ __( 'Icon Spacing', 'alpus-core' ) }
                                value={ attributes.icon_space_selector }
                                onChange={ ( value ) => { setAttributes( { icon_space_selector: value } ); } }
                                help={ __( 'Set the spacing(px) between button label and icon.', 'alpus-core' ) }
                            />
                        ) }

                        { attributes.show_icon && (
                            <UnitControl
                                label={ __( 'Icon Size', 'alpus-core' ) }
                                value={ attributes.icon_size_selector }
                                onChange={ ( value ) => { setAttributes( { icon_size_selector: value } ); } }
                            />
                        ) }

                        { attributes.show_icon && (
                            <SelectControl
                                label={ __( 'Icon Position', 'alpus-core' ) }
                                value={ attributes.icon_pos }
                                options={ [
                                    { label: __( 'After', 'alpus-core' ), value: 'after' },
                                    { label: __( 'Before', 'alpus-core' ), value: 'before' }
                                ] }
                                onChange={ ( value ) => { setAttributes( { icon_pos: value } ); } }
                            />
                        ) }

                        { attributes.show_icon && (
                            <SelectControl
                                label={ __( 'Icon Hover Effect', 'alpus-core' ) }
                                value={ attributes.icon_hover_effect }
                                options={ [
                                    { label: __( 'None', 'alpus-core' ), value: '' },
                                    { label: __( 'Slide Left', 'alpus-core' ), value: 'btn-slide-left' },
                                    { label: __( 'Slide Right', 'alpus-core' ), value: 'btn-slide-right' },
                                    { label: __( 'Slide Up', 'alpus-core' ), value: 'btn-slide-up' },
                                    { label: __( 'Slide Down', 'alpus-core' ), value: 'btn-slide-down' },
                                    { label: __( 'Slide Reveal', 'alpus-core' ), value: 'btn-reveal' }
                                ] }
                                onChange={ ( value ) => { setAttributes( { icon_hover_effect: value } ); } }
                            />
                        ) }

                        { '' !== attributes.icon_hover_effect && 'btn-reveal' !== attributes.icon_hover_effect && (
                            <ToggleControl
                                label={ __( 'Animation Infinite', 'alpus-core' ) }
                                checked={ attributes.icon_hover_effect_infinite }
                                onChange={ ( value ) => { setAttributes( { icon_hover_effect_infinite: value } ); } }
                            />
                        ) }

                    </PanelBody>

                    <PanelBody label={ __( 'Font Settings', 'alpus-core' ) }>

                        <AlpusTypographyControl
                            label={ __( 'Text Typography', 'alpus-core' ) }
                            value={ font_settings }
                            options={ {} }
                            onChange={ ( value ) => { setAttributes( { font_settings: value } ); } }
                        />

                    </PanelBody>

                    <AlpusStyleOptionsControl
                        label={ __( 'Style Options', 'alpus-core' ) }
                        value={ style_options }
                        options={ { hoverOptions: true } }
                        onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                    />

                </InspectorControls>

                <style>
                    { buttonStyle }
                </style>

                <div className={ `btn-wrapper ${ selectorCls }` }>
                    <a className={ buttonCls } href={ attributes.link ? attributes.link : '#' }>
                        { attributes.show_icon && 'before' === attributes.icon_pos && (
                            <i className={ attributes.icon ? attributes.icon : '' }></i>
                        ) }
                        <RichText
                            key='editable'
                            tagName='span'
                            className='button-label'
                            value={ attributes.label }
                            data-text={ attributes.button_text_hover_effect ? attributes.label : '' }
                            onChange={ ( value ) => { setAttributes( { label: value } ); } }
                        />
                        { attributes.show_icon && 'after' === attributes.icon_pos && (
                            <i className={ attributes.icon ? attributes.icon : '' }></i>
                        ) }
                    </a>
                </div>
            </>
        )
    };

    if ( alpus_admin_vars ) {
        registerBlockType( alpus_admin_vars.theme + '/' + alpus_admin_vars.theme + '-button', {
            title: alpus_admin_vars.theme_display_name + __( ' Button', 'alpus-core' ),
            icon: 'alpus',
            category: alpus_admin_vars.theme,
            attributes: {
                label: {
                    type: 'string',
                    default: __( 'Click Here', 'alpus-core' ),
                },
                link_source: {
                    type: 'string',
                },
                dynamic_content: {
                    type: 'object',
                },
                link: {
                    type: 'string',
                    default: ''
                },
                button_expand: {
                    type: 'boolean'
                },
                button_align_selector: {
                    type: 'string',
                    default: ''
                },
                button_type: {
                    type: 'string',
                    default: ''
                },
                button_skin: {
                    type: 'string',
                    default: ''
                },
                button_gradient_skin: {
                    type: 'string',
                    default: ''
                },
                button_text_hover_effect: {
                    type: 'string',
                },
                button_size: {
                    type: 'string',
                },
                link_hover_type: {
                    type: 'string',
                    default: ''
                },
                shadow: {
                    type: 'string',
                    default: ''
                },
                button_border: {
                    type: 'string',
                    default: ''
                },
                show_icon: {
                    type: 'boolean'
                },
                icon: {
                    type: 'string',
                    default: 'fas fa-arrow-right'
                },
                icon_pos: {
                    type: 'string',
                    default: 'after'
                },
                icon_hover_effect: {
                    type: 'string',
                    default: ''
                },
                icon_hover_effect_infinite: {
                    type: 'boolean'
                },
                link_break: {
                    type: 'string',
                    default: 'nowrap'
                },
                button_typography: {
                    type: 'object',
                    default: {}
                },
                icon_space_selector: {
                    type: 'int'
                },
                icon_size_selector: {
                    type: 'string'
                },
                style_options: {
                    type: 'object',
                },
                color_tab: {
                    type: 'string',
                    default: 'normal'
                },
                font_settings: {
                    type: 'object',
                    default: {}
                }
            },
            keywords: [
                __( 'button', 'alpus-core' )
            ],
            edit: AlpusButton,
            save: function() {
                return null;
            }
        } );
    }
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );