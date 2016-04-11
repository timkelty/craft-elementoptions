<?php
namespace Craft;

/**
 * Class OptionData
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) 2014, Pixel & Tonic, Inc.
 * @license   http://craftcms.com/license Craft License Agreement
 * @see       http://craftcms.com
 * @package   craft.app.fieldtypes
 * @since     1.0
 */
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
	public function __construct($label, $value, $selected, $elementType, $elementIds)
	{
		$criteria = $this->getElementCriteria($elementType, $elementIds);
		$obj = array('element' => $criteria->first());
		$this->label    = craft()->templates->renderObjectTemplate($label, $obj);
		$this->value    = craft()->templates->renderObjectTemplate($value, $obj);
		$this->element  = $criteria;
		$this->selected = $selected;
	}

	/**
	 * Returns the options.
	 *
	 * @return array|null
	 */
	public function getElementCriteria($elementType, $elementIds)
	{
		$criteria = craft()->elements->getCriteria($elementType);
		$criteria->id = $elementIds;
		return $criteria;
	}

}
