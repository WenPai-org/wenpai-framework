<?php
/**
 * 小部件生成器
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace Wenpai\Framework;

use WP_Widget;
use WP_Widget_Factory;

require_once 'class-fields.php';

if ( !class_exists( 'Widget' ) ) {

    /**
     * 小工具生成器
     *
     * @since 1.0.0
     */
    class Widget extends WP_Widget {

        private $prefix = '';

        private $args = array();

        public function __construct( string $prefix, array $args ) {
            $this->prefix = $prefix;

            $this->args = wp_parse_args( $args, array(
                'id'          => '',
                'title'       => '',
                'classname'   => '',
                'description' => '',
                'fields'      => array(),
            ) );

            parent::__construct(
                $prefix . '_' . $this->args['id'],
                $this->args['title'],
                array(
                    'description' => $this->args['description'],
                )
            );
        }

        public static function create( string $prefix, array $args ) {
            $wp_widget_factory = new WP_Widget_Factory();
            $wp_widget_factory->register( new self( $prefix, $args ) );
        }

        public function widget( $args, $instance ) {
            echo 'hello,world';
        }

        public function form( $instance ): string {
            $fields_obj = new Fields( Fields::Widget );

            foreach ( $this->args['fields'] as $field ) {
                $field['prefix']  = $this->prefix;
                $field['section'] = $this->args['id'] ?? '';
                $args = $fields_obj->parse_field_array( $field );

                echo '<p>';
                echo '<label>' . $args['name'] . ':</label>';
                call_user_func( array( $fields_obj, 'callback_' . $args['type'] ), $args );
                echo '</p>';
            }

            return '';
        }

        public function update( $new_instance, $old_instance ): array {
            return $new_instance;
        }

    }

}
