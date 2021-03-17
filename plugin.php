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

use Wenpai\Framework\Setting;
use Wenpai\Framework\Widget;

require_once 'framework/class-setting.php';
require_once 'framework/class-widget.php';

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
                'id'          => 'text',
                'type'        => 'text',
                'name'        => '文本框',
                'placeholder' => '我是文本框',
            ),
        ),
    ), array(
        'id'     => 'two',
        'title'  => '选项卡二',
        'fields' => array(
            array(
                'id'          => 'text',
                'type'        => 'text',
                'name'        => '文本框',
                'placeholder' => '我是文本框',
            ),
        ),
    ),
) );

/**
 * 创建小工具
 */
Widget::create( EXAMPLE_PREFIX, array(
    'id'          => 'one_width',
    'title'       => '小工具一',
    'classname'   => 'widget-one',
    'description' => '这是一个小工具',
    'fields'      => array(
        array(
            'id'          => 'text',
            'type'        => 'text',
            'name'        => '文本框',
            'placeholder' => '我是文本框',
        ),
    ),
) );
