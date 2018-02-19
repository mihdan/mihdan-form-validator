<?php
/**
 * Plugin Name: Mihdan: Form Validate
 * Plugin URI: https://www.kobzarev.com/
 * Description: With this feature rich jQuery plugin it becomes easy to validate user input while keeping your HTML markup clean from javascript code. Even though this plugin has a wide range of validation functions it's designed to require as little bandwidth as possible. This is achieved by grouping together validation functions in "modules", making it possible for the programmer to load only those functions that's needed to validate a particular form.
 * Version: 1.0.0
 * Author: Mikhail Kobzarev
 * Author URI: https://www.kobzarev.com/
 *
 * GitHub Plugin URI: https://github.com/mihdan/mihdan-form-validator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mihdan_Form_Validator' ) ) {

	/**
	 * Class Mihdan_Form_Validator
	 */
	final class Mihdan_Form_Validator {

		const SLUG = 'mihdan_form_validator';

		/**
		 * Путь к плагину
		 *
		 * @var string
		 */
		public static $dir_path;

		/**
		 * URL до плагина
		 *
		 * @var string
		 */
		public static $dir_uri;

		/**
		 * Хранит экземпляр класса
		 *
		 * @var $instance
		 */
		private static $instance;

		/**
		 * Вернуть единственный экземпляр класса
		 *
		 * @return Mihdan_Form_Validator
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Инициализируем нужные методы
		 *
		 * Mihdan_Form_Validator constructor.
		 */
		private function __construct() {
			$this->setup();
			$this->includes();
			$this->hooks();
		}

		/**
		 * Установка основных переменных плагина
		 */
		private function setup() {
			self::$dir_path = apply_filters( 'mihdan_form_validator_dir_path', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			self::$dir_uri   = apply_filters( 'mihdan_form_validator_dir_uri', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}

		/**
		 * Подключаем зависимости
		 */
		private function includes() {}

		/**
		 * Хукаем.
		 */
		private function hooks() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Регистрируем скрипты
		 */
		public function enqueue_scripts() {
			wp_register_script( self::SLUG, self::$dir_uri . 'vendor/form-validator/form-validator/jquery.form-validator.js', array( 'jquery' ), null, true );
			$config = apply_filters( self::SLUG . '_config', array(
				'modules' => 'toggleDisabled,security',
				'lang' => 'ru',
				'validateOnBlur' => 'true',
				'validateHiddenInputs' => 'true',
				'errorMessagePosition' => 'inline',
				'disabledFormFilter' => 'form:not([novalidate])',
				'addValidClassOnAll' => 'true',
			) );
			wp_enqueue_script( self::SLUG );
			wp_localize_script( self::SLUG, self::SLUG . '_config', $config );
			wp_add_inline_script( self::SLUG, 'jQuery(function($){ jQuery.validate( ' . self::SLUG . '_config ); });' );
		}
	}

	function Mihdan_Form_Validator() {
		return Mihdan_Form_Validator::get_instance();
	}

	$GLOBALS['mihdan_form_validator'] = Mihdan_Form_Validator();
}
// eof