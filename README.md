#mr meta box

**mr meta box** is a simple class for using powerful WordPress meta boxes as easily as possible.
Version: 0.1
Contributors: Miha Rekar - [mrfoto](https://github.com/mrfoto)

##What are meta boxes?

With meta boxes you can make WordPress as versatile as any other CMS. Maybe you just want to add some **user friendly** custom fields to post or page or maybe you are dealing with **custom post types** and want to make them 1000 times more powerful - meta boxes are an **awesome way** to do that. The problem is that it's fairly complicated to make them and it requires a lot of code. Things get pretty messy even when you just want to add one simple text field as shown in this [Smashing Magazine Tutorial](http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/).

##Awesome, but surely someone already thought of that?

There are some plugins for making meta boxes but when you are developing a plugin or a theme you probably don't want it to rely on some other plugin. There are also a few libraries but most of them are overcomplicating this thing or are very poorly written. There are only 2 I could recommend:

* [My Meta Box](https://github.com/bainternet/My-Meta-Box) by [Ohad Raz](http://en.bainternet.info/)
* [Reusable Custom WordPress Meta Boxes](https://github.com/tammyhart/Reusable-Custom-WordPress-Meta-Boxes) by [Tammy Hart](http://www.tammyhartdesigns.com/)

##Well, why would you create your own then?

Because I think I **can** do better and I **want** to do better for my own WordPress plugins and themes. I think it should be even **easier**, require even **less code** and provide even **better results**. I want to use **HTML5** magic wherever possible and use **fallbacks** for browsers that just aren't that advanced. I want it to look **beautiful**; to be as similar to **default WordPress panels** as possible. **I want it to just work**.

##Great, how do I use it?

mr meta box is still in **development phase**, but you can already use it. There are more features coming, but it can already do most of what others do, but better and prettier. All suggestions, critics, problems,… you had with the other libraries/plugins,… are much appreciated so I can make mr meta box even better. If you have any problems with mr meta box please [open an issue](https://github.com/mrfoto/mr-meta-box/issues).

##Well, let's do this!

There are **only 3 steps** to get your shiny meta boxes working:
###1. Require mr meta box
Download mr meta box and place it in your plugin/theme/wherever than put this code in your `functions.php` or your main plugin file or wherever you want to use meta boxes.
```php
require_once('mr-meta-box/mr-meta-box.php');
```

###2. Define mr meta box
Define what you need - the only required field is `'id'`. Down there are the default values, so if you want to use the same, you don't have to define it. Awesome, huh?
```php
$config = array(
	'id' => null, //string Meta box ID - required
	'title' => 'Title', //string Title of the meta box
	'prefix' => '', //string Prefix of the field ids
	'postType' => array('post'), //array Array of post types you want to add meta box to
	'context' => 'normal', //string The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side')
	'priority' => 'default', // string The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
	'usage' => 'theme', //string 'theme', 'plugin' or 'http://example.com/path/to/mr-meta-box/folder'
	'showInColumns' => false //boolean Whether to show the mr meta box fields in 3 columns - comes handy where there is many fields in one mr meta box
);
$metaBox = new mrMetaBox($config);
```

###3. Define fields
Now, that your mr meta box is ready, you just need to tell it what fields to show. Here is where it gets **really interesting**. If, for example, you just want to add some quick fields, and don't care about any default values, formats, limitation or any other options, I've provided a shortcut method `addFieldsSimple`:
```php
$metaBox->addFieldsSimple(array(
	'Text' => 'Name',
	'Textarea' => 'Description',
	'Checkbox' => 'Agree to Terms of Service'
));
```
This will auto generate those 3 fields. As you can see, you provide a simple array with field types as keys and labels as values. Couldn't be any simpler.

But, there are times when you will want to precisely tune your meta box - let's say for client. Here is where the `addField` method comes in. It is worth noting that those two methods are 100% compatible, so you can use the first one for some fields and the second one for others.

There are many different types of fields you can have in your mr meta box:

* Text
* Textarea
* WYSIWYG
* Checkbox
* Select
* RadioGroup
* CheckboxGroup
* Color
* Date
* Time
* Range
* Image

There is a `demo.php` in the works but until then here is an example on how you can use them:
```php
	$config = array('id' => 'test_meta_box', 'title' => 'mr Meta Box Demo', 'prefix' => 'mr_', 'postType' => array('post', 'page'), 'usage' => 'plugin');
	$metaBox = new mrMetaBox($config);
	$metaBox->addField(array('type' => 'Text', 'id' => 'name', 'default' => 'John Doe', 'label' => 'Full Name: '));
	$metaBox->addField(array('type' => 'Date', 'id' => 'birthday', 'label' => 'Date of birth: ', 'dateFormat' => 'dd.mm.yy','minDate' => '-100y', 'maxDate' => '-1d'));
	$metaBox->addField(array('type' => 'Textarea', 'id' => 'cv', 'label' => 'CV: '));
	$metaBox->addField(array('type' => 'Checkbox', 'id' => 'agree', 'label' => 'I agree with TOS: '));
	$metaBox->addField(array('type' => 'Color', 'id' => 'eye_color', 'label' => 'Color of your eyes: '));
	$metaBox->addField(array('type' => 'Range', 'id' => 'height', 'label' => 'Height: ', 'min' => 50, 'max' => 220, 'step' => 5));
	$metaBox->addField(array('type' => 'Time', 'id' => 'appointment_time', 'label' => 'Time of the appointment: ', 'timeFormat' => 'hh:mm TT', 'ampm' => 'true', 'show' => array('Hour', 'Minute')));
	$metaBox->addField(array('type' => 'Image', 'id' => 'portrait', 'label' => 'Portrait', 'attachToPost' => true));
	$metaBox->addField(array('type' => 'WYSIWYG', 'id' => 'description', 'label' => 'Tell me about yourself:', 'showHTML' => true));
	$metaBox->addField(array('type' => 'Select', 'id' => 'car', 'label' => 'Car maker: ', 'options' => array('Audi', 'BMW', 'Alfa Romeo'), 'default' => 'Select car'));
	$metaBox->addField(array('type' => 'RadioGroup', 'id' => 'animal', 'label' => 'Favorite animal:', 'options' => array('Koala', 'Zebra', 'Hedgehog')));
	$metaBox->addField(array('type' => 'CheckboxGroup', 'id' => 'pets', 'label' => 'Have any pets?', 'options' => array('Cat', 'Dog', 'Aligator')));

```

##Is it all your work?

Mostly, but it relies on the works of many others:
* [WordPress](http://wordpress.org/) - no shit, Sherlock
* [jQuery](http://jquery.com/) - included with WordPress
* [jQuery UI](http://jqueryui.com/)  - included with WordPress
* [Farbtastic](http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/) - included with WordPress
* [Modernizr](http://modernizr.com/)
* [jQuery Timepicker Addon](https://github.com/trentrichardson/jQuery-Timepicker-Addon)

##License

mr meta box is developed by [Miha Rekar](http://mr.si/) and licensed under the [MIT License](http://opensource.org/licenses/mit-license.php)