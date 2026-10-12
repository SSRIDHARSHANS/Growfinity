# GrowFinity

A learning website for students who are unsure how to start in a technical engineering domain.
Sign up, log in, choose a domain, follow its step-by-step roadmap, and watch the lessons inside the site.

**Built with:** HTML, CSS, JavaScript, PHP, MySQL (VS Code + XAMPP)

## Features
- Sign up and log in (passwords hashed with `password_hash`, prepared statements, CSRF tokens)
- Six domains with six-step roadmaps: Embedded Systems, VLSI, IoT, Web Development, AI & ML, Cyber Security
- Roadmap page with a progress trail for each domain
- Lesson page with an embedded video, "mark complete" and previous/next navigation
- Progress saved per user

## Run it on your computer
1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this folder to `C:\xampp\htdocs\growfinity` (macOS/Linux: the `htdocs` folder of XAMPP).
3. Open `http://localhost/phpmyadmin` > **Import** > choose `database/growfinity.sql` > **Go**.
4. If your MySQL has a password, edit `includes/db.php`. XAMPP's default (user `root`, empty password) works as is.
5. Open `http://localhost/growfinity/` in your browser.

## Add your lesson videos
Each step has a `video_id` column. Use the part after `v=` in a YouTube link
(`https://www.youtube.com/watch?v=VIDEO_ID`). In phpMyAdmin, open the SQL tab and run:

```sql
UPDATE roadmap_steps SET video_id = 'VIDEO_ID' WHERE domain_id = 1 AND step_order = 1;
```

Steps with an empty `video_id` show a "Video coming soon" message.

## Folder structure
```
growfinity/
├── index.php          landing page
├── signup.php         create account
├── login.php          log in
├── logout.php         log out
├── dashboard.php      choose a domain
├── roadmap.php        learning path for one domain
├── learn.php          lesson video + progress
├── includes/          db.php, init.php, header.php, footer.php
├── assets/css/        style.css
├── assets/js/         main.js
└── database/          growfinity.sql
```

## Upload to GitHub
```bash
cd growfinity
git init
git add .
git commit -m "GrowFinity: learning roadmap website"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/growfinity.git
git push -u origin main
```
GitHub does not run PHP, so the site runs locally with XAMPP or on PHP hosting. Do not commit real database passwords.
