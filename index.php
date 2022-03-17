<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Use this namespace
use Steampixel\Component;

// Include component class
include 'src/Steampixel/Component.php';

// Add a second components folder for demonstration of component and block overriding
Component::addFolder('components/custom');

// Add the folder where the components live
Component::addFolder('components/default');

// Create the layout component together with some child components
$page = Component::create('pages/index');

// Assign a few variables to the layout component
$page->assign([
  'lang' => 'en',
  'title' => 'Lorem Ipsum'
])
->print();
