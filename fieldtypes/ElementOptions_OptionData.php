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
	public $assets;

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
	public function __construct($label, $value, $assets, $selected)
	{
		$this->label    = $label;
		$this->value    = $value;
		$this->assets   = $this->getAssetsCriteria($assets);
		$this->selected = $selected;
	}

	/**
	 * Returns the options.
	 *
	 * @return array|null
	 */
	public function getAssetsCriteria($ids)
	{
		$criteria = craft()->elements->getCriteria(ElementType::Asset);
		$criteria->id = $ids;
		return $criteria;
	}

}
