Slideshow module using jquery.cycle plugin.

Usage:
In mysite/_config.php, place ff. code:
<code>Object::add_extension('Page', 'SlideshowPageExtension');</code>

Add in the template:
<code>$SlideshowWidget</code>

Run dev/build?flush=1

To eradicate the title header of the widget, place sliderstripe/templates/WidgetHolder.ss to themes/currentTheme/ directory
