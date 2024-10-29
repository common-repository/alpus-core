const AlpusDynamicContentControl = function({
	label,
	value,
	options,
	onChange
}) {
	const __ = wp.i18n.__,
		TextControl = wp.components.TextControl,
		SelectControl = wp.components.SelectControl,
		useState = wp.element.useState,
		useEffect = wp.element.useEffect,
		useMemo = wp.element.useMemo,
		el = wp.element.createElement;

	if ( ! value ) {
		value = {};
	}
	if ( ! options.field_type ) {
		options.field_type = 'field';
	}

	let acf_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' } ];
	if ( alpus_block_vars.acf && alpus_block_vars.acf[options.field_type] ) {
		alpus_block_vars.acf[options.field_type].forEach( function( field_arr, index ) {
			_.forEach( field_arr.options, function( label, key ) {
				acf_fields.push( { label: field_arr.label + ' - ' + label, value: key } );
			} );
		} );
	}
	const [ acfFields, setAcfFields ] = useState( acf_fields );

	useMemo(
		() => {
			acf_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' } ];
			if ( alpus_block_vars.acf && alpus_block_vars.acf[options.field_type] ) {
				alpus_block_vars.acf[options.field_type].forEach( function( field_arr, index ) {
					_.forEach( field_arr.options, function( label, key ) {
						acf_fields.push( { label: field_arr.label + ' - ' + label, value: key } );
					} );
				} );
			}
			if ( acfFields !== acf_fields ) {
				setAcfFields( acf_fields );
			}
		},
		[ alpus_block_vars.acf ]
	);

	let metabox_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' } ];
	if ( alpus_block_vars.meta_fields ) {
		_.forEach( alpus_block_vars.meta_fields, function( value, key ) {
			if ( 'global' === key || key === options.content_type || key === options.content_type_value ) {
				_.forEach( value, function( label_type, title ) {
					if ( ( options.field_type && ( ! label_type[1].length || -1 !== label_type[1].indexOf( options.field_type ) ) ) ||
						! options.field_type ) {
						metabox_fields.push( { label: label_type[0], value: title } );
					}
				} );
			}
		} );
	}

	let post_info_fields = [], tax_fields = [],
		source_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' }, { label: __( 'Page or Post Info', 'alpus-core' ), value: 'post' }, { label: __( 'Alpus Meta Box Field', 'alpus-core' ), value: 'metabox' }, { label: __( 'Meta Field', 'alpus-core' ), value: 'meta' }, { label: __( 'Taxonomy', 'alpus-core' ), value: 'tax' } ];

	if ( acf_fields.length > 1 ) {
		source_fields.push( { label: __( 'Advanced Custom Field', 'alpus-core' ), value: 'acf' } );
	}

	if ( 'image' === options.field_type ) {
		post_info_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' }, { label: __( 'Featured Image', 'alpus-core' ), value: 'thumbnail' }, { label: __( 'Author Picture on Gravatar', 'alpus-core' ), value: 'author_img' } ];
		tax_fields = [];
	} else if ( 'link' === options.field_type ) {
		post_info_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' }, { label: __( 'Permalink', 'alpus-core' ), value: 'permalink' }, { label: __( 'Author Posts Url', 'alpus-core' ), value: 'author_posts_url' }, { label: __( 'Featured Image Url', 'alpus-core' ), value: 'thumbnail_url' } ];
		tax_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' }, { label: __( 'Term Link', 'alpus-core' ), value: 'term_link' } ];
	} else if ( 'field' === options.field_type ) {
		post_info_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' }, { label: __( 'ID', 'alpus-core' ), value: 'id' }, { label: __( 'Title', 'alpus-core' ), value: 'title' }, { label: __( 'Content', 'alpus-core' ), value: 'content' }, { label: __( 'Excerpt', 'alpus-core' ), value: 'excerpt' }, { label: __( 'Date', 'alpus-core' ), value: 'date' }, { label: __( 'Post Status', 'alpus-core' ), value: 'status' }, { label: __( 'Like Count', 'alpus-core' ), value: 'like_count' } ];
		tax_fields = [ { label: __( 'Please select...', 'alpus-core' ), value: '' }, { label: __( 'ID', 'alpus-core' ), value: 'id' }, { label: __( 'Title', 'alpus-core' ), value: 'title' }, { label: __( 'Description', 'alpus-core' ), value: 'desc' }, { label: __( 'Post Count', 'alpus-core' ), value: 'count' } ];
	}

	return el(
		'div',
		{ className: 'alpus-dynamic-content-control alpus-typography-control' },
		el(
			'h3',
			{ className: 'components-base-control', style: {marginBottom: 15} },
			label
		),
		el( SelectControl, {
			label: __( 'Source', 'alpus-core' ),
			help: __( 'Page or Post Info is used in posts list and Taxonomy is used in terms list.', 'alpus-core' ),
			value: value.source,
			options: source_fields,
			onChange: ( val ) => { value.source = val; onChange( value ); },
		} ),
		'post' == value.source && el( SelectControl, {
			label: __( 'Page or Post Info', 'alpus-core' ),
			value: value.post_info,
			options: post_info_fields,
			onChange: ( val ) => { value.post_info = val; onChange( value ); },
		} ),
		'metabox' == value.source && el( SelectControl, {
			label: __( 'Alpus Meta Box Field', 'alpus-core' ),
			value: value.metabox,
			options: metabox_fields,
			onChange: ( val ) => { value.metabox = val; onChange( value ); },
		} ),
		'acf' == value.source && el( SelectControl, {
			label: __( 'Advanced Custom Field', 'alpus-core' ),
			value: value.acf,
			options: acfFields,
			onChange: ( val ) => { value.acf = val; onChange( value ); },
		} ),
		'meta' == value.source && el( TextControl, {
			label: __( 'Custom Meta key', 'alpus-core' ),
			value: value.meta,
			onChange: ( val ) => { value.meta = val; onChange( value ); },
		} ),
		'tax' == value.source && el( SelectControl, {
			label: __( 'Taxonomy Field', 'alpus-core' ),
			value: value.tax,
			options: tax_fields,
			onChange: ( val ) => { value.tax = val; onChange( value ); },
		} ),
		'field' === options.field_type && el( TextControl, {
			label: __( 'Before Text', 'alpus-core' ),
			value: value.before,
			onChange: ( val ) => { value.before = val; onChange( value ); },
		} ),
		'field' === options.field_type && el( TextControl, {
			label: __( 'After Text', 'alpus-core' ),
			value: value.after,
			onChange: ( val ) => { value.after = val; onChange( value ); },
		} ),
		'image' !== options.field_type && el( TextControl, {
			label: __( 'Fallback', 'alpus-core' ),
			value: value.fallback,
			onChange: ( val ) => { value.fallback = val; onChange( value ); },
		} ),
	);
};

export default AlpusDynamicContentControl;