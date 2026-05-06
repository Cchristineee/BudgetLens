<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Reports</title>
    <link rel="stylesheet" href="admin_reports.css">
</head>
<body>
    <div class="dashboard-layout">
        
    <!-- Admin Sidebar Nav ★ -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main content ★ -->
    <main class="reports-page">
        <header class = "reports-title">
            <h1>User Reports</h1>
            <p>1 new report • 6 total</p>
        </header>

        <section class="reports-layout">
        <!-- Left Pannel ★ -->
         <!-- Hardcoded ...... ★ -->    
         <aside class="reports-list-panel">
            <div class="filters">
               <!-- <input type="text" placeholder="Search reports...."/> -->
                
                <div class="filter-row">
                    <select>
                        <option>All Status</option>
                        <option>New</option>
                        <option>Read</option>
                        <option>Completed</option>
                    </select>

                    <select>
                        <option>All Categories</option>
                        <option>Bug</option>
                        <option>Data Issue</option>
                        <option>Feature Request</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>
                <div class="report-card">
                    <div>
                        <span class="icon red">☼</span>
                        <span class="pill bug">Bug</span>
                    </div>
                    <h3>Receipt scan not working when I upload my receipt...</h3>
                    <p>Christine Grimadeau</p>
                    <small>2026-05-06 09:00 AM</small>
                </div>

                <div class="report-card active">
                    <div>
                        <span class="icon blue">♧</span>
                        <span class="pill feature">Feature Request</span>
                        <span class="pill new">NEW</span>
                    </div>
                    <h3>Add export to Excel feature</h3>
                    <p>Mike Torres</p>
                    <small>2026-05-06 08:10 AM</small>
                </div>

                <div class="report-card">
                    <div>
                        <span class="icon green">▱</span>
                        <span class="pill feedback">Feedback</span>
                    </div>
                    <h3>Love the new dashboard!</h3>
                    <p>Ariana Brown</p>
                    <small>2026-05-03 04:32 PM</small>
                </div>
        </aside>

        <!-- Right Pannel ★ -->  
        <!-- Hardcoded ...... ★ -->  
        <section class="report-detail-panel">
            <div class="detail-top">
                <div class="detail-tags">
                <span class="icon green">▱</span>
                <span class="pill feedback">Feedback</span>
                <span class="pill read">READ</span>
            </div>

            <button class="archive-btn">Archive</button>
        </div>

        <h2>Love the new dashboard!</h2>
        <p class="date">2026-05-03 04:32 PM</p>

        <div class="user-card">
            <h3>Ariana Brown</h3>
            <p>aBrown@icloud.com</p>
        </div>

        <div class="message-section">
            <h3>Message</h3>
            <p>Just wanted to say that the recent dashboard is amazing! The monthly overview
                cards are exactly what I needed. Keep it up team!
            </p>
        </div>
        </div>
    </section>
  </section>
</main>





        
            

