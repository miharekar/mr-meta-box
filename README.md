mr meta box
=============
**mr meta box** is a simple class for using powerful WordPress meta boxes as easily as possible.

What are meta boxes?
-------
With meta boxes you can make WordPress as versatile as any other CMS. Maybe you just want to add some **user friendly** custom fields to post or page or maybe you are dealing with **custom post types** and want to make them 1000 times more powerful - meta boxes are an **awesome way** to do that. The problem is that it's fairly complicated to make them and it requires a lot of code. Things get pretty messy even when you just want to add one simple text field as shown in this [Smashing Magazine Tutorial](http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/).

Awesome, but surely someone already thought of that?
-------
There are some plugins for making meta boxes but when you are developing a plugin or a theme you probably don't want it to rely on some other plugin. There are also a few libraries but most of them are overcomplicating this thing or are very poorly written. There are only 2 I could recommend:

* [My Meta Box](https://github.com/bainternet/My-Meta-Box) by [Ohad Raz](http://en.bainternet.info/)
* [Reusable Custom WordPress Meta Boxes](https://github.com/tammyhart/Reusable-Custom-WordPress-Meta-Boxes) by [Tammy Hart](http://www.tammyhartdesigns.com/)


Well, why would you create your own then?
-------
Because I think I **can** do better and I **want** to do better for my own WordPress plugins and themes. I think it should be even **easier**, require even **less code** and provide even **better results**. I want to use **HTML5** magic wherever possible and use **fallbacks** for browsers that just aren't that advanced. I want it to look **beautiful**; to be as similar to **default WordPress panels** as possible. **I want it to just work**. 

Great, how do I use it?
-------
###mr-meta-box IS NOT YET PRODUCTION READY!
Sorry for all the screaming - I just want to make sure nobody uses this yet cause it is still in **very early development phase**. But all suggestions, critics, problems you had with the other libraries/plugins,… are already much appreciated and I promise I'll do my best to try make this thing as good as possible - for me and for all the WordPress developer community.

OK, but what will it look like when it will work?
-------
This already works, but again, it's not yet production ready.
```php
if (is_admin()){
	$prefix = 'mr_';
	$config = array(
		'id' => 'test_meta_box',
		'title' => 'mr Meta Box Demo',
		'prefix' => 'mr_',
		'postType' => array('post', 'page'),
		'usage' => 'plugin'
	);
	$metaBox = new mrMetaBox($config);
	$metaBox->addField(array('type' => 'Text', 'id' => 'name', 'default' => 'John Doe', 'label' => 'Full Name: '));
	$metaBox->addField(array('type' => 'Date', 'id' => 'birthday', 'label' => 'Date of birth: ', 'dateFormat' => 'dd.mm.yy','minDate' => '-100y', 'maxDate' => '-1d'));
	$metaBox->addField(array('type' => 'Textarea', 'id' => 'cv', 'default' => 'Here goes your CV.', 'label' => 'CV: '));
	$metaBox->addField(array('type' => 'Checkbox', 'id' => 'agree', 'label' => 'I agree with TOS: '));
	$metaBox->addField(array('type' => 'Color', 'id' => 'eye_color', 'label' => 'Color of your eyes: '));
	$metaBox->addField(array('type' => 'Range', 'id' => 'height', 'label' => 'Height: ', 'min' => 50, 'max' => 220, 'step' => 5));
	$metaBox->addField(array('type' => 'Time', 'id' => 'appointment_time', 'label' => 'Time of the appointment: ', 'timeFormat' => 'hh:mm TT', 'ampm' => 'true', 'show' => array('Hour', 'Minute')));
	$metaBox->addField(array('type' => 'Image', 'id' => 'portrait', 'label' => 'Portrait', 'attachToPost' => true));
}
```

Is it all your work?
-------
Mostly, but it relies on the many works of others:
* [WordPress](http://wordpress.org/) - no shit, Sherlock
* [jQuery](http://jquery.com/) - included with WordPress
* [jQuery UI](http://jqueryui.com/)  - included with WordPress
* [Farbtastic](http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/) - included with WordPress
* [ThickBox](http://thickbox.net/) - included with WordPress
* [Modernizr](http://modernizr.com/)
* [jQuery Timepicker Addon](https://github.com/trentrichardson/jQuery-Timepicker-Addon)

License
-------
mr-meta-box is developed by [Miha Rekar](http://mr.si/) and licensed under the [GPLv2 License](http://www.gnu.org/licenses/gpl-2.0.html)