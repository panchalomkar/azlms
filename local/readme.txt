// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Readme file for local customisations
 *
 * @package    local
 * @copyright  2009 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

Local customisations directory
==============================
This directory is the recommended place for local customisations.
Wherever possible, customisations should be written using one
of the standard plug-in points like modules, blocks, auth plugins, themes, etc.

See also https://moodledev.io/docs/apis/plugintypes/local for more
information.


Directory structure
-------------------
This directory has standard plugin structure. All standard plugin features
are supported. There may be some extra files with special meaning in /local/.

Sample /local/ directory listing:
/local/nicehack/         - first customisation plugin
/local/otherhack/        - other customisation plugin
/local/preupgrade.php    - executed before each core upgrade, use $version and $CFG->version
                           if you need to tweak specific local hacks
/local/defaults.php      - custom admin setting defaults



Local plugins
=============
Local plugins are used in cases when no standard plugin fits, examples are:
* event consumers communicating with external systems
* custom definitions of web services and external functions
* applications that extend moodle at the system level (hub server, amos server, etc.)
* new database tables used in core hacks (discouraged)
* new capability definitions used in core hacks
* custom admin settings

Standard plugin features:
* /local/pluginname/version.php - version of script (must be incremented after changes)
* /local/pluginname/db/install.xml - executed during install (new version.php found)
* /local/pluginname/db/install.php - executed right after install.xml
* /local/pluginname/db/uninstall.php - executed during uninstallation
* /local/pluginname/db/upgrade.php - executed after version.php change
* /local/pluginname/db/access.php - definition of capabilities
* /local/pluginname/db/events.php - event handlers and subscripts
* /local/pluginname/db/messages.php - messaging registration
* /local/pluginname/db/services.php - definition of web services and web service functions
* /local/pluginname/db/subplugins.php - list of subplugins types supported by this local plugin
* /local/pluginname/lang/en/local_pluginname.php - language file
* /local/pluginname/settings.php - admin settings


Local plugin version specification
----------------------------------
version.php is mandatory for most of the standard plugin infrastructure.
The version number must be incremented most plugin changes, the changed
version tells Moodle to invalidate all caches, do db upgrades if necessary,
install new capabilities, register event handlers, etc.

Example:
/local/nicehack/version.php
<?php
$plugin->version  = 2010022400;   // The (date) version of this plugin
$plugin->requires = 2010021900;   // Requires this Moodle version


Local plugin capabilities
-------------------------
Each local plugin may define own capabilities. It is not recommended to define
capabilities belonging to other plugins here, but it should work too.

/local/nicehack/access.php content
<?php
$local_nicehack_capabilities = array(
    'local/nicehack:nicecapability' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
    ),
);


Local plugin language strings
-----------------------------
If customisation needs new strings it is recommended to use normal plugin
strings.

sample language file /local/nicehack/lang/en/local_nicehack.php
<?php
$string['hello'] = 'Hi {$a}';
$string['nicehack:nicecapability'] = 'Some capability';


use of the new string in code:
echo get_string('hello', 'local_nicehack', 'petr');


Local plugin admin menu items
-----------------------------
It is possible to add new items and categories to the admin_tree block.
I you need to define new admin setting classes put them into separate
file and require_once() from settings.php

For example if you want to add new external page use following
/local/nicehack/settings.php
<?php
$ADMIN->add('root', new admin_category('tweaks', 'Custom tweaks'));
$ADMIN->add('tweaks', new admin_externalpage('nicehackery', 'Tweak something',
            $CFG->wwwroot.'/local/nicehack/setuppage.php'));

Or if you want a new standard settings page for the plugin, inside the local
plugins category:
<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // needs this condition or there is error on login page
    $settings = new admin_settingpage('local_thisplugin', 'This plugin');
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_thisplugin/option',
        'Option', 'Information about this option', 100, PARAM_INT));
}

Local plugin event handlers
---------------------------
Events are intended primarily for communication "core --> plugins".
(It should not be use in opposite direction!)
In theory it could be also used for "plugin --> plugin" communication too.
The list of core events is documented in lib/db/events.php

sample files
/local/nicehack/db/events.php
$handlers = array (
    'user_deleted' => array (
         'handlerfile'      => '/local/nicehack/lib.php',
         'handlerfunction'  => 'nicehack_userdeleted_handler',
         'schedule'         => 'instant'
     ),
);

NOTE: events are not yet fully implemented in current Moodle 2.0dev.


Local plugin database tables
----------------------------
XMLDB editors is the recommended tool. Please note that modification
of core table structure is highly discouraged.

If you really really really need to modify core tables you might want to do
that in install.php and later upgrade.php

Note: it is forbidden to manually modify the DB structure, without corresponding
      changes in install.xml files.

List of upgrade related files:
/local/nicehack/db/install.xml - contains XML definition of new tables
/local/nicehack/db/install.php - executed after db creation, may be also used
                                 for general install code
/local/nicehack/db/upgrade.php - executed when version changes


Local plugin web services
-------------------------
During plugin installation or upgrade, the web service definitions are read
from /local/nicehack/db/services.php and are automatically installed/updated in Moodle.

sample files
/local/nicehack/db/services.php
$$functions = array (
    'nicehack_hello_world' => array(
                'classname'   => 'local_nicehack_external',
                'methodname'  => 'hello_world',
                'classpath'   => 'local/nicehack/externallib.php',
                'description' => 'Get hello world string',
                'type'        => 'read',
    ),
);
$services = array(
        'Nice hack service 1' => array(
                'functions' => array ('nicehack_hello_world'),
                'enabled'=>1,
        ),
);


You will need to write the /local/nicehack/externallib.php - external functions
description and code. See some examples from the core files (/user/externallib.php,
/group/externallib.php...).

Local plugin navigation hooks
-----------------------------
There are two functions that your plugin can define that allow it to extend the main
navigation and the settings navigation.
These two functions both need to be defined within /local/nicehack/lib.php.

sample code
<?php
function local_nicehack_extend_navigation(global_navigation $nav) {
    // $nav is the global navigation instance.
    // Here you can add to and manipulate the navigation structure as you like.
    // This callback was introduced in 2.0 as nicehack_extends_navigation(global_navigation $nav)
    // In 2.3 support was added for local_nicehack_extends_navigation(global_navigation $nav).
    // In 2.9 the name was corrected to local_nicehack_extend_navigation() for consistency
}
function local_nicehack_extend_settings_navigation(settings_navigation $nav, context $context) {
    // $nav is the settings navigation instance.
    // $context is the context the settings have been loaded for (settings is context specific)
    // Here you can add to and manipulate the settings structure as you like.
    // This callback was introduced in 2.3, originally as local_nicehack_extends_settings_navigation()
    // In 2.9 the name was corrected to the imperative mood ('extend', not 'extends')
}

Other local customisation files
===============================

Customised site defaults
------------------------
Different default site settings can be stored in file /local/defaults.php.
These new defaults are used during installation, upgrade and later are
displayed as default values in admin settings. This means that the content
of the defaults files is usually updated BEFORE installation or upgrade.

These customised defaults are useful especially when using CLI tools
for installation and upgrade.

Sample /local/defaults.php file content:
<?php
$defaults['moodle']['forcelogin'] = 1;  // new default for $CFG->forcelogin
$defaults['scorm']['maxgrade'] = 20;    // default for get_config('scorm', 'maxgrade')
$defaults['moodlecourse']['numsections'] = 11;
$defaults['moodle']['hiddenuserfields'] = array('city', 'country');

First bracket contains string from column plugin of config_plugins table.
Second bracket is the name of setting. In the admin settings UI the plugin and
name of setting is separated by "|".

The values usually correspond to the raw string in config table, with the exception
of comma separated lists that are usually entered as real arrays.

Please note that not all settings are converted to admin_tree,
they are mostly intended to be set directly in config.php.


2.0 pre-upgrade script
----------------------
You an use /local/upgrade_pre20.php script for any code that needs to
be executed before the main upgrade to 2.0. Most probably this will
be used for undoing of old hacks that would otherwise break normal
2.0 upgrade.

This file is just included directly, there does not need to be any
function inside. If the execution stops the script is executed again
during the next upgrade. The first execution of lib/db/upgrade.php
increments the version number and the pre upgrade script is not
executed any more.



1.9.x upgrade notes
===================
1.9.x contains basic support for local hacks placed directly into
/local/ directory. This old local API was completely removed and can
not be used any more in 2.0. All old customisations need to be
migrated to new local plugins before running of the 2.0 upgrade script.



Other site customisation outside of "/local/" directory
=======================================================

Local language pack modifications
---------------------------------
Moodle supports other type of local customisation of standard language
packs. If you want to create your own language pack based on another
language create new dataroot directory with "_local" suffix, for example
following file with content changes string "Login" to "Sign in":
moodledata/lang/en_local
<?php
  $string['login'] = 'Sign in';

See also http://docs.moodle.org/en/Language_editing


Custom script injection
-----------------------
Very old customisation option that allows you to modify scripts by injecting
code right after the require 'config.php' call.

This setting is enabled by manually setting $CFG->customscripts variable
in config.php script. The value is expected to be full path to directory
with the same structure as dirroot. Please note this hack only affects
files that actually include the config.php!

Examples:
* disable one specific moodle page without code modification
* alter page parameters on the fly

  <!-- ── Externship Details ── -->
    <div class="lr-card lr-ext-card">
      <div class="lr-ext-title">Externship details</div>
      <div class="lr-ext-greeting">{{greeting}}</div>

      {{#has_sites}}
      <div class="lr-ext-body">
        <!-- Left: hours + details -->
        <div class="lr-ext-left">
          <!-- SVG progress circle -->
          <div style="margin-bottom:14px;">
            <div class="lr-progress-wrap" style="display:inline-block;">
              <svg width="120" height="120" viewBox="0 0 140 140">
                <circle cx="70" cy="70" r="60" stroke="#e5e7eb" stroke-width="12" fill="none"/>
                <circle cx="70" cy="70" r="60"
                  stroke="#0D9A00" stroke-width="12" fill="none"
                  stroke-linecap="round"
                  stroke-dasharray="{{ext_circ}}"
                  stroke-dashoffset="{{ext_offset}}"
                  transform="rotate(-90 70 70)"
                  style="transition:stroke-dashoffset 1s ease;"/>
              </svg>
              <div class="lr-progress-pct" style="font-size:22px;">{{ext_percent}}%</div>
            </div>
          </div>

          <!-- Required hours -->
          <div class="lr-hours-required">
            Total Extern Hours Required to Graduate
            <strong>{{total_required}}</strong>
          </div>

          <!-- Legend -->
          <div class="lr-legend">
            <div class="lr-legend-item">
              <div class="lr-legend-dot dot-orange"></div>
              Total Approved Hours Across all Sites: <strong>{{approved_hrs}}</strong>
            </div>
            <div class="lr-legend-item">
              <div class="lr-legend-dot dot-blue"></div>
              Total Pending Hours to be Approved by Employer: <strong>{{pending_hrs}}</strong>
            </div>
          </div>
        </div>

        <!-- Right: donut + site details -->
        <div>
          <!-- Donut chart -->
          <div class="lr-donut-wrap" style="margin-bottom:16px;">
            <svg width="160" height="160" viewBox="0 0 160 160">
              <circle cx="80" cy="80" r="60" fill="#f4f5f7"/>
              <!-- Approved arc (orange) -->
              <circle cx="80" cy="80" r="60"
                stroke="#e07b00" stroke-width="24" fill="none"
                stroke-dasharray="{{donut_approved}} {{donut_circ}}"
                stroke-dashoffset="0"
                transform="rotate(-90 80 80)"/>
              <!-- Pending arc (navy) -->
              <circle cx="80" cy="80" r="60"
                stroke="#1a2a4a" stroke-width="24" fill="none"
                stroke-dasharray="{{donut_pending}} {{donut_circ}}"
                stroke-dashoffset="-{{donut_approved}}"
                transform="rotate(-90 80 80)"/>
              <text x="80" y="76" text-anchor="middle" font-size="22" font-weight="700" fill="#1a2a4a">{{site_count}}</text>
              <text x="80" y="94" text-anchor="middle" font-size="12" fill="#888">Sites</text>
            </svg>
          </div>

          <!-- Site details -->
          <div style="font-size:13px;font-weight:700;color:#1a2a4a;margin-bottom:10px;">EXTERNSHIP DETAILS:</div>
          {{#sites}}
          <div style="margin-bottom:12px;">
            <div class="lr-site-detail">
              <i class="ti ti-building" style="color:#1a2a4a;"></i>
              <div><strong>Location/Company Name:</strong> {{companyname}}</div>
            </div>
            <div class="lr-site-detail">
              <i class="ti ti-map-pin" style="color:#e03c3c;"></i>
              <div><strong>Address:</strong> {{address}}</div>
            </div>
            <div class="lr-site-detail">
              <i class="ti ti-phone" style="color:#1faa6b;"></i>
              <div><strong>Phone:</strong> {{phone}}</div>
            </div>
            <div class="lr-site-detail">
              <i class="ti ti-user-check" style="color:#e07b00;"></i>
              <div><strong>Supervisor:</strong> {{supervisor}}</div>
            </div>
            <div class="lr-site-detail">
              <i class="ti ti-calendar" style="color:#4db89a;"></i>
              <div><strong>Start Date:</strong> {{startdate}}</div>
            </div>
          </div>
          {{/sites}}
        </div>
      </div>
      {{/has_sites}}

      {{^has_sites}}
      <div class="lr-nosite">
        <div class="lr-nosite-icon">
          <i class="ti ti-file-off"></i>
        </div>
        <p style="font-size:13px;color:#888;">No externship site assigned yet.</p>
      </div>
      {{/has_sites}}
    </div>

    <!-- ── Timesheet Details ── -->
    <div class="lr-card lr-ts-card">
      <div class="lr-ts-title">Timesheet Details</div>
      <div class="lr-table-wrap">
        <table class="lr-table">
          <thead>
            <tr>
              <th>Extern Date</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Attend Hrs</th>
              <th>Sched Hrs</th>
              <th>Status</th>
              {{#ismanager}}<th>Action</th>{{/ismanager}}
            </tr>
          </thead>
          <tbody id="lr-ts-body">
            {{#has_timesheets}}
            {{#timesheets}}
            <tr id="lr-row-{{id}}">
              <td>{{externdate}}</td>
              <td>{{starttime}}</td>
              <td>{{endtime}}</td>
              <td>
                <span id="lr-hrs-{{id}}">{{attendhrs}}</span>
                {{^approved}}
                <i class="ti ti-pencil lr-edit-btn" onclick="lrEditHrs({{id}},{{attendhrs_raw}})"
                   title="Edit"></i>
                {{/approved}}
              </td>
              <td>{{schedhrs}}</td>
              <td>
                <span id="lr-status-{{id}}" class="ts-status ts-{{status_class}}">{{status}}</span>
              </td>
              {{#ismanager}}
              <td>
                {{^approved}}
                <button class="lr-btn-approve" onclick="lrAction({{id}},'Approved')">Approve</button>
                <button class="lr-btn-reject"  onclick="lrAction({{id}},'Rejected')">Reject</button>
                {{/approved}}
                {{#approved}}<span style="font-size:12px;color:#aaa;">Done</span>{{/approved}}
              </td>
              {{/ismanager}}
            </tr>
            {{/timesheets}}
            {{/has_timesheets}}
            {{^has_timesheets}}
            <tr><td colspan="7" class="lr-no-ts">No timesheet records found.</td></tr>
            {{/has_timesheets}}
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="lr-pagination-wrap">
        <div class="lr-pagination-info">
          Showing {{ts_from}} to {{ts_to}} of {{ts_total}} result
        </div>
        <div class="lr-pagination">
          <a href="{{prev_url}}" class="lr-page-btn {{^has_prev}}disabled{{/has_prev}}">
            <i class="ti ti-chevron-left"></i>
          </a>
          {{#page_links}}
          <a href="{{url}}" class="lr-page-btn {{#active}}active{{/active}}">{{num}}</a>
          {{/page_links}}
          <a href="{{next_url}}" class="lr-page-btn {{^has_next}}disabled{{/has_next}}">
            <i class="ti ti-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>

  </div><!-- /#lr-content -->
</div><!-- /.lr-wrap -->

<!-- ══════════════════════════════════════════
     ADD ENTRY MODAL
     ══════════════════════════════════════════ -->
<div class="lr-overlay" id="lr-modal-overlay">
  <div class="lr-modal">
    <div class="lr-modal-header">
      <div class="lr-modal-title">Add Externship Entry</div>
      <button class="lr-modal-close" onclick="lrCloseModal()">✕</button>
    </div>

    <!-- Tabs -->
    <div class="lr-nav-tabs">
      {{^has_site_modal}}
      <button class="lr-nav-tab active" onclick="lrTab(this,'lr-tab-site')">Site Info</button>
      {{/has_site_modal}}
      <button class="lr-nav-tab {{#has_site_modal}}active{{/has_site_modal}}"
              onclick="lrTab(this,'lr-tab-ts')">Timesheet</button>
    </div>

    <!-- Site tab -->
    {{^has_site_modal}}
    <div class="lr-tab-pane active" id="lr-tab-site">
      <form method="post" action="{{wwwroot}}/local/result/save_entry.php">
        <input type="hidden" name="userid"    value="{{selected_userid}}">
        <input type="hidden" name="entrytype" value="site">
        <input type="hidden" name="sesskey"   value="{{sesskey}}">
        <div class="lr-form-group">
          <label>Company Name *</label>
          <input type="text" name="companyname" class="lr-form-control" required>
        </div>
        <div class="lr-form-group">
          <label>Address</label>
          <textarea name="address" class="lr-form-control"></textarea>
        </div>
        <div class="lr-form-group">
          <label>Phone</label>
          <input type="text" name="phone" class="lr-form-control">
        </div>
        <div class="lr-form-group">
          <label>Supervisor</label>
          <input type="text" name="supervisor" class="lr-form-control">
        </div>
        <div class="lr-form-group">
          <label>User Type</label>
          <select name="usertype" class="lr-form-control">
            <option value="internal">Internal User</option>
            <option value="external">External User</option>
          </select>
        </div>
        <div class="lr-form-group">
          <label>Start Date *</label>
          <input type="date" name="startdate" class="lr-form-control" required>
        </div>
        <div class="lr-modal-footer">
          <button type="button" class="lr-btn-cancel" onclick="lrCloseModal()">Cancel</button>
          <button type="submit" class="lr-btn-save">Save & Next</button>
        </div>
      </form>
    </div>
    {{/has_site_modal}}

    <!-- Timesheet tab -->
    <div class="lr-tab-pane {{#has_site_modal}}active{{/has_site_modal}}" id="lr-tab-ts">
      <form method="post" action="{{wwwroot}}/local/result/save_entry.php">
        <input type="hidden" name="userid"    value="{{selected_userid}}">
        <input type="hidden" name="entrytype" value="timesheet">
        <input type="hidden" name="siteid"    value="{{first_site_id}}">
        <input type="hidden" name="sesskey"   value="{{sesskey}}">
        <div class="lr-form-group">
          <label>Date *</label>
          <input type="date" name="externdate" class="lr-form-control" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div class="lr-form-group">
            <label>Start Time *</label>
            <input type="time" name="starttime" class="lr-form-control" required>
          </div>
          <div class="lr-form-group">
            <label>End Time *</label>
            <input type="time" name="endtime" class="lr-form-control" required>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div class="lr-form-group">
            <label>Attend Hours *</label>
            <input type="number" step="0.01" name="attendhrs" class="lr-form-control" required>
          </div>
          <div class="lr-form-group">
            <label>Sched Hours *</label>
            <input type="number" step="0.01" name="schedhrs" class="lr-form-control" required>
          </div>
        </div>
        <div class="lr-modal-footer">
          <button type="button" class="lr-btn-cancel" onclick="lrCloseModal()">Cancel</button>
          <button type="submit" class="lr-btn-save">Save Timesheet</button>
        </div>
      </form>
    </div>

  </div>
</div>
