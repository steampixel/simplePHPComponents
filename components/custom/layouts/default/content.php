<?PHP

  namespace Steampixel;

  // This is a sample custom component
  // We want to extend the original layouts/default/content

  // First set the props
  $title = $this->prop('title', [
    'type' => 'string',
    'required' => true
  ]);

  $subtitle = $this->prop('subtitle', [
    'type' => 'string',
    'required' => false
  ]);

  $contents = $this->prop('contents', [
    'type' => 'array',
    'required' => false
  ]);

?>

<!-- Insert a breadcrumb menu before the content starts -->
<div class="container">
  <a href="#">home</a> / <a href="#">blog</a> / <a href="#">Lorem Ipsum</a>
</div>

<?PHP

// Lets render the original component
Component::create('layouts/default/content', 'components/default')
->assign(['title' => $title, 'subtitle' => $subtitle, 'contents' => $contents])
->print();
