import AlpusTypographyControl, { alpusGenerateTypographyCSS } from '../controls/typography';
import AlpusStyleOptionsControl, { alpusGenerateStyleOptionsCSS, alpusGenerateStyleOptionsClass } from '../controls/style-options';
import AlpusDynamicContentControl from '../controls/dynamic-content';
import {alpusAddHelperClasses} from '../controls/editor-extra-classes';
/**
 * 06. Alpus Icon
 *
 * @since 1.2.1
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
        useEffect = wp.element.useEffect,
        useState = wp.element.useState;

    const AlpusIcon = function ( { attributes, setAttributes, edit = false, clientId } ) {
        const [ icon, setIcon ] = useState( attributes.icon );

        let selectorCls = 'alpus-gb-icon-' + Math.ceil( Math.random() * 10000 ),
            style_options = Object.assign( {}, attributes.style_options ),
            responsiveCls = alpusGenerateStyleOptionsClass( style_options ),
            additionalCls = attributes.className ? attributes.className + ' ' : '',
            realIcon = icon,
            dynamic_content = Object.assign( {}, attributes.dynamic_content ),
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
        useEffect(
            () => {
                let field_name = '';
                if ( attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].source ) {
                    if ( 'post' == attributes[ 'dynamic_content' ].source ) {
                        field_name = attributes[ 'dynamic_content' ].post_info;
                    } else {
                        field_name = attributes[ 'dynamic_content' ][ attributes[ 'dynamic_content' ].source ];
                    }
                    if ( field_name ) {
                        jQuery.ajax( {
                            url: alpus_core_vars.ajax_url,
                            data: {
                                action: 'alpus_dynamic_tags_get_value',
                                nonce: alpus_core_vars.nonce,
                                content_type: content_type ? content_type : 'post',
                                content_type_value: content_type ? content_type_value : alpus_block_vars.edit_post_id,
                                source: attributes[ 'dynamic_content' ].source,
                                field_name: field_name
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function ( res ) {
                                let text;
                                if ( res && res.success ) {
                                    text = '' + res.data;
                                } else {
                                    text = attributes[ 'dynamic_content' ].fallback;
                                }
                                setIcon( text );
                            }
                        } );
                    }
                }
            },
            [ attributes[ 'source' ], attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].source, attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].post_info, attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].metabox, attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].acf, attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].meta, attributes[ 'dynamic_content' ] && attributes[ 'dynamic_content' ].tax ],
        );

        if ( attributes.source ) {
            if ( !realIcon ) {
                realIcon = '';
            }
        } else {
            realIcon = attributes.icon;
        }

        let iconStyle = '',
            iconStyleClass = '';
        iconStyle = alpusGenerateStyleOptionsCSS( style_options, selectorCls );
        iconStyleClass += ' ' + responsiveCls + ' ' + additionalCls;

        let css = '';
        if ( attributes.st_fs ) {
            css += 'font-size:' + attributes.st_fs + 'px;';
        }
        if ( attributes.st_pd ) {
            css += 'padding:' + attributes.st_pd + 'px;';
        }
        if ( attributes.st_icon_clr ) {
            css += 'color:' + attributes.st_icon_clr + ';';
        }
        if ( css ) {
            iconStyle += '.' + selectorCls + '{' + css + '}';
        }
        if ( attributes.st_icon_clr_hover ) {
            iconStyle += '.' + selectorCls + ':hover{ color:' + attributes.st_icon_clr_hover + '; }';
        }

        // add helper classes to parent block element
        if ( attributes.className ) {
            alpusAddHelperClasses( attributes.className, clientId );
        }

        return (
            <>
                <InspectorControls key="inspector1">
                    <PanelBody
                        title={ __( 'Icon', 'alpus-core' ) }
                    >
                        <SelectControl
                            label={ __( 'Icon Source', 'alpus-core' ) }
                            value={ attributes.source }
                            options={ [ { label: __( 'Custom Icon', 'alpus-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpus-core' ), value: 'dynamic' } ] }
                            onChange={ ( value ) => { setAttributes( { source: value } ); } }
                        />
                        { 'dynamic' == attributes.source && (
                            <AlpusDynamicContentControl
                                label={ __( 'Dynamic Icon', 'alpus-core' ) }
                                value={ dynamic_content }
                                options={ { field_type: 'field', content_type: content_type, content_type_value: content_type_value } }
                                onChange={ ( value ) => { setAttributes( { dynamic_content: value } ); } }
                            />
                        ) }
                        { !attributes.source && (
                            <TextControl
                                label={ __( 'Icon', 'alpus-core' ) }
                                value={ attributes.icon }
                                onChange={ ( value ) => { setAttributes( { icon: value } ); } }
                                placeholder={ __( 'Type the icon class name', 'alpus-core' ) }
                                help={ __( 'Please check this link to see icons which WP Alpus supports.', 'alpus-core' ) }
                            />
                        ) }

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
                    </PanelBody>

                    <PanelBody
                        title={ __( 'Icon Style', 'alpus-core' ) }
                        initialOpen={ false }
                    >
                        <RangeControl
                            label={ __( 'Size (px)', 'alpus-core' ) }
                            value={ attributes.st_fs }
                            min={ 0 }
                            max={ 300 }
                            step={ 1 }
                            allowReset={ true }
                            onChange={ ( val ) => setAttributes( { st_fs: val } ) }
                        />
                        <RangeControl
                            label={ __( 'Padding (px)', 'alpus-core' ) }
                            value={ attributes.st_pd }
                            min={ 0 }
                            max={ 100 }
                            step={ 1 }
                            allowReset={ true }
                            onChange={ ( val ) => setAttributes( { st_pd: val } ) }
                        />

                        <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                            { __( 'Color', 'alpus-core' ) }
                            <span className="alpus-color-show" style={ { backgroundColor: attributes.st_icon_clr } }>
                            </span>
                        </label>
                        <ColorPalette
                            label={ __( 'Color', 'alpus-core' ) }
                            colors={ [] }
                            value={ attributes.st_icon_clr }
                            onChange={ ( value ) => setAttributes( { st_icon_clr: value } ) }
                        />
                        <div className="spacer" />
                        <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                            { __( 'Hover Color', 'alpus-core' ) }
                            <span className="alpus-color-show" style={ { backgroundColor: attributes.st_icon_clr_hover } }>
                            </span>
                        </label>
                        <ColorPalette
                            label={ __( 'Color', 'alpus-core' ) }
                            colors={ [] }
                            value={ attributes.st_icon_clr_hover }
                            onChange={ ( value ) => setAttributes( { st_icon_clr_hover: value } ) }
                        />
                    </PanelBody>

                    <AlpusStyleOptionsControl
                        label={ __( 'Style Options', 'alpus-core' ) }
                        value={ style_options }
                        options={ { hoverOptions: true } }
                        onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                    />
                </InspectorControls>
                <div className={ 'alpus-icon ' + iconStyleClass.trim() + ' ' + selectorCls }>
                    <style>{ iconStyle }</style>
                    <i className={ realIcon }></i>
                </div>
            </>
        )
    };

    if ( alpus_admin_vars ) {
        registerBlockType( alpus_admin_vars.theme + '/' + alpus_admin_vars.theme + '-icon', {
            title: alpus_admin_vars.theme_display_name + __( ' Icon', 'alpus-core' ),
            icon: 'alpus',
            category: alpus_admin_vars.theme,
            attributes: {
                source: {
                    type: 'string',
                },
                dynamic_content: {
                    type: 'object',
                },
                icon: {
                    type: 'string',
                    default: 'fas fa-star'
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
                st_icon_clr: {
                    type: 'string'
                },
                st_icon_clr_hover: {
                    type: 'string'
                },
                st_fs: {
                    type: 'int'
                },
                st_pd: {
                    type: 'int'
                },
                style_options: {
                    type: 'object'
                },
            },
            keywords: [
                'icon'
            ],
            description: __( 'Icon', 'alpus-core' ),
            edit: AlpusIcon,
        } );
    }

} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );