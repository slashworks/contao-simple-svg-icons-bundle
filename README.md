# Contao 4 & 5 Simple SVG Icons Bundle


## About

With this extension you can easily add SVG icons from an icon-sprite file to your website via Contao Inserttags.  
It also allows to output inline SVG from an SVG file in the file system.


## Installation

Install with ```composer require slashworks/contao-simple-svg-icons```.  
After updating the database, select the SVG icon sprite file in the settings of your theme. Use the new field **icon files** to select the SVG icon sprite file. The icons you can use are taken from this file.  
For an easy start, download the [example-sprite.svg][example-sprite-file] and place it inside the files folder of your contao installation.  

An SVG icon sprite file is a collection of multiple SVG icons, defined within a ```<symbol>```.  
The menu icon for example looks like this:
```html
<symbol viewBox="0 0 24 24" id="ic_menu_24px" title="lucky cat">
    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
</symbol>
```
The important part for using this icon is the **id** of the symbol.
  
For further information about creating your own SVG icon sprite file you can check out [A Guide to Create and Use SVG Sprites](https://w3bits.com/svg-sprites/).


## Usage

### SVG Sprite
When you want to output a symbol from an SVG sprite, the base format is  
```{{svg::ICON_ID}}```.  
The rendered output:
```html
<svg class="svg-icon ICON_ID" viewBox="x y w h">
    <use xlink:href="…">…</use>
</svg>
```

### Inline SVG
You can also output an SVG file inline.  
Instead of the symbol's id you have to provide the UUID of the SVG file in the file system:  
```{{svg::a8824458-a08e-11e9-9d96-81cb79fa7a74}}```  
The rendered output:
```html
<svg class="svg-inline" viewBox="x y w h">
    <path></path>
    …
</svg>
``` 

---

## Parameters

You can pass additional parameters to the insert tag:

**class:** ```{{svg::ICON_ID|UUID?class=my-css-class}}```   
This adds the given string as CSS class to the svg element and will be rendered as:
```html
<svg class="svg-inline ICON_ID my-css-class">…</svg>
```

**id:** ```{{svg::ICON_ID|UUID?id=my-css-id}}```  
This adds the given string as CSS ID to the svg element and will be rendered as:  
```html
<svg class="svg-inline ICON_ID" id="my-css-id">…</svg>
```

**width**: ```{{svg::UUID?width=120}}```  
The width parameter only works with inline SVG.  
If the width property is set without a height property, the extension tries to get the aspect ratio from already existing width/height attributes or the viewBox attribute and sets the height accordingly. You can always overwrite this with CSS.

**height**: ```{{svg::UUID?height=80}}```  
The height parameter only works with inline SVG.  
If the height property is set without a width property, the extension tries to get the aspect ratio from already existing width/height attributes or the viewBox attribute and sets the width accordingly. You can always overwrite this with CSS.

You can use multiple parameters in any combination:  
```{{svg::UUID?width=120}}```  
```{{svg::UUID?height=80&class=custom-icon}}```  
```{{svg::ICON_ID|UUID?id=my-icon-id&class=custom-icon-class}}```


## Tips

The main purpose for svg icons is to use them in conjuction with text, e. g. menu burger, contact information, slider ui elements, …. As a starting point for the CSS styling of the svg icons you could use the following definitions:
```css
.svg-inline {
    fill: currentColor;
    height: auto;
    width: 1em;
}
```
This will scale the icon according to the font-size of the parent element.  
Additionally, the vertical positioning requires some further adjustments for fine tuning. You can try to use **vertical-align**, **relative** positioning with some **top** or **bottom** values, or make the parent a **flex**-container and use **align-items** to properly position the enclosed icon.


## Example: Burger Menu

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

button .svg-inline {
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

## Backend widget for icon selection

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


## Licensing

This contao module is licensed under the terms of the LGPLv3.


## Credits

The icons used in the example sprite have been taken from [Google Material Icons](https://material.io/tools/icons).

[burger-menu-step-1]: screenshots/step-1.png
[burger-menu-step-2]: screenshots/step-2.png
[burger-menu-step-3]: screenshots/step-3.png
[burger-menu-hover]: screenshots/button-hover.gif
[burger-menu-hover-multi-color]: screenshots/button-hover-multi-color.gif
[example-sprite-file]: example-sprite.svg
