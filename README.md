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

## Define component files
Defining your own components is very easy. In this example we will render a hero component including a title and an optional subtitle.
file: `components/custom/content/hero.php`
```php
<?PHP

  // Get and verify the component props
  $title = $this->prop('title', [
    'type' => 'string',
    'required' => true
  ]);

  $subtitle = $this->prop('subtitle',[
    'type' => 'string',
    'required' => false
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

## Installation using Composer
Just run `composer require steampixel/simple-php-components`
Than add the autoloader to your project like this:
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
There are various ways you can print the rendered HTML to your website.

So you can use for example the build in `print()` method:
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
You can push various kind of props to component instances. So you can easily recycle and reuse your components.
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

$title = $this->prop('title', [
  'type' => 'string',
  'required' => true
]);

$subtitle = $this->prop('subtitle',[
  'type' => 'string',
  'required' => false
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
As you can see there is a props section at the top of the file. By calling `$this->prop('prop_name')` the component will get a component prop. Optionally you can specify the type and requirement of the prop. In the above case both props are of the type `string`. If you try to push an `int` to the title for example an exception will be thrown. Just drop the `type` to avoid the type check.

Also the title prop is required for rendering the component. If you do not `assign()` the title prop the component will throw an error. As you can see the subtitle prop is optional. The markup for this will only be rendered if it is defined. You can also drop the `required` flag to make the property optional.

## Nested components
It is possible to nest your components. This is realy important. Because you don't want to have a single huge monolithic component. So the advice is to split it down in several chunks. In this way you are also able to override every ub component separately later.

## Component folder structure
Its up to you how you want to organise your components.

## Add more component folders and extend single components

## License
This project is licensed under the MIT License. See LICENSE for further information.
