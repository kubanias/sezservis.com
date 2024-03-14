<?php if (!defined('B2')) exit(0);

// Process all the files only if 'process' segment was specified
if (__segment(1) != 'process') exit(0);

include_once (__info('base_dir') . __get_config(['compressor', 'reg_file']));
compressor::process_all();
