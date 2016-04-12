<?php
/**
 * @author    Tim Kelty
 * @copyright Copyright (c) 2016 Tim Kelty
 * @link      http://fusionary.com/
 * @package   ElementOptions
 * @since     1.0.0
 */

namespace Craft;

abstract class ElementOptions_BaseOptionsFieldType extends BaseOptionsFieldType
{
	// Properties
	// =========================================================================
	protected $multi = true;
	protected $allowLargeThumbsView = false;
	protected $inputJsClass = 'Craft.BaseElementSelectInput';

	// Public Methods
	// =========================================================================

	/**
	 * @inheritDoc IFieldType::getInputHtml()
	 *
	 * @param string $name
	 * @param mixed  $values
	 *
	 * @return string
	 */
	public function getInputHtml($name, $values)
	{
		$options = $this->getTranslatedOptions();

		// If this is a new entry, look for any default options
		if ($this->isFresh())
		{
			$values = $this->getDefaultValue();
		}

		$id = craft()->templates->formatInputId($name);
		$namespacedId = craft()->templates->namespaceInputId($id);

		craft()->templates->includeCssResource('elementoptions/css/element-select.css');
		craft()->templates->includeJsResource('elementoptions/js/element-select.js');
		craft()->templates->includeJs("$('#{$namespacedId}-options').elementSelect();");

		foreach ($options as &$option) {
			$option = new ElementOptions_OptionData($option['label'], $option['value'], false, $option['element']);
		}
		return craft()->templates->render('elementoptions/_components/fieldtypes/BaseElementOptions/input', array(
			'id'	       => $id,
			'name'         => $name,
			'options'      => $options,
			'values'       => $values,
			'settings'     => $this->getSettings(),
		));
	}

	// Protected Methods
	// =========================================================================

	/**
	 * @inheritDoc BaseOptionsFieldType::getOptionsSettingsLabel()
	 *
	 * @return string
	 */
	protected function getOptionsSettingsLabel()
	{
		return Craft::t('Options');
	}

	public function getSettingsHtml()
	{
		$options = $this->getOptions();

		if (!$options)
		{
			$options = array(array('label' => '', 'value' => ''));
		}

		$templateVars = array(
			'allowLimit'        => true,
			'viewModeFieldHtml' => $this->getViewModeFieldHtml(),
			'type'              => $this->elementType,
			'settings'          => $this->getSettings(),
			'elementSelect'     => $this->getElementSelectTemplateVariables(),
			'label'             => $this->getOptionsSettingsLabel(),
			'instructions'      => Craft::t('Define the available options.'),
			'id'                => 'options',
			'name'              => 'options',
			'addRowLabel'       => Craft::t('Add an option'),
			'cols'              => array(
				'label' => array(
					'heading'      => Craft::t('Option Label'),
					'type'         => 'singleline',
					'autopopulate' => 'value'
				),
				'value' => array(
					'heading'      => Craft::t('Value'),
					'type'         => 'singleline',
					'class'        => 'code'
				),
				'element' => array(
					'heading'      => $this->elementType,
					'type'         => 'elementSelect',
					'class'		   => 'has-elementselect'
				),
				'default' => array(
					'heading'      => Craft::t('Default?'),
					'type'         => 'checkbox',
					'class'        => 'thin'
				),
			),
			'rows' => $options,
		);

		return craft()->templates->render('elementoptions/_components/fieldtypes/BaseElementOptions/settings', $templateVars);
	}

	protected function getOptions()
	{
		$options = array();
		foreach (parent::getOptions() as $option) {
			$elementCriteria = $this->getElementCriteria($option['element']);
			$option['element'] = $elementCriteria;
			$options[] = $option;
		}

		return $options;
	}

	protected function getElementSelectTemplateVariables($criteria = false)
	{
		if (!($criteria instanceof ElementCriteriaModel))
		{
			$criteria = craft()->elements->getCriteria($this->elementType);
			$criteria->id = false;
		}

		$criteria->status = null;
		$criteria->localeEnabled = null;

		return array(
			'jsClass'            => $this->inputJsClass,
			'elementType'        => craft()->elements->getElementType($this->elementType),
			'id'                 => craft()->templates->namespaceInputId('__ROW__-elementSelect'),
			'name' 				 => '',
			'criteria'           => $criteria,
			'limit'              => 1,
		);
	}

	/**
	 * @inheritDoc IFieldType::prepValue()
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function prepValue($value)
	{
		$selectedValues = ArrayHelper::stringToArray($value);

		if ($this->multi)
		{
			if (is_array($value))
			{
				// Convert all the values to ElementOptions_OptionData objects
				foreach ($value as &$val)
				{
					$data = $this->getOptionData($val);
					$val = new ElementOptions_OptionData($data['label'], $val, true, $data['element']);
				}
			}
			else
			{
				$value = array();
			}

			$value = new MultiOptionsFieldData($value);
		}
		else
		{
			// Convert the value to a ElementOptions_SingleOptionFieldData object
			$data = $this->getOptionData($value);
			$value = new ElementOptions_SingleOptionFieldData($data['label'], $value, true, $data['element']);
		}

		$options = array();

		foreach ($this->getOptions() as $option)
		{
			$selected = in_array($option['value'], $selectedValues);
			$options[] = new ElementOptions_OptionData($option['label'], $option['value'], $selected, $option['element']);
		}

		$value->setOptions($options);

		return $value;
	}

	public function getElementCriteria($elementIds)
	{
		$criteria = craft()->elements->getCriteria($this->elementType);
		$criteria->id = $elementIds;
		return $criteria;
	}

	/**
	 * @inheritDoc IFieldType::validate()
	 *
	 * @param array $value
	 *
	 * @return true|string|array
	 */
	public function validate($value)
	{
		$errors = array();
		$limit = $this->getSettings()->limit;
		if (($limit = $this->getSettings()->limit) && is_array($value) && count($value) > $limit)
		{
			if ($limit == 1)
			{
				$errors[] = Craft::t('There can’t be more than one selection.');
			}
			else
			{
				$errors[] = Craft::t('There can’t be more than {limit} selections.', array('limit' => $limit));
			}
		}

		if ($errors)
		{
			return $errors;
		}
		else
		{
			return true;
		}
	}

	protected function getOptionData($value)
	{
		foreach ($this->getOptions() as $option)
		{
			if ($option['value'] == $value)
			{
				return array(
					'label' => $option['label'],
					'element' => $option['element'],
				);
			}
		}
	}

	protected function defineSettings()
	{
		return array(
			'limit' => array(AttributeType::Number, 'min' => 0),
			'options' => array(AttributeType::Mixed, 'default' => array()),
			'selectionLabel' => AttributeType::String,
			'viewMode' => array(AttributeType::String),
			'showCheckbox' => array(AttributeType::Bool, 'default' => true),
			'showLabel' => array(AttributeType::Bool, 'default' => true),
		);
	}

	/**
	 * Returns the HTML for the View Mode setting.
	 *
	 * @return string|null
	 */
	protected function getViewModeFieldHtml()
	{
		$supportedViewModes = $this->getSupportedViewModes();

		if (!$supportedViewModes || count($supportedViewModes) == 1)
		{
			return null;
		}

		$viewModeOptions = array();

		foreach ($supportedViewModes as $key => $label)
		{
			$viewModeOptions[] = array('label' => $label, 'value' => $key);
		}

		return craft()->templates->renderMacro('_includes/forms', 'selectField', array(
			array(
				'label' => Craft::t('View Mode'),
				'instructions' => Craft::t('Choose how the field should look for authors.'),
				'id' => 'viewMode',
				'name' => 'viewMode',
				'options' => $viewModeOptions,
				'value' => $this->getSettings()->viewMode
			)
		));
	}

	/**
	 * Returns the field’s supported view modes.
	 *
	 * @return array|null
	 */
	protected function getSupportedViewModes()
	{
		$viewModes = array(
			'list' => Craft::t('List'),
		);

		if ($this->allowLargeThumbsView)
		{
			$viewModes['large'] = Craft::t('Large Thumbnails');
		}

		return $viewModes;
	}
}
