# lean micro lib!

## FAQ
### What is lean?
> _lean_ is a micro library written in PHP5.3 consisting of carefully crafted components, each made to satisfy a specific purpose, taking work off your shoulders while trying to stay as lean, clean and concise as possible.

### What is lean not?
> _lean_ is not a framework. It does not put a frame around your code or limit you to do stuff in a particular way. You just include the autoloader and use the components you like.

### Why lean?
> _lean_ was created in an effort to get away from huge monilithic frameworks that can virtually do anything but provide you with stuff you won't ever need and making you in turn need to know a whole bunch of classes, dependencies and what not.
> _lean_ is inspired by the Slim microframework and its slim outline, taking its concept and pushing them forward.

### Can I use it RIGHT NOW?!
> Sure. go ahead and fire it up, toy with it. I would not recommend to use it in a productive environment just yet though. There are still interfaces prone to changes and components to be written. Stay tuned!

## components

+ Autoloader
+ (Application)
+ Form
+ (Scaffolding)
+ Dump
+ I18N
+ Template

### Autoloader

The autoloader is pretty straight forward. It makes use of PHP5.3s namespaces and maps them to the filesystem
The class lean\form\Element for example is located under lean/form/element.php, it's really that easy.
There is only one specialty built in: underscores. Only the first chunk of underscored component name counts.

An example:
foo\bar\Qux will be expected to be in foo/bar/qux.php
as will foo\bar\Qux_Baz and foo\bar\Qux_Baz_Kos

This enables you to put similar classes into one small php file instead of having to split them up.

### Application
(Planned) Application is just in the planning phase as of now. It is intended to become a component that will lean on Slim (that's part of why it's called lean) and provide it with Controller/Action logic.

### Form
The form component is well in its early stages. Have a look if you like to see what it is intended to become but there are still vital parts missing, such as validation.

### (Scaffolding)
(Planned) The scaffolding will build on lean\Form and provide an easy way to create CRUD style forms out of ORM objects.

### Dump
lean\Dump is a utility class that lets you print data in a neat way and in the depth you want,  making debugging a little less awful.

    lean\Dump::flat($foo); // will only dump the first level of the object or array (you may pass scalars as well, it can handle them just fine)
    lean\Dump::deep(3, $foo); // will dump the first three levels of the object or array (you may pass scalars as well, it can handle them just fine)`

### Template
Template is a very lightweight PHP-template wrapper. You may use Template_Base if you do not like the magic going on but Template is somewhat more convinient to use.

    // It's easy to use: just put this whereever you need a template
    $template = new lean\Template('somefile.php');
    $template->foo = 'bar';

    // and in the template go
    echo $this->foo; // will print bar