<?php

/**
 * CC app Class
 *
 * @package			Cicool  
 * @category		component
 */

class Cc_App
{
	
	/**
	 * App
	 *
	 * @var			array
	 * @access		public
	 */
	public $app = [];

	/**Menu Data
	 *
	 * @var			array
	 * @access		public
	 */
	public $menuData = [];

	/**
	 * Header frontend register
	 *
	 * @var			array
	 * @access		public
	 */
	public $headers = [];

	/**
	 * Registered event
	 *
	 * @var			array
	 * @access		public
	 */
	public $registeredEvent = [];

	/**
	 * Tab Setting
	 *
	 * @var			array
	 * @access		public
	 */
	public $tabSetting = [];

	/**
	 * Tab Content
	 *
	 * @var			array
	 * @access		public
	 */
	public $tabContent= [];

	/**
	 * Tab Parent Name
	 *
	 * @var			string
	 * @access		public
	 */
	public $tabParentName;

	/**
	 * Route prefix
	 *
	 * @var			string
	 * @access		public
	 */
	public $routePrefix;

	/**
	 * CodeIgniter instance
	 *
	 * @var			object
	 * @access		public
	 */
	public $ci;

	const TYPE_MENU = 2; 
	const TYPE_LABEL = 1; 
	const ADMIN_SIDEBAR_MENU = 'admin_sidebar_menu'; 


	public function __construct($config = [])
	{
		$this->ci =& get_instance();
		$this->ci->load->database();

		$this->initialize($config);
	}

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array $config
	 * @return	void
	 */
	public function initialize($config = [])
	{
		foreach ($config as $key => $val)
		{
			$this->{$key} = $val;
		}

		return $this;
	}

	/**
	 * Get option
	 *
	 * @access		public
	 * @param		string $option_name
	 * @param		string $option_value
	 * @return		boolean
	 */
	public function getOption($option_name, $default = null)
	{
		if ($option = $this->optionExists($option_name)) {
			if (!empty($option)) {
				return $option;
			} else {
				return $default;
			}
		}

		return $default;
	}

	/**
	 * Add option
	 *
	 * @access		public
	 * @param		string $option_name
	 * @param		string $option_value
	 * @return		boolean
	 */
	public function addOption($option_name =  null, $option_value = null)
	{
		if ($this->optionExists($option_name, $option_value) === false) {
			return $this->ci->db->insert('cc_options', [ 
				'option_name' => $option_name,
				'option_value' => $option_value 
				]);
		}

		return false;
	}

	/**
	 * Set option
	 *
	 * @access		public
	 * @param		string $option_name
	 * @param		string $option_value
	 * @return		boolean
	 */
	public function setOption($option_name =  null, $option_value = null)
	{
		if ($this->optionExists($option_name, $option_value) === false) {
			$this->addOption($option_name, $option_value);
		} else {
			return $this->ci->db
						->where(['option_name' => $option_name])
						->update('cc_options', [ 'option_value' => $option_value]);
		}
	}

	/**
	 * Delete option
	 *
	 * @access		public
	 * @param		string $option_name
	 * @return		boolean
	 */
	public function deleteOption($option_name =  null)
	{
		return $this->ci->db
					->where(['option_name' => $option_name])
					->delete('cc_options');
	}

	/**
	 * Check option exists 
	 *
	 * @access		public
	 * @param		string $option_name
	 * @return		mixed string | boolean
	 */
	public function optionExists($option_name =  null)
	{
		$result = $this->ci->db->get_where('cc_options', [ 'option_name' => $option_name]);

		if ($row = $result->row()) {
			return $row->option_value;
		}

		return false;
	}

	/**
	 * Get header frontend 
	 *
	 * @access		public
	 * @return		String
	 */
	public function eventListen($eventName = null, $params = [])
	{
		$body = null;
		foreach ($this->getEvent($eventName) as $function) {
			if (is_object($function)) {
				$body .= $function($params);
			} elseif(function_exists($function)) {
				$body .= call_user_func_array($function, $params);
			} elseif(is_string($function)) {
				list($class, $method) = explode('::', $function);
				$body .= (new $class())->$method($params);
			}
		}

		return $body;
	}

	
	/**
	 * Cc on event 
	 *
	 * @access		public
	 * @param 		String $eventName
	 * @param 		Mixed String | Object $action
	 * @return		String
	 */
	public function onEvent($eventName = null, $action = null)
	{
		$this->registeredEvent[$eventName][] = $action;

		return $this;
	}

	/**
	 * Get event 
	 *
	 * @access		public
	 * @param 		String $eventName
	 * @return		Mixed String | Array
	 */
	public function getEvent($eventName = null)
	{
		if (isset($this->registeredEvent[$eventName])) {
			return $this->registeredEvent[$eventName];
		} else {
			return [];
		}
	}



	/**
	 * Get header frontend 
	 *
	 * @access		public
	 * @return		String
	 */
	public function filter($filterName = null, $params = [])
	{
		$params = [];
		foreach ($this->getFilter($filterName) as $array) {
			$params[] = $array;
		}

		return $params;
	}

	
	/**
	 * Cc on event 
	 *
	 * @access		public
	 * @param 		String $filterName
	 * @param 		Mixed String | Object $action
	 * @return		String
	 */
	public function addFilter($filterName = null, $action = null)
	{
		$this->registeredFilter[$filterName][] = $action;

		return $this;
	}

	/**
	 * Get event 
	 *
	 * @access		public
	 * @param 		String $filterName
	 * @return		Mixed String | Array
	 */
	public function getFilter($filterName = null)
	{
		if (isset($this->registeredFilter[$filterName])) {
			return $this->registeredFilter[$filterName];
		} else {
			return [];
		}
	}

	/**
	 * Get header 
	 *
	 * @access		public
	 * @param 		String $eventName
	 * @return		String
	 */
	public function getHeader()
	{
		return $this->eventListen('front_head');
	}

	/**
	 * Get footer 
	 *
	 * @access		public
	 * @return		String
	 */
	public function getFooter()
	{
		return $this->eventListen('front_footer');
	}

	/**
	 * Get navigation 
	 *
	 * @access		public
	 * @return		String
	 */
	public function getNavigation()
	{
		return $this->eventListen('front_navigation');
	}


	/**
	 * Cc on route 
	 *
	 * @access		public
	 * @param 		String $route
	 * @param 		Mixed String | Array $method
	 * @param 		Mixed String | Object $action
	 * @return		String
	 */
	public function onRoute($route = null, $method = 'get', $action = null)
	{
		if ($this->thisRoute($this->routePrefix .$route, $method, $action)) {
			$this->routePrefix = $route;
			if (is_object($action)) {
				$action();
			} elseif(function_exists($action)) {
				call_user_func_array($action);
			}
			exit;
		}
	}

	/**
	 * Cc this route 
	 *
	 * @access		public
	 * @param 		String $route
	 * @param 		Mixed String | Array $method
	 * @return		Boolean
	 */
	public function thisRoute($route = null, $method = 'get')
	{
		$current_uri = $this->ci->uri->uri_string;
		$route = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $route ?? '');

		if (is_string($method)) {
			$method = [$method];
		}
		if (preg_match('#^'.$route.'$#', $current_uri, $matches)) {
			if (in_array($this->ci->input->method(), $method)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Cc on event 
	 *
	 * @access		public
	 * @param 		String $eventName
	 * @param 		Mixed String | Object $action
	 * @return		String
	 */
	public function registerPage($pageName, $action = null)
	{
		$this->registeredEvent['show_page_'.$pageName][] = $action;

		return $this;
	}

	/**
	 * Cc on event 
	 *
	 * @access		public
	 * @param 		String $eventName
	 * @param 		Mixed String | Object $action
	 * @return		String
	 */
	public function registerClassPage($class)
	{
		$methods = get_class_methods($class);

		foreach ($methods as $method) {
			$name = (new \ReflectionClass($class))->getShortName();
			$this->registerPage($method, "$name::$method");
		}

		return $this;
	}

	/**
	 * Reorder Menu
	 */
	private function reorderMenu()
	{
		$model = cicool()->filter('admin_sidebar_menu', $this->menuData);
		foreach ((array)$model as $val) {
			if (strlen($val->id) < 5) {
				throw new Exception('menu id length must >= 5');
			}
			$this->menuData[$val->id] = [
				'label' 		=> $val->label,
				'type' 			=> $val->type,
				'icon_color' 	=> $val->icon_color,
				'link' 			=> $val->link,
				'parent' 		=> $val->parent,
				'icon' 			=> $val->icon,
				'sort'			=> $val->sort,
				'menu_type_id'	=> $val->menu_type_id
			];
		}
	}

	/** 
	 * Render sidebar
	 */
	private function renderSidebar($data, $parent = 0)
	{
		# Set content first
		$html = '';

		foreach ($data as $key => $val) {
			# if has sub menu
			$hasSubMenu = false;
			$active = ((app()->input->get('page') == $val['link']) ? 'active' :'');
			$parentClass = 'class="nav-item '.$active.'"';

			# Find sub menu
			foreach ($data as $menu) {
				if ($menu['parent'] == $key) {
					$hasSubMenu = true;
					$parentClass = "class='nav-item".($active ? ' menu-open' : '')."'";
					break;
				}
			}

			# For label iconize handle, coz sub menu dont have icon
			if ($val['parent'] == 0) {
				$label = "<i class='nav-icon ".$val['icon']."'></i><p>".$val['label'];
			} else {
				$label = "<i class='nav-icon fa fa-circle-o '></i><p>".$val['label'];
			}

			# Get parent level menu
			if ($val['parent'] == $parent) {
				$rawLink = trim((string) $val['link']);
				$link = ($hasSubMenu || in_array($rawLink, ['', '#', '-'], true))
					? '#'
					: webPageUrl($rawLink);

				# Write tag here
				if ($val['type'] == 'menu') {
					$html .= "<li ".$parentClass.">
								<a href='".$link."' class='nav-link ".$active."'>".$label;
					if ($hasSubMenu) {
						$html .= ' <i class="nav-arrow fa fa-angle-left"></i>';
					}
					$html .= "</p></a>";



				} else {
					$html .= "<li class='nav-header'>".$val['label'];
				}

				if ($hasSubMenu) {
					$html .= "<ul class='nav nav-treeview'>";
					$html .= $this->renderSidebar($data, $key);
					$html .= "</ul>";
				}

				$html .= "</li>";
			}
		}

		return $html;
	}

	public function getSidebar()
	{
		($this->reorderMenu());

		return $this->renderSidebar($this->menuData);
	}

	public function addMenu($type, $menu = [])
	{
		$id = rand(99999,999999);
		$this->idMenu = $id;
		$this->menuType = $menu['menu_type_id'];
		$this->menuBarType = $type;
		$menu['id'] = $id;
		$menu['parent'] = 0;
		$menu['sort'] = 0;
		$menu['icon_color'] = isset($menu['icon_color']) ? $menu['icon_color'] : '';

		$this->addFilter('admin_sidebar_menu', (object)$menu);

		return $this;
	}

	public function addSubMenu($menu)
	{
		$id = rand(99999,999999);
		$menu['id'] = $id;
		$menu['menu_type_id'] = $this->menuType;
		$menu['parent'] = $this->idMenu;
		$menu['sort'] = 0;
		$menu['icon_color'] = isset($menu['icon_color']) ? $menu['icon_color'] : '';
		$type = $this->menuBarType;
		$this->addFilter($type, (object)$menu);
		return $this;
	}

	public function addTabSetting($config = [])
	{
		$config = extendsObject($config);
		$tabId = 'tab_'.url_title($config['id']);
		$isActive = $this->ci->input->get('tab') === $tabId;
		$this->tabSetting[($config['id'])] = '<li class="'.($isActive ? 'active' : '').'" role="presentation"><a href="#'.$tabId.'" id="settings-tab-'.url_title($config['id']).'" class="tab_group '.($isActive ? 'active' : '').'" data-bs-toggle="tab" role="tab" aria-controls="'.$tabId.'" aria-selected="'.($isActive ? 'true' : 'false').'"><i class="'.$config['icon'].' text-green"></i> '.$config['label'].'</a></li>';

		$this->tabParentName = $config['id']; 

		return $this;
	}

	public function addTabContent($config = [])
	{
		$config = extendsObject($config);

		$this->tabContent[($this->tabParentName)] = $config['content'];

		return $this;
	}

	public function renderTabSetting()
	{
		return implode("\n", ($this->tabSetting));
	}

	public function renderTabContent()
	{
		$html = '';

		foreach ($this->tabContent as $tabName => $content) {
			$tabId = 'tab_'.url_title($tabName);
			$isActive = $this->ci->input->get('tab') === $tabId;
			$html .= '<div class="tab-pane '.($isActive ? 'active show' : '').'" id="'.$tabId.'" role="tabpanel" aria-labelledby="settings-tab-'.url_title($tabName).'">
				<div class="row">
					'.$content.'
				</div>
			</div>';
		}

		return $html;
	}

	public function settingOnSave($action)
	{
		$this->onEvent('save_setting', $action);
		return $this;
	}

	public function settingBeforeSave($action)
	{
		$this->onEvent('before_save_setting', $action);
		return $this;
	}

}
