mr-meta-box
=============

**mr-meta-box** is a simple class for using powerful WordPress meta boxes as easily as possible.

What are meta boxes?
-------

Maybe you just want to add some **user friendly** custom fields to post or page or maybe you are dealing with **custom post types** and want to make them 1000 times more powerful - meta boxes are an **awesome way** to do that. The problem is that it's fairly complicated to make them and it requires a lot of code. Things get pretty messy even when you just want to add one simple text field as shown in this [Smashing Magazine Tutorial](http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/).

Awesome, but surely someone already thought of that?
-------

There are some plugins for making meta boxes but when you are developing a plugin or a theme you probably don't want it to rely on some other plugin. There are also a few libraries but most of them are overcomplicating this thing or are very poorly written. There are only 2 I would recommend:

* [My-Meta-Box](https://github.com/bainternet/My-Meta-Box) by [Ohad Raz](http://en.bainternet.info/)
* [Reusable-Custom-WordPress-Meta-Boxes](https://github.com/tammyhart/Reusable-Custom-WordPress-Meta-Boxes) by [Tammy Hart](http://www.tammyhartdesigns.com/)


Well, why would you create your own then?
-------

Because I think I **can** do better and I **want** to do better for my own WordPress plugins and themes. I think it should be even **easier**, require even **less code** and provide even **better results**.

Great, how do I use it?
-------

###mr-meta-box IS NOT YET PRODUCTION READY!
Sorry for all the screaming - I just want to make sure nobody uses this yet cause it is still in **very early development phase**. But all suggestions, critics, problems you had with the other libraries/plugins,… are already much appreciated and I promise I'll do my best to try make this thing as good as possible - for me and for all the WordPress developer community.

OK, but what will it look like when it will work?
-------

Probably something like this:
```php
if (is_admin()){
	$prefix = 'test_';
	$config = array(
		'id' => 'test_meta_box',
		'title' => 'Testing, testing',
		'postType' => array('post', 'page')
	);
	$metaBox = new mrMetaBox($config);
	$metaBox->addField(array('type' => 'Text', 'id' => $prefix.'name', 'default' => 'John Doe', 'label' => 'Full Name: '));
	$metaBox->addField(array('type' => 'Textarea', 'id' => $prefix.'cv', 'default' => 'Here goes your CV.', 'label' => 'CV: '));
	$metaBox->addField(array('type' => 'Checkbox', 'id' => $prefix.'agree', 'label' => 'I agree with TOS: '));
}
```

License
-------

mr-meta-box is developed by [Miha Rekar](http://mr.si/) and licensed under the [GPLv2 License](http://www.gnu.org/licenses/gpl-2.0.html)