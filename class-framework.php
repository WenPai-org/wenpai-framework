<?php
/**
 * 文派开发框架
 *
 * 轻量优雅的WordPress开发框架
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace Wenpai;

if ( !class_exists( 'Framework' ) ) {

    /**
     * 文派开发框架主类
     */
    class Framework {

        /**
         * 存储全局的设置项数据
         *
         * @since 1.0.0
         *
         * @var array
         */
        private static $args = array(
            'admin_options' => array(),
            'sections' => array(),
        );

        /**
         * 框架初始化
         *
         * @since 1.0.0
         */
        public static function init() {
            add_action( 'admin_init', array( __CLASS__, '_settings_init' ) );
            add_action( 'admin_menu', array( __CLASS__, '_create_admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( __CLASS__, '_admin_enqueue_scripts' ) );
        }

        /**
         * 创建后台管理员菜单
         *
         * @since 1.0.0
         */
        public static function _create_admin_menu() {
            foreach ( self::$args['admin_options'] as $prefix => $item ) {
                add_submenu_page(
                    'options-general.php',
                    $item['menu_title'],
                    $item['menu_title'],
                    'manage_options',
                    $item['menu_slug'],
                    function () use ($prefix) {
                        echo '<div class="wrap">';

                        self::_show_navigation( $prefix );
                        self::_show_forms( $prefix );

                        echo '</div>';
                    }
                );
            }
        }

        /**
         * 向WordPress管理后台注入框架依赖的CSS与JS
         *
         * @since 1.0.0
         */
        public static function _admin_enqueue_scripts() {
            wp_enqueue_style( 'wp-color-picker' );

            wp_enqueue_media();
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'jquery' );

            add_action('admin_footer', [__CLASS__, '_script']);
        }

        /**
         * 向页面打印选项卡顶栏的HTML
         *
         * @since 1.0.0
         *
         * @param string $prefix 设置页的前缀
         */
        private static function _show_navigation( string $prefix ) {
            $html = '<h2 class="nav-tab-wrapper">';

            $count = count( self::$args['sections'][$prefix] );

            if ( 1 === $count ) {
                return;
            }

            foreach ( self::$args['sections'][$prefix] as $tab ) {
                $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
            }

            $html .= '</h2>';

            echo $html;
        }

        /**
         * 创建设置页
         *
         * @since 1.0.0
         *
         * @param string $prefix 设置页前缀
         * @param array $args {
         *     菜单信息
         *
         *     @type string $menu_title 菜单标题
         *     @type string $menu_slug  菜单Slug
         * }
         */
        public static function create_options( string $prefix, array $args ) {
            self::$args['admin_options'][$prefix] = $args;
        }

        /**
         * 创建设置项组
         *
         * @since 1.0.0
         *
         * @param string $prefix 设置项前缀
         * @param array $args {[
         *     选项卡信息
         *
         *     @type string $title 设置选项卡标题
         *     @type array $fields {[
         *         一个选项卡中可以包含多个设置项
         *
         *         @type string $id    设置项ID
         *         @type string $type  设置项类型
         *         @type string $title 设置项标题
         *     ]}
         * ]}
         */
        public static function create_section( string $prefix, array $args ) {
            self::$args['sections'][$prefix] = array_merge( self::$args['sections'][$prefix] ?? [], $args );
        }

        /**
         * 输出依赖的内联JS
         *
         * @since 1.0.0
         */
        public static function _script() {
            echo <<<EOT
              <script>
              jQuery(document).ready(function($) {
                $('.wp-color-picker-field').wpColorPicker();
                $('.group').hide();
                var activetab = '';
                if (typeof(localStorage) != 'undefined' ) {
                  activetab = localStorage.getItem("activetab");
                }

                if(window.location.hash){
                  activetab = window.location.hash;
                  if (typeof(localStorage) != 'undefined' ) {
                    localStorage.setItem("activetab", activetab);
                  }
                }

                if ('' !== activetab && $(activetab).length ) {
                  $(activetab).fadeIn();
                } else {
                  $('.group:first').fadeIn();
                }
                $('.group .collapsed').each(function(){
                  $(this).find('input:checked').parent().parent().parent().nextAll().each(
                      function(){
                        if ($(this).hasClass('last')) {
                          $(this).removeClass('hidden');
                          return false;
                        }
                        $(this).filter('.hidden').removeClass('hidden');
                      });
                });

                if ('' !== activetab && $(activetab + '-tab').length ) {
                  $(activetab + '-tab').addClass('nav-tab-active');
                }
                else {
                  $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                }
                $('.nav-tab-wrapper a').click(function(evt) {
                  $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                  $(this).addClass('nav-tab-active').blur();
                  var clicked_group = $(this).attr('href');
                  if (typeof(localStorage) != 'undefined' ) {
                    localStorage.setItem("activetab", $(this).attr('href'));
                  }
                  $('.group').hide();
                  $(clicked_group).fadeIn();
                  evt.preventDefault();
                });

                $('.wpsa-browse').on('click', function (event) {
                  event.preventDefault();

                  var self = $(this);

                  var file_frame = wp.media.frames.file_frame = wp.media({
                    title: self.data('uploader_title'),
                    button: {
                      text: self.data('uploader_button_text'),
                    },
                    multiple: false
                  });

                  file_frame.on('select', function () {
                    attachment = file_frame.state().get('selection').first().toJSON();
                    self.prev('.wpsa-url').val(attachment.url).change();
                  });

                  file_frame.open();
                });
              });
              </script>
EOT;
        }

        /**
         * 设置项初始化
         *
         * @since 1.0.0
         */
        public static function _settings_init() {
            foreach ( self::$args['sections'] as $item ) {
                foreach ( $item as $section ) {
                    register_setting( $section['id'], $section['id'] );
                    add_settings_section( $section['id'], $section['title'], null, $section['id'] );

                    foreach ($section['fields'] as $field) {
                        $args = array(
                            'name'        => $field['id'],
                            'section'     => $section['id'],
                            'size'        => $field['size'],
                            'placeholder' => $field['placeholder'],
                        );

                        add_settings_field( "{$section['id']}[{$field['id']}]", $field['name'], [__CLASS__, 'callback_' . $field['type']], $section['id'], $section['id'], $args );
                    }
                }
            }
        }

        /**
         * 输出HTML表单
         *
         * @since 1.0.0
         *
         * @param string $prefix 区块前缀
         */
        private static function _show_forms( string $prefix ) {
            echo '<div class="metabox-holder">';
            foreach ( self::$args['sections'][$prefix] as $item ) {
                echo '<div id="' . $item['id'] . '" class="group" style="display: none;">';
                echo '<form method="post" action="options.php">';
                do_action( "{$prefix}_form_top_{$item['id']}", $item );
                settings_fields( $item['id'] );
                do_settings_sections( $item['id'] );
                do_action( "{$prefix}_form_bottom_{$item['id']}", $item );
                echo '<div style="padding-left: 10px">';
                submit_button();
                echo '</div>';
                echo '</form>';
                echo '</div>';
            }
            echo '</div>';
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
        private static function _get_field_description( array $args ): string {
            if ( ! empty( $args['desc'] ) ) {
                $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
            } else {
                $desc = '';
            }

            return $desc;
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
        public static function callback_text( array $args ) {
            $value       = get_option($args['section'])[$args['name']];
            $size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
            $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

            $html        = sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"%5$s/>', $size, $args['section'], $args['name'], $value, $placeholder );
            $html       .= self::_get_field_description( $args );

            echo $html;
        }

    }

    Framework::init();

}
