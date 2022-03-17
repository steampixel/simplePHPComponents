<?PHP

namespace Steampixel;

class Component {

	// Define an array of folders with components inside
	public static $folders = [];

	// This is the name of the component instance
	public $name = false;

	// This component is bound to a specific folder
	public $folder = false;

	// Define an array containing variables for rendering the component
	public $props = [];

	// Add a new folder to the component class
	public static function addFolder($folder) {
		array_push(self::$folders, $folder);
	}

	// This is a static factory method
	public static function create($component_name, $folder = false) {
		return new self($component_name, $folder);
	}

	// This is the constructor for the instance
	public function __construct($component_source, $folder) {

		$this->name = $component_source;

		$this->folder = $folder;

	}

	// Auto convert instances to string
	public function __toString() {
		return $this->render();
	}

	// Add new data to the component instance
	public function assign($key, $value = null) {
    if(is_array($key)) {
      foreach($key as $_key => $_value) {
  			$this->props[$_key] = $_value;
  		}
    } else {
      $this->props[$key] = $value;
    }
		return $this;
	}

	// Return a prop to the component instance
	public function prop($prop_name, $options = []) {

		$return_prop = Null;

		// Check if the prop exists in the props array
		if(isset($this->props[$prop_name])) {

			$return_prop = $this->props[$prop_name];

		} else {

			// Check if there is a default value
			if(isset($options['default'])) {
				$return_prop = $options['default'];
			}

		}

		// Check if the prop is required
		if(isset($options['required'])&&$options['required']) {
			if($return_prop===NUll) {
				throw new \Exception('The prop "'.$prop_name.'" is required for rendering the component "'.$this->name.'"!');
			}
		}

		// Check the type of the prop
		if(isset($return_prop)) {
			if(isset($options['type'])) {

				$prop_type = gettype($return_prop);

				if(is_array($options['type'])) {
					$type_validated = false;
					foreach($options['type'] as $type) {
						if($type == $prop_type) {
							$type_validated = true;
							break;
						}
					}
					if(!$type_validated) {
						throw new \Exception('The prop "'.$prop_name.'" must be of one of the following types for the component "'.$this->name.'": "'.implode(',',$options['type']).'". The current type is "'.$prop_type.'"');
					}
				} else {
					if($options['type'] != $prop_type) {
						throw new \Exception('The prop "'.$prop_name.'" must be of type "'.$options['type'].'" for the component "'.$this->name.'". The current type is "'.$prop_type.'"');
					}
				}

			}
		}

		return $return_prop;

	}

	// Render the component
	public function render() {

		$result = '';

		// Start the output buffering
		ob_start();

		// Catch the tempalte return
		$template_return = false;

		$loaded = false;
		if($this->folder) {

			// Load this component from a specific folder
			$loaded = $this->loadFromFolder($this->folder, $this->name);

		} else {

			// Try to find this component in all folders
			foreach(self::$folders as $key => $folder) {
				//echo 'checking file '.$folder.'/'.$this->name.'.php<br>';
				$loaded = $this->loadFromFolder($folder, $this->name);
				// Break on first match
				if($loaded){
					break;
				}
			}

		}

		if(!$loaded) {
			throw new \Exception('Unable to render component "'.$this->name.'".');
		}

		// Get the results
		$result = ob_get_clean();

    return $result;

	}

	// Print out component HTML
	public function print () {
		echo $this->render();
	}

	public function loadFromFolder($folder, $name) {
		if(file_exists($folder.'/'.$name.'.php')) {
			include($folder.'/'.$name.'.php');
			return true;
		}
		return false;
	}

}
