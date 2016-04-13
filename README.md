# ElementOptions plugin for Craft CMS

ElementOptions fieldtypes function similar to the native [Checkboxes](https://craftcms.com/docs/checkboxes-fields) fieldtypes, with the addition of a selected element to each option.
Each element type is supported with it's own fieldtype: Assets, Users, Entries, and Categories.

![Screenshot](resources/img/screenshots/field-input.png)

The most applicable element type is Assets, as it allows you to create a field where users can select from a pre-defined set of Assets.

## Installation
- Via Composer: `composer require timkelty/craft-elementoptions`
- Git: `git clone https://github.com/timkelty/craft-elementoptions.git craft/plugins/elementoptions`
- Download & unzip the into `craft/plugins/elementoptions` directory

ElementOptions works on Craft 2.4.x and Craft 2.5.x.

## Settings
![Screenshot](resources/img/screenshots/field-settings.png)

See [Craft Docs for Checkboxes Fields / Settings](https://craftcms.com/docs/checkboxes-fields#settings).

- Limit the number of selectable options
- Select an element for each option, and how that element will be displayed (thumbnails, label, checkbox)
- **Option Label** and **Value** may contain tags that reference the selected entry, such as `{title}` or `{slug}`. This can be useful if you want the label/value to change with element, and vice-versa.

### Templating
See [Craft Docs for Checkboxes Fields / Templating](https://craftcms.com/docs/checkboxes-fields#templating).

- `option.element` is available. It will return an `ElementCriteriaModel`.

```html
<ul>
    {% for option in entry.elementOptionsFieldHandle %}
        <li>{{ option.element.first().title }}</li>
    {% endfor %}
</ul>
```
