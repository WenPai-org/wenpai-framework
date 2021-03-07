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
        );

        /**
         * 框架初始化
         *
         * @since 1.0.0
         */
        public static function init() {
            add_action( 'admin_menu', array( __CLASS__, '_create_admin_menu' ) );
        }

        /**
         * 创建后台管理员菜单
         *
         * @since 1.0.0
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
         * @param string $prefix 设置页前缀
         * @param array $args {
         *     菜单信息
         *
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