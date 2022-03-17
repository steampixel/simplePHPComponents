<?PHP

  namespace Steampixel;

  // Get the component props
  $title = $this->prop('title', [
    'type' => 'string',
    'required' => true
  ]);

  // Modify the title
  $title = 'My custom page - '.$title;

  // Lets render the original component and push a modifyed title to it
  Component::create('layouts/default/title', 'components/default')
  ->assign(['title' => $title])
  ->print();

?>
