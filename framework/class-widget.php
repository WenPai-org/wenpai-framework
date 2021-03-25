<?php
/**
 * 小部件生成器
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace WenPai\Framework;

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

        private $widget_id = '';

        public function __construct( string $prefix, array $args ) {
            $this->args = wp_parse_args( $args, array(
                'id'          => '',
                'title'       => '',
                'classname'   => '',
                'description' => '',
                'fields'      => array(),
            ) );

            $this->prefix    = $prefix;
            $this->widget_id = $prefix . '_' . $this->args['id'];

            parent::__construct(
                $this->widget_id,
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
            echo str_replace( '">', ' '. $this->args['classname'] .'">', $args['before_widget'] ?? '' );
            if ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . $instance['title'] . $args['after_title'];
            }
            call_user_func( $this->widget_id, $instance );
            echo $args['after_widget'] ?? '';
        }

        public function form( $instance ): string {
            $fields_obj = new Fields( Fields::Widget );

            foreach ( $this->args['fields'] as $field ) {
                /** field数组的value和id字段的赋值顺序不可更改，具体原因稍微读下这两行代码就晓得了 */
                $field['value'] = $instance[$field['name']] ?? '';
                $field['name']  = $this->get_field_name( $field['name'] );
                $args = $fields_obj->parse_field_array( $field );

                echo '<p>';
                echo "<label for='{$field['name']}'>{$args['label']}:</label>";
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
