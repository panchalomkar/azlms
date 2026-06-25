<?php
// ================================================================
// File: local/notifications/index.php
// Full notification page — matches Image 3
// ================================================================
require_once('../../config.php');
require_once($CFG->dirroot . '/local/notifications/classes/notification_helper.php');
require_login();

global $USER, $PAGE, $OUTPUT, $CFG;

$PAGE->set_url(new moodle_url('/local/notifications/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Notifications');
$PAGE->set_pagelayout('base');

$PAGE->requires->css(new moodle_url(
    'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css'));

echo $OUTPUT->header();
?>

<style>
/* ════════════════════════════════════════════
   NOTIFICATIONS FULL PAGE — matches Image 3
   ════════════════════════════════════════════ */
.nfp-wrap { max-width: 85%;  padding:28px 20px; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }

.nfp-page-title { font-size:22px; font-weight:700; color:#1a2a4a; margin-bottom:20px; }

/* Card */
.nfp-card {
    background:#fff; border-radius:16px; border:1px solid #eef0f4;
    box-shadow:0 2px 12px rgba(0,0,0,.05); overflow:hidden;
}

/* Card header */
.nfp-card-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 20px; border-bottom:1px solid #eef0f4; flex-wrap:wrap; gap:10px;
}
.nfp-card-header-title { font-size:15px; font-weight:600; color:#1a2a4a; }
.nfp-actions { display:flex; gap:10px; }

.nfp-btn-delete {
    background:#e03c3c; color:#fff; border:none; border-radius:50px;
    padding:8px 18px; font-size:13px; font-weight:600; cursor:pointer;
    transition:opacity .15s;
        height: 30px;
    text-align: center;
    align-items: center;
    display: flex;
}
.nfp-btn-delete:hover { opacity:.85; }

.nfp-btn-markread {
    background:#1a2a4a; color:#fff; border:none; border-radius:50px;
    padding:8px 18px; font-size:13px; font-weight:600; cursor:pointer;
    transition:background .15s;    height: 30px;
    text-align: center;
    align-items: center;
    display: flex;
}
.nfp-btn-markread:hover { background:#263660; }

/* Notification item */
.nfp-item {
    display:flex; align-items:flex-start; gap:14px;
    padding:16px 20px; border-bottom:1px solid #f4f5f7;
    transition:background .12s; text-decoration:none;
}
.nfp-item:last-child { border-bottom:none; }
.nfp-item:hover { background:#fafafa; }
.nfp-item.unread { background:#f9fbff; }

.nfp-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:20px;
}

.nfp-body { flex:1; min-width:0; }
.nfp-msg  { font-size:14px; color:#222; line-height:1.5; margin-bottom:4px; }
.nfp-link {
    font-size:13px; font-weight:600; color:#1a2a4a;
    text-decoration:none; display:inline-block;
}
.nfp-link:hover { text-decoration:underline; }

.nfp-time { font-size:12px; color:#aaa; white-space:nowrap; flex-shrink:0; padding-top:2px; }

/* Empty state */
.nfp-empty {
    text-align:center; padding:60px 20px; color:#aaa;
}
.nfp-empty i { font-size:48px; display:block; margin-bottom:12px; }
.nfp-empty p { font-size:14px; }

/* ── Skeleton ── */
@keyframes nfp-shimmer {
    0%   { background-position:-500px 0; }
    100% { background-position: 500px 0; }
}
.nfp-skel {
    border-radius:6px;
    background:linear-gradient(90deg,#e8eaed 25%,#f5f5f5 50%,#e8eaed 75%);
    background-size:500px 100%;
    animation:nfp-shimmer 1.3s infinite linear;
    display:block;
}
.nfp-sk-item { display:flex; align-items:center; gap:14px; padding:16px 20px; border-bottom:1px solid #f4f5f7; }
.nfp-sk-icon { width:44px; height:44px; border-radius:12px; flex-shrink:0; }
.nfp-sk-body { flex:1; display:flex; flex-direction:column; gap:7px; }
.nfp-sk-line { height:13px; border-radius:4px; }
.nfp-sk-time { width:60px; height:13px; border-radius:4px; flex-shrink:0; }
</style>

<!-- Skeleton shown immediately -->
<div class="nfp-wrap">
    <div class="nfp-page-title">Notification</div>

    <!-- SKELETON -->
    <div class="nfp-card" id="nfp-skeleton">
        <div class="nfp-card-header">
            <div class="nfp-skel" style="height:18px;width:140px;border-radius:6px;"></div>
            <div style="display:flex;gap:10px;">
                <div class="nfp-skel" style="height:34px;width:90px;border-radius:50px;"></div>
                <div class="nfp-skel" style="height:34px;width:130px;border-radius:50px;"></div>
            </div>
        </div>
        <?php for($i=0;$i<6;$i++): ?>
        <div class="nfp-sk-item">
            <div class="nfp-skel nfp-sk-icon"></div>
            <div class="nfp-sk-body">
                <div class="nfp-skel nfp-sk-line" style="width:85%;"></div>
                <div class="nfp-skel nfp-sk-line" style="width:40%;"></div>
            </div>
            <div class="nfp-skel nfp-sk-time"></div>
        </div>
        <?php endfor; ?>
    </div>

    <!-- REAL CONTENT (hidden until JS loads data) -->
    <div class="nfp-card" id="nfp-content" style="display:none;">
        <div class="nfp-card-header">
            <div class="nfp-card-header-title">All notifications</div>
            <div class="nfp-actions">
                <button class="nfp-btn-delete"   onclick="nfpDeleteAll()">Delete all</button>
                <button class="nfp-btn-markread" onclick="nfpMarkAllRead()">Mark all as read</button>
            </div>
        </div>
        <div id="nfp-list">
            <!-- Filled by JS -->
        </div>
    </div>
</div>

<script>
(function(){
'use strict';

const ICONS = {
    enrolment         : { icon:'ti-book',           bg:'#fff4e0', color:'#e07b00' },
    assignment        : { icon:'ti-clipboard-text', bg:'#fff0f0', color:'#e03c3c' },
    pending_assignment: { icon:'ti-clipboard-text', bg:'#fff0f0', color:'#e03c3c' },
    gmeet             : { icon:'ti-video',           bg:'#eef0f4', color:'#1a2a4a' },
    event             : { icon:'ti-calendar-event', bg:'#eafaf3', color:'#1faa6b' },
    approval          : { icon:'ti-check',   bg:'#eafaf3', color:'#1faa6b' },
    pending_quiz      : { icon:'ti-clipboard-list', bg:'#f3f0ff', color:'#8b5cf6' },
    incomplete_module : { icon:'ti-books',           bg:'#f4f5f7', color:'#6b7280' },
    default           : { icon:'ti-bell',            bg:'#eef0f4', color:'#1a2a4a' },
};

var allNotifs = [];

function getIcon(type){ return ICONS[type] || ICONS.default; }

function renderItem(n){
    var cfg = getIcon(n.type);
    var msg = (n.message||'').replace(/^[⚠️📋📚]\s*/u,'');
    var viewLink = n.url
        ? '<a href="'+n.url+'" class="nfp-link">View</a>'
        : '';
    return '<div class="nfp-item'+(n.isread?'':' unread')+'" data-id="'+n.id+'">'
        + '<div class="nfp-icon" style="background:'+cfg.bg+';">'
        +   '<i class="ti '+cfg.icon+'" style="color:'+cfg.color+';font-size:20px;"></i>'
        + '</div>'
        + '<div class="nfp-body">'
        +   '<div class="nfp-msg">'+msg+'</div>'
        +   viewLink
        + '</div>'
        + '<div class="nfp-time">'+n.time+'</div>'
        + '</div>';
}

function render(items){
    var list = document.getElementById('nfp-list');
    if(!items.length){
        list.innerHTML = '<div class="nfp-empty">'
            + '<i class="ti ti-bell-off"></i>'
            + '<p>No notifications yet</p></div>';
    } else {
        list.innerHTML = items.map(renderItem).join('');
    }
}

// Load from API
fetch('<?= (new moodle_url('/local/notifications/api.php'))->out(false) ?>', {
    credentials:'same-origin'
})
.then(r=>r.json())
.then(data=>{
    allNotifs = data.notifications || [];
    document.getElementById('nfp-skeleton').style.display = 'none';
    document.getElementById('nfp-content').style.display  = 'block';
    render(allNotifs);
})
.catch(()=>{
    document.getElementById('nfp-skeleton').innerHTML =
        '<div class="nfp-empty"><i class="ti ti-wifi-off"></i><p>Failed to load</p></div>';
});

// Delete all
window.nfpDeleteAll = function(){
    if(!confirm('Delete all notifications?')) return;
    allNotifs = [];
    render(allNotifs);
};

// Mark all as read
window.nfpMarkAllRead = function(){
    allNotifs = allNotifs.map(n=>({...n, isread:true}));
    render(allNotifs);
    // Optional: AJAX to persist
    fetch('<?= (new moodle_url('/local/notifications/mark_read.php'))->out(false) ?>', {
        method:'POST',
        credentials:'same-origin',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'sesskey=<?= sesskey() ?>&action=markallread'
    });
};

}());
</script>

<?php
echo $OUTPUT->footer();
