<?php
/**
 * Alpus Dynamic Tags class
 *
 * @author     D-THEMES
 *
 * @since      1.0
 *
 * @version    1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Alpus_Core_Custom_Field_Post_User_Tag extends Elementor\Core\DynamicTags\Tag {

    protected $post_id;

    protected $is_archive;

    public function get_name() {
        return 'alpus-custom-field-post-user';
    }

    public function get_title() {
        return esc_html__( 'Posts / Author', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::TEXT_CATEGORY,
            Alpus_Core_Dynamic_Tags::NUMBER_CATEGORY,
            Alpus_Core_Dynamic_Tags::POST_META_CATEGORY,
            Alpus_Core_Dynamic_Tags::COLOR_CATEGORY,
        );
    }

    protected function register_controls() {
        $this->add_control(
            'dynamic_field_post_object',
            array(
                'label'   => esc_html__( 'Object Field', 'alpus-core' ),
                'type'    => Elementor\Controls_Manager::SELECT,
                'default' => 'post_title',
                'groups'  => $this->get_object_fields(),
            )
        );

        $this->add_control(
            'dynamic_field_post_date_format',
            array(
                'label'     => esc_html__( 'Format', 'alpus-core' ),
                'type'      => Elementor\Controls_Manager::SELECT,
                'options'   => array(
                    ''      => esc_html__( 'Default', 'alpus-core' ),
                    'M d Y' => gmdate( 'M d Y' ),
                    'd M Y' => gmdate( 'd M Y' ),
                ),
                'condition' => array(
                    'dynamic_field_post_object' => 'post_date',
                ),
            )
        );
    }

    public function get_object_fields() {
        $fields = array(
            array(
                'label'   => esc_html__( 'Post', 'alpus-core' ),
                'options' => array(
                    'post_id'          => esc_html__( 'Post ID', 'alpus-core' ),
                    'post_title'       => esc_html__( 'Title', 'alpus-core' ),
                    'post_date'        => esc_html__( 'Date', 'alpus-core' ),
                    'post_content'     => esc_html__( 'Content', 'alpus-core' ),
                    'post_excerpt'     => esc_html__( 'Excerpt', 'alpus-core' ),
                    'post_status'      => esc_html__( 'Post Status', 'alpus-core' ),
                    'comment_count'    => esc_html__( 'Comments Count', 'alpus-core' ),
                    'alpus_post_likes' => esc_html__( 'Like Posts Count', 'alpus-core' ),
                ),
            ),
            array(
                'label'   => esc_html__( 'Author', 'alpus-core' ),
                'options' => array(
                    'ID'    => esc_html__( 'Author ID', 'alpus-core' ),
                    'url'   => esc_html__( 'Author URL', 'alpus-core' ),
                    'email' => esc_html__( 'Author E-mail', 'alpus-core' ),
                    'login' => esc_html__( 'Author Login', 'alpus-core' ),
                    'name'  => esc_html__( 'Author Name', 'alpus-core' ),
                ),
            ),
        );

        return $fields;
    }

    public function render() {
        do_action( 'alpus_core_dynamic_before_render' );

        $this->post_id = get_the_ID();
        $atts          = $this->get_settings();
        $ret           = '';

        $property = $atts['dynamic_field_post_object'];

        $ret = (string) $this->get_prop( $property );

        if ( 'post_content' === $property ) {
            if ( ! empty( $this->post_id ) && Elementor\Plugin::$instance->documents->get( $this->post_id )->is_built_with_elementor() ) {
                $editor       = Elementor\Plugin::$instance->editor;
                $is_edit_mode = $editor->is_edit_mode();

                $editor->set_edit_mode( false );

                global $post;
                $temp = $post;
                $post = '';

                $ret = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $this->post_id, $is_edit_mode );

                $post = $temp;

                $editor->set_edit_mode( $is_edit_mode );
            } else {
                $ret = apply_filters( 'the_content', $ret );
            }
        }

        if ( 'alpus_post_likes' == $property ) {
            $ret = get_post_meta( $this->post_id, $property, true );
        }

        if ( is_array( $ret ) ) {
            $temp_content = '';

            if ( count( $ret ) > 1 ) {
                foreach ( $ret as $value ) {
                    $temp_content .= (string) $value;
                }
            } else {
                $temp_content .= (string) $ret[0];
            }
            $ret = $temp_content;
        }

        if ( ! is_wp_error( $ret ) ) {
            echo alpus_strip_script_tags( $ret );
        }

        do_action( 'alpus_core_dynamic_after_render' );
    }

    // helper functions
    public function get_post_object() {
        $post_object = false;

        global $post;

        if ( is_singular() ) {
            $post_object = $post;
        } elseif ( is_tax() || is_category() || is_tag() || is_author() || is_home() ) {
            $post_object = get_queried_object();
        } elseif ( wp_doing_ajax() ) {
            $post_object = get_post( $this->post_id );
        } elseif ( class_exists( 'Woocommerce' ) && is_shop() ) {
            $post_object = get_post( (int) get_option( 'woocommerce_shop_page_id' ) );
        } elseif ( is_archive() || is_post_type_archive() ) {
            $this->is_archive = true;
            $post_object      = get_queried_object();
        }

        return $post_object;
    }

    public function get_prop( $property = null, $object = null ) {
        $author_properties = array(
            'ID',
            'url',
            'email',
            'login',
            'name',
        );

        if ( $author_properties && in_array( $property, $author_properties ) ) {
            if ( 'name' == $property ) {
                $value = get_the_author();
            } else {
                $value = get_the_author_meta( $property );
            }

            return wp_kses_post( $value );
        } else {
            $this->is_archive = false;
            $object           = $this->get_post_object();
            $vars             = $object ? get_object_vars( $object ) : array();

            if ( 'post_id' === $property ) {
                $vars['post_id'] = isset( $vars['ID'] ) ? $vars['ID'] : false;
            } elseif ( 'post_title' == $property ) {
                if ( $this->is_archive ) {
                    $vars['post_title'] = isset( $vars['label'] ) ? $vars['label'] : false;
                }
                global $alpus_layout;
                Alpus_Layout_Builder::get_instance()->setup_titles();

                if ( ! empty( $alpus_layout['is_page_header'] ) && $alpus_layout['title'] ) {
                    $vars['post_title'] = $alpus_layout['title'];
                }
            } elseif ( 'post_date' == $property ) {
                $atts = $this->get_settings();

                if ( $atts['dynamic_field_post_date_format'] ) {
                    $vars[ $property ] = get_post_time( $atts['dynamic_field_post_date_format'], false, $object );
                }
            }
        }

        return isset( $vars[ $property ] ) ? $vars[ $property ] : false;
    }
}
