<style>
    .notification-badge {
        pointer-events: none;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    /* NEW: section header inside dropdown */
    .notif-section-header {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        padding: 6px 8px 2px;
        background: #f1f3f5;
        border-bottom: 1px solid #dee2e6;
        letter-spacing: 0.5px;
    }
    /* NEW: type icon badges */
    .notif-type-pill {
        display: inline-block;
        font-size: 0.65rem;
        padding: 1px 5px;
        border-radius: 8px;
        margin-right: 4px;
        font-weight: 600;
    }
    .pill-pending_assignment { background:#fff3cd; color:#856404; }
    .pill-pending_quiz       { background:#d1e7dd; color:#0f5132; }
    .pill-incomplete_module  { background:#cfe2ff; color:#084298; }
    .pill-enrolment          { background:#e2d9f3; color:#432874; }
    .pill-assignment         { background:#fde8d8; color:#7d3c00; }
    .pill-gmeet              { background:#d4edda; color:#155724; }
    .pill-event              { background:#d6eaf8; color:#1a5276; }
    .pill-approval           { background:#d5f5e3; color:#1e8449; }
</style>

<header class="asDashboardHeader">
    <div class="inputGroupSearch position-relative" style="width: fit-content;">
        <input type="text" id="searchInput" placeholder="Search..." autocomplete="off"
               style="padding:6px 10px; width:250px; border:1px solid #ccc; border-radius:6px;">
    </div>

    <div class="AsDHrightSec" style="justify-content: space-evenly; width: 330px; align-items: center;">
        <div class="userSec">
            <div class="userIcon">
                <?php
                global $USER, $OUTPUT;
                echo $OUTPUT->user_picture($USER, ['size'=>50, 'class'=>'rounded-circle', 'link'=>false]);
                ?>
            </div>
            <div class="userName">
                <p style="margin-left:5px;"><?= $USER->firstname . ' ' . $USER->lastname ?></p>
            </div>
        </div>

        <div class="BellIcon position-relative">
            <svg id="notificationBell" width="24" height="24" viewBox="0 0 24 24" fill="none" style="cursor:pointer;">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M7.25013 4.664C8.12013 3.367 9.62313 2 12.0001 2C14.3771 2 15.8801 3.367 16.7501 4.664C17.3248 5.53512 17.7427 6.49997 17.9851 7.515L19.5201 15.429C19.6043 15.8633 19.5914 16.3108 19.4824 16.7395C19.3734 17.1682 19.1709 17.5675 18.8894 17.9087C18.608 18.25 18.2546 18.5248 17.8545 18.7135C17.4544 18.9021 17.0175 19 16.5751 19H7.42513C6.98278 19 6.54591 18.9021 6.14581 18.7135C5.74571 18.5248 5.39228 18.25 5.11083 17.9087C4.82939 17.5675 4.6269 17.1682 4.51786 16.7395C4.40882 16.3108 4.39594 15.8633 4.48013 15.429L6.01513 7.515C6.25744 6.49962 6.6754 5.53543 7.25013 4.664Z"
                    fill="#404B6D" />
                <circle id="notifCountBadge" cx="18" cy="6" r="6" fill="#FF0000" style="display:none;" />
                <text id="notifCountText" x="18" y="9" text-anchor="middle" font-size="8" fill="#fff" style="display:none;">0</text>
            </svg>

            <!-- Notification dropdown — unchanged HTML -->
            <div id="notificationDropdown" class="position-absolute bg-white shadow p-2"
                 style="display:none; top:30px; right:0; width:320px; max-height:450px; overflow-y:auto; z-index:999; border-radius:8px;">
                <p class="text-muted text-center mb-0">Loading...</p>
            </div>
        </div>
    </div>
</header>

<script>
const wwwroot = (typeof M !== 'undefined' && M.cfg && M.cfg.wwwroot) ? M.cfg.wwwroot : '';

// ── Search (unchanged) ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchInput');
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const query = input.value.trim();
            if (query.length > 0) {
                const base = wwwroot || '';
                window.location.href = base + '/search/index.php?q=' + encodeURIComponent(query);
            }
        }
    });
});

// ── Notification bell ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const bell     = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    const badge    = document.getElementById('notifCountBadge');
    const badgeText= document.getElementById('notifCountText');

    // ── Group definitions — controls order & labels in dropdown ──────────
    const GROUPS = [
        { type: 'pending_assignment', label: '⚠️ Pending Assignments' },
        { type: 'pending_quiz',       label: '📋 Pending Quizzes'     },
        { type: 'incomplete_module',  label: '📚 Incomplete Modules'  },
        { type: 'enrolment',          label: '🎓 Enrolments'          },
        { type: 'assignment',         label: '📝 Submitted Assignments'},
        { type: 'gmeet',              label: '📹 Google Meet'         },
        { type: 'event',              label: '📅 Upcoming Events'     },
        { type: 'approval',           label: '✅ Timesheet Approvals' },
    ];

    // ── Fetch & update badge ─────────────────────────────────────────────
    async function updateNotifications() {
        try {
            const response = await fetch('fetch_notifications.php');
            const data     = await response.json();

            if (data.status === 'success') {
                // Badge shows total of ALL notifications
                const count = data.notifications.length;
                if (count > 0) {
                    badge.style.display    = 'block';
                    badgeText.style.display= 'block';
                    badgeText.textContent  = count > 99 ? '99+' : count;
                } else {
                    badge.style.display    = 'none';
                    badgeText.style.display= 'none';
                }
                return data.notifications;
            }
            return [];
        } catch (err) {
            console.error('Notification fetch error:', err);
            return [];
        }
    }

    // ── Build one notification row ───────────────────────────────────────
    function buildRow(notif) {
        const item = document.createElement('div');
        item.className = 'notification-item p-2 border-bottom';
        item.style.cursor = 'pointer';

        const pill = `<span class="notif-type-pill pill-${notif.type}">${notif.type.replace(/_/g,' ')}</span>`;
        const time = notif.time
            ? `<small class="text-muted d-block mt-1">${notif.time}</small>`
            : '';

        item.innerHTML = `
            <p class="mb-0" style="font-size:13px; line-height:1.4;">
                ${pill}${notif.message}
            </p>
            ${time}
        `;

        if (notif.url) {
            item.addEventListener('click', async () => {
                try {
                    await fetch('mark_read.php?id=' + (notif.id || ''), { method: 'GET' });
                } catch(e) { /* silent */ }
                window.location.href = notif.url;
            });
        }
        return item;
    }

    // ── Render grouped dropdown ──────────────────────────────────────────
    function renderDropdown(notifications) {
        dropdown.innerHTML = '';

        if (!notifications.length) {
            dropdown.innerHTML = '<p class="text-center text-muted mb-0 p-3">No new notifications</p>';
            return;
        }

        // Group all notifications by type
        const grouped = {};
        notifications.forEach(n => {
            if (!grouped[n.type]) grouped[n.type] = [];
            grouped[n.type].push(n);
        });

        // Render in defined group order
        let hasAny = false;
        GROUPS.forEach(group => {
            const items = grouped[group.type];
            if (!items || !items.length) return;

            hasAny = true;

            // Section header
            const header = document.createElement('div');
            header.className = 'notif-section-header';
            header.textContent = `${group.label} (${items.length})`;
            dropdown.appendChild(header);

            // Rows
            items.forEach(notif => dropdown.appendChild(buildRow(notif)));
        });

        // Catch any type not in GROUPS (future-proof)
        Object.keys(grouped).forEach(type => {
            if (!GROUPS.find(g => g.type === type)) {
                const header = document.createElement('div');
                header.className = 'notif-section-header';
                header.textContent = type;
                dropdown.appendChild(header);
                grouped[type].forEach(notif => dropdown.appendChild(buildRow(notif)));
            }
        });

        if (!hasAny) {
            dropdown.innerHTML = '<p class="text-center text-muted mb-0 p-3">No new notifications</p>';
        }
    }

    // ── Bell click (unchanged behaviour, new rendering) ──────────────────
    bell.addEventListener('click', async function(event) {
        event.stopPropagation();

        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
            dropdown.innerHTML     = '<p class="text-muted text-center mb-0 p-3">Loading...</p>';

            const notifications = await updateNotifications();
            renderDropdown(notifications);
        } else {
            dropdown.style.display = 'none';
        }
    });

    // ── Close on outside click (unchanged) ───────────────────────────────
    document.addEventListener('click', function(event) {
        if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    // ── Initial badge load on page load (unchanged) ───────────────────────
    updateNotifications();
});
</script>

<style>
.notification-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}
</style>