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

        public function form($instance): string {
            echo 'asf';
        }

        public function update($new_instance, $old_instance): array {
        }

    }

}
