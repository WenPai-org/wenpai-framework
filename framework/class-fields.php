<?php
/**
 * 组件库
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace Wenpai\Framework;

if ( !class_exists( 'Fields' ) ) {

    /**
     * 组件库
     *
     * 该类封装了一批通用组件，例如：text（文本框）、switcher（开关）
     *
     * @since 1.0.0
     */
    class Fields {

        const Setting = 'setting';

        const Widget = 'widget';

        private $type = '';

        public function __construct( $type = self::Setting ) {
            $this->type = $type;
        }

        /**
         * 获取某个字段被HTML包裹的描述信息
         *
         * @since 1.0.0
         *
         * @param array $args 字段信息的数组，直接原样传入即可
         *
         * @return string 被HTML包裹的描述信息
         */
        private function _get_field_description( array $args ): string {
            if ( ! empty( $args['desc'] ) ) {
                $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
            } else {
                $desc = '';
            }

            return $desc;
        }

        /**
         * 获取设置项值
         *
         * @param string $option  设置项ID
         * @param string $prefix  所属的应用程序前缀
         * @param string $section 设置组ID
         * @param string $default 默认值
         *
         * @return mixed|string
         */
        public function get_option(string $option, string $prefix, string $section, $default = ''): string {
            $options = get_option( "{$prefix}_{$section}" );

            if ( isset( $options[$option] ) ) {
                return $options[$option];
            }

            return $default;
        }

        /**
         * 格式化字段信息数组
         *
         * @param array $args 要格式化的字段信息数组
         *
         * @return array 返回格式化后的数据
         */
        public function parse_field_array( array $args ): array {
            $defaults = array(
                'name'        => '',
                'prefix'      => '',
                'id'          => '',
                'section'     => '',
                'size'        => '',
                'placeholder' => '',
            );

            return wp_parse_args( $args, $defaults );
        }

        /**
         * Text组件
         *
         * @since 1.0.0
         *
         * @param array $args {
         *     @type string $name        字段名
         *     @type string $section     区块ID
         *     @type string $size        大小
         *     @type string $placeholder HTML placeholder属性值
         * }
         */
        public function callback_text( array $args ) {
            $value       = self::get_option($args['id'], $args['prefix'], $args['section']);
            if ( self::Setting === $this->type ) {
                $size = isset($args['size']) && ! empty($args['size']) ? $args['size'] : 'regular';
                $size .= '-text';
            } elseif ( self::Widget === $this->type ) {
                $size = isset($args['size']) && ! empty($args['size']) ? $args['size'] : 'widefat';
            }
            $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

            $html        = sprintf( '<input type="text" class="%1$s" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"%5$s/>',
                $size, "{$args['prefix']}_{$args['section']}", $args['id'], $value, $placeholder );
            $html       .= self::_get_field_description( $args );

            echo $html;
        }

    }
}
