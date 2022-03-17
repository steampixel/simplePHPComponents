<?PHP

  // Get the component props
  $title = $this->prop('title', [
    'type' => 'string',
    'required' => true
  ]);

  // Modify the title
  $title = 'My custom page - '.$title;

?>

<title><?=$title ?></title>
