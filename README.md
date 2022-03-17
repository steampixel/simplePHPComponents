# Simple PHP Components
Hey! This is a simple, puristic and small PHP template rendering engine that comes without any parsing or extra templating language. Build simple templates or complex themes with pure PHP. This concept just works by gluing strings together. That means there is no complex parsing method that will eat up RAM or CPU. That makes this rendering approach extremely fast.

## Features
* Organize your components inside a clean folder structure
* A component can be a layout, a page or a single content
* Easy reuse of components
* Push variables aka properties (props) to components
* Verify component props
* Create nested components
* Easily override, modify, append or prepend components
* Superfast: We don't need to parse anything
* Superfast: No caching needed
* No extra templating language required

## Simple example:
```php
<?PHP
// Require the class
include 'src\Steampixel\Component.php';

// Use this namespace
use Steampixel\Component;

// Add a folder where your component files live in
Component::addFolder('components/default');

// Render your first component and add some props to it
// This will create, render and print the file components/content/hero.php
Component::create('content/hero')
  ->assign([
    'title' => 'Hello World',
    'subtitle' => 'No sea takimata sanctus est']
  )
  ->print();
```

## Define your own component files
Defining your own components is easy. In this example we will render a hero component including a title and an optional subtitle.
Just create a .php file inside your custom components folder or wherever you want: `components/custom/content/hello.php`
```php

<section class="hero is-medium is-primary is-bold">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        Hello World
      </h1>
    </div>
  </div>
</section>

```
Then you can easily render your component:
```php
Component::create('content/hello')->print();
```

## Installation using Composer
Just run `composer require steampixel/simple-php-components`
Then add the autoloader to your project like this:
```php
// Autoload files using composer
require_once __DIR__ . '/vendor/autoload.php';

// Use this namespace
use Steampixel\Component;

// Add the folder where the components live
Component::addFolder('components/default');

// Render and print a component
Component::create('layouts/default/footer')->print();
```

## Test setup with Docker
I have created a little Docker test setup.

1. Build the image: `docker build -t simplephpcomponents docker/image-php-7.4.1`

2. Spin up a container: `docker run -d -p 80:80 -v $pwd:/var/www/html --name simplephpcomponents simplephpcomponents`

3. Open your browser and navigate to http://localhost

## Alternative print mehthods
There are various ways you can print the components content to your website:

You can use for example the build in `print()` method:
```php
<?PHP
Component::create('partials/footer')->print();
```

Or just use `echo` like in this example:
```php
<?PHP
echo Component::create('partials/footer');
```

Or `render()` the HTML to a string and keep it for later:
```php
<?PHP
$html = Component::create('partials/footer')->render();
echo $html;
```

## Push props to components
With assign() you can push various kind of properties to component instances. So you can easily recycle and reuse your components.
```php
<?PHP

Component::create('content/text')
  ->assign([
    'header' => 'Header 1',
    'text' => 'Hello world'
  ])
  ->print();

Component::create('content/text')
  ->assign([
    'header' => 'Header 2',
    'text' => 'Lorem Ipsum'
  ])
  ->print();
```

You can also call the `assign()` method more than once.
The assign method can take an array of props or alternatively just a prop name and a value as separated arguments like this:
```php
<?PHP

Component::create('content/text')
  ->assign('header', 'Header 1')
  ->assign('text', 'Hello world')
  ->print();
```

## Specify, require and verify component props
Every component can optionally require and verify props. If the prop is then missing or if the type is wrong, the component will throw an error.
Check out this example in: `components/custom/content/hero.php`:
```php
<?PHP

// This prop is required
$title = $this->prop('title', [
  'type' => 'string',
  'required' => true
]);

// This prop is optional with a default value
$subtitle = $this->prop('subtitle',[
  'type' => 'string',
  'required' => false,
  'default' => 'hello world'
]);

?>

<section class="hero is-medium is-primary is-bold">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        <?=$title ?>
      </h1>
      <?PHP if($subtitle) { ?>
        <h2 class="subtitle">
          <?=$subtitle ?>
        </h2>
      <?PHP } ?>
    </div>
  </div>
</section>

```
As you can see, there is a props section at the top of the file. By calling `$this->prop('prop_name')` the component will retrieve a component prop. Optionally, you can specify the type and requirement of the prop. In the above case, both props are of the type `string`. If you try to push an `int` to the title, for example, an exception will be thrown. Just drop the `type` to avoid the type check. Available types are:
* bool
* int
* float
* string
* array
* object
* callable

Also, the title prop is required for rendering the component. If you do not `assign()` the title prop, the component will throw an error. As you can see, the subtitle prop is optional. The markup for this will only be rendered if it is defined. You can also drop the `required` option to make the property optional.

## Nested components
It is possible to nest your components. This is really important. Because you don't want to have a single huge monolithic component. So the advice is to split it down in several chunks. In this way you are also able to override every subcomponent later with plugins. Just call `Component::create('component/name')->print();` inside your components.

## Component folder structure
It's up to you how you want to organize your components. But I would recommend having
* contents - reusable content modules
* layouts - reusable page layouts
* pages - a page is a component that utilizes a layout and pushes contents to it
* partials - For example, a button that is used inside contents or layouts

## Add more component folders and extend single components
You can have more than one component folder. Use `Component::addFolder('components/custom');` to add more folders. For example, a plugin could add its own folder.
The sequence of the defined folders is critical. The engine will search in the first folders first. This is important to know if you want to extend a theme, for example.

## Extend, override and manipulate existing components with own components.
You can easily modify existing components without touching them. Just create a second folder containing your own components. If you want to extend existing components, you have to add your folder BEFORE the other components' folder! Then just copy the file you want to extend together with its containing folder structure to your own components' folder.

For example, if you want to modify the title of your page copy the file `components/default/layouts/default/title.php` to `components/custom/layouts/default/title.php`. Then load your custom folder inside your app with `Component::addFolder('components/custom');`.

Now, if a component with the name `layouts/default/title` will be created, the engine will find your component first.

Now you have several options.

### Override existing components
Overriding components is easy. Just copy the component to your custom folder and do what you want inside this new component file.

### Inherit from existing components
Inside your own custom component file, you can call the original component and thereby inherit its contents. This is useful if you for example want to add just a text snippet or if you want to wrap it inside an additional container. Take a look at the file `custom/default/content.php` This will override the original one from the default folder. But it will load the original file inside itself and so add a text to it with:
```php
<?PHP
Component::create('layouts/default/content', 'components/default')
->assign(['title' => $title, 'subtitle' => $subtitle, 'contents' => $contents])
->print();
```
With the second parameter of the `create()` method, you can specify from which components folder you want to load the component. So you can still access and print the original one inside your customization.

### Modify props of existing components
You can easily modify props of the original components within your own component extension file. Take a look at the file `components/custom/layout/default/title.php` This file will receive the original title, will prepend a text to it, and then pushes this modified property to the original file.

## Can I use blocks, as in many other templating engines?
No, this is not possible. Blocks inside of components would require a complex parsing logic. And I want to keep this as simple as possible.
Instead, my advice is to split a huge component in smaller ones. So you can address and extend its parts by other components.

## License
This project is licensed under the MIT License. See LICENSE for further information.
