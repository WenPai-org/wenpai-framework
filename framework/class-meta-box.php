<?php
/**
 * 元框生成器
 *
 * @version 1.0.0
 *
 * @package Wenpai\Framework
 */

namespace WenPai\Framework;

use WP_Post;

require_once 'class-fields.php';

if ( ! class_exists( Meta_Box::class ) ) {

    /**
     * 元框生成器
     *
     * @since 1.0.0
     */
    class Meta_Box {

        /**
         * 创建元框的元信息
         *
         * 数据结构参考构造函数的注释
         *
         * @since 1.0.0
         * @var array
         */
        private $args;

        /**
         * 应用前缀
         *
         * @since 1.0.0
         * @var string
         */
        private $prefix;

        /**
         * 元框ID
         *
         * @since 1.0.0
         * @var string
         */
        private $meta_box_id;

        /**
         * 构造函数
         *
         * @since 1.0.0
         * @param string $prefix     应用前缀
         * @param array $args {
         *     @type string $id      元框ID
         *     @type string $title   元框标题
         *     @type string $context 元框的位置-常规：normal,左侧：side,高级：'advanced'
         *     @type array  $screens 元框生效的文章类型
         *     @type array  $fields  元框中包含的字段
         * }
         */
        public function __construct( string $prefix, array $args ) {
            $this->args = wp_parse_args( $args, array(
                'id'          => '',
                'title'       => '',
                'context'     => 'advanced',
                'screens'     => array(),
                'fields'      => array(),
            ) );

            $this->prefix    = $prefix;
            $this->meta_box_id = $prefix . '_' . $this->args['id'];
        }

        /**
         * 注册钩子
         *
         * @since 1.0.0
         */
        public function register_hook() {
            add_action( 'add_meta_boxes', array( $this, 'add' ) );
            add_action( 'save_post', array( $this, 'save' ) );
        }

        /**
         * 根据用户传入数据实例化Meta_Box类
         *
         * @since 1.0.0
         * @param string $prefix     应用前缀
         * @param array $args {
         *     @type string $id      元框ID
         *     @type string $title   元框标题
         *     @type string $context 元框的位置-常规：normal,左侧：side,高级：'advanced'
         *     @type array  $screens 元框生效的文章类型
         *     @type array  $fields  元框中包含的字段
         * }
         */
        public static function create( string $prefix, array $args ) {
            $meta_box = new self( $prefix, $args );
            $meta_box->register_hook();
        }

        /**
         * 添加一个元框
         *
         * @since 1.0.0
         */
        public function add() {
            foreach ( (array)$this->args['screens'] as $screen ) {
                add_meta_box(
                    $this->meta_box_id,
                    $this->args['title'],
                    array( $this, 'html' ),
                    $screen,
                    $this->args['context']
                );
            }
        }

        /**
         * 在文章发布 or 保存时更新元框数据
         *
         * @since 1.0.0
         * @param int $post_id 文章ID
         */
        public function save( int $post_id ) {
            /** TODO:这里是否需要验证nonce */
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
            }

            foreach ( $this->args['fields'] as $field ) {
                if ( array_key_exists( $field['name'], $_POST ) ) {
                  /** TODO:POST数据需要消毒 */
                    update_post_meta(
                        $post_id,
                        $field['name'],
                        $_POST[$field['name']]
                    );
                }
            }
        }

        /**
         * 输出元框表单
         *
         * @since 1.0.0
         * @param WP_Post $post Post对象
         */
        public function html( WP_Post $post ) {
            $fields_obj = new Fields( Fields::Meta_Box );

            foreach ( $this->args['fields'] as $field ) {
                /** field数组的value和id字段的赋值顺序不可更改，具体原因稍微读下这两行代码就晓得了 */
                $field['value'] = get_post_meta( $post->ID, $field['name'], true );
                $field = $fields_obj->parse_field_array( $field );

                echo '<p>';
                if ( ! empty( $field['label'] ) ) {
                    echo "<label for='{$field['name']}'>{$field['label']}:</label>";
                }
                call_user_func( array( $fields_obj, 'callback_' . $field['type'] ), $field );
                echo '</p>';
            }
        }

    }

}
