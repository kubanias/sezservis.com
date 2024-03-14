<?php if (!defined('B2')) exit(0);

/**
 * @package JetFin
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 05.11.2022
 * @version: 1.0
 *
 * Useful functions that will be used inside the current project
 */

class g {
	/**------------------------------------------------------------- SHOW 404 -----------------------------------------------------------------------
	 * Shows 404 page and returns corresponding header
	 * @return void
	 */
	public static function show_404() : bool {
		if (file_exists(__info('view_dir') . '/pages/404.php')) { include_once(__info('view_dir') . '/pages/404.php'); exit(0); }

	    header('HTTP/1.0 404 Not Found');
	    exit(0);

	    return false; // Just for IDE
	}

	/**------------------------------------------------------------- SHOW 403 -----------------------------------------------------------------------
	 * Shows 403 page and returns corresponding header
	 * @return void
	 */
	public static function show_403() : bool {
		if (file_exists(__info('view_dir') . '/pages/403.php')) { include_once(__info('view_dir') . '/pages/403.php'); exit(0); }

	    header('HTTP/1.0 403 Forbidden');
	    exit(0);

	    return false; // Just for IDE
	}

}