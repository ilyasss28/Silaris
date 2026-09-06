<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CLI-only database migration runner.
 *
 * Usage: php index.php migrate
 */
class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!is_cli()) {
            show_404();
        }
    }

    public function index()
    {
        $this->load->library('migration');

        if ($this->migration->current() === false) {
            fwrite(STDERR, $this->migration->error_string().PHP_EOL);
            exit(1);
        }

        $row = $this->db->select('version')->get('migrations')->row();
        $version = $row ? (int) $row->version : 0;

        fwrite(STDOUT, 'Database migration completed at version '.$version.PHP_EOL);
    }
}
