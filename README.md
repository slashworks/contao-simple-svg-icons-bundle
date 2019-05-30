Contao 4 Simple SVG Icons Bundle
================================


About
-----
With this extension you can easily add SVG icons from an icon-sprite file to your website via Contao Inserttags.


Installation
------------------

Install with ```composer require slashworks/contao-simple-svg-icons```.  
After updating the database, select the SVG icon sprite file in the settings of your theme. Use the new field **icon files** to select the SVG icon sprite file. The icons you can use are taken from this file.  
For an easy start, download the [example-sprite.svg][example-sprite-file] and place it inside the files folder of your contao installation.  

An SVG icon sprite file is a collection of multiple SVG icons, defined within a ```<symbol>```.  
The menu icon for example looks like this:
```html
<symbol viewBox="0 0 24 24" id="ic_menu_24px">
    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
</symbol>
```
The important part for using this icon is the **id** of the symbol.
  
For further information about creating your own SVG icon sprite file you can check out [A Guide to Create and Use SVG Sprites](https://w3bits.com/svg-sprites/).


Usage
-----

You can use an SVG icon with the Contao inserttags.  
The base format is ```{{svg::ICON_ID}}```.  
The output will be in the format:
```html
<svg class="svg-icon ICON_ID" viewBox="x y w h">
    <use xlink:href="…">…</use>
</svg>
```

You can use an optional third parameter in the inserttag to include a custom CSS class:
```{{svg::ICON_ID::CUSTOM_CLASS}}```  
This will be rendered as:
```html
<svg class="svg-icon ICON_ID CUSTOM_CLASS">…</svg>
```

---

An example would then be:
```
{{svg::icon_menu_24px::my-css-class}}
```
And the rendered output:
```html
<svg class="svg-icon icon_menu_24px my-css-class">…</svg>
```


Tips
-----

The main purpose for svg icons is to use them in conjuction with text, e. g. menu burger, contact information, slider ui elements, …. As a starting point for the CSS styling of the svg icons you could use the following definitions:
```css
.svg-icon {
    fill: currentColor;
    height: auto;
    width: 1em;
}
```
This will scale the icon according to the font-size of the parent element.  
Additionally, the vertical positioning requires some further adjustments for fine tuning. You can try to use **vertical-align**, **relative** positioning with some **top** or **bottom** values, or make the parent a **flex**-container and use **align-items** to properly position the enclosed icon.


Example: Burger Menu
--------------------

Lets walk through a burger menu example.  
We want to use the icon_menu_24px from the example sprite, so our inserttag looks like this:  
```
{{svg::icon_menu_24px}}
```

---

The HTML code with the inserttag:
```html
<button>
    Menu {{svg::icon_menu_24px}}
</button>
```
![Burger menu in its initial state][burger-menu-step-1]

---

After setting the font-size for the button and the width for the svg, it will look like this:
```css
button {
    font-size: 1em;
}

button .svg-icon {
    width: 1.5em;
}
```
![Burger menu with font-size and icon width][burger-menu-step-2]

---

Finally we adjust the vertical alignment of the icon:
```css
button {
    align-items: center;
    display: inline-flex;
}

button svg {
    margin-left: 5px;
}
```
![Burger menu with correct icon alignment][burger-menu-step-3]

---

When using the recommended CSS from the tips section, the SVG icon will inherit ```color``` from the button.
```css
button:hover {
    color: red;
}
```
![Burger menu hover][burger-menu-hover]

---

Of course you can define a different color for the SVG icon itself:
```css
button:hover svg {
    color: green;
}
```
![Burger menu hover with separate icon color][burger-menu-hover-multi-color]

---

Backend widget for icon selection
---------------------------------

The extension contains a custom backend widget to make the icon selection easy and intuitive.  
To use the widget, use the following code as reference.  

Example dca file, e. g. tl_content 
```php
<?php

use \Slashworks\ContaoSimpleSvgIconsBundle\DataContainer\General;

$GLOBALS['TL_DCA']['tl_content']['fields']['myIcon'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_content']['icon'],
    'inputType'        => 'svgiconselect', // This field uses the custom backend widget
    'options_callback' => array(General::class, 'getIcons'), // Get all svg icons selected in the themes.
    'reference'        => &$GLOBALS['TL_LANG']['MSC']['icons'], // Use the symbols ID as key for a translation.
    'eval'             => array('includeBlankOption' => true),
    'sql'              => "varchar(64) NOT NULL default ''",
);
```

Example language file
```php
<?php

$GLOBALS['TL_LANG']['MSC']['icons']['icon-arrow-right'] = 'Pfeil nach rechts';
$GLOBALS['TL_LANG']['MSC']['icons']['icon-arrow-left'] = 'Pfeil nach links';
```


Licensing
---------

This contao module is licensed under the terms of the LGPLv3.


Credits
-------

The icons used in the example sprite have been taken from [Google Material Icons](https://material.io/tools/icons).

[burger-menu-step-1]: screenshots/step-1.png
[burger-menu-step-2]: screenshots/step-2.png
[burger-menu-step-3]: screenshots/step-3.png
[burger-menu-hover]: screenshots/button-hover.gif
[burger-menu-hover-multi-color]: screenshots/button-hover-multi-color.gif
[example-sprite-file]: example-sprite.svg
