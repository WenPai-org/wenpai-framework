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

/**
 * 文派开发框架主类
 */
if ( !class_exists( 'Framework' ) ) {

    class Framework {

        private static $args = array(
            'admin_options' => array(),
        );

        public static function init() {
            add_action( 'admin_menu', array( __CLASS__, '_create_admin_menu' ) );
        }

        /**
         * 创建后台管理员菜单
         */
        public static function _create_admin_menu() {
            foreach ( self::$args['admin_options'] as $item ) {
                add_submenu_page(
                    'options-general.php',
                    $item['menu_title'],
                    $item['menu_title'],
                    'manage_options',
                    $item['menu_slug'],
                    function () {
                        echo 'test';
                    }
                );
            }
        }

        /**
         * 创建设置页
         *
         * @since 1.0.0
         *
         * @param string $prefix
         * @param array $args {
         *     @type string $menu_title 菜单标题
         *     @type string $menu_slug  菜单Slug
         * }
         */
        public static function create_options( $prefix, $args ) {
            self::$args['admin_options'][$prefix] = $args;
        }

    }

    Framework::init();

}