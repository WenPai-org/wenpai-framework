<?php
/**
 * 公共方法类
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace WenPai\Framework;

if ( ! class_exists( Common::class ) ) {

    /**
     * 公共方法类
     *
     * 该类提供一些可能有用的方法封装
     *
     * @since 1.0.0
     */
    class Common {

        /**
         * 插件激活时执行回调
         *
         * @param string $file 插件的主文件绝对路径，“主文件”指编写插件Meta信息的文件
         * @param mixed $function 回调函数
         */
        public static function active( string $file, $function ) {
            register_activation_hook( $file, $function );
        }

        /**
         * 插件停用时执行回调
         *
         * @param string $file 插件的主文件绝对路径，“主文件”指编写插件Meta信息的文件
         * @param mixed $function 回调函数
         */
        public static function deactivate( string $file, $function ) {
            register_deactivation_hook( $file, $function );
        }

        /**
         * 插件被从站点中删除时执行回调
         *
         * @param string $file 插件的主文件绝对路径，“主文件”指编写插件Meta信息的文件
         * @param mixed $function 回调函数
         */
        public static function uninstall( string $file, $function ) {
            register_uninstall_hook( $file, $function );
        }

    }

}
