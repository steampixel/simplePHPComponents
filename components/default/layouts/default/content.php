<?PHP

  namespace Steampixel;

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

  // Add a hero component
  Component::create('content/hero')
  ->assign(['title' => $title, 'subtitle' => $subtitle])
  ->print();

  // Print the contents
  if($contents) {
    foreach($contents as $content) {
      $content->print();
    }
  }

?>
