<?php
// Main PHP endpoint for the Resume Builder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_content'])) {
    $content = $_POST['resume_content'];
    $computedStyles = $_POST['computed_styles'];

    $filename = "resume_executive_" . date('Y-m-d_H-i') . ".html";
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Executive Resume</title>
        <style>
            :root { " . $computedStyles . " }
            body { font-family: 'Open Sans', sans-serif; margin: 0; padding: 0; }
            .resume-container { width: 21cm; margin: 0 auto; }
            
            /* PDF PRINT STYLES */
            .resume-page { 
                display: flex !important; 
                flex-direction: column !important; 
                width: 100%; 
                padding: 0 !important; 
                background: white;
            }
            
            .header-section { 
                background-color: #1c2833 !important; 
                color: white !important; 
                -webkit-print-color-adjust: exact; 
                padding: 30px var(--content-padding);
            }
            
            .contact-bar { 
                background-color: #2c3e50 !important; 
                color: white !important; 
                -webkit-print-color-adjust: exact; 
                padding: 15px var(--content-padding);
                display: flex !important;
                flex-wrap: wrap !important;
                gap: var(--item-gap) !important;
                margin-bottom: 0 !important;
            }

            .resume-body { display: flex !important; flex-direction: row !important; }
            
            .main-content { 
                flex: 1 !important; 
                padding: 30px var(--content-padding) !important; 
                padding-top: var(--section-gap) !important;
                background: white; 
                display: flex !important;
                flex-direction: column !important;
                gap: var(--section-gap) !important;
            }
            
            .right-sidebar { 
                width: var(--sidebar-width) !important; 
                background-color: #ecf0f1 !important; 
                -webkit-print-color-adjust: exact; 
                padding: 30px 20px !important;
                padding-top: var(--section-gap) !important;
                display: flex !important;
                flex-direction: column !important;
                gap: var(--section-gap) !important;
            }

            #experienceSection, #personalList, #hobbiesList, .dynamic-list { 
                display: flex !important; 
                flex-direction: column !important; 
                gap: var(--item-gap) !important; 
            }
            
            #skillsList {
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 8px !important;
            }

            .footer-sign { display: flex !important; justify-content: space-between !important; margin-top: 30px; width: 100%; }
            .edu-table td { padding-top: calc(var(--item-gap) * 0.5); padding-bottom: calc(var(--item-gap) * 0.5); }

            /* PRINT LOGIC FOR INPUTS */
            input[type='radio'] { display: none; }
            .radio-group label { display: none; } 
            .radio-group label.active-radio { display: inline; }
            select { -webkit-appearance: none; appearance: none; border: none; background: transparent; padding: 0; margin: 0; }

            h1, h2, h3, h4, p, div { margin: 0; padding: 0; }
            [contenteditable] { border: none !important; }
            
            .remove-btn, .remove-btn-skill, .remove-section-btn, .add-btn, .ui-controls, .editor-panel { display: none !important; }
        </style>
    </head>
    <body>
        " . $content . "
        <script>
             // window.onload = function() { window.print(); }
        </script>
    </body>
    </html>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Resume Builder</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* CSS VARIABLES */
        :root {
            /* Colors */
            --header-bg: #1c2833;       /* Deep Charcoal Navy */
            --contact-bg: #2c3e50;      /* Slate Blue */
            --accent-color: #27ae60;    /* Emerald Green */
            --text-main: #333;
            --sidebar-bg: #ecf0f1;      /* Very Light Grey */
            
            /* Dimensions */
            --page-width: 210mm;
            --page-height: 297mm;
            
            /* ADJUSTABLE DEFAULTS */
            --base-font-size: 14px;
            --section-gap: 30px;
            --item-gap: 15px;
            --content-padding: 40px;
            --sidebar-width: 30%;
            --line-height: 1.6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #555;
            color: var(--text-main);
            display: flex;
            flex-direction: row;
            justify-content: center;
            padding: 20px;
            font-size: var(--base-font-size);
        }

        /* --- RESUME PAGE --- */
        .resume-page {
            background: white;
            width: var(--page-width);
            min-height: var(--page-height);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
            position: relative;
            display: flex;
            flex-direction: column;
            margin-right: 20px;
            transition: all 0.2s ease;
        }

        /* --- HEADER --- */
        .header-section {
            background-color: var(--header-bg);
            color: white;
            padding: 40px var(--content-padding);
            text-align: left;
            margin-bottom: 0; 
        }

        .header-section h1.name {
            font-family: 'Roboto Slab', serif;
            font-size: 3em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .header-section .job-title {
            font-size: 1.2em;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent-color);
            font-weight: 600;
        }

        /* --- CONTACT BAR (Full Width) --- */
        .contact-bar {
            background-color: var(--contact-bg);
            color: white;
            padding: 12px var(--content-padding);
            display: flex;
            flex-wrap: wrap;
            gap: var(--item-gap);
            font-size: 0.9em;
            align-items: center;
            margin-bottom: 0; 
        }

        .contact-item {
            display: flex;
            align-items: center;
            position: relative;
        }

        /* --- SPLIT BODY --- */
        .resume-body {
            display: flex;
            flex-grow: 1;
        }

        /* MAIN CONTENT (LEFT) */
        .main-content {
            flex: 1;
            padding: var(--section-gap) 30px 30px var(--content-padding);
            background: white;
            display: flex;
            flex-direction: column;
            gap: var(--section-gap);
        }

        /* RIGHT SIDEBAR */
        .right-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            padding: var(--section-gap) 20px 30px 20px;
            border-left: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            gap: var(--section-gap);
        }

        /* --- SECTIONS --- */
        .section { position: relative; }

        .section-title {
            font-family: 'Roboto Slab', serif;
            font-size: 1.2em;
            color: var(--header-bg);
            text-transform: uppercase;
            border-left: 5px solid var(--accent-color);
            padding-left: 10px;
            margin-bottom: 15px;
            font-weight: 700;
            display: flex; justify-content: space-between; align-items: center;
        }

        /* LIST CONTAINERS */
        #experienceSection, #personalList, #hobbiesList, .dynamic-list {
            display: flex;
            flex-direction: column;
            gap: var(--item-gap);
        }

        /* Entries */
        .entry, .sidebar-item {
            position: relative;
            page-break-inside: avoid;
            padding-right: 20px;
            margin-bottom: 0;
        }

        .entry-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2px; }
        .entry-title { font-weight: 700; font-size: 1.05em; color: #000; }
        .entry-date { font-weight: 600; font-size: 0.9em; color: #666; text-align: right; min-width: 100px; }
        .entry-subtitle { font-weight: 600; color: var(--header-bg); font-size: 0.95em; margin-bottom: 5px; }
        .entry-content { font-size: 0.95em; line-height: var(--line-height); color: #444; text-align: justify; }

        /* Sidebar Styles */
        .sidebar-item { font-size: 0.9em; }
        .sidebar-label { font-weight: 700; color: var(--header-bg); display: block; margin-bottom: 3px; }

        /* Inputs in Sidebar */
        .radio-group label { margin-right: 8px; font-size: 0.9em; cursor: pointer; color: var(--text-main); }
        select.sidebar-select { width: 100%; border: none; background: transparent; font-family: inherit; font-size: inherit; color: inherit; cursor: pointer; padding: 0; }

        /* Skills */
        #skillsList { display: flex; flex-wrap: wrap; gap: 8px; }
        .skill-tag {
            background: white; border: 1px solid #ccc; padding: 5px 10px; 
            border-radius: 4px; font-size: 0.85em; display: inline-block;
            position: relative;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        /* Table */
        .edu-table { width: 100%; border-collapse: collapse; font-size: 0.9em; }
        .edu-table th { text-align: left; padding: 5px 0; border-bottom: 2px solid #ccc; color: var(--header-bg); font-weight: bold; }
        .edu-table td { padding-top: calc(var(--item-gap) * 0.5); padding-bottom: calc(var(--item-gap) * 0.5); border-bottom: 1px solid #eee; vertical-align: top; position: relative; padding-right: 15px; }

        /* Footer */
        .footer-sign {
            display: flex; justify-content: space-between; margin-top: 20px; align-items: flex-end; padding-top: 10px; border-top: 2px solid #eee;
        }
        .sign-line { border-top: 1px solid #333; width: 180px; margin-top: 10px; text-align: center; font-weight: bold; font-size: 0.9em; }

        /* --- BUTTON STYLES --- */
        .btn { padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 1rem; margin-top: 8px; width: 100%; transition: all 0.2s; display: block; text-align: center; }
        .btn-blue { background-color: #3498db; color: white; } .btn-blue:hover { background-color: #2980b9; }
        .btn-outline-blue { border: 2px solid #3498db; background-color: transparent; color: #3498db; } .btn-outline-blue:hover { background-color: #3498db; color: white; }
        .btn-primary { background-color: #2c3e50; color: white; } .btn-primary:hover { opacity: 0.9; }

        /* --- UI CONTROLS --- */
        .ui-controls, .add-btn, .remove-btn, .remove-btn-skill, .remove-section-btn { display: none; }
        body.edit-mode .ui-controls { display: block; margin-bottom: 5px;}
        body.edit-mode .add-btn { display: inline-block; background: var(--header-bg); color: white; border: none; padding: 5px 10px; font-size: 0.7rem; margin-top:10px; cursor: pointer; border-radius: 3px; }

        .remove-btn, .remove-btn-skill {
            position: absolute; background: #e74c3c; color: white; border: none; border-radius: 50%; cursor: pointer; 
            display: none; align-items: center; justify-content: center; z-index: 100; font-weight: bold;
        }
        .remove-btn { top: 0; right: 0; width: 20px; height: 20px; font-size: 10px; }
        .remove-btn-skill { top: -5px; right: -5px; width: 14px; height: 14px; font-size: 9px; }

        .remove-section-btn {
            position: absolute; top: 0; right: 0; background: #c0392b; color: white; border: none; padding: 5px 10px; font-size: 0.7rem; cursor: pointer; display: none; z-index: 500; border-radius: 0 0 0 5px;
        }

        body.edit-mode .entry:hover .remove-btn, 
        body.edit-mode .sidebar-item:hover .remove-btn-skill,
        body.edit-mode .skill-tag:hover .remove-btn-skill,
        body.edit-mode .contact-item:hover .remove-btn-skill,
        body.edit-mode tr:hover .remove-btn,
        body.edit-mode .section:hover .remove-section-btn { display: flex; }

        [contenteditable="true"] { border: 1px solid transparent; padding: 1px; min-width: 10px; transition: border-color 0.2s; }
        body.edit-mode [contenteditable="true"] { border-bottom: 1px dashed #ccc; background-color: rgba(255, 255, 255, 0.2); }
        body.edit-mode [contenteditable="true"]:focus { outline: 2px solid var(--accent-color); background-color: white; color: black; }
        .objective-controls select { width: 100%; padding: 5px; margin-bottom: 5px; }

        .editor-panel {
            width: 250px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            height: fit-content; position: fixed; right: 30px; top: 30px; display: flex; flex-direction: column; gap: 15px; z-index: 1000;
        }
        .editor-panel h3 { color: var(--header-bg); border-bottom: 2px solid var(--header-bg); padding-bottom: 10px; margin-bottom: 5px; }
        .control-group label { display: block; font-size: 0.85rem; font-weight: bold; color: #555; margin-bottom: 5px; }
        .control-group input[type="range"] { width: 100%; cursor: pointer; }

        @media print {
            @page { margin: 0; }
            body { background: white; padding: 0; display: block; -webkit-print-color-adjust: exact; margin: 0; }
            .editor-panel, .remove-section-btn, .add-btn, .remove-btn, .remove-btn-skill { display: none !important; }
            .resume-page { margin: 0; box-shadow: none; width: 100%; min-height: 100vh; padding: 0 !important; }
            .header-section { padding-left: var(--content-padding) !important; padding-right: var(--content-padding) !important; }
            
            .contact-bar { padding-left: var(--content-padding) !important; padding-right: var(--content-padding) !important; gap: var(--item-gap) !important; margin-bottom: 0 !important; }
            
            .main-content { padding: 30px var(--content-padding) !important; padding-top: var(--section-gap) !important; gap: var(--section-gap) !important; }
            .right-sidebar { width: var(--sidebar-width) !important; padding-top: var(--section-gap) !important; gap: var(--section-gap) !important; }
            .footer-sign { display: flex !important; flex-direction: row !important; justify-content: space-between !important; width: 100% !important; }
            
            #experienceSection, #educationSection, #personalList, #hobbiesList, .dynamic-list { gap: var(--item-gap) !important; }
            .section { margin-bottom: var(--section-gap) !important; }
            .edu-table td { padding-top: calc(var(--item-gap) * 0.5) !important; padding-bottom: calc(var(--item-gap) * 0.5) !important; }
            
            input[type='radio'] { display: none; }
            .radio-group label { display: none; } 
            .radio-group label.active-radio { display: inline; }
            select { -webkit-appearance: none; appearance: none; border: none; background: transparent; padding: 0; margin: 0; }

            .entry, .section { break-inside: avoid; }
            [contenteditable="true"] { border: none !important; padding: 0 !important; }
        }
        
        @media screen and (max-width: 1100px) {
            body { flex-direction: column-reverse; }
            .editor-panel { width: 100%; position: static; margin-bottom: 20px; }
            .resume-page { margin-right: 0; transform: scale(0.9); transform-origin: top center; }
        }
    </style>
</head>

<body class="edit-mode">

    <div class="resume-page" id="resumeArea">

        <header class="header-section">
            <h1 class="name" contenteditable="true">ALEXANDER SMITH</h1>
            <div class="job-title" contenteditable="true">SENIOR SOFTWARE ENGINEER</div>
        </header>

        <div class="contact-bar" id="contactList">
            <div class="contact-item">
                <span contenteditable="true">+1 (555) 123-4567</span>
                <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
            </div>
            <div class="contact-item">
                <span contenteditable="true">alex.smith@email.com</span>
                <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
            </div>
            <div class="contact-item">
                <span contenteditable="true">New York, NY</span>
                <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
            </div>
            <div class="contact-item">
                <span contenteditable="true">linkedin.com/in/alexsmith</span>
                <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
            </div>
            <button class="add-btn" onclick="addContact()" style="margin-left:10px;">+</button>
        </div>

        <div class="resume-body">
            
            <main class="main-content">
                
                <div class="section">
                    <div class="section-title">Professional Profile</div>
                    <div class="profile-text" contenteditable="true" style="line-height: var(--line-height);">
                        Executive-level software engineer with a strong background in developing scalable web applications. 
                        Committed to writing clean code and optimizing performance. Looking for a challenging role 
                        in a forward-thinking company.
                    </div>
                    <div class="ui-controls" style="margin-top:5px; background:#f9f9f9; padding:5px; font-size:0.8rem;">
                        <select onchange="if(this.value) { this.parentElement.previousElementSibling.innerText = this.value; this.selectedIndex = 0; }">
                            <option value="">-- Quick Profile Text --</option>
                            <option value="Dedicated professional with 5+ years of experience in...">Experience 5+</option>
                            <option value="Motivated fresher seeking an entry-level position to apply...">Fresher</option>
                        </select>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">
                        Experience
                        <div class="ui-controls" id="fresherControl" style="font-size:0.7rem; text-transform: none; display:inline-block; margin-left: 15px; font-weight: normal;">
                            <label><input type="checkbox" id="fresherCheck" onchange="toggleFresherMode()"> I am a Fresher</label>
                        </div>
                    </div>
                    
                    <div id="experienceSection">
                        <div class="entry">
                            <div class="entry-header">
                                <span class="entry-title" contenteditable="true">Senior Developer</span>
                                <span class="entry-date" contenteditable="true">2020 - Present</span>
                            </div>
                            <div class="entry-subtitle" contenteditable="true">Tech Solutions Inc. | New York</div>
                            <div class="entry-content" contenteditable="true">
                                • Led a team of 5 developers to create a new CRM system.<br>
                                • Reduced database query times by 30% through optimization.
                            </div>
                            <button class="remove-btn" onclick="removeElement(this.parentElement)">x</button>
                        </div>
                    </div>

                    <div id="fresherSection" style="display:none; font-style:italic; color:#777; margin-bottom:15px;">
                        <p>Fresher - Seeking entry level opportunities. (Optional: Add Internships below)</p>
                    </div>

                    <button class="add-btn" id="addExpBtn" onclick="addExperience()">+ Add Experience</button>
                </div>

                <div class="section">
                    <div class="section-title">Education</div>
                    <div id="educationSection">
                        <table class="edu-table" id="eduTable">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Year</th>
                                    <th style="width: 35%;">Degree / Course</th>
                                    <th style="width: 35%;">Institute / Board</th>
                                    <th style="width: 15%;">Grades</th>
                                    <th style="width: 0;"></th> </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div contenteditable="true">2016-2020</div></td>
                                    <td><div contenteditable="true">B.Tech CS</div></td>
                                    <td><div contenteditable="true">State University</div></td>
                                    <td><div contenteditable="true">8.5 CGPA</div></td>
                                    <td><button class="remove-btn" onclick="removeElement(this.parentElement.parentElement)">x</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button class="add-btn" onclick="addEducationRow()">+ Add Education Row</button>
                </div>

                <div id="extraSectionsMain"></div>
                <button class="add-btn" onclick="addMainSection()" style="width:auto; margin-top:20px;">+ Add Custom Section</button>

                <div class="section">
                    <div class="section-title">Declaration</div>
                    <div class="profile-text" contenteditable="true" style="font-style: italic; font-size: 0.9em; color: #666;">
                        I hereby declare that the above-mentioned information is correct to the best of my knowledge and belief.
                    </div>
                </div>

                <div class="footer-sign">
                    <div class="sign-box"><div contenteditable="true">Date: <?php echo date("d/m/Y"); ?></div></div>
                    <div class="sign-box"><div class="sign-line" contenteditable="true">Signature</div></div>
                </div>

            </main>

            <aside class="right-sidebar">
                
                <div class="section">
                    <div class="section-title">Personal Info</div>
                    <div id="personalList">
                        <div class="sidebar-item">
                            <span class="sidebar-label" contenteditable="true">Father's Name</span>
                            <div contenteditable="true">Mr. Robert Smith</div>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>

                        <div class="sidebar-item">
                            <span class="sidebar-label" contenteditable="true">Date of Birth</span>
                            <div contenteditable="true">March 15, 1990</div>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>
                        <div class="sidebar-item">
                            <span class="sidebar-label" contenteditable="true">Nationality</span>
                            <div contenteditable="true">American</div>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>
                        <div class="sidebar-item">
                            <span class="sidebar-label" contenteditable="true">Languages</span>
                            <div contenteditable="true">English, Spanish</div>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>

                        <div class="sidebar-item">
                            <span class="sidebar-label" contenteditable="true">Gender</span>
                            <div class="radio-group">
                                <label class="active-radio"><input type="radio" name="gender" value="Male" checked onclick="updateRadioClasses()"> Male</label>
                                <label><input type="radio" name="gender" value="Female" onclick="updateRadioClasses()"> Female</label>
                                <label><input type="radio" name="gender" value="Other" onclick="updateRadioClasses()"> Other</label>
                            </div>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>

                        <div class="sidebar-item">
                            <span class="sidebar-label" contenteditable="true">Marital Status</span>
                            <select class="sidebar-select">
                                <option value="Unmarried">Unmarried</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>
                    </div>
                    <button class="add-btn" onclick="addPersonalInfo()">+ Add Info</button>
                </div>

                <div class="section">
                    <div class="section-title">Skills</div>
                    <div id="skillsList">
                        <div class="skill-tag"><span contenteditable="true">PHP / Laravel</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                        <div class="skill-tag"><span contenteditable="true">JavaScript</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                        <div class="skill-tag"><span contenteditable="true">MySQL</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                        <div class="skill-tag"><span contenteditable="true">React JS</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    </div>
                    <button class="add-btn" onclick="addSkill()">+ Add Skill</button>
                </div>

                <div class="section">
                    <div class="section-title">Hobbies</div>
                    <div id="hobbiesList">
                        <div class="sidebar-item">
                            <span contenteditable="true">• Reading Sci-Fi</span>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>
                        <div class="sidebar-item">
                            <span contenteditable="true">• Photography</span>
                            <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                        </div>
                    </div>
                    <button class="add-btn" onclick="addHobby()">+ Add Hobby</button>
                </div>

                <div id="extraSectionsSidebar"></div>
                <button class="add-btn" onclick="addSidebarSection()" style="margin-top:20px;">+ Add List</button>

            </aside>

        </div>
    </div>

    <div class="editor-panel">
        <h3>Design Controls</h3>
        
        <div class="control-group">
            <label>View Mode</label>
            <button class="btn btn-outline-blue" onclick="toggleEditMode()" id="editToggleBtn">Lock / Preview</button>
        </div>

        <div class="control-group">
            <label>Font Size</label>
            <input type="range" min="12" max="16" value="14" oninput="updateVar('--base-font-size', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Sidebar Width</label>
            <input type="range" min="25" max="45" value="30" oninput="updateVar('--sidebar-width', this.value + '%')">
        </div>

        <div class="control-group">
            <label>Content Width</label>
            <input type="range" min="30" max="80" value="40" oninput="updateVar('--content-padding', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Section Gap</label>
            <input type="range" min="15" max="50" value="30" oninput="updateVar('--section-gap', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Item Gap</label>
            <input type="range" min="5" max="30" value="15" oninput="updateVar('--item-gap', this.value + 'px')">
        </div>

        <div style="margin-top:auto;">
            <form action="" method="POST" target="_blank" onsubmit="return false">
                <input type="hidden" name="resume_content" id="resumeContentInput">
                <input type="hidden" name="computed_styles" id="computedStylesInput">
                <button type="button" data-pdf-download="true" onclick="downloadResumePDF(event)" class="btn btn-primary">Save / Download</button>
            </form>
            <button class="btn btn-blue" onclick="window.print()">Print PDF</button>
        </div>
    </div>

    <script>
        window.onload = function() {
            const expSection = document.getElementById('experienceSection');
            if(expSection && expSection.children.length > 0) {
                document.getElementById('fresherControl').style.display = 'none';
            }
            updateRadioClasses(); // Init classes
        };

        function updateVar(variable, value) {
            document.documentElement.style.setProperty(variable, value);
        }

        function toggleEditMode() {
            document.body.classList.toggle('edit-mode');
            const btn = document.getElementById('editToggleBtn');
            if (document.body.classList.contains('edit-mode')) {
                btn.textContent = "Lock / Preview";
                btn.classList.add('btn-outline-blue');
                btn.classList.remove('btn-blue');
            } else {
                btn.textContent = "Back to Edit Mode";
                btn.classList.remove('btn-outline-blue');
                btn.classList.add('btn-blue');
            }
        }

        function removeElement(el) { 
            if(confirm('Delete this item?')) {
                el.remove(); 
                const expSection = document.getElementById('experienceSection');
                if(expSection && expSection.children.length === 0) {
                    const fresherControl = document.getElementById('fresherControl');
                    if(fresherControl) fresherControl.style.display = 'block'; 
                }
            }
        }

        function toggleFresherMode() {
            const isFresher = document.getElementById('fresherCheck').checked;
            const expSection = document.getElementById('experienceSection');
            const fresherMsg = document.getElementById('fresherSection');
            const addExpBtn = document.getElementById('addExpBtn');
            if (isFresher) {
                expSection.style.display = 'none'; addExpBtn.style.display = 'none'; fresherMsg.style.display = 'block';
            } else {
                expSection.style.display = 'flex';
                addExpBtn.style.display = ''; fresherMsg.style.display = 'none';
            }
        }

        function addExperience() {
            const container = document.getElementById('experienceSection');
            const newBlock = document.createElement('div');
            newBlock.className = 'entry';
            newBlock.innerHTML = `
                <div class="entry-header"><span class="entry-title" contenteditable="true">Job Title</span><span class="entry-date" contenteditable="true">Year - Year</span></div>
                <div class="entry-subtitle" contenteditable="true">Company Name | Location</div>
                <div class="entry-content" contenteditable="true">Description...</div>
                <button class="remove-btn" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newBlock);
            document.getElementById('fresherControl').style.display = 'none';
        }

        function addEducationRow() {
            const tbody = document.querySelector('#eduTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `<td><div contenteditable="true">Year</div></td><td><div contenteditable="true">Degree</div></td><td><div contenteditable="true">Institute</div></td><td><div contenteditable="true">Grade</div></td><td><button class="remove-btn" onclick="removeElement(this.parentElement.parentElement)">x</button></td>`;
            tbody.appendChild(newRow);
        }

        function addContact() {
            const container = document.getElementById('contactList');
            const newDiv = document.createElement('div');
            newDiv.className = 'contact-item';
            newDiv.innerHTML = `<span contenteditable="true">New Contact</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.insertBefore(newDiv, container.lastElementChild); // Insert before button
        }

        function addPersonalInfo() {
            const container = document.getElementById('personalList');
            const newDiv = document.createElement('div');
            newDiv.className = 'sidebar-item';
            newDiv.innerHTML = `<span class="sidebar-label" contenteditable="true">Label</span><div contenteditable="true">Value</div><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newDiv);
        }

        function addSkill() {
            const container = document.getElementById('skillsList');
            const newTag = document.createElement('div');
            newTag.className = 'skill-tag';
            newTag.innerHTML = `<span contenteditable="true">New Skill</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newTag);
        }

        function addHobby() {
            const container = document.getElementById('hobbiesList');
            const newTag = document.createElement('div');
            newTag.className = 'sidebar-item';
            newTag.innerHTML = `<span contenteditable="true">• New Hobby</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newTag);
        }

        function addMainSection() {
            const container = document.getElementById('extraSectionsMain');
            const div = document.createElement('div');
            div.className = 'section';
            div.style.position = 'relative';
            div.innerHTML = `
                <div class="section-title" contenteditable="true">NEW SECTION TITLE</div>
                <div class="entry">
                    <div class="entry-content" contenteditable="true">Add your details here. Click to edit.</div>
                </div>
                <button class="remove-section-btn" onclick="removeElement(this.parentElement)">Remove Section</button>
            `;
            container.appendChild(div);
        }

        function addSidebarSection() {
            const container = document.getElementById('extraSectionsSidebar');
            const div = document.createElement('div');
            div.className = 'section';
            div.style.position = 'relative';
            const uniqueId = 'customList_' + Date.now();
            div.innerHTML = `
                <div class="section-title" contenteditable="true" style="font-size:1em;">NEW LIST</div>
                <div id="${uniqueId}" class="dynamic-list">
                    <div class="sidebar-item">
                        <div contenteditable="true">• New Item</div>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                </div>
                <button class="add-btn" onclick="addCustomSidebarItem('${uniqueId}')">+ Add Item</button>
                <button class="remove-section-btn" onclick="removeElement(this.parentElement)">Remove Section</button>
            `;
            container.appendChild(div);
        }

        function addCustomSidebarItem(listId) {
            const container = document.getElementById(listId);
            const newDiv = document.createElement('div');
            newDiv.className = 'sidebar-item';
            newDiv.innerHTML = `<div contenteditable="true">• New Item</div><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newDiv);
        }

        // --- MANAGE RADIO CLASSES FOR PRINT ---
        function updateRadioClasses() {
            const allRadios = document.querySelectorAll('input[name="gender"]');
            allRadios.forEach(radio => {
                const label = radio.parentElement;
                if(radio.checked) {
                    label.classList.add('active-radio');
                } else {
                    label.classList.remove('active-radio');
                }
            });
        }

        function prepareSave() {
            const body = document.body;
            const wasEditing = body.classList.contains('edit-mode');
            if (wasEditing) body.classList.remove('edit-mode');
            
            // 1. Update Radio Classes & Attributes
            updateRadioClasses();
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(r => {
                if(r.checked) r.setAttribute('checked', 'checked');
                else r.removeAttribute('checked');
            });

            // 2. Update Select Attributes
            const selects = document.querySelectorAll('select');
            selects.forEach(s => {
                const val = s.value;
                const options = s.querySelectorAll('option');
                options.forEach(o => {
                    if(o.value === val) o.setAttribute('selected', 'selected');
                    else o.removeAttribute('selected');
                });
            });

            const style = getComputedStyle(document.documentElement);
            let cssVars = "";
            ['--base-font-size', '--section-gap', '--content-padding', '--item-gap', '--sidebar-width', '--line-height'].forEach(v => {
                cssVars += `${v}: ${style.getPropertyValue(v)}; `;
            });
            
            document.getElementById('computedStylesInput').value = cssVars;
            document.getElementById('resumeContentInput').value = document.getElementById('resumeArea').innerHTML;
            
            if (wasEditing) body.classList.add('edit-mode');
        }
    </script>
<script src="template_switcher.js?v=data-sync-4"></script>
</body>
</html>
