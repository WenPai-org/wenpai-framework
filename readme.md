# 文派开发框架

## 设计目标

期待交付一个轻量、可自由扩展、高性能、低耦合的WordPress开发框架，第一阶段计划包含设置和路由模块。

## 见识一下？

### 注册一个设置页

```php 
Setting::create_options( 'wenpai_framework_example', array(
    'menu_title' => '文派开发框架',
    'menu_slug' => 'wenpai_framework'
) );

Setting::create_section( 'wenpai_framework_example', array(
    array(
        'id'     => 'one',
        'title'  => '选项卡一',
        'fields' => array(
            array(
                'id'    => 'text',
                'type'  => 'text',
                'title' => '文本框',
            ),
        )
    ), array(
        'id'     => 'two',
        'title'  => '选项卡二',
        'fields' => array(
            array(
                'id'    => 'text',
                'type'  => 'text',
                'title' => '文本框',
            ),
        )
    )
) );
```

### 注册一个路由

```php 
Route::permission([Middleware::class, 'restApiPermission'])->type(RouteType::API)->get([
    'wcy/v1' => [
        'plugins' => [new Plugins(), 'get_items'],
        'themes' => [new Themes(), 'get_items']
    ]
]);
```

## 设计灵感

此框架设计过程中参考了[Laravel](https://github.com/laravel/laravel)、[WordPress Settings Api Class](https://github.com/tareq1988/wordpress-settings-api-class)、[codestarframework](https://codestarframework.com/)
