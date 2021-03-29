<?php
/**
 * 组件库
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace WenPai\Framework;

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

        const Meta_Box = 'meta_box';

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
                if ( self::Setting === $this->type ) {
                    $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
                } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                    $desc = sprintf( '<small class="description">%s</small>', $args['desc'] );
                }
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
         * @return array|string
         */
        public function get_option(string $option, string $prefix, string $section, $default = '') {
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
                'label'       => '',
                'section'     => '',
                'size'        => '',
                'placeholder' => '',
                'value'       => '',
                'desc'        => '',
                'default'     => '',
                'options'     => '',
                'std'         => '',
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
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option($args['name'], $args['prefix'], $args['section'], $args['default']);
                $size  = isset($args['size']) && ! empty($args['size']) ? $args['size'] : 'regular';
                $size .= '-text';
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset($args['size']) && ! empty($args['size']) ? $args['size'] : 'widefat';
                $name  = $args['name'];
            }
            $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

            $html        = sprintf( '<input type="text" class="%1$s" id="%2$s" name="%2$s" value="%3$s"%4$s/>',
                $size, $name, $value, $placeholder );
            $html       .= self::_get_field_description( $args );

            echo $html;
        }

        function callback_select( $args ) {
            $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'] );
            $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
            $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";

            $html  = sprintf( '<select class="%1$s" name="%2$s" id="%2$s">', $size, $name );

            foreach ( $args['options'] as $key => $label ) {
                $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
            }

            $html .= sprintf( '</select>' );
            $html .= $this->_get_field_description( $args );

            echo $html;
        }

        function callback_switcher( $args ) {
            $value = self::get_option($args['name'], $args['prefix'], $args['section']);
            $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";

            $html  = '<fieldset>';
            $html  .= sprintf( '<label for="wpf-%1$s">', $name );
            $html  .= sprintf( '<input type="hidden" name="%1$s" value="off" />', $name );
            $html  .= sprintf( '<input type="checkbox" class="checkbox" id="wpf-%1$s" name="%1$s" value="on" %2$s />', $name, checked( $value, 'on', false ) );
            $html  .= sprintf( '%1$s</label>', $args['desc'] );
            $html  .= '</fieldset>';

            echo $html;
        }

        function callback_checkbox( $args ) {
            $value = $this->get_option( $args['name'], $args['prefix'], $args['section'], $args['std'] );
            $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";

            $html  = '<fieldset>';
            $html .= sprintf( '<input type="hidden" name="%1$s" value="" />', $name );
            foreach ( $args['options'] as $key => $label ) {
                $checked = isset( $value[$key] ) ? $value[$key] : '0';
                $html    .= sprintf( '<label for="wpf-%1$s[%2$s]">', $name, $key );
                $html    .= sprintf( '<input type="checkbox" class="checkbox" id="wpf-%1$s[%2$s]" name="%1$s[%2$s]" value="%2$s" %3$s />', $name, $key, checked( $checked, $key, false ) );
                $html    .= sprintf( '%1$s</label><br>',  $label );
            }

            $html .= $this->_get_field_description( $args );
            $html .= '</fieldset>';

            echo $html;
        }

        function callback_radio( $args ) {
            $value = $this->get_option( $args['name'], $args['prefix'], $args['section'], $args['default'] );
            $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            $html  = '<fieldset>';

            foreach ( $args['options'] as $key => $label ) {
                $html .= sprintf( '<label for="wpf-%1$s[%2$s]">',  $name, $key );
                $html .= sprintf( '<input type="radio" class="radio" id="wpf-%1$s[%2$s]" name="%1$s" value="%2$s" %3$s />', $name, $key, checked( $value, $key, false ) );
                $html .= sprintf( '%1$s</label><br>', $label );
            }

            $html .= $this->_get_field_description( $args );
            $html .= '</fieldset>';

            echo $html;
        }

    }
}
