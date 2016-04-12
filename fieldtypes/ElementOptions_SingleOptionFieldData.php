<?php
/**
 * @author    Tim Kelty
 * @copyright Copyright (c) 2016 Tim Kelty
 * @link      http://fusionary.com/
 * @package   ElementOptions
 * @since     1.0.0
 */

namespace Craft;

class ElementOptions_SingleOptionFieldData extends ElementOptions_OptionData
{
	// Properties
	// =========================================================================

	/**
	 * @var
	 */
	private $_options;

	// Public Methods
	// =========================================================================

	/**
	 * Returns the options.
	 *
	 * @return array|null
	 */
	public function getOptions()
	{
		return $this->_options;
	}

	/**
	 * Sets the options.
	 *
	 * @param array $options
	 *
	 * @return null
	 */
	public function setOptions($options)
	{
		$this->_options = $options;
	}
}
