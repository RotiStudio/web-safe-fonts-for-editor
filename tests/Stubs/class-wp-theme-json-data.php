<?php
/**
 * Minimal stand-in for WordPress's WP_Theme_JSON_Data.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

/**
 * Holds theme.json data and records what update_with() received.
 */
class WP_Theme_JSON_Data {

	/**
	 * Theme.json data.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * The argument of the last update_with() call, null when it was not called.
	 *
	 * @var array|null
	 */
	public $update = null;

	/**
	 * Constructor.
	 *
	 * @param array $data Theme.json data.
	 */
	public function __construct( array $data = array() ) {
		$this->data = $data;
	}

	/**
	 * Returns the theme.json data.
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Records the new data instead of merging it.
	 *
	 * @param array $new_data Data to merge.
	 * @return self
	 */
	public function update_with( $new_data ) {
		$this->update = $new_data;
		return $this;
	}
}
