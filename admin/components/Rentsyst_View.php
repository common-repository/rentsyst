<?php

namespace rentsyst\admin\components;

class Rentsyst_View {

	public $folderView = 'admin/pages';

	public $fullPath;

	public function render($viewName, $arg = [])
	{
		$_obInitialLevel_ = ob_get_level();
		ob_start();
		ob_implicit_flush(false);
		extract($arg, EXTR_OVERWRITE);
		try {
			$path = $this->fullPath ? $this->fullPath : WP_RENTSYST_PLUGIN_DIR . '/' . $this->folderView . '/' . $viewName . '.php';
			require $path . '';
			return ob_get_clean();
		} catch (\Exception $e) {
			while (ob_get_level() > $_obInitialLevel_) {
				if (!@ob_end_clean()) {
					ob_clean();
				}
			}
			throw $e;
		} catch (\Throwable $e) {
			while (ob_get_level() > $_obInitialLevel_) {
				if (!@ob_end_clean()) {
					ob_clean();
				}
			}
			throw $e;
		}
	}
}
