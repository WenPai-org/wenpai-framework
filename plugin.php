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

use Wenpai\Framework;

require_once 'class-framework.php';

/**
 * 创建设置页
 */
Framework::create_options( 'wenpai_framework_example', array(
    'menu_title' => '文派开发框架',
    'menu_slug' => 'wenpai_framework'
) );

/**
 * 创建设置组
 */
Framework::create_section( 'wenpai_framework_example', array(
    array(
        'title'  => '选项卡一',
        'fields' => array(
            array(
                'id'    => 'text',
                'type'  => 'text',
                'title' => '文本框',
            ),
        )
    )
) );