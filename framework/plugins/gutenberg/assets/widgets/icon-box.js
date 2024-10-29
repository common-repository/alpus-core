import AlpusTypographyControl, { alpusGenerateTypographyCSS } from '../controls/typography';
import AlpusStyleOptionsControl, { alpusGenerateStyleOptionsCSS, alpusGenerateStyleOptionsClass } from '../controls/style-options';
import AlpusDynamicContentControl from '../controls/dynamic-content';
/**
 * 05. Alpus Icon Box
 *
 * @since 1.2.0
 */
( function ( wpI18n, wpBlocks, wpBlockEditor, wpComponents ) {
    "use strict";

    var __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        RichText = wpBlockEditor.RichText,
        ColorPalette = wp.components.ColorPalette,
        PanelBody = wpComponents.PanelBody,
        RangeControl = wpComponents.RangeControl,
        SelectControl = wpComponents.SelectControl,
        TextControl = wpComponents.TextControl,
        TextareaControl = wpComponents.TextareaControl,
        useEffect = wp.element.useEffect,
        useState = wp.element.useState;

    const AlpusIconBox = function ( { attributes, setAttributes, edit = false } ) {
        const [ icon, setIcon ] = useState( attributes.icon );
        const [ title, setTitle ] = useState( attributes.title );
        const [ desc, setDesc ] = useState( attributes.desc );

        let selectorCls = 'alpus-gb-icon-box-' + Math.ceil( Math.random() * 10000 ),
            title_font_settings = Object.assign( {}, attributes.title_font_settings ),
            desc_font_settings = Object.assign( {}, attributes.desc_font_settings ),
            style_options = Object.assign( {}, attributes.style_options ),
            responsiveCls = alpusGenerateStyleOptionsClass( style_options ),
            additionalCls = attributes.className ? attributes.className + ' ' : '',
            realIcon = icon,
            realTitle = title,
            realDesc = desc,
            icon_dynamic_content = Object.assign( {}, attributes.icon_dynamic_content ),
            title_dynamic_content = Object.assign( {}, attributes.title_dynamic_content ),
            desc_dynamic_content = Object.assign( {}, attributes.desc_dynamic_content ),
            link_dynamic_content = Object.assign( {}, attributes.link_dynamic_content );

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
        [ 'icon', 'title', 'desc' ].forEach( ( field ) => {
            useEffect(
                () => {
                    let field_name = '';
                    if ( attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].source ) {
                        if ( 'post' == attributes[ field + '_dynamic_content' ].source ) {
                            field_name = attributes[ field + '_dynamic_content' ].post_info;
                        } else {
                            field_name = attributes[ field + '_dynamic_content' ][ attributes[ field + '_dynamic_content' ].source ];
                        }
                        if ( field_name ) {
                            jQuery.ajax( {
                                url: alpus_core_vars.ajax_url,
                                data: {
                                    action: 'alpus_dynamic_tags_get_value',
                                    nonce: alpus_core_vars.nonce,
                                    content_type: content_type ? content_type : 'post',
                                    content_type_value: content_type ? content_type_value : alpus_block_vars.edit_post_id,
                                    source: attributes[ field + '_dynamic_content' ].source,
                                    field_name: field_name
                                },
                                type: 'post',
                                dataType: 'json',
                                success: function ( res ) {
                                    let text;
                                    if ( res && res.success ) {
                                        text = '' + res.data;
                                    } else {
                                        text = attributes[ field + '_dynamic_content' ].fallback;
                                    }
                                    if ( 'icon' == field ) {
                                        setIcon( text );
                                    } else if ( 'title' == field ) {
                                        setTitle( text );
                                    } else if ( 'desc' == field ) {
                                        setDesc( text );
                                    }
                                }
                            } );
                        }
                    }
                },
                [ attributes[ field + '_source' ], attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].source, attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].post_info, attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].metabox, attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].acf, attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].meta, attributes[ field + '_dynamic_content' ] && attributes[ field + '_dynamic_content' ].tax ],
            );
        } );

        if ( attributes.icon_source ) {
            if ( !realIcon ) {
                realIcon = '';
            }
        } else {
            realIcon = attributes.icon;
        }
        if ( attributes.title_source ) {
            if ( !realTitle ) {
                realTitle = '';
            }
            if ( attributes.title_dynamic_content && attributes.title_dynamic_content.before ) {
                realTitle = attributes.title_dynamic_content.before + realTitle;
            }
            if ( attributes.title_dynamic_content && attributes.title_dynamic_content.after ) {
                realTitle += attributes.title_dynamic_content.after;
            }
        } else {
            realTitle = attributes.title;
        }
        if ( attributes.desc_source ) {
            if ( !realDesc ) {
                realDesc = '';
            }
            if ( attributes.desc_dynamic_content && attributes.desc_dynamic_content.before ) {
                realDesc = attributes.desc_dynamic_content.before + realDesc;
            }
            if ( attributes.desc_dynamic_content && attributes.desc_dynamic_content.after ) {
                realDesc += attributes.desc_dynamic_content.after;
            }
        } else {
            realDesc = attributes.desc;
        }


        let iconBoxStyle = '',
            iconBoxStyleClass = attributes.icon_view;
        iconBoxStyle = alpusGenerateStyleOptionsCSS( style_options, selectorCls ) +
            alpusGenerateTypographyCSS( title_font_settings, selectorCls + ' .icon-box-title' ) +
            alpusGenerateTypographyCSS( desc_font_settings, selectorCls + ' p' );
        iconBoxStyleClass += ' ' + responsiveCls + ' ' + additionalCls;
        if ( attributes.icon_shape ) {
            iconBoxStyleClass += ' ' + attributes.icon_shape;
        }
        if ( attributes.icon_pos ) {
            iconBoxStyleClass += ' ' + attributes.icon_pos;
        }

        if ( attributes.icon_primary_selector ) {
            iconBoxStyle += 'html .' + selectorCls + '{ --alpus-icon-primary:' + attributes.icon_primary_selector + '; }';
        }
        if ( attributes.icon_primary_hover_selector ) {
            iconBoxStyle += 'html .' + selectorCls + '{ --alpus-icon-primary-hover:' + attributes.icon_primary_hover_selector + '; }';
        }
        if ( attributes.icon_secondary_selector ) {
            iconBoxStyle += 'html .' + selectorCls + '{ --alpus-icon-secondary:' + attributes.icon_secondary_selector + '; }';
        }
        if ( attributes.icon_secondary_hover_selector ) {
            iconBoxStyle += 'html .' + selectorCls + '{ --alpus-icon-secondary-hover:' + attributes.icon_secondary_hover_selector + '; }';
        }
        if ( attributes.icon_spacing_selector ) {
            iconBoxStyle += 'html .' + selectorCls + '{ --alpus-icon-spacing:' + attributes.icon_spacing_selector + 'px; }';
        }
        if ( attributes.icon_size_selector ) {
            iconBoxStyle += 'html .' + selectorCls + ' .icon-box-icon { font-size:' + attributes.icon_size_selector + 'px; }';
        }
        if ( attributes.icon_padding_selector ) {
            iconBoxStyle += 'html .' + selectorCls + ' .icon-box-icon { padding:' + attributes.icon_padding_selector + 'px; }';
        }
        if ( attributes.icon_border_width_selector ) {
            iconBoxStyle += 'html .' + selectorCls + ' .icon-box-icon { border-width:' + attributes.icon_border_width_selector + 'px; }';
        }
        if ( attributes.content_align_selector ) {
            iconBoxStyle += 'html .' + selectorCls + ', html .' + selectorCls + ' .icon-box-content { text-align:' + attributes.content_align_selector + '; }';
        }
        if ( attributes.content_valign_selector ) {
            iconBoxStyle += 'html .' + selectorCls + '{ align-items:' + attributes.content_valign_selector + '; }';
        }
        if ( attributes.title_spacing_selector ) {
            iconBoxStyle += 'html .' + selectorCls + ' .icon-box-title { margin-bottom:' + attributes.title_spacing_selector + 'px; }';
        }

        if ( !document.getElementById( 'alpus-icon-box-css' ) ) {
            let style = document.createElement( 'link' );
            style.setAttribute( "rel", "stylesheet" );
            style.id = "alpus-icon-box-css";
            style.href = alpus_block_vars.core_url + '/widgets/icon-box/icon-box' + ( document.body.classList.contains( 'rtl' ) ? '-rtl' : '' ) + '.min.css';
            document.body.insertAdjacentElement( 'beforeend', style );
        }
        return (
            <>
                <InspectorControls key="inspector1">
                    <PanelBody
                        title={ __( 'Icon Box', 'alpus-core' ) }
                    >
                        <SelectControl
                            label={ __( 'Icon Source', 'alpus-core' ) }
                            value={ attributes.icon_source }
                            options={ [ { label: __( 'Custom Icon', 'alpus-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpus-core' ), value: 'dynamic' } ] }
                            onChange={ ( value ) => { setAttributes( { icon_source: value } ); } }
                        />
                        { 'dynamic' == attributes.icon_source && (
                            <AlpusDynamicContentControl
                                label={ __( 'Dynamic Icon', 'alpus-core' ) }
                                value={ icon_dynamic_content }
                                options={ { field_type: 'field', content_type: content_type, content_type_value: content_type_value } }
                                onChange={ ( value ) => { setAttributes( { icon_dynamic_content: value } ); } }
                            />
                        ) }
                        { !attributes.icon_source && (
                            <TextControl
                                label={ __( 'Icon', 'alpus-core' ) }
                                value={ attributes.icon }
                                onChange={ ( value ) => { setAttributes( { icon: value } ); } }
                                placeholder={ __( 'Type the icon class name', 'alpus-core' ) }
                                help={ __( 'Please check this link to see icons which WP Alpus supports.', 'alpus-core' ) }
                            />
                        ) }

                        <SelectControl
                            label={ __( 'View', 'alpus-core' ) }
                            value={ attributes.icon_view }
                            options={ [
                                { label: __( 'Default', 'alpus-core' ), value: '' },
                                { label: __( 'Stacked', 'alpus-core' ), value: 'icon-inversed' },
                                { label: __( 'Framed', 'alpus-core' ), value: 'icon-border' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { icon_view: value } ); } }
                        />
                        { attributes.icon_view && (
                            <SelectControl
                                label={ __( 'Shape', 'alpus-core' ) }
                                value={ attributes.icon_shape }
                                options={ [
                                    { label: __( 'Circle', 'alpus-core' ), value: 'icon-circle' },
                                    { label: __( 'Square', 'alpus-core' ), value: '' }
                                ] }
                                onChange={ ( value ) => { setAttributes( { icon_shape: value } ); } }
                            />
                        ) }
                        <SelectControl
                            label={ __( 'Title Source', 'alpus-core' ) }
                            value={ attributes.title_source }
                            options={ [ { label: __( 'Custom Text', 'alpus-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpus-core' ), value: 'dynamic' } ] }
                            onChange={ ( value ) => { setAttributes( { title_source: value } ); } }
                        />
                        { 'dynamic' == attributes.title_source && (
                            <AlpusDynamicContentControl
                                label={ __( 'Dynamic Text', 'alpus-core' ) }
                                value={ title_dynamic_content }
                                options={ { field_type: 'field', content_type: content_type, content_type_value: content_type_value } }
                                onChange={ ( value ) => { setAttributes( { title_dynamic_content: value } ); } }
                            />
                        ) }
                        { !attributes.title_source && (
                            <TextControl
                                label={ __( 'Title', 'alpus-core' ) }
                                value={ attributes.title }
                                onChange={ ( value ) => { setAttributes( { title: value } ); } }
                            />
                        ) }

                        <SelectControl
                            label={ __( 'Description Source', 'alpus-core' ) }
                            value={ attributes.desc_source }
                            options={ [ { label: __( 'Custom Text', 'alpus-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpus-core' ), value: 'dynamic' } ] }
                            onChange={ ( value ) => { setAttributes( { desc_source: value } ); } }
                        />
                        { 'dynamic' == attributes.desc_source && (
                            <AlpusDynamicContentControl
                                label={ __( 'Dynamic Text', 'alpus-core' ) }
                                value={ desc_dynamic_content }
                                options={ { field_type: 'field', content_type: content_type, content_type_value: content_type_value } }
                                onChange={ ( value ) => { setAttributes( { desc_dynamic_content: value } ); } }
                            />
                        ) }
                        { !attributes.desc_source && (
                            <TextareaControl
                                label={ __( 'Description', 'alpus-core' ) }
                                value={ attributes.desc }
                                onChange={ ( value ) => { setAttributes( { desc: value } ); } }
                            />
                        ) }
                        <div className="spacer" />

                        <SelectControl
                            label={ __( 'Link Source', 'alpus-core' ) }
                            value={ attributes.link_source }
                            options={ [ { label: __( 'Custom Link', 'alpus-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpus-core' ), value: 'dynamic' } ] }
                            onChange={ ( value ) => { setAttributes( { link_source: value } ); } }
                        />
                        { 'dynamic' == attributes.link_source && (
                            <AlpusDynamicContentControl
                                label={ __( 'Dynamic Link', 'alpus-core' ) }
                                value={ link_dynamic_content }
                                options={ { field_type: 'link', content_type: content_type, content_type_value: content_type_value } }
                                onChange={ ( value ) => { setAttributes( { link_dynamic_content: value } ); } }
                            />
                        ) }
                        <TextControl
                            label={ __( 'Link', 'alpus-core' ) }
                            value={ attributes.link }
                            onChange={ ( value ) => { setAttributes( { link: value } ); } }
                        />
                        <SelectControl
                            label={ __( 'Icon Position', 'alpus-core' ) }
                            value={ attributes.icon_pos }
                            options={ [
                                { label: __( 'Left', 'alpus-core' ), value: 'icon-box-side' },
                                { label: __( 'Top', 'alpus-core' ), value: '' },
                                { label: __( 'Right', 'alpus-core' ), value: 'icon-box-side right' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { icon_pos: value } ); } }
                        />
                        <SelectControl
                            label={ __( 'Title HTML Tag', 'alpus-core' ) }
                            value={ attributes.title_tag }
                            options={ [
                                { label: __( 'h1', 'alpus-core' ), value: 'h1' },
                                { label: __( 'h2', 'alpus-core' ), value: 'h2' },
                                { label: __( 'h3', 'alpus-core' ), value: 'h3' },
                                { label: __( 'h4', 'alpus-core' ), value: 'h4' },
                                { label: __( 'h5', 'alpus-core' ), value: 'h5' },
                                { label: __( 'h6', 'alpus-core' ), value: 'h6' },
                                { label: __( 'div', 'alpus-core' ), value: 'div' },
                                { label: __( 'span', 'alpus-core' ), value: 'span' },
                                { label: __( 'p', 'alpus-core' ), value: 'p' }
                            ] }
                            onChange={ ( value ) => { setAttributes( { title_tag: value } ); } }
                        />
                    </PanelBody>

                    <PanelBody
                        title={ __( 'Icon Style', 'alpus-core' ) }
                        initialOpen={ false }
                    >
                        <div className="alpus-typography-control alpus-dimension-control">
                            <h3 className="components-base-control" style={ { marginBottom: 15 } }>
                                { __( 'Normal', 'alpus-core' ) }
                            </h3>
                            <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                                { __( 'Primary Color', 'alpus-core' ) }
                                <span className="alpus-color-show" style={ { backgroundColor: attributes.icon_primary_selector } }>
                                </span>
                            </label>
                            <ColorPalette
                                label={ __( 'Color', 'alpus-core' ) }
                                colors={ [] }
                                value={ attributes.icon_primary_selector }
                                onChange={ ( value ) => setAttributes( { icon_primary_selector: value } ) }
                            />
                            { ( 'icon-inversed' === attributes.icon_view || 'icon-border' === attributes.icon_view ) && (
                                <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                                    { __( 'Secondary Color', 'alpus-core' ) }
                                    <span className="alpus-color-show" style={ { backgroundColor: attributes.icon_secondary_selector } }>
                                    </span>
                                </label>
                            ) }
                            { ( 'icon-inversed' === attributes.icon_view || 'icon-border' === attributes.icon_view ) && (
                                <ColorPalette
                                    label={ __( 'Color', 'alpus-core' ) }
                                    colors={ [] }
                                    value={ attributes.icon_secondary_selector }
                                    onChange={ ( value ) => setAttributes( { icon_secondary_selector: value } ) }
                                />
                            ) }
                        </div>
                        <div className="alpus-typography-control alpus-dimension-control">
                            <h3 className="components-base-control" style={ { marginBottom: 15 } }>
                                { __( 'Hover', 'alpus-core' ) }
                            </h3>
                            <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                                { __( 'Primary Color Hover', 'alpus-core' ) }
                                <span className="alpus-color-show" style={ { backgroundColor: attributes.icon_primary_hover_selector } }>
                                </span>
                            </label>
                            <ColorPalette
                                label={ __( 'Color', 'alpus-core' ) }
                                colors={ [] }
                                value={ attributes.icon_primary_hover_selector }
                                onChange={ ( value ) => setAttributes( { icon_primary_hover_selector: value } ) }
                            />
                            { ( 'icon-inversed' === attributes.icon_view || 'icon-border' === attributes.icon_view ) && (
                                <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                                    { __( 'Secondary Color Hover', 'alpus-core' ) }
                                    <span className="alpus-color-show" style={ { backgroundColor: attributes.icon_secondary_hover_selector } }>
                                    </span>
                                </label>
                            ) }
                            { ( 'icon-inversed' === attributes.icon_view || 'icon-border' === attributes.icon_view ) && (
                                <ColorPalette
                                    label={ __( 'Color', 'alpus-core' ) }
                                    colors={ [] }
                                    value={ attributes.icon_secondary_hover_selector }
                                    onChange={ ( value ) => setAttributes( { icon_secondary_hover_selector: value } ) }
                                />
                            ) }
                        </div>
                        <RangeControl
                            label={ __( 'Spacing (px)', 'alpus-core' ) }
                            value={ attributes.icon_spacing_selector }
                            min={ 0 }
                            max={ 100 }
                            step={ 1 }
                            allowReset={ true }
                            onChange={ ( val ) => setAttributes( { icon_spacing_selector: val } ) }
                        />
                        <RangeControl
                            label={ __( 'Size (px)', 'alpus-core' ) }
                            value={ attributes.icon_size_selector }
                            min={ 0 }
                            max={ 300 }
                            step={ 1 }
                            allowReset={ true }
                            onChange={ ( val ) => setAttributes( { icon_size_selector: val } ) }
                        />
                        <RangeControl
                            label={ __( 'Padding (px)', 'alpus-core' ) }
                            value={ attributes.icon_padding_selector }
                            min={ 0 }
                            max={ 100 }
                            step={ 1 }
                            allowReset={ true }
                            onChange={ ( val ) => setAttributes( { icon_padding_selector: val } ) }
                        />
                        <RangeControl
                            label={ __( 'Border Width (px)', 'alpus-core' ) }
                            value={ attributes.icon_border_width_selector }
                            min={ 0 }
                            max={ 50 }
                            step={ 1 }
                            allowReset={ true }
                            onChange={ ( val ) => setAttributes( { icon_border_width_selector: val } ) }
                        />
                    </PanelBody>

                    <PanelBody
                        title={ __( 'Content', 'alpus-core' ) }
                        initialOpen={ false }
                    >
                        <SelectControl
                            label={ __( 'Alignment', 'alpus-core' ) }
                            value={ attributes.content_align_selector }
                            options={ [ { label: __( 'None', 'alpus-core' ), value: '' }, { label: __( 'Left', 'alpus-core' ), value: 'left' }, { label: __( 'Center', 'alpus-core' ), value: 'center' }, { label: __( 'Right', 'alpus-core' ), value: 'right' }, { label: __( 'Justified', 'alpus-core' ), value: 'justify' } ] }
                            onChange={ ( value ) => setAttributes( { content_align_selector: value } ) }
                        />
                        { attributes.icon_pos != '' && <SelectControl
                            label={ __( 'Vertical Alignment', 'alpus-core' ) }
                            value={ attributes.content_valign_selector }
                            options={ [ { label: __( 'Top', 'alpus-core' ), value: '' }, { label: __( 'Middle', 'alpus-core' ), value: 'center' }, { label: __( 'Bottom', 'alpus-core' ), value: 'flex-end' } ] }
                            onChange={ ( value ) => setAttributes( { content_valign_selector: value } ) }
                        /> }
                        <RangeControl
                            label={ __( 'Title Spacing', 'alpus-core' ) }
                            value={ attributes.title_spacing_selector }
                            min={ 0 }
                            max={ 100 }
                            step={ 1 }
                            onChange={ ( val ) => setAttributes( { title_spacing_selector: val } ) }
                        />
                        <AlpusTypographyControl
                            label={ __( 'Title Typography', 'alpus-core' ) }
                            value={ title_font_settings }
                            options={ {} }
                            onChange={ ( value ) => { setAttributes( { title_font_settings: value } ); } }
                        />
                        <AlpusTypographyControl
                            label={ __( 'Description Typography', 'alpus-core' ) }
                            value={ desc_font_settings }
                            options={ {} }
                            onChange={ ( value ) => { setAttributes( { desc_font_settings: value } ); } }
                        />
                    </PanelBody>

                    <AlpusStyleOptionsControl
                        label={ __( 'Style Options', 'alpus-core' ) }
                        value={ style_options }
                        options={ {} }
                        onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                    />
                </InspectorControls>
                <div className={ "icon-box " + iconBoxStyleClass + ' ' + selectorCls }>
                    <style>{ iconBoxStyle }</style>
                    <div className="icon-box-icon">
                        { attributes.link ?
                            <a href={ attributes.link }>
                                <i className={ realIcon }></i>
                            </a>
                            : <i className={ realIcon }></i> }
                    </div>
                    <div className="icon-box-content">
                        <RichText
                            key='editable_title'
                            tagName={ attributes.title_tag }
                            className={ 'icon-box-title' }
                            value={ realTitle }
                            onChange={ ( value ) => { setAttributes( { title: value } ); } }
                        />
                        <RichText
                            key='editable_desc'
                            tagName='p'
                            value={ realDesc }
                            onChange={ ( value ) => { setAttributes( { desc: value } ); } }
                        />
                    </div>
                </div>
            </>
        )
    };

    if ( alpus_admin_vars ) {
        registerBlockType( alpus_admin_vars.theme + '/' + alpus_admin_vars.theme + '-icon-box', {
            title: alpus_admin_vars.theme_display_name + __( ' Icon Box', 'alpus-core' ),
            icon: 'alpus',
            category: alpus_admin_vars.theme,
            attributes: {
                icon_source: {
                    type: 'string',
                },
                icon_dynamic_content: {
                    type: 'object',
                },
                icon: {
                    type: 'string',
                    default: 'fas fa-star'
                },
                icon_view: {
                    type: 'string',
                },
                icon_shape: {
                    type: 'string',
                    default: 'icon-circle'
                },
                title_source: {
                    type: 'string',
                },
                title_dynamic_content: {
                    type: 'object',
                },
                title: {
                    type: 'string',
                    default: __( 'This is the heading', 'alpus-core' )
                },
                desc_source: {
                    type: 'string',
                },
                desc_dynamic_content: {
                    type: 'object',
                },
                desc: {
                    type: 'string',
                    default: __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpus-core' )
                },
                link_source: {
                    type: 'string',
                },
                link_dynamic_content: {
                    type: 'object',
                },
                link: {
                    type: 'string'
                },
                icon_pos: {
                    type: 'string',
                    default: ''
                },
                title_tag: {
                    type: 'string',
                    default: 'h3'
                },
                icon_primary_selector: {
                    type: 'string'
                },
                icon_primary_hover_selector: {
                    type: 'string'
                },
                icon_secondary_selector: {
                    type: 'string'
                },
                icon_secondary_hover_selector: {
                    type: 'string'
                },
                icon_spacing_selector: {
                    type: 'string'
                },
                icon_size_selector: {
                    type: 'int'
                },
                icon_padding_selector: {
                    type: 'int'
                },
                icon_border_width_selector: {
                    type: 'int'
                },
                content_align_selector: {
                    type: 'string'
                },
                content_valign_selector: {
                    type: 'string'
                },
                title_spacing_selector: {
                    type: 'int'
                },
                style_options: {
                    type: 'object'
                },
                title_font_settings: {
                    type: 'object'
                },
                desc_font_settings: {
                    type: 'object'
                }
            },
            keywords: [
                __( 'icon box' )
            ],
            description: __( 'Icon Box', 'alpus-core' ),
            edit: AlpusIconBox,
        } );
    }

} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );