<?php
/**
 * Alpus Dynamic Tags class
 *
 * @author     D-THEMES
 *
 * @since      1.2.1
 *
 * @version    1.2.1
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Alpus_Core_Custom_Link_Post_User_Tag extends Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'alpus-custom-link-post-user';
    }

    public function get_title() {
        return esc_html__( 'Post Link', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::URL_CATEGORY,
        );
    }

    /**
     * remove Fallback function
     */
    public function register_advanced_section() {
        parent::register_advanced_section();

        $this->remove_control( 'before' );
        $this->remove_control( 'after' );
    }

    protected function register_controls() {
        $this->add_control(
            'dynamic_link_post_object',
            array(
                'label'   => esc_html__( 'Object Link', 'alpus-core' ),
                'type'    => Elementor\Controls_Manager::SELECT,
                'default' => 'post_url',
                'groups'  => $this->get_object_links(),
            )
        );
    }

    public function get_object_links() {
        $fields = array(
            array(
                'label'   => esc_html__( 'Post', 'alpus-core' ),
                'options' => array(
                    'post_url'           => esc_html__( 'Post Url', 'alpus-core' ),
                    'site_url'           => esc_html__( 'Site Url', 'alpus-core' ),
                    'author_archive_url' => esc_html__( 'Author Archive', 'alpus-core' ),
                    'author_website_url' => esc_html__( 'Author Website', 'alpus-core' ),
                    'comments_url'       => esc_html__( 'Comments Url', 'alpus-core' ),
                ),
            ),
        );

        return $fields;
    }

    public function render() {
        if ( is_404() ) {
            return;
        }
        do_action( 'alpus_core_dynamic_before_render' );

        $atts     = $this->get_settings();
        $property = $atts['dynamic_link_post_object'];

        switch ( $property ) {
            case 'post_url':
                $ret = get_permalink();
                break;

            case 'site_url':
                $ret = home_url();
                break;

            case 'author_archive_url':
                global $authordata;

                if ( $authordata ) {
                    $ret = get_author_posts_url( $authordata->ID, $authordata->user_nicename );
                }
                break;

            case 'author_website_url':
                $ret = get_the_author_meta( 'url' );
                break;

            case 'comments_url':
                $ret = get_comments_link();
                break;
            default:
                $ret = '';
                break;
        }

        if ( ! is_wp_error( $ret ) ) {
            echo alpus_strip_script_tags( $ret );
        }

        do_action( 'alpus_core_dynamic_after_render' );
    }
}
