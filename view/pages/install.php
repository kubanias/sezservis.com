<?php if (!defined('B2')) exit(0);

// Start installation of all the modules if 'process' segment was specified
if (__segment(1) == 'process') {
	__install_all();
}
