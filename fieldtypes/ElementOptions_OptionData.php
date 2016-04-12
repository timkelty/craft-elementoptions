<?php
/**
 * @author    Tim Kelty
 * @copyright Copyright (c) 2016 Tim Kelty
 * @link      http://fusionary.com/
 * @package   ElementOptions
 * @since     1.0.0
 */

namespace Craft;

class ElementOptions_OptionData extends OptionData
{
	// Properties
	// =========================================================================

	/**
	 * @var
	 */
	public $element;

	// Public Methods
	// =========================================================================

	/**
	 * Constructor
	 *
	 * @param string $label
	 * @param string $value
	 * @param        $selected
	 *
	 * @return OptionData
	 */
	public function __construct($label, $value, $selected, $elementCriteria)
	{
		$obj            = array('element' => $elementCriteria->first());
		$this->label    = craft()->templates->renderObjectTemplate($label, $obj);
		$this->value    = craft()->templates->renderObjectTemplate($value, $obj);
		$this->element  = $elementCriteria;
		$this->selected = $selected;
	}
}
