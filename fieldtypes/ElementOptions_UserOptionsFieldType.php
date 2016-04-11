<?php
/**
 * @author    Tim Kelty
 * @copyright Copyright (c) 2016 Tim Kelty
 * @link      http://fusionary.com/
 * @package   ElementOptions
 * @since     1.0.0
 */

namespace Craft;

class ElementOptions_UserOptionsFieldType extends ElementOptions_BaseOptionsFieldType
{
	// Properties
	// =========================================================================
	protected $allowLargeThumbsView = true;
	protected $elementType = 'User';
	protected $elementMethod = 'users';
	protected $inputJsClass = 'Craft.BaseElementSelectInput';

	// Public Methods
	// =========================================================================

	/**
	 * @inheritDoc IComponentType::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('User Options');
	}
}
