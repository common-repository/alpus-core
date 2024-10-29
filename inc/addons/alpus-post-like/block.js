/**
 * Alpus Framework Post Type Builder blocks
 *
 * @since 1.0
 */

import AlpusTypographyControl, {alpusGenerateTypographyCSS} from '../../../framework/plugins/gutenberg/assets/controls/typography';
import AlpusStyleOptionsControl, {alpusGenerateStyleOptionsCSS} from '../../../framework/plugins/gutenberg/assets/controls/style-options';
import {alpusAddHelperClasses} from '../../../framework/plugins/gutenberg/assets/controls/editor-extra-classes';

( function ( wpI18n, wpBlocks, wpBlockEditor, wpComponents ) {
    "use strict";

    const __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        SelectControl = wpComponents.SelectControl,
        TextControl = wpComponents.TextControl,
        RangeControl = wpComponents.RangeControl,
        ToggleControl = wpComponents.ToggleControl,
        UnitControl = wp.components.__experimentalUnitControl,
        Disabled = wpComponents.Disabled,
        PanelBody = wpComponents.PanelBody,
        ServerSideRender = wp.serverSideRender;

    const tmp_options = document.getElementById( 'content_type_term' ).options, alpus_all_terms = [];
    for (var i = 0; i < tmp_options.length; i++) {
        var option = tmp_options[i];
        if ( option.value ) {
            alpus_all_terms.push( { label: option.innerText.trim(), value: option.value } );
        }
    }

    const AlpusTBPostLIke = function ( { attributes, setAttributes, name, clientId } ) {

        const content_type = document.getElementById( 'content_type' ).value;
        let content_type_value,
            attrs = { disable_action: attributes.disable_action, icon_cls: attributes.icon_cls, dislike_icon_cls: attributes.dislike_icon_cls, icon_pos: attributes.icon_pos, el_class: attributes.el_class };
        if ( content_type ) {
            attrs.content_type = content_type;
            content_type_value = document.getElementById( 'content_type_' + content_type );
            if ( content_type_value ) {
                content_type_value = content_type_value.value;
                attrs.content_type_value = content_type_value;
            }
        }

        let internalStyle = '',
            font_settings = Object.assign( {}, attributes.font_settings );

        const style_options = Object.assign( {}, attributes.style_options );
        let selectorCls;
        if ( attributes.el_class ) {
            selectorCls = attributes.el_class;
        } else {
            selectorCls = 'alpus-tb-post-like-' + Math.ceil( Math.random() * 10000 );
            setAttributes( { el_class: selectorCls } );
        }

        if ( attributes.alignment || attributes.font_settings ) {
            let fontAtts = attributes.font_settings;
            fontAtts.alignment = attributes.alignment;

            internalStyle += alpusGenerateTypographyCSS( fontAtts, selectorCls );
        }

        if ( attributes.st_icon_fs ) {
            internalStyle += '.' + selectorCls + ' .alpus-tb-icon{font-size:' + attributes.st_icon_fs + '}';
        }
        if ( attributes.st_icon_spacing || 0 === attributes.st_icon_spacing ) {
            if ( 'right' === attributes.icon_pos ) {
                internalStyle += '.' + selectorCls + ' .alpus-tb-icon{margin-left:' + Number( attributes.st_icon_spacing ) + 'px}';
            } else {
                internalStyle += '.' + selectorCls + ' .alpus-tb-icon{margin-right:' + Number( attributes.st_icon_spacing ) + 'px}';
            }
        }

        // add helper classes to parent block element
        if ( attributes.className ) {
            alpusAddHelperClasses( attributes.className, clientId );
        }

        return (
            <>
                <InspectorControls key="inspector">
                    <ToggleControl
                        label={ __( 'Disable action?', 'alpus-core' ) }
                        checked={ attributes.disable_action }
                        onChange={ ( value ) => { setAttributes( { disable_action: value } ); } }
                    />
                    <TextControl
                        label={ __( 'Like Icon Class (ex: fas fa-pencil-alt)', 'alpus-core' ) }
                        value={ attributes.icon_cls }
                        onChange={ ( value ) => { setAttributes( { icon_cls: value } ); } }
                    />
                    { attrs.icon_cls && (
                        <TextControl
                            label={ __( 'Dislike Icon Class (ex: fas fa-pencil-alt)', 'alpus-core' ) }
                            value={ attributes.dislike_icon_cls }
                            onChange={ ( value ) => { setAttributes( { dislike_icon_cls: value } ); } }
                        />
                    ) }
                    { attrs.icon_cls && (
                        <SelectControl
                            label={ __( 'Icon Position', 'alpus-core' ) }
                            value={ attributes.icon_pos }
                            options={ [ { label: __( 'Left', 'alpus-core' ), value: '' }, { label: __( 'Right', 'alpus-core' ), value: 'right' } ] }
                            onChange={ ( value ) => { setAttributes( { icon_pos: value } ); } }
                        />
                    ) }
                    { attrs.icon_cls && (
                        <UnitControl
                            label={ __( 'Icon Size', 'alpus-core' ) }
                            value={ attributes.st_icon_fs }
                            onChange={ ( value ) => { setAttributes( { st_icon_fs: value } ); } }
                        />
                    ) }
                    { attrs.icon_cls && (
                        <div className={ 'spacer' } />
                    ) }
                    { attrs.icon_cls && (
                        <RangeControl
                            label={ __( 'Spacing (px)', 'alpus-core' ) }
                            help={ __( 'Spacing between icon and meta', 'alpus-core' ) }
                            value={ attributes.st_icon_spacing }
                            min="0"
                            max="100"
                            allowReset={ true }
                            onChange={ ( value ) => { setAttributes( { st_icon_spacing: value } ); } }
                        />
                    ) }
                    <PanelBody title={ __( 'Font Settings', 'alpus-core' ) } initialOpen={ false }>
                        <SelectControl
                            label={ __( 'Text Align', 'alpus-core' ) }
                            value={ attributes.alignment }
                            options={ [ { 'label': __( 'Inherit', 'alpus-core' ), 'value': '' }, { 'label': __( 'Left', 'alpus-core' ), 'value': 'left' }, { 'label': __( 'Center', 'alpus-core' ), 'value': 'center' }, { 'label': __( 'Right', 'alpus-core' ), 'value': 'right' }, { 'label': __( 'Justify', 'alpus-core' ), 'value': 'justify' } ] }
                            onChange={ ( value ) => { setAttributes( { alignment: value } ); } }
                        />
                        <AlpusTypographyControl
                            label={ __( 'Typography', 'alpus-core' ) }
                            value={ font_settings }
                            options={ { } }
                            onChange={ ( value ) => {
                                setAttributes( { font_settings: value } );
                            } }
                        />
                    </PanelBody>
                    <AlpusStyleOptionsControl
                        label={ __( 'Style Options', 'alpus-core' ) }
                        value={ style_options }
                        options={ { hoverOptions: true } }
                        onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                    />
                </InspectorControls>
                <Disabled>
                    <style>
                        { internalStyle }
                        { alpusGenerateStyleOptionsCSS( style_options, selectorCls ) }
                    </style>
                    <ServerSideRender
                        block={ name }
                        attributes={ attrs }
                    />
                </Disabled>
            </>
        )
    }
    registerBlockType( alpus_admin_vars.theme + '-tb/' + alpus_admin_vars.theme + '-post-like', {
        title: __( 'Post Like', 'alpus-core' ),
        icon: 'alpus',
        category: alpus_admin_vars.theme + '-tb',
        keywords: [ 'type builder', 'mini', 'card', 'post', 'like', 'feature', 'care', 'wishlist', 'recommend', 'dislike' ],
        attributes: {
            content_type: {
                type: 'string',
            },
            content_type_value: {
                type: 'string',
            },
            disable_action: {
                type: 'boolean',
            },
            icon_cls: {
                type: 'string',
            },
            dislike_icon_cls: {
                type: 'string',
            },
            icon_pos: {
                type: 'string',
            },
            st_icon_fs: {
                type: 'string',
            },
            st_icon_spacing: {
                type: 'int',
            },
            alignment: {
                type: 'string',
            },
            font_settings: {
                type: 'object',
                default: {},
            },
            style_options: {
                type: 'object',
            },
            el_class: {
                type: 'string',
            }
        },
        edit: AlpusTBPostLIke,
        save: function () {
            return null;
        }
    } );
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );