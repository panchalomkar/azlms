<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'azdata';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_general_ci',
);

// @error_reporting(E_ALL | E_STRICT);
// @ini_set('display_errors', '1');
// $CFG->debug = (E_ALL | E_STRICT);
// $CFG->debugdisplay = 1;

// $CFG->debug = (E_ALL | E_STRICT); // Show all problems.
// $CFG->debugdisplay = 1;           // Display errors on the page.

$CFG->wwwroot   = 'http://echo.local';
// $CFG->wwwroot = 'http://company5.lms.local/lms5';
$CFG->dataroot  = 'C:\\xampp\\azmdata';


// $CFG->wwwroot   = 'https://www.azschoolofmedicalassistant.com/echocardiogram';
// $CFG->dataroot  = '/var/www/echocardiogram/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

//$CFG->noemailever = true; 

require_once(__DIR__ . '/lib/setup.php');





// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
