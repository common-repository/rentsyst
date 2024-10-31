<?php

namespace rentsyst\admin\controllers;

use rentsyst\admin\components\Rentsyst_View;

class Rentsyst_BaseController {

	protected $view;

	public function __construct()
	{
		$this->view = new Rentsyst_View();
	}

	public function render($viewName, $arg = [])
	{
		$arg['service'] = $this;
		return $this->view->render($viewName, $arg);
	}

	public function add_my_setting(){

		global $submenu;
		$current = sanitize_text_field($_GET['page']) ?? null;
		$tabs = $submenu['rentsyst'];
		$tabs[0][0] = 'Rentsyst';
		$html = '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab ){
			$class = ( $tab[2] == $current ) ? 'nav-tab-active' : '';
			$html .= '<a class="nav-tab ' . $class . '" href="?page=' . $tab[2] . '">' . $tab[0] . '</a>';
		}
		$html .= '</h2>';
		echo $html;

	}

}
