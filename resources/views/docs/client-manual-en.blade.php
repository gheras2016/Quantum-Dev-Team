<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #1f2937; font-size: 11.5pt; line-height: 1.6; }
        h1, h2, h3 { color: #1e3a8a; }
        .cover { text-align: center; padding-top: 120px; }
        .cover .logo { width: 90px; height: 90px; background-color: #2563eb; color: #fff; font-size: 44pt; font-weight: bold; border-radius: 20px; display: inline-block; line-height: 90px; }
        .cover h1 { font-size: 30pt; margin: 24px 0 6px; color: #111827; }
        .cover .brand { font-size: 15pt; color: #2563eb; font-weight: bold; }
        .cover .sub { font-size: 13pt; color: #6b7280; margin-top: 8px; }
        .cover .meta { margin-top: 60px; font-size: 11pt; color: #6b7280; }
        .section-title { background-color: #eff6ff; color: #1e3a8a; padding: 8px 12px; border-left: 4px solid #2563eb; font-size: 15pt; font-weight: bold; margin: 22px 0 10px; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0 14px; }
        th { background-color: #2563eb; color: #fff; padding: 7px 9px; text-align: left; font-size: 11pt; }
        td { border-bottom: 1px solid #e5e7eb; padding: 7px 9px; vertical-align: top; }
        td.name { font-weight: bold; color: #1e3a8a; width: 32%; }
        .phase { border: 1px solid #e5e7eb; border-left: 4px solid #2563eb; border-radius: 6px; padding: 8px 12px; margin-bottom: 8px; }
        .phase .n { color: #2563eb; font-weight: bold; }
        .badge { background-color: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 10px; font-size: 9pt; }
        li { margin-bottom: 3px; }
        .footer-note { color: #9ca3af; font-size: 9pt; text-align: center; margin-top: 20px; }
    </style>
</head>
<body dir="ltr">

    <div class="cover">
        <div class="logo">Q</div>
        <h1>System Guide</h1>
        <div class="brand">Quantum Dev Team</div>
        <div class="sub">Company Website & Management System</div>
        <div class="meta">
            Version {{ $version }} &nbsp;•&nbsp; {{ $date }}<br>
            Client Documentation
        </div>
    </div>

    <pagebreak />

    <div class="section-title">1. System Overview</div>
    <p>
        The Quantum Dev Team system is a complete, bilingual (Arabic &amp; English) web platform
        made of two parts: a <b>public marketing website</b> that showcases the team's services,
        projects and members, and a full <b>admin dashboard</b> that lets you manage all site
        content without any technical work.
    </p>
    <p>It is built to modern software-engineering standards with a focus on performance, security, user experience and future scalability.</p>

    <div class="section-title">2. System Sections</div>

    <h3>A. Public Website (visitor-facing)</h3>
    <table>
        <tr><th>Section</th><th>Description</th></tr>
        <tr><td class="name">Home</td><td>An engaging landing page: team intro, services, featured projects, testimonials and animated statistics.</td></tr>
        <tr><td class="name">About</td><td>The team's vision, fields of work, message and management team.</td></tr>
        <tr><td class="name">Services</td><td>Detailed presentation of the technical services offered.</td></tr>
        <tr><td class="name">Projects</td><td>Portfolio with category filtering and a details page per project.</td></tr>
        <tr><td class="name">Blog</td><td>Technical articles that build authority and improve search visibility.</td></tr>
        <tr><td class="name">Team</td><td>Team members with their roles, skills and professional links.</td></tr>
        <tr><td class="name">Contact</td><td>A direct contact form (delivered to the dashboard) plus a floating WhatsApp button.</td></tr>
        <tr><td class="name">Request a Project</td><td>A smart intake form (type, timeline, description).</td></tr>
    </table>

    <h3>B. Admin Dashboard (management system)</h3>
    <table>
        <tr><th>Section</th><th>Description</th></tr>
        <tr><td class="name">Dashboard</td><td>Overview with KPIs, a content chart, and recent activity &amp; messages.</td></tr>
        <tr><td class="name">Projects</td><td>Create/edit projects with cover, gallery, technologies, categories and tags.</td></tr>
        <tr><td class="name">Services</td><td>Full control over the services, their order and status.</td></tr>
        <tr><td class="name">Team</td><td>Manage members, roles, skills and photos.</td></tr>
        <tr><td class="name">Blog &amp; Content</td><td>Manage articles, testimonials and FAQs.</td></tr>
        <tr><td class="name">Technologies / Categories / Tags</td><td>Lookup lists used to classify projects and articles.</td></tr>
        <tr><td class="name">Messages &amp; Requests</td><td>Receive and track contact messages and project requests, with Excel / PDF export.</td></tr>
        <tr><td class="name">Users &amp; Roles</td><td>Manage admin accounts and their roles (super admin, admin, content manager, editor).</td></tr>
        <tr><td class="name">Settings</td><td>Control the logo, contact info, hero text and statistics.</td></tr>
        <tr><td class="name">Trash</td><td>Restore or permanently delete removed items.</td></tr>
    </table>

    <pagebreak />

    <div class="section-title">3. Key Features</div>
    <ul>
        <li><b>Bilingual:</b> full Arabic &amp; English support with instant switching and correct text direction (RTL/LTR).</li>
        <li><b>Responsive:</b> works smoothly on mobile, tablet and desktop.</li>
        <li><b>Dark mode:</b> comfortable day and night.</li>
        <li><b>SEO:</b> sitemap, clean URLs and social-share metadata.</li>
        <li><b>Security:</b> multi-level permissions, spam-protected forms and encrypted data.</li>
        <li><b>Activity log:</b> tracks every admin action (who did what and when).</li>
        <li><b>Performance:</b> automatic image compression, persistent cloud storage and fast loading.</li>
        <li><b>Notifications:</b> instant email alerts on new messages or project requests.</li>
    </ul>

    <div class="section-title">4. Development Phases</div>
    <div class="phase"><span class="n">Phase 1 — Analysis &amp; Planning:</span> understand requirements, define sections, design the database. <span class="badge">Completed</span></div>
    <div class="phase"><span class="n">Phase 2 — Design:</span> UI, visual identity and user experience. <span class="badge">Completed</span></div>
    <div class="phase"><span class="n">Phase 3 — Development:</span> build the public site and the full admin dashboard. <span class="badge">Completed</span></div>
    <div class="phase"><span class="n">Phase 4 — Testing &amp; QA:</span> automated tests plus performance and security review. <span class="badge">Completed</span></div>
    <div class="phase"><span class="n">Phase 5 — Deployment:</span> ship to the cloud (Render) with a PostgreSQL database. <span class="badge">Completed</span></div>
    <div class="phase"><span class="n">Phase 6 — Support &amp; Continuous Improvement:</span> new enhancements and monitoring based on feedback. <span class="badge">Ongoing</span></div>

    <div class="section-title">5. Technical Stack (at a glance)</div>
    <table>
        <tr><td class="name">Framework</td><td>Laravel 10 (PHP)</td></tr>
        <tr><td class="name">Database</td><td>PostgreSQL / MySQL</td></tr>
        <tr><td class="name">Front end</td><td>Blade + Tailwind CSS + Alpine.js</td></tr>
        <tr><td class="name">Hosting</td><td>Render (Docker) + Cloudinary image storage</td></tr>
        <tr><td class="name">Security</td><td>Spatie Permissions + CSRF &amp; form protection</td></tr>
    </table>

    <div class="section-title">6. Changelog</div>
    <table>
        <tr><th>Version</th><th>Date</th><th>Updates</th></tr>
        @foreach ($changelog as $entry)
            <tr>
                <td class="name">{{ $entry['version'] }}</td>
                <td>{{ $entry['date'] }}</td>
                <td>{{ $entry['notes'] }}</td>
            </tr>
        @endforeach
    </table>

    <p class="footer-note">© {{ date('Y') }} Quantum Dev Team — All rights reserved. This document is updated with every new system release.</p>

</body>
</html>
