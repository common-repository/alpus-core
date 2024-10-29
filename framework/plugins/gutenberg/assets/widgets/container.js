import AlpusStyleOptionsControl, { alpusGenerateStyleOptionsCSS } from '../controls/style-options';
import { alpusGenerateStyleOptionsClass } from '../controls/style-options';
import {alpusAddHelperClasses} from '../controls/editor-extra-classes';
/**
 * 03. Alpus Container
 * 
 * @since 1.2.0
 */
( function ( wpI18n, wpBlocks, wpBlockEditor, wpComponents ) {
    "use strict";

    var __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InnerBlocks = wpBlockEditor.InnerBlocks,
        InspectorControls = wpBlockEditor.InspectorControls,
        PanelBody = wpComponents.PanelBody,
        ToggleControl = wpComponents.ToggleControl,
        SelectControl = wpComponents.SelectControl;

    /**
     *  Encode style options for enqueue key
     * 
     *  @param object attr attributes object 
     *  @since 1.2.0 
     */
    const alpusHashCode = function ( attr ) {
        if ( typeof attr == 'object' && Object.keys( attr ).length != 0 ) {
            var attrStr = encodeURIComponent( JSON.stringify( attr ) ).replace( '/[^0-9a-zA-A]/gi' ),
                blockId = 0;

            for ( var i = 0; i < attrStr.length; i++ ) {
                blockId += attrStr.charCodeAt( i ) - 50;
            }
            return Math.abs( blockId );
        }
        return false;
    };

    const AlpusContainer = function ( { attributes, setAttributes, clientId } ) {
        let containerCls = 'alpus-gb-container ',
            styleOptions = Object.assign( {}, attributes.style_options ),
            selectorCls = 'alpus-gb-container-' + Math.ceil( Math.random() * 10000 );
        containerCls += selectorCls + alpusGenerateStyleOptionsClass( styleOptions );
        let containerStyles = alpusGenerateStyleOptionsCSS( styleOptions, selectorCls );
        if ( attributes.className ) {
            containerCls += ' ' + attributes.className.trim();
        }

        if ( attributes.flex_box ) {
            containerCls += ' d-flex';
            if ( attributes.flex_wrap ) {
                containerCls += ' flex-wrap';
            }
            containerCls += ' justify-content-' + attributes.horizontal_align;
            containerCls += ' align-items-' + attributes.vertical_align;
        } else {
            containerCls += ' text-' + attributes.text_align;
        }

        // add helper classes to parent block element
        if ( attributes.className ) {
            alpusAddHelperClasses( attributes.className, clientId );
        }

        return (
            <>
                <InspectorControls key="inspector">

                    <PanelBody label={ __( 'Container', 'alpus-core' ) }>
                        <ToggleControl
                            label={ __( 'Flex Container?', 'alpus-core' ) }
                            checked={ attributes.flex_box }
                            onChange={ ( value ) => { setAttributes( { flex_box: value } ); } }
                            help={ __( 'If check this option, the container do as flex style.', 'alpus-core' ) }
                        />

                        { attributes.flex_box && (
                            <ToggleControl
                                label={ __( 'Wrap onto Multiple Lines?', 'alpus-core' ) }
                                checked={ attributes.flex_wrap }
                                onChange={ ( value ) => { setAttributes( { flex_wrap: value } ); } }
                            />
                        ) }

                        { attributes.flex_box && (
                            <SelectControl
                                label={ __( 'Horizontal Align', 'alpus-core' ) }
                                value={ attributes.horizontal_align }
                                options={ [
                                    { label: __( 'Left', 'alpus-core' ), value: 'start' },
                                    { label: __( 'Center', 'alpus-core' ), value: 'center' },
                                    { label: __( 'Right', 'alpus-core' ), value: 'end' },
                                    { label: __( 'Space Between', 'alpus-core' ), value: 'between' },
                                    { label: __( 'Space Around', 'alpus-core' ), value: 'around' },
                                ] }
                                onChange={ ( value ) => { setAttributes( { horizontal_align: value } ); } }
                            />
                        ) }

                        { attributes.flex_box && (
                            <SelectControl
                                label={ __( 'Vertical Align', 'alpus-core' ) }
                                value={ attributes.vertical_align }
                                options={ [
                                    { label: __( 'Top', 'alpus-core' ), value: 'start' },
                                    { label: __( 'Center', 'alpus-core' ), value: 'center' },
                                    { label: __( 'Bottom', 'alpus-core' ), value: 'end' },
                                ] }
                                onChange={ ( value ) => { setAttributes( { vertical_align: value } ); } }
                            />
                        ) }

                        { !attributes.flex_box && (
                            <SelectControl
                                label={ __( 'Text Align', 'alpus-core' ) }
                                value={ attributes.text_align }
                                options={ [
                                    { label: __( 'Left', 'alpus-core' ), value: 'start' },
                                    { label: __( 'Center', 'alpus-core' ), value: 'center' },
                                    { label: __( 'Right', 'alpus-core' ), value: 'end' },
                                ] }
                                onChange={ ( value ) => { setAttributes( { text_align: value } ); } }
                            />
                        ) }

                    </PanelBody>

                    <AlpusStyleOptionsControl
                        label={ __( 'Style Options', 'alpus-core' ) }
                        value={ styleOptions }
                        options={ {} }
                        onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                    />
                </InspectorControls>
                <div className={ containerCls }>
                    { containerStyles && (
                        <style>{ containerStyles }</style>
                    ) }
                    <InnerBlocks />
                </div>
            </>
        );
    };

    if ( alpus_admin_vars ) {
        registerBlockType( alpus_admin_vars.theme + '/' + alpus_admin_vars.theme + '-container', {
            title: alpus_admin_vars.theme_display_name + __( ' Container', 'alpus-core' ),
            icon: 'alpus',
            category: alpus_admin_vars.theme,
            attributes: {
                flex_box: {
                    type: 'boolean',
                },
                flex_wrap: {
                    type: 'boolean',
                },
                horizontal_align: {
                    type: 'string',
                    default: 'start',
                },
                vertical_align: {
                    type: 'string',
                    default: 'start',
                },
                text_align: {
                    type: 'string',
                    default: 'left'
                },
                style_options: {
                    type: 'object',
                },
            },
            keywords: [
                __( 'container', 'alpus-core' ),
                __( 'wrap', 'alpus-core' ),
                __( 'flex', 'alpus-core' ),
                __( 'block', 'alpus-core' ),
            ],
            description: __( 'Container Wrapper.', 'alpus-core' ),
            edit: AlpusContainer,
            save: function ( props ) {
                return (
                    <InnerBlocks.Content />
                );
            }
        } );
    }
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );