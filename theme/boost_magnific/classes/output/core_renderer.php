<?php
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
 * core_renderer.php
 *
 * @package     theme_boost_magnific
 * @copyright   2024 Eduardo kraus (http://eduardokraus.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_magnific\output;
//use core_renderer;
use moodle_url;
use user_picture;
use context_system;
use custom_menu;
use custom_menu_item;
use html_writer;


class core_renderer extends \theme_boost\output\core_renderer {
      public function render_login(\core_auth\output\login $form) {

        $context = $form->export_for_template($this);

        // ── Remove "Is this your first time here?" signup panel ──
        $context->cansignup       = false;
        $context->hasinstructions = false;
        $context->instructions    = '';

        // ── Keep identity providers (Google etc) but they will be
        //    styled small by our CSS overrides in login.mustache ──

        // Render using theme/YOUR_THEME/templates/core/login.mustache
        return $this->render_from_template('core/login', $context);
    }
// public function custom_creation_menu() {
//     global $CFG;

//     return '
//     <div class="custom-dashboard-menu">

//         <ul class="list-unstyled">
//             <li><a href="'.$CFG->wwwroot.'/course/edit.php?category=1">Home</a></li>
//             <li><a href="'.$CFG->wwwroot.'/local/course_ai/index.php">Dashboard</a></li>
//         </ul>
//     </div>';
// }
public function custom_header(): string {
        global $CFG, $USER, $PAGE, $SITE;
 
        // Skip on login / maintenance only
        if (in_array($PAGE->pagelayout, ['login', 'maintenance'])) {
            return '';
        }
 
        $isediting = $PAGE->user_is_editing();
        $editurl   = new moodle_url(
            $PAGE->url,
            ['sesskey' => sesskey(), 'edit' => $isediting ? 0 : 1]
        );
 
        // Avatar
        $initials          = strtoupper(
            substr($USER->firstname, 0, 1) . substr($USER->lastname, 0, 1)
        );
        $userpicture       = new user_picture($USER);
        $userpicture->size = 44;
        $avatarurl         = $userpicture->get_url($PAGE)->out(false);
 
        // Logo
        $logourl = '';
        if (!empty($this->page->theme->settings->logo_color)) {
            $logourl = $this->page->theme->setting_file_url('logo_color', 'logo_color');
        }
 
        $notif_page_url = (new moodle_url('/local/notifications/index.php'))->out(false);
        $profile_url    = (new moodle_url('/user/profile.php', ['id' => $USER->id]))->out(false);
        $logout_url     = (new moodle_url('/login/logout.php', ['sesskey' => sesskey()]))->out(false);
 
        ob_start();
        ?>
<style>
/* ════════════════════════════════════════════════
   CUSTOM HEADER — matches screenshot exactly
   ════════════════════════════════════════════════ */
 
/* Reset Moodle's default navbar on this layout */
.navbar, #nav-drawer { display: none !important; }
 
.custom-header {
       display: flex;
    align-items: center;
    height: 72px;
    background: #f7f8fa;
    /* border-bottom: 1px solid #e5e7eb; */
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    /* box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06); */
    box-sizing: border-box;
    padding: 0;
    padding: 12px 12px 12px 12px;
gap: 12px;
}
 
/* ── LEFT: Logo box — separated with border-right ── */
.ch-logo-box {
    display         : flex;
    align-items     : center;
    justify-content : center;
    /* same width as the sidebar so it lines up perfectly */
    width           : 241px;
    min-width       : 241px;
    height          : 100%;
    padding         : 0 20px;
    text-decoration : none;
    box-sizing      : border-box;
    flex-shrink     : 0;
     background: #fff;
     height: 60px;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06);
    align-items: center;
}
.ch-space{
 background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06);
    align-items: center;  
    height: 60px; 
}
.ch-logo-box img {
    max-height : 46px;
    max-width  : 180px;
    width      : auto;
    height     : auto;
    object-fit : contain;
}
.ch-logo-name {
    font-size   : 16px;
    font-weight : 700;
    color       : #1a2a4a;
    white-space : nowrap;
}
 
/* ── CENTER: Search ── */
.ch-search {
    flex       : 1;
    max-width  : 500px;
    margin     : 0 32px;
    position   : relative;
}
.ch-search input {
    width         : 75%;
    height        : 42px;
    border        : unset;
    border-radius : 50px;
    padding       : 0 18px 0 44px;
    font-size     : 14px;
    background    : #f7f8fa;
    color         : #333;
    outline       : none;
    box-sizing    : border-box;
    transition    : border-color .2s, background .2s;
}
.ch-search input:focus {
    border-color : #1a2a4a;
    background   : #fff;
}
.ch-search input::placeholder { color: #b0b5be; }
.ch-search .ch-search-icon {
    position   : absolute;
    left       : 16px;
    top        : 50%;
    transform  : translateY(-50%);
    color      : #9aa0a6;
    font-size  : 16px;
    pointer-events: none;
    background: white;
    border-radius: 50%;
}
 
/* ── RIGHT: icons + avatar ── */
.ch-right {
    display     : flex;
    align-items : center;
    gap         : 12px;
    padding     : 0 24px 0 0;
    margin-left : auto;
    flex-shrink : 0;
}
 
/* Bell button — navy filled circle (matches screenshot) */
.ch-bell-btn {
    position        : relative;
    width           : 42px;
    height          : 42px;
    border-radius   : 50%;
    background      : #1a2a4a;        /* navy fill like screenshot */
    border          : none;
    display         : flex;
    align-items     : center;
    justify-content : center;
    cursor          : pointer;
    transition      : background .15s;
    flex-shrink     : 0;
    padding         : 0;
}
.ch-bell-btn:hover { background: #263660; }
.ch-bell-btn i { font-size: 20px; color: #fff; }
 
/* Bell badge */
.ch-badge {
    position      : absolute;
    top           : 2px;
    right         : 2px;
    background    : #e74c3c;
    color         : #fff;
    font-size     : 9px;
    font-weight   : 700;
    border-radius : 50px;
    min-width     : 16px;
    height        : 16px;
    padding       : 0 3px;
    display       : flex;
    align-items   : center;
    justify-content: center;
    border        : 2px solid #fff;
    line-height   : 1;
}
 
/* Avatar + name — NO dropdown, just a link to profile */
.ch-user-link {
    display         : flex;
    align-items     : center;
    gap             : 10px;
    text-decoration : none;
    padding         : 4px 8px;
    border-radius   : 50px;
    transition      : background .15s;
}
.ch-user-link:hover { background: #f7f8fa; }
 
.ch-avatar {
    width        : 42px;
    height       : 42px;
    border-radius: 50%;
    object-fit   : cover;
    border       : 2px solid #e5e7eb;
    flex-shrink  : 0;
    background   : #1a2a4a;
}
.ch-avatar-initials {
    width           : 42px;
    height          : 42px;
    border-radius   : 50%;
    background      : #1a2a4a;
    color           : #fff;
    font-size       : 15px;
    font-weight     : 600;
    display         : flex;
    align-items     : center;
    justify-content : center;
    flex-shrink     : 0;
}
.ch-user-info { line-height: 1.3; }
.ch-user-name {
    font-size   : 14px;
    font-weight : 600;
    color       : #1a2a4a;
    display     : block;
    white-space : nowrap;
}
.ch-user-email {
    font-size  : 12px;
    color      : #8a8f98;
    display    : block;
    white-space: nowrap;
}
 
/* Edit toggle icon button */
.ch-icon-btn {
    position        : relative;
    width           : 38px;
    height          : 38px;
    border-radius   : 50%;
    background      : #f7f8fa;
    border          : 1px solid #e5e7eb;
    display         : flex;
    align-items     : center;
    justify-content : center;
    cursor          : pointer;
    text-decoration : none;
    color           : #1a2a4a;
    transition      : background .15s;
    flex-shrink     : 0;
}
.ch-icon-btn:hover { background: #eef0f4; }
.ch-icon-btn i { font-size: 18px; color: #1a2a4a; }
 
/* ── Notification dropdown ── */
.ch-notif-wrapper { position: relative; }
 
#ch-notif-panel {
    display       : none;
    position      : absolute;
    top           : calc(100% + 10px);
    right         : 0;
    width         : 380px;
    background    : #ffffff;
    border        : 1px solid #e5e7eb;
    border-radius : 20px;
    box-shadow    : 0 20px 60px rgba(0,0,0,0.15);
    z-index       : 10001;
    overflow      : hidden;
    animation     : np-drop-in .18s ease;
}
#ch-notif-panel.open { display: block; }
 
@keyframes np-drop-in {
    from { opacity:0; transform:translateY(10px); }
    to   { opacity:1; transform:translateY(0); }
}
 
/* ── Panel header ── */
.np-header {
    display         : flex;
    align-items     : center;
    justify-content : space-between;
    padding         : 18px 20px 0;
}
.np-title { font-size:17px; font-weight:700; color:#1a2a4a; }
.np-close-btn {
    width:32px; height:32px; border-radius:50%;
    border:1px solid #e5e7eb; background:#fff;
    cursor:pointer; font-size:16px; color:#555;
    display:flex; align-items:center; justify-content:center;
    transition:background .15s;
}
.np-close-btn:hover { background:#f7f8fa; }
 
/* ── Tabs — matches Figma: ALL underlined, Unread with count badge ── */
.np-tabs {
    display     : flex;
    gap         : 0;
    padding     : 14px 20px 0;
    border-bottom: 1px solid #eef0f4;
    margin-top  : 4px;
    
    height: 30px;
}
.np-tab-btn {
    padding         : 8px 4px;
    margin-right    : 24px;
    font-size       : 15px;
    font-weight     : 600;
    color           : #aab0bc;
    border          : none;
    background      : none;
    cursor          : pointer;
    border-bottom   : 2px solid transparent;
    margin-bottom   : -1px;
    display         : flex;
    align-items     : center;
    gap             : 7px;
    transition      : color .15s;
       text-transform: capitalize;
    letter-spacing  : 0.02em;
    width: 50%;
    justify-content: center;
}
.np-tab-btn.active {
    color        : #1a2a4a;
    border-bottom: 2px solid #1a2a4a;
    background: #ffff!important;
}
.np-tab-count {
    background: #e5e7eb;
    color: #555;
    font-size: 12px;
    font-weight: 700;
    border-radius: 8px;
    padding: 1px 6px;
    min-width: 22px;
    height: 20px;
    text-align: center;
    /* height: 39px; */
    display: flex;
    justify-content: center;
    align-items: center;
}
.np-tab-btn.active .np-tab-count { background:#1a2a4a; color:#fff; }
 
/* ── Notification list ── */
.np-list {
    max-height  : 400px;
    overflow-y  : auto;
    padding     : 6px 0;
}
.np-list::-webkit-scrollbar { width:3px; }
.np-list::-webkit-scrollbar-thumb { background:#e5e7eb; border-radius:3px; }
 
/* ── Single notification item — Figma layout ── */
.np-item {
    display         : flex;
    align-items     : flex-start;
    gap             : 14px;
    padding         : 14px 20px;
    cursor          : pointer;
    transition      : background .12s;
    text-decoration : none;
    color           : inherit;
    border-bottom   : 1px solid #f4f5f7;
}
.np-item:last-child { border-bottom: none; }
.np-item:hover { background: #f9fafb; }
 
/* Icon tile — rounded square with colored bg */
.np-icon-tile {
    width           : 44px;
    height          : 44px;
    border-radius   : 12px;
    display         : flex;
    align-items     : center;
    justify-content : center;
    flex-shrink     : 0;
    font-size       : 20px;
}
 
/* Icon colors matching Figma exactly */
.np-icon-enrolment         { background:#fff4e0; color:#e07b00; }
.np-icon-assignment        { background:#fff0f0; color:#e03c3c; }
.np-icon-pending_assignment{ background:#fff0f0; color:#e03c3c; }
.np-icon-event             { background:#eafaf3; color:#1faa6b; }
.np-icon-gmeet             { background:#eef0f4; color:#1a2a4a; }
.np-icon-approval          { background:#eafaf3; color:#1faa6b; }
.np-icon-pending_quiz      { background:#ede9fe; color:#7c3aed; }
.np-icon-incomplete_module { background:#f3f4f6; color:#6b7280; }
.np-icon-default           { background:#eef0f4; color:#1a2a4a; }
 
/* Item body */
.np-item-body { flex:1; min-width:0; }
.np-item-top {
    display         : flex;
    align-items     : flex-start;
    justify-content : space-between;
    gap             : 8px;
    margin-bottom   : 3px;
}
.np-item-msg {
    font-size   : 13px;
    color       : #1a2a4a;
    line-height : 1.5;
    font-weight : 400;
}
.np-item-time {
    font-size   : 11px;
    color       : #aab0bc;
    white-space : nowrap;
    flex-shrink : 0;
    margin-top  : 2px;
}
.np-item-view {
    font-size       : 13px;
    font-weight     : 600;
    color           : #1a2a4a;
    text-decoration : none;
    display         : inline-block;
    margin-top      : 2px;
}
.np-item-view:hover { text-decoration: underline; }
 
/* ── Empty state ── */
.np-empty {
    text-align : center;
    padding    : 36px 20px;
    color      : #aab0bc;
    font-size  : 13px;
}
.np-empty i { font-size:32px; display:block; margin-bottom:10px; }
 
/* ── Footer ── */
.np-footer {
    padding     : 14px 20px;
    border-top  : 1px solid #eef0f4;
}
.np-viewall-btn {
    display         : inline-flex;
    align-items     : center;
    gap             : 10px;
    background      : #1a2a4a;
    color           : #fff;
    border          : none;
    border-radius   : 50px;
    padding         : 11px 22px;
    font-size       : 14px;
    font-weight     : 600;
    cursor          : pointer;
    text-decoration : none;
    transition      : background .15s;
}
.np-viewall-btn:hover { background: #263660; color:#fff; }
.np-viewall-arrow {
    width           : 26px;
    height          : 26px;
    border-radius   : 50%;
    background      : rgba(255,255,255,0.15);
    display         : flex;
    align-items     : center;
    justify-content : center;
}
 
/* ── Skeleton shimmer ── */
@keyframes np-shimmer {
    0%   { background-position:-400px 0; }
    100% { background-position: 400px 0; }
}
.np-skel {
    border-radius    : 6px;
    background       : linear-gradient(90deg,#e8eaed 25%,#f5f5f5 50%,#e8eaed 75%);
    background-size  : 400px 100%;
    animation        : np-shimmer 1.2s infinite linear;
    display          : block;
}
.np-sk-row  { display:flex; align-items:center; gap:14px; padding:14px 20px; border-bottom:1px solid #f4f5f7; }
.np-sk-icon { width:44px; height:44px; border-radius:12px; flex-shrink:0; }
.np-sk-body { flex:1; display:flex; flex-direction:column; gap:8px; }
.np-sk-line { height:13px; border-radius:4px; }
 
@media (max-width:640px) {
    #ch-notif-panel { width:calc(100vw - 20px); right:-50px; }
}
</style>
 
<header id="" class="custom-header">
 
    <!-- ── LEFT: Logo (separated box) ── -->
    <a href="<?= $CFG->wwwroot ?>" class="ch-logo-box">
        <?php if (!empty($logourl)): ?>
            <img src="<?= $logourl ?>" alt="<?= s($SITE->fullname) ?>">
        <?php else: ?>
            <span class="ch-logo-name"><?= format_string($SITE->fullname) ?></span>
        <?php endif; ?>
    </a>
 
    <!-- ── CENTER: Search ── -->
     <div class="ch-space d-flex w-100" >
    <div class="ch-search">
        <i class="ti ti-search ch-search-icon"></i>
        <input type="text" placeholder="Search ..."
               onkeydown="if(event.key==='Enter'){
                   window.location='<?= $CFG->wwwroot ?>/course/search.php?search='
                   +encodeURIComponent(this.value);}">
    </div>
 
    <!-- ── RIGHT: Bell + Avatar ── -->
    <div class="ch-right">
 
        <!-- Bell — navy circle, opens notification dropdown -->
        <div class="ch-notif-wrapper">
            <button class="ch-bell-btn" id="ch-bell-btn" title="Notifications">
                <i class="ti ti-bell"></i>
                <span class="ch-badge" id="ch-notif-badge" style="display:none;">0</span>
            </button>
 
            <!-- Dropdown panel -->
            <div id="ch-notif-panel" role="dialog" aria-label="Notifications">
 
    <!-- Header -->
    <div class="np-header">
        <span class="np-title">Notification</span>
        <button class="np-close-btn" id="ch-notif-close" aria-label="Close">✕</button>
    </div>
 
    <!-- Tabs: ALL | Unread -->
    <div class="np-tabs">
        <button class="np-tab-btn active" id="np-tab-all"
                onclick="npSwitchTab('all')">All</button>
        <button class="np-tab-btn" id="np-tab-unread"
                onclick="npSwitchTab('unread')">
            Unread
            <span class="np-tab-count" id="np-unread-ct">0</span>
        </button>
    </div>
 
    <!-- Skeleton (shown while loading) -->
    <div id="np-skeleton" class="np-list">
        <?php for($i=0;$i<5;$i++): ?>
        <div class="np-sk-row">
            <div class="np-skel np-sk-icon"></div>
            <div class="np-sk-body">
                <div class="np-skel np-sk-line" style="width:<?= [88,70,80,60,75][$i] ?>%;"></div>
                <div class="np-skel np-sk-line" style="width:30%;"></div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
 
    <!-- Real lists (hidden until loaded) -->
    <div id="np-list-all"    class="np-list" style="display:none;"></div>
    <div id="np-list-unread" class="np-list" style="display:none;"></div>
 
    <!-- Footer -->
    <div class="np-footer">
        <a href="<?= $notif_page_url ?>" class="np-viewall-btn">
            View all
            <span class="np-viewall-arrow">
                <i class="ti ti-arrow-right" style="font-size:14px;"></i>
            </span>
        </a>
    </div>
</div>
 
        </div>
 
        <!-- Avatar + Name — direct link to profile, NO dropdown -->
         <?php if (has_capability('moodle/course:update', context_system::instance())): 
            $modal1 = 'custom-user-trigger';
            $iconnone="inline-flex";
         else:
            $iconnone="none";
             endif; ?>
  
<div class="ch-user-wrapper">
    <a href="#"
       id="<?php echo $modal1; ?>"
       class="ch-user ch-user-link">

        <?php if ($avatarurl): ?>
            <img src="<?php echo $avatarurl; ?>"
                 alt="<?php echo fullname($USER); ?>"
                 class="ch-avatar">
        <?php else: ?>
            <div class="ch-avatar-initials">
                <?php echo $initials; ?>
            </div>
        <?php endif; ?>

        <div class="ch-user-info">
            <span class="ch-user-name">
                <?php echo fullname($USER); ?>
            </span>

            <span class="ch-user-role">
                <?php echo s($USER->email); ?>
            </span>
        </div>

        <i class="fa fa-chevron-down header-chevron" style="<?php echo 'display:' . $iconnone; ?>"></i>
    </a>

    <?php echo $this->custom_user_menu_items(); ?>

        </div>
        <!-- Edit toggle (only for those with edit capability) -->
        <?php if (has_capability('moodle/course:update', context_system::instance())): ?>
        <a href="<?= $editurl->out(false) ?>" class="ch-icon-btn"
           title="<?= $isediting ? 'Turn Editing Off' : 'Turn Editing On' ?>">
            <i class="fa <?= $isediting ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
        </a>
        <?php endif; ?>
 
    </div>
    </div>
</header>
 
<script>
document.addEventListener('DOMContentLoaded', function() {

    const trigger = document.getElementById('custom-user-trigger');
    const menu = document.getElementById('custom-user-dropdown-menu');

    if (!trigger || !menu) {
        return;
    }

    trigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        menu.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {

        if (!e.target.closest('.custom-user-menu-wrapper')) {
            menu.classList.remove('show');
        }
    });

});
(function(){
'use strict';
 
/* ── Icon config — matches Figma colours exactly ── */
const NOTIF_CFG = {
    enrolment         : { icon:'ti-book',            cls:'np-icon-enrolment'          },
    assignment        : { icon:'ti-clipboard-text',  cls:'np-icon-assignment'         },
    pending_assignment: { icon:'ti-clipboard-text',  cls:'np-icon-pending_assignment' },
    event             : { icon:'ti-calendar-event',  cls:'np-icon-event'              },
    gmeet             : { icon:'ti-video',            cls:'np-icon-gmeet'              },
    approval          : { icon:'ti-check',    cls:'np-icon-approval'           },
    pending_quiz      : { icon:'ti-clipboard-list',  cls:'np-icon-pending_quiz'       },
    incomplete_module : { icon:'ti-books',            cls:'np-icon-incomplete_module'  },
};
function getCfg(type){ return NOTIF_CFG[type] || { icon:'ti-bell', cls:'np-icon-default' }; }
 
var allNotifs = [], unreadNotifs = [], loaded = false, currentTab = 'all';
 
/* ── Render one item — Figma layout ── */
function renderItem(n) {
    var cfg = getCfg(n.type);
    var msg = (n.message || '').replace(/^[⚠️📋📚🎉]\s*/u, '');
 
    var viewLink = n.url
        ? '<a href="' + n.url + '" class="np-item-view" onclick="event.stopPropagation()">View</a>'
        : '';
 
    // ✅ ONE single <div> wraps BOTH icon AND body — not separate siblings
    return '<div class="np-item">'
 
        // Icon tile
        + '<div class="np-icon-tile ' + cfg.cls + '">'
        +   '<i class="ti ' + cfg.icon + '"></i>'
        + '</div>'
 
        // Body (text + time + view link)
        + '<div class="np-item-body">'
        +   '<div class="np-item-top">'
        +     '<div class="np-item-msg">' + msg + '</div>'
        +     '<div class="np-item-time">' + (n.time || '') + '</div>'
        +   '</div>'
        +   viewLink
        + '</div>'
 
        + '</div>'; // ← close np-item
}
 
/* ── Render list into element ── */
function renderList(el, items) {
    if (!items || !items.length) {
        el.innerHTML = '<div class="np-empty">'
            + '<i class="ti ti-bell-off"></i>'
            + 'No notifications</div>';
    } else {
        el.innerHTML = items.slice(0, 6).map(renderItem).join('');
    }
}
 
/* ── Show the correct tab list ── */
function showTab(tab) {
    document.getElementById('np-skeleton').style.display    = 'none';
    document.getElementById('np-list-all').style.display    = tab === 'all'    ? 'block' : 'none';
    document.getElementById('np-list-unread').style.display = tab === 'unread' ? 'block' : 'none';
 
    // Active tab styling
    document.getElementById('np-tab-all').classList.toggle('active',    tab === 'all');
    document.getElementById('np-tab-unread').classList.toggle('active', tab === 'unread');
}
 
/* ── Tab switch ── */
window.npSwitchTab = function(tab) {
    currentTab = tab;
    if (loaded) showTab(tab);
};
 
/* ── Fetch from API ── */
function loadNotifications() {
    if (loaded) return;
 
    // Show skeleton while loading
    document.getElementById('np-skeleton').style.display    = 'block';
    document.getElementById('np-list-all').style.display    = 'none';
    document.getElementById('np-list-unread').style.display = 'none';
 
    fetch('<?= $CFG->wwwroot ?>/local/notifications/api.php', {
        credentials: 'same-origin'
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        allNotifs    = data.notifications || [];
        var unreadCt = parseInt(data.unread_count) || 0;
        unreadNotifs = allNotifs.slice(0, unreadCt);
 
        // Badge on bell
        var badge = document.getElementById('ch-notif-badge');
        if (allNotifs.length) {
            badge.textContent    = allNotifs.length > 99 ? '99+' : allNotifs.length;
            badge.style.display  = 'flex';
        } else {
            badge.style.display = 'none';
        }
 
        // Unread count on tab
        document.getElementById('np-unread-ct').textContent = unreadCt;
 
        // Render both lists
        renderList(document.getElementById('np-list-all'),    allNotifs);
        renderList(document.getElementById('np-list-unread'), unreadNotifs);
 
        loaded = true;
        showTab(currentTab);
    })
    .catch(function() {
        document.getElementById('np-skeleton').innerHTML =
            '<div class="np-empty"><i class="ti ti-wifi-off"></i>Failed to load</div>';
    });
}
 
/* ── Bell toggle ── */
var bell  = document.getElementById('ch-bell-btn');
var panel = document.getElementById('ch-notif-panel');
 
bell.addEventListener('click', function(e) {
    e.stopPropagation();
    var isOpen = panel.classList.toggle('open');
    if (isOpen) loadNotifications();
});
 
document.getElementById('ch-notif-close').addEventListener('click', function() {
    panel.classList.remove('open');
});
 
/* ── Close on outside click ── */
document.addEventListener('click', function(e) {
    if (!e.target.closest('.ch-notif-wrapper')) {
        panel.classList.remove('open');
    }
});
 
/* ── Close on Escape ── */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') panel.classList.remove('open');
});
 
}());
</script>
        <?php
        return ob_get_clean();
    }

    // lecture panel
public function custom_creation_menu() {
    global $CFG, $USER, $PAGE;

$isteacher = has_capability(
    'moodle/course:update',
    \context_system::instance()
) && !is_siteadmin();

    $currenturl = $PAGE->url->out(false);
 
    // Helper: build a nav item
    // $url, $label, $icon (Tabler icon name), $active (bool)
    $item = function($url, $label, $icon, $active = false) {
        $activeStyle = $active
            ? 'background:#4db89a;'
            : 'background:transparent;';
        $textColor   = $active ? '#ffffff' : '#a0b8d8';
        $iconBg      = $active
            ? 'background:rgba(255,255,255,0.18);'
            : 'background:rgba(255,255,255,0.08);';
 
        return '
        <li>
            <a href="' . $url . '"
               class="nav-item' . ($active ? ' active' : '') . '"
               style="display:flex;align-items:center;gap:12px;
                      padding:5px 9px;border-radius:50px;
                      text-decoration:none;transition:background 0.15s;
                      ' . $activeStyle . '"
               onmouseover="if(!this.classList.contains(\'active\')){this.style.background=\'rgba(255,255,255,0.07)\';}"
               onmouseout="if(!this.classList.contains(\'active\')){this.style.background=\'transparent\';}">
                <span style="width:36px;height:36px;border-radius:50%;
                             display:flex;align-items:center;justify-content:center;flex-shrink:0;
                             ' . $iconBg . '">
                    <i class="ti ti-' . $icon . '"
                       style="font-size:18px;color:' . $textColor . ';"
                       aria-hidden="true"></i>
                </span>
                <span style="font-size:15px;font-weight:' . ($active ? '500' : '400') . ';
                             color:' . $textColor . ';white-space:nowrap;">
                    ' . $label . '
                </span>
            </a>
        </li>';
    };
 
    // Detect which page is active
    $isDashboard   = strpos($currenturl, '/my') !== false
                     || strpos($currenturl, 'local/course_ai') !== false;
    $isCourses     = strpos($currenturl, '/course') !== false
                     && strpos($currenturl, 'edit.php') === false;
    $isResult      = strpos($currenturl, '/dashboard/result') !== false;
    $isGrades      = strpos($currenturl, '/grade') !== false;
    $isAttendance  = strpos($currenturl, '/local/attendance') !== false;
    $isSchedule    = strpos($currenturl, '/local/schedule') !== false;
    $isProfile     = strpos($currenturl, '/user/profile') !== false;
    $isSettings    = strpos($currenturl, '/user/edit') !== false;
    $isMessages    = strpos($currenturl, '/local/messages') !== false;
    $isAdmin = strpos($currenturl, '/admin/') !== false;
    $isQuizReport  = strpos($currenturl, '/local/incourse/quiz_report.php') !== false;
$isForumReport = strpos($currenturl, '/local/incourse/forum_grade.php') !== false;
 
    ob_start();
    ?>
    <style>
    /* ── Sidebar wrapper ── */
    .custom-dashboard-menu {
        background    : #1a2a4a;
        border-radius : 16px;
        padding       : 6px 14px;
        width         : 240px;
        min-height    : 100%;
        box-sizing    : border-box;
        font-family   : -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
 
    /* Section label (MENU / GENERAL) */
    .custom-dashboard-menu .menu-section-label {
        color          : #6b89b8;
        font-size      : 11px;
        font-weight    : 600;
        letter-spacing : 0.08em;
        text-transform : uppercase;
        margin         : 0 0 10px 10px;
        display        : block;
    }
 
    /* Nav list reset */
    .custom-dashboard-menu ul {
        list-style : none;
        padding    : 0;
        margin     : 0 0 8px 0;
        display    : flex;
        flex-direction : column;
        gap        : 3px;
    }
 
    /* Active pill — always teal */
    .custom-dashboard-menu .nav-item.active {
        background : #4db89a !important;
    }
 
    /* Hover for inactive items */
    .custom-dashboard-menu .nav-item:not(.active):hover {
        background : rgba(255,255,255,0.07) !important;
    }
 
    /* Divider between sections */
    .custom-dashboard-menu .menu-divider {
        border     : none;
        border-top : 1px solid rgba(255,255,255,0.07);
        margin     : 14px 0;
    }
 
    /* Responsive: collapse to icon-only on narrow sidebars */
    @media (max-width: 200px) {
        .custom-dashboard-menu { width: 68px; padding: 20px 8px; }
        .custom-dashboard-menu span[style*="font-size:15px"] { display: none; }
        .custom-dashboard-menu .menu-section-label { display: none; }
    }
    </style>
 
   <div class="custom-dashboard-menu">

    <span class="menu-section-label">
        <?php
        if (is_siteadmin()) {
            echo 'Administrator';
        } else if ($isteacher) {
            echo 'Teacher';
        } else {
            echo 'Student';
        }
        ?>
    </span>

    <ul class="list-unstyled">

        <?php if (is_siteadmin()) { ?>

            <!-- Admin Menu (5 items) -->
            <?php echo $item($CFG->wwwroot.'/my',
                'Dashboard','layout-dashboard',$isDashboard); ?>

            <?php echo $item($CFG->wwwroot.'/course/index.php',
                'Courses','school',$isCourses); ?>

            <?php echo $item($CFG->wwwroot . '/local/incourse/quiz_report.php','Quiz Report','clipboard-data', $isQuizReport); ?>

            <?php echo $item( $CFG->wwwroot . '/local/incourse/forum_grade.php','Forum Report', 'message-2',$isForumReport); ?>

            <?php echo $item($CFG->wwwroot.'/admin/search.php',
                'Site Administration','settings-cog',$isAdmin); ?>
     <?php } else if ($isteacher) { ?>

            <!-- Teacher Menu (7 items) -->
            <?php echo $item($CFG->wwwroot.'/my',
                'Dashboard','layout-dashboard',$isDashboard); ?>

            <?php echo $item($CFG->wwwroot.'/local/courses/',
                'My Courses','school',$isCourses); ?>

            <?php echo $item($CFG->wwwroot.'/grade/report/grader/index.php',
                'Gradebook','star',$isGrades); ?>

            <?php echo $item($CFG->wwwroot.'/local/attendance/index.php',
                'Attendance','calendar-stats',$isAttendance); ?>

            <?php echo $item($CFG->wwwroot.'/local/schedule/index.php',
                'Schedule','calendar-time',$isSchedule); ?>

            <?php echo $item($CFG->wwwroot.'/local/result/index.php',
                'Results','clipboard-list',$isResult); ?>

            <?php echo $item($CFG->wwwroot.'/local/messages/index.php',
                'Messages','message-circle',$isMessages); ?>

        <?php } else { ?>

            <!-- Student Menu (keep existing) -->
            <?php echo $item($CFG->wwwroot.'/my',
                'Dashboard','layout-dashboard',$isDashboard); ?>

            <?php echo $item($CFG->wwwroot.'/local/courses/',
                'Courses','school',$isCourses); ?>

            <?php echo $item($CFG->wwwroot.'/dashboard/result.php',
                'Result','clipboard-list',$isResult); ?>

            <?php echo $item($CFG->wwwroot.'/grade/report/user/index.php',
                'Grades','star',$isGrades); ?>

            <?php echo $item($CFG->wwwroot.'/local/attendance/index.php',
                'My Attendance','calendar-stats',$isAttendance); ?>

            <?php echo $item($CFG->wwwroot.'/local/schedule/index.php',
                'Schedule','calendar-time',$isSchedule); ?>

            <?php echo $item($CFG->wwwroot.'/local/messages/index.php',
                'Chat Inbox','message-circle',$isMessages); ?>


    <hr class="menu-divider">

    <span class="menu-section-label">General</span>

    <ul class="list-unstyled">

        <?php echo $item(
            $CFG->wwwroot.'/user/profile.php?id='.$USER->id,
            'Profile',
            'user',
            $isProfile
        ); ?>

        <?php echo $item(
            $CFG->wwwroot.'/user/edit.php?id='.$USER->id,
            'Settings',
            'settings',
            $isSettings
        ); ?>
 <?php echo $item(
                $CFG->wwwroot . '/login/logout.php?sesskey=' . sesskey(),
                'Log Out',
                'logout',
                false
            ); ?>
       

    </ul>

            <?php } ?>

    </ul>

</div>
    <?php
    return ob_get_clean();
}
    /**
     *custom_menu_drawer
     *
     * @return string
     *
     * @throws \coding_exception
     */
    public function custom_menu_drawer() {
        global $CFG;

        if (!empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        } else {
            return '';
        }

        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu, '', '', 'custom-menu-drawer');
    }

    /**
     * render_custom_menu
     *
     * @param custom_menu $menu
     * @param string $wrappre
     * @param string $wrappost
     * @param string $menuid
     *
     * @return string
     *
     * @throws \coding_exception
     */
    public function render_custom_menu(custom_menu $menu, $wrappre = '', $wrappost = '', $menuid = '') {
        if (!$menu->has_children()) {
            return '';
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            if (stristr($menuid, 'drawer')) {
                $content .= $this->render_custom_menu_item_drawer($item, 0, $menuid, false);
            } else {
                $content .= $this->render_custom_menu_item($item, 0, $menuid);
            }
        }
        $content = $wrappre . $content . $wrappost;
        return $content;
    }

    /**
     * render_custom_menu_item
     *
     * @param custom_menu_item $menunode
     * @param int $level = 0
     * @param string $menuid
     *
     * @return string
     *
     * @throws \coding_exception
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0, $menuid = '') {
        static $submenucount = 0;

        // If the node has a url, then use it, even if it has children as the URL could be that of an overview page.
        if ($menunode->get_url() !== null) {
            $url = $menunode->get_url();
        } else {
            $url = '#';
        }
        if ($menunode->has_children()) {
            $content = "
                <li class='nav-item dropdown my-auto'>
                    <a href='{$url}'
                       class='dropdown-item dropdown-toggle nav-link aaaa'
                       role='button'
                       id='{$menuid}{$submenucount}'
                       aria-haspopup='true'
                       aria-expanded='false'
                       aria-controls='dropdown{$menuid}{$submenucount}'
                       data-target='{$url}'
                       data-toggle='dropdown'
                       title='{$menunode->get_title()}'>
                        {$this->get_text($menunode)}
                    </a>
                    <ul role='menu'
                        class='dropdown-menu'
                        id='dropdown{$menuid}{$submenucount}'
                        aria-labelledby='{$menuid}{$submenucount}'>";

            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 1, "{$menuid}{$submenucount}");
            }
            $content .= '</ul></li>';
        } else {
            if (preg_match("/^#+$/", $this->get_text($menunode))) {
                // This is a divider.
                $content = html_writer::start_tag('li', ['class' => 'dropdown-divider']);
            } else {
                if ($level == 0) {
                    $content = '<li class="nav-item">';
                    $linkclass = 'nav-link';
                } else {
                    $content = '<li>';
                    $linkclass = 'dropdown-item';
                }

                /* This is a bit of a cludge, but allows us to pass url, of type moodle_url with a param of
                 * "helptarget", which when equal to "_blank", will create a link with target="_blank" to allow the link to open
                 * in a new window.  This param is removed once checked.
                 */
                $attributes = [
                    'title' => $menunode->get_title(),
                    'class' => $linkclass,
                ];
                if (is_object($url) && (get_class($url) == 'moodle_url')) {
                    $helptarget = $url->get_param('helptarget');
                    if ($helptarget != null) {
                        $url->remove_params('helptarget');
                        $attributes['target'] = $helptarget;
                    }
                }
                $content .= html_writer::link($url, $this->get_text($menunode), $attributes);

                $content .= "</li>";
            }
        }
        return $content;
    }

    /**
     * render_custom_menu_item_drawer
     *
     * @param custom_menu_item $menunode
     * @param int $level = 0
     * @param string $menuid
     * @param bool $indent
     *
     * @return string
     *
     * @throws \coding_exception
     */
    protected function render_custom_menu_item_drawer(custom_menu_item $menunode, $level = 0, $menuid = '', $indent = false) {
        static $submenucount = 0;

        if ($menunode->has_children()) {
            $submenucount++;
            $content = "
                <li class='m-l-0'>
                    <a href='#{$menuid}{$submenucount}'
                       class='list-group-item dropdown-toggle'
                       aria-haspopup='true'
                       data-target='#'
                       data-toggle'collapse'
                       title='{$menunode->get_title()}'>
                        {$this->get_text($menunode)}
                    </a>
                    <ul class='collapse' id='{$menuid}{$submenucount}'>";
            $indent = true;
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item_drawer($menunode, 1, "{$menuid}{$submenucount}", $indent);
            }
            $content .= '</ul></li>';
        } else {
            // The node doesn't have children so produce a final menuitem.
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }

            if ($indent) {
                $dataindent = 1;
                $marginclass = 'm-l-1';
            } else {
                $dataindent = 0;
                $marginclass = 'm-l-0';
            }

            $content = "
                <li class='{$marginclass}'>
                    <a href='{$url}'
                       class='list-group-item list-group-item-action'
                       data-key=''
                       data-isexpandable='0'
                       data-indent='{$dataindent}'
                       data-showdivider='0'
                       data-type='1'
                       data-nodetype='1'
                       data-collapse='0'
                       data-forceopen='1'
                       data-isactive='1'
                       data-hidden='0'
                       data-preceedwithhr='0'
                       data-parent-key='{$menuid}'>
                        <div class='{$marginclass}'>
                            {$this->get_text($menunode)}
                        </div>
                    </a>
                </li>";
        }
        return $content;
    }

    /**
     * Get text translate
     *
     * @param custom_menu_item $menunode
     *
     * @return string
     *
     * @throws \coding_exception
     */
    public function get_text($menunode) {
        $text = $menunode->get_text();
        if (strpos($text, ",")) {
            $texts = explode(",", $text);

            return get_string($texts[0], $texts[1]);
        }

        return $text;
    }

//    public function header() {
//         global $USER;

//         // Get the default context
//         $templatecontext = parent::header();

//         // Inject isadmin flag
//         $templatecontext['isadmin'] = is_siteadmin($USER);

//         return $templatecontext;
//     }

     
}
