<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Tools Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- CSS Reset & Variables --- */
        :root {
            --primary-color: #4a90e2;
            /* Modern Blue */
            --secondary-color: #2c3e50;
            /* Dark Blue/Grey */
            --bg-color: #f4f7f6;
            --card-bg: #ffffff;
            --text-color: #333;
            --sidebar-width: 250px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background-color: var(--bg-color);
            min-height: 100vh;
        }

        /* --- Sidebar Styling --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--secondary-color);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 600;
        }

        .nav-links {
            list-style: none;
            padding-top: 20px;
            flex-grow: 1;
        }

        .nav-links li a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 16px;
            transition: var(--transition);
        }

        .nav-links li a:hover,
        .nav-links li a.active {
            background-color: var(--primary-color);
            color: #fff;
            padding-left: 30px;
            /* Slide effect */
        }

        .nav-links li a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        .logout-btn {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn a {
            display: block;
            text-align: center;
            background: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        /* --- Main Content Styling --- */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
            transition: var(--transition);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header-top h1 {
            color: var(--secondary-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        /* --- Tools Grid --- */
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .tool-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            /* Keep text clean */
            color: var(--text-color);
        }

        .tool-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background: rgba(74, 144, 226, 0.1);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            margin: 0 auto 15px auto;
        }

        .tool-card h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .tool-card p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .btn-launch {
            display: inline-block;
            padding: 8px 20px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-header h2,
            .nav-links li a span,
            .logout-btn span {
                display: none;
            }

            .nav-links li a {
                justify-content: center;
                padding: 20px 0;
            }

            .nav-links li a i {
                margin: 0;
                font-size: 20px;
            }

            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }

        /* Dropdown Styles */
        .dropdown-container {
            display: none;
            background-color: #202d3a;
            /* Slightly darker than sidebar */
        }

        .dropdown-container a {
            padding-left: 55px !important;
            /* Indent sub-items */
            font-size: 14px;
        }

        .dropdown-search {
            width: 85%;
            margin: 10px auto;
            display: block;
            padding: 8px;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            outline: none;
        }

        .dropdown-search::placeholder {
            color: #bdc3c7;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Toolkit<span style="color:var(--primary-color);">Pro</span></h2>
        </div>
        <ul class="nav-links">
            <li><a href="#" class="active"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li><a href="profile.php"><i class="fa-solid fa-user"></i> <span>My Profile</span></a></li>
            <li><a href="profile.php"><i class="fa-solid fa-user"></i> <span>SOCIETY'S</span></a></li>
            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="fa-solid fa-calculator"></i>
                    <span>Calculators</span>
                    <i class="fa-solid fa-chevron-down"
                        style="margin-left: auto; width: auto; font-size: 0.8em; transition: transform 0.3s;"></i>
                </a>
                <div class="dropdown-container">
                    <input type="text" id="calcSearch" class="dropdown-search" placeholder="Search...">
                    <a href="calculators/dayscalculator/days_calculator.html">Days Calculator</a>
                    <a href="calculators/simple_interest.html">Simple Interest Calculator</a>
                    <a href="calculators/compound_interest.html">Compound Interest Calculator</a>
                </div>
            </li>
            <li><a href="settings.php"><i class="fa-solid fa-gear"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout-btn">
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a>
        </div>
    </aside>

    <main class="main-content">
        <header class="header-top">
            <div>
                <h1>Welcome Back!</h1>
                <p style="color: #7f8c8d;">Select a tool to get started.</p>
            </div>
            <div class="user-info">
                <a href="login.php"
                    style="text-decoration:none; margin-right: 15px; background: var(--primary-color); color: white; padding: 5px 15px; border-radius: 4px; font-size: 0.9em;">Login</a>
                <span>User Admin</span>
                <div class="user-avatar">UA</div>
            </div>
        </header>

        <div class="tools-grid">

            <a href="resume_builder/index.php" class="tool-card">
                <div class="icon-box">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <h3>Resume Builder</h3>
                <p>Create professional resumes with our drag-and-drop editor.</p>
                <span class="btn-launch">Launch Tool</span>
            </a>

         <!--   <a href="pdf_tool/convert.php" class="tool-card">
                <div class="icon-box" style="color: #e74c3c; background: rgba(231, 76, 60, 0.1);">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <h3>PDF Converter</h3>
                <p>Convert Word, Excel, or Images into secure PDF files.</p>
                <span class="btn-launch">Launch Tool</span>
            </a>

            <a href="text_tool/index.php" class="tool-card">
                <div class="icon-box" style="color: #27ae60; background: rgba(39, 174, 96, 0.1);">
                    <i class="fa-solid fa-font"></i>
                </div>
                <h3>Text Utility</h3>
                <p>Case converter, word counter, and text formatting tools.</p>
                <span class="btn-launch">Launch Tool</span>
            </a>

            <a href="img_converter/index.php" class="tool-card">
                <div class="icon-box" style="color: #9b59b6; background: rgba(155, 89, 182, 0.1);">
                    <i class="fa-solid fa-image"></i>
                </div>
                <h3>Image Converter</h3>
                <p>Resize, compress, and convert images (JPG, PNG, WebP).</p>
                <span class="btn-launch">Launch Tool</span>
            </a>

            <a href="admin/login.php" class="tool-card">
                <div class="icon-box" style="color: #f39c12; background: rgba(243, 156, 18, 0.1);">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3>Admin Access</h3>
                <p>Manage users and system settings.</p>
                <span class="btn-launch">Login</span>
            </a>

            <a href="#" class="tool-card" style="border: 2px dashed #bdc3c7;">
                <div class="icon-box" style="color: #bdc3c7; background: transparent;">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <h3 style="color: #bdc3c7;">Add New Tool</h3>
                <p>Coming Soon...</p>
            </a> -->

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdown = document.querySelector('.dropdown-toggle');
            var container = document.querySelector('.dropdown-container');
            var icon = dropdown.querySelector('.fa-chevron-down');

            dropdown.addEventListener('click', function (e) {
                e.preventDefault();
                if (container.style.display === 'block') {
                    container.style.display = 'none';
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    container.style.display = 'block';
                    icon.style.transform = 'rotate(180deg)';
                }
            });

            var searchInput = document.getElementById('calcSearch');
            searchInput.addEventListener('keyup', function () {
                var filter = this.value.toUpperCase();
                var links = container.getElementsByTagName('a');
                for (var i = 0; i < links.length; i++) {
                    var txtValue = links[i].textContent || links[i].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        links[i].style.display = "";
                    } else {
                        links[i].style.display = "none";
                    }
                }
            });
        });
    </script>
</body>

</html>