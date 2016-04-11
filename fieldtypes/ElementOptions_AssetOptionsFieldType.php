<?php
/**
 * @author    Tim Kelty
 * @copyright Copyright (c) 2016 Tim Kelty
 * @link      http://fusionary.com/
 * @package   ElementOptions
 * @since     1.0.0
 */

namespace Craft;

class ElementOptions_AssetOptionsFieldType extends BaseOptionsFieldType
{
	// Properties
	// =========================================================================
	protected $multi = true;
	protected $allowLargeThumbsView = true;

	// Public Methods
	// =========================================================================

	/**
	 * @inheritDoc IComponentType::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Asset Options');
	}

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

		craft()->templates->includeCssResource('elementoptions/css/fieldtypes/BaseElementOptions/input.css');
		craft()->templates->includeJsResource('elementoptions/js/fieldtypes/BaseElementOptions/input.js');
		craft()->templates->includeJs("$('#{$namespacedId}-options').elementSelect();");

		return craft()->templates->render('elementoptions/_components/fieldtypes/BaseElementOptions/input', array(
			'id'	       => $id,
			'name'         => $name,
			'options'      => $values->getOptions(),
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
			'type'              => 'Assets',
			'settings'          => $this->getSettings(),
			'optionsSettings' => array(
				'label'        => $this->getOptionsSettingsLabel(),
				'instructions' => Craft::t('Define the available options.'),
				'id'           => 'options',
				'name'         => 'options',
				'addRowLabel'  => Craft::t('Add an option'),
				'cols'         => array(
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
					'assets' => array(
						'heading'      => Craft::t('Assets'),
						'type'         => 'assets',
						'class'		   => 'has-elementselect'
					),
					'default' => array(
						'heading'      => Craft::t('Default?'),
						'type'         => 'checkbox',
						'class'        => 'thin'
					),
				),
				'rows' => $options,
			),
		);
		craft()->templates->includeJsResource('elementoptions/js/fieldtypes/BaseElementOptions/settings.js');

		return craft()->templates->render('elementoptions/_components/fieldtypes/BaseElementOptions/settings', $templateVars);
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
				// Convert all the values to AssetsSelect_OptionData objects
				foreach ($value as &$val)
				{
					$data = $this->getOptionData($val);
					$val = new ElementOptions_OptionData($data['label'], $val, $data['assets'], true);
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
			// Convert the value to a AssetsSelect_SingleOptionFieldData object
			$data = $this->getOptionData($value);
			$value = new ElementOptions_SingleOptionFieldData($data['label'], $value, $data['assets'], true);
		}

		$options = array();

		foreach ($this->getOptions() as $option)
		{
			$selected = in_array($option['value'], $selectedValues);
			$options[] = new ElementOptions_OptionData($option['label'], $option['value'], $option['assets'], $selected);
		}

		$value->setOptions($options);

		return $value;
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
					'assets' => $option['assets'],
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
			'viewMode' => array(AttributeType::String, 'default' => 'large'),
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
