<?php
/**
 * Plugin Name:       Gutenberg Product Additional Information
 * Description:       Caractéristiques techniques du produit
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gutenberg-product-additional-information
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */


class GutenbergProductAdditionalInformation {

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		register_block_type_from_metadata(__DIR__,['render_callback' => [__CLASS__,'block_dynamic_render']]);
		add_filter('block_categories_all', [__CLASS__, 'gutenberg_category'], 10, 2);
	}

	/**
	 * init
	 *
	 * @return void
	 */
	public static function init() {
		new self;
	}

	/**
	 * Add category gutenberg if not exist
	 * @param $categories
	 * @return array
	 */
	public static function gutenberg_category($categories){
		if(!in_array(['slug' => 'gutenberg', 'title' => __('gutenberg Blocks', 'mario-blocks')],$categories)) {
			$news = array(
				'slug' => 'gutenberg',
				'title' => __('gutenberg Blocks', 'mario-blocks'),
			);
			if (!in_array($news, $categories))
				return array_merge(
					array(
						$news,
					),
					$categories
				);
		}
		return $categories;
	}

	/**
	 * CALLBACK
	 *
	 * Render callback for the dynamic block.
	 *
	 * Instead of rendering from the block's save(), this callback will render the front-end
	 *
	 * @since    1.0.0
	 * @param $att Attributes from the JS block
	 * @return string Rendered HTML
	 */
	public static function block_dynamic_render() {
		global $product;
		
		$html = "";

		ob_start();
		 	do_action( 'woocommerce_product_additional_information', $product );
			$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}
}

add_action( 'init', array ( 'GutenbergProductAdditionalInformation', 'init' ) );
