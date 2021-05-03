<?php
/**
 * 组件库
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace WenPai\Framework;

if ( ! class_exists( Fields::class ) ) {

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
         * @param array $args 字段信息的数组，直接原样传入即可
         * @return string 被HTML包裹的描述信息
         */
        private function _get_field_description( array $args ): string {
            if ( ! empty( $args['desc'] ) ) {
                if ( self::Setting === $this->type ) {
                    $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
                } elseif ( self::Widget === $this->type ) {
                    $desc = sprintf( '<br /><small class="description">%s</small>', $args['desc'] );
                } elseif ( self::Meta_Box === $this->type ) {
                    $desc = sprintf( '<p class="howto" id="new-tag-post_tag-desc">%s</p>', $args['desc'] );
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
         * @return array|string
         */
        public function get_option( string $option, string $prefix, string $section, $default = '', $network = false ) {
            $network = is_multisite() && $network;

            $options = $network ? get_site_option( "{$prefix}_{$section}") : get_option( "{$prefix}_{$section}" );
            if ( isset( $options[ $option ] ) ) {
                return $options[ $option ];
            }

            return $default;
        }

        /**
         * 格式化字段信息数组
         *
         * @param array $args 要格式化的字段信息数组
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
                'html'        => '',
                'network'     => false,
                'fields'      => [],
            );

            return wp_parse_args( $args, $defaults );
        }

        /**
         * 文本框组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $desc 描述
         *    @type string $placeholder HTML placeholder属性值
         * }
         */
        public function callback_text( array $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'regular';
                $size  .= '-text';
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'widefat';
                $name  = $args['name'];
            }
            $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

            $html = sprintf( '<input type="text" class="%1$s" id="%2$s" name="%2$s" value="%3$s"%4$s/>',
                $size, $name, $value, $placeholder );
            $html .= self::_get_field_description( $args );

            echo $html;
        }

        public function callback_number( $args ) {
            $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
            $size        = isset( $args['size'] ) && !empty( $args['size'] ) ? $args['size'] : 'regular';
            $type        = isset( $args['type'] ) ? $args['type'] : 'number';
            $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';
            $min         = ( $args['min'] === '' ) ? '' : ' min="' . $args['min'] . '"';
            $max         = ( $args['max'] === '' ) ? '' : ' max="' . $args['max'] . '"';
            $step        = ( $args['step'] === '' ) ? '' : ' step="' . $args['step'] . '"';

            $html        = sprintf( '<input type="%1$s" class="%2$s-number" id="%3$s" name="%3$s" value="%4$s"%5$s%6$s%7$s%8$s/>', $type, $size, $name, $value, $placeholder, $min, $max, $step );
            $html       .= $this->_get_field_description( $args );

            echo $html;
        }

        /**
         * 密码框组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $desc 描述
         * }
         */
        public function callback_password( array $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'regular';
                $size  .= '-text';
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'widefat';
                $name  = $args['name'];
            }

            $html = sprintf( '<input type="password" class="%1$s" id="%2$s" name="%2$s" value="%3$s"/>',
                $size, $name, $value );
            $html .= self::_get_field_description( $args );

            echo $html;
        }

        /**
         * 下拉框组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $options 选项
         *    @type string $default 默认值
         *    @type string $desc 描述
         * }
         */
        public function callback_select( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
                $size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'widefat';
                $name  = $args['name'];
            }

            $html = sprintf( '<select style="box-sizing: border-box;" class="%1$s" name="%2$s" id="%2$s">', $size, $name );

            foreach ( $args['options'] as $key => $label ) {
                $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
            }

            $html .= sprintf( '</select>' );
            $html .= self::_get_field_description( $args );

            echo $html;
        }

        /**
         * 开关组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $default 默认值
         *    @type string $desc 描述
         * }
         */
        public function callback_switcher( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $name  = $args['name'];
            }

            $html = '<fieldset>';
            $html .= sprintf( '<label for="wpf-%1$s">', $name );
            $html .= sprintf( '<input type="hidden" name="%1$s" value="off" />', $name );
            $html .= sprintf( '<input type="checkbox" class="checkbox" id="wpf-%1$s" name="%1$s" value="on" %2$s />', $name, checked( $value, 'on', false ) );
            $html .= sprintf( '%1$s</label>', $args['desc'] );
            $html .= '</fieldset>';

            echo $html;
        }

        /**
         * 多选框组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $std 未知东西
         *    @type string $options 选项
         *    @type string $default 默认值
         * }
         */
        public function callback_checkbox( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network']  );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $name  = $args['name'];
            }

            $html = '<fieldset>';
            $html .= sprintf( '<input type="hidden" name="%1$s" value="" />', $name );
            foreach ( $args['options'] as $key => $label ) {
                $checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
                $html    .= sprintf( '<label for="wpf-%1$s[%2$s]">', $name, $key );
                $html    .= sprintf( '<input type="checkbox" class="checkbox" id="wpf-%1$s[%2$s]" name="%1$s[%2$s]" value="%2$s" %3$s />', $name, $key, checked( $checked, $key, false ) );
                $html    .= sprintf( '%1$s</label><br>', $label );
            }

            $html .= self::_get_field_description( $args );
            $html .= '</fieldset>';

            echo $html;
        }

        /**
         * 单选框组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $options 选项
         *    @type string $default 默认值
         * }
         */
        public function callback_radio( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $name  = $args['name'];
            }
            $html = '<fieldset>';

            foreach ( $args['options'] as $key => $label ) {
                $html .= sprintf( '<label for="wpf-%1$s[%2$s]">', $name, $key );
                $html .= sprintf( '<input type="radio" class="radio" id="wpf-%1$s[%2$s]" name="%1$s" value="%2$s" %3$s />', $name, $key, checked( $value, $key, false ) );
                $html .= sprintf( '%1$s</label><br>', $label );
            }

            $html .= self::_get_field_description( $args );
            $html .= '</fieldset>';

            echo $html;
        }

        /**
         * 多行文本框组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $placeholder placeholder属性值
         *    @type string $desc 描述
         * }
         */
        public function callback_textarea( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['std'], $args['network'] );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
                $size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'components-textarea-control__input css-1l8z26q-StyledTextarea-inputStyleNeutral-inputStyleFocus-inputControl ebk7yr50 widefat';
                $name  = $args['name'];
            }
            $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

            $html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s" name="%2$s"%3$s>%4$s</textarea>', $size, $name, $placeholder, $value );
            $html .= self::_get_field_description( $args );

            echo $html;
        }

        /**
         * 原生HTML组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $html HTML代码
         * }
         */
        public function callback_html( $args ) {
            echo $args['html'];
        }

        /**
         * 颜色拾取组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $default 默认颜色
         * }
         */
        public function callback_color( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
                $size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'widefat';
                $name  = $args['name'];
            }

            $html = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s" name="%2$s" value="%3$s" data-default-color="%4$s" />', $size, $name, $value, $args['default'] );
            $html .= self::_get_field_description( $args );

            echo $html;
        }

        /**
         * 文件上传组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $std 未知属性
         * }
         */
        public function callback_file( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['std'], $args['network'] );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
                $size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : 'widefat';
                $name  = $args['name'];
            }
            $label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' );

            $html = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s" name="%2$s" value="%3$s"/>', $size, $name, $value );
            $html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
            $html .= self::_get_field_description( $args );

            echo $html;
        }

        /**
         * TinyMCE编辑器组件
         *
         * @since 1.0.0
         * @param array $args {
         *    @type string $name 字段名
         *    @type string $section 区块ID
         *    @type string $size 大小
         *    @type string $std 未知属性
         * }
         */
        public function callback_tinymce( $args ) {
            $value = $size = $name = '';
            if ( self::Setting === $this->type ) {
                $value = self::get_option( $args['name'], $args['prefix'], $args['section'], $args['std'], $args['network']  );
                $name  = "{$args['prefix']}_{$args['section']}[{$args['name']}]";
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : '500px';
            } elseif ( self::Widget === $this->type || self::Meta_Box === $this->type ) {
                $value = $args['value'];
                $size  = isset( $args['size'] ) && ! empty( $args['size'] ) ? $args['size'] : '400px';
                $name  = $args['name'];
            }

            echo '<div style="max-width: ' . $size . ';">';

            $editor_settings = array(
                'teeny'         => true,
                'textarea_name' => $name,
                'textarea_rows' => 10
            );

            if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
                $editor_settings = array_merge( $editor_settings, $args['options'] );
            }

            wp_editor( $value, $args['section'] . '-' . $args['name'], $editor_settings );

            echo '</div>';

            echo self::_get_field_description( $args );
        }

        function callback_card( $args ) {
            $value = $this->get_option( $args['name'], $args['prefix'], $args['section'], $args['default'], $args['network'] );
            if ( ! empty( $value ) ) {
                foreach ( $value as $card_id => $card ) {
                    echo '<section class="card">';
                    foreach ( $args['fields'] as $key => $field ) {
                        echo '<li>';
                        echo '<label>' . $field['label'] . '</label>';
                        echo '<aside>';
                        $field['prefix']  = $args['prefix'];
                        $field['section'] = sprintf( '%s[%s][%s]', $args['section'], $args['name'], $card_id ) ?? '';
                        $field['default'] = @$card[ $field['name'] ];
                        $field['network'] = $args['network'];

                        $field = $this->parse_field_array( $field );

                        call_user_func( array( $this, 'callback_' . $field['type'] ), $field );
                        echo '</aside>';
                        echo '</li>';
                    }
                    echo '<footer>';
                    echo '<a class="remove-card" href="javascript:">' . esc_html__( 'Remove' ) . '</a>';
                    echo '</footer>';
                    echo '</section>';
                }
            }
            ?>
          <footer class="card-footer"><a href="javascript:;" class="add-card"><?php esc_html_e( 'Add' ) ?></a></footer>
          <style>
              a {
                  text-decoration: none;
              }

              section.card {
                  padding: 1.5em;
                  margin: 0 0 10px;
                  background: #f3f2f2;
              }

              section.card li {
                  display: flex;
                  list-style: none;
              }

              section.card > li > label {
                  min-width: 100px;
                  line-height: 2.1;
              }

              .loading-position aside label {
                  display: block;
                  margin: 10px 0 0;
              }

              .loading-position aside label:first-child {
                  margin: 0;
              }

              section.card + .card-footer {
                  margin-top: 10px
              }

              section.card > footer {
                  text-align: right;
              }
          </style>
          <script>
            var $ = jQuery.noConflict();
            var i = $("section.card").length;
            $('.add-card').on('click', function () {
              var html = '';
              html += `
				<section class="card">
        <?php
              foreach ( $args['fields'] as $field ) {
                  echo '<li>';
                  echo '<label>' . $field['label'] . '</label>';
                  echo '<aside>';
                  $field['prefix']  = $args['prefix'];
                  $field['section'] = sprintf( '%s[%s][%s]', $args['section'], $args['name'], '`+ i +`' ) ?? '';
                  $field            = $this->parse_field_array( $field );
                  call_user_func( array( $this, 'callback_' . $field['type'] ), $field );
                  echo '</aside>';
                  echo '</li>';
              }
              ?>
                  <footer>
                    <a class="remove-card" href="javascript:">删除</a>
                  </footer>
                </section>
				`;
              $('.card-footer').before(html);
              i++;
              bindListener();
            });

            function bindListener() {
              $(".remove-card").on("click", function () {
                $(this).parent().parent().remove();
              })
            }
            bindListener();
          </script>
            <?php
        }

    }

}
