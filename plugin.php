<?php
/**
 * Plugin Name: 文派开发框架功能演示插件
 * Description: 文派开发框架，轻量优雅的WordPress开发框架
 * Author: 文派
 * Author URI:https://wenpai.org
 * Version: 1.0.0
 * Network: True
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use Wenpai\Framework\{ Setting, Widget, Meta_Box };

require_once 'framework/class-setting.php';
require_once 'framework/class-widget.php';
require_once 'framework/class-meta-box.php';

/**
 * 声明设置项前缀标识
 */
define('EXAMPLE_PREFIX', 'wenpai_framework_example');

/**
 * 创建设置页
 */
Setting::create_options( EXAMPLE_PREFIX, array(
    'menu_title' => '文派开发框架',
    'menu_slug' => 'wenpai_framework'
) );

/**
 * 创建设置组
 */
Setting::create_section( EXAMPLE_PREFIX, array(
    array(
        'id'     => 'one',
        'title'  => '选项卡一',
        'fields' => array(
            array(
                'name'        => 'text_one',
                'type'        => 'text',
                'label'       => '文本框',
                'placeholder' => '请输入文本',
                'desc'        => '这是一个文本框',
            ),
            array(
                'name'    => 'select',
                'label'   => '下拉框',
                'desc'    => '这是一个下拉框',
                'type'    => 'select',
                'default' => 'no',
                'options' => array(
                    'yes' => 'Yes',
                    'no'  => 'No'
                )
            ),
            array(
                'name'  => 'switcher_one',
                'label' => '开关',
                'desc'  => '这是一个开关',
                'type'  => 'switcher'
            ),
            array(
                'name'  => 'checkbox',
                'label' => '多选框',
                'desc'  => '这是一个多选框',
                'type'  => 'checkbox',
                'default' => array(
                    'one' => 'one',
                    'four' => 'four'
                ),
                'options' => array(
                    'one'   => 'One',
                    'two'   => 'Two',
                    'three' => 'Three',
                    'four'  => 'Four'
                ),
            ),
        ),
    ), array(
        'id'     => 'two',
        'title'  => '选项卡二',
        'fields' => array(
            array(
                'name'        => 'text_one',
                'type'        => 'text',
                'label'       => '文本框',
                'placeholder' => '请输入文本',
                'desc'        => '这是一个文本框',
            ),
        ),
    ),
) );

/**
 * 创建小工具
 */
Widget::create( EXAMPLE_PREFIX, array(
    'id'          => 'one_widget',
    'title'       => '小工具一',
    'classname'   => 'widget-one',
    'description' => '这是一个小工具',
    'fields'      => array(
        array(
            'name'        => 'title',
            'type'        => 'text',
            'label'       => '标题',
            'placeholder' => '请输入标题',
            'desc'        => '标题是name为title的字段，前端输出时会自动输出在小工具的标题栏',
        ),
        array(
            'name'        => 'text_one',
            'type'        => 'text',
            'label'       => '文本框',
            'placeholder' => '请输入文本',
            'desc'        => '这是一个文本框',
        ),
    ),
) );

Meta_Box::create( EXAMPLE_PREFIX, array(
    'id'          => 'one_meta_box',
    'title'       => '元框一',
    'context'     => 'side',
    'screens'     => array( 'post', 'page' ),
    'fields'      => array(
        array(
            'name'        => 'text_one',
            'type'        => 'text',
            'label'       => '文本框',
            'placeholder' => '请输入文本',
            'desc'        => '这是一个文本框',
        ),
    ),
) );

/**
 * 小工具生成器会执行一个名称格式为[应用前缀+Widget ID]的函数回调
 */
if ( ! function_exists( 'wenpai_framework_example_one_widget' ) ) {
    /**
     * one_widget小工具的函数回调
     *
     * @param array $instance 小工具中保存的表单数据
     */
    function wenpai_framework_example_one_widget( array $instance ) {
        echo $instance['text_one'] ?? '';
    }
}
