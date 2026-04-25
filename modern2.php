<?php
// Main PHP endpoint for the Resume Builder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_content'])) {
    $content = $_POST['resume_content'];
    $computedStyles = $_POST['computed_styles'];

    $filename = "resume_modern_" . date('Y-m-d_H-i') . ".html";
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Modern Resume</title>
        <style>
            :root { " . $computedStyles . " }
            body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; }
            .resume-container { width: 21cm; margin: 0 auto; }
            
            /* RE-APPLY LAYOUT FOR PDF */
            .resume-page { display: flex !important; flex-direction: row !important; width: 100%; min-height: 297mm; }
            .left-panel { width: var(--sidebar-width) !important; background-color: #2c3e50 !important; color: white !important; -webkit-print-color-adjust: exact; padding: 30px 20px; }
            .right-panel { flex: 1 !important; padding: 30px var(--content-padding); background-color: white !important; }
            
            /* FORCE SIGNATURE TO RIGHT */
            .footer-sign { display: flex !important; justify-content: space-between !important; margin-top: 30px; width: 100%; }
            
            /* GAP FIXES */
            .entry, .info-item, #fresherSection p { margin-bottom: var(--item-gap); }
            .section { margin-bottom: var(--section-gap); }
            .section-right { margin-bottom: var(--section-gap); }
            
            /* TABLE FIX */
            .edu-table td { padding-top: calc(var(--item-gap) * 0.5); padding-bottom: calc(var(--item-gap) * 0.5); }

            /* PRINT LOGIC FOR INPUTS */
            input[type='radio'] { display: none; }
            .radio-group label { display: none; } 
            .radio-group label.active-radio { display: inline; }
            select { -webkit-appearance: none; appearance: none; border: none; background: transparent; padding: 0; margin: 0; color: inherit; }
            
            /* DARK MODE SELECT FIX FOR PRINT (Force White Text on Sidebar) */
            .left-panel select, .left-panel .radio-group { color: white !important; }

            h1, h2, h3, h4, p, div { margin: 0; padding: 0; }
            .skill-tag { border: 1px solid rgba(255,255,255,0.3); }
            .edu-table { width: 100%; border-collapse: collapse; }
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
    <title>Modern Resume Builder</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Colors */
            --primary-color: #2c3e50; /* Sidebar Dark */
            --accent-color: #3498db;  /* Blue Highlights */
            --text-color: #333;
            
            /* Dimensions */
            --page-width: 210mm;
            --page-height: 297mm;
            
            /* ADJUSTABLE DEFAULTS */
            --base-font-size: 14px;
            --section-gap: 25px;
            --item-gap: 15px;
            --content-padding: 40px;
            --sidebar-width: 35%; /* Percentage width of sidebar */
            --line-height: 1.5;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #555;
            color: var(--text-color);
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
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex; /* Flex row for Side-by-Side */
            margin-right: 20px;
            transition: all 0.2s ease;
        }

        /* --- LEFT PANEL (SIDEBAR) --- */
        .left-panel {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .left-panel h3 {
            font-size: 1.1em;
            text-transform: uppercase;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: white;
            letter-spacing: 1px;
        }

        .left-panel .section { margin-bottom: var(--section-gap); }

        .info-item {
            margin-bottom: var(--item-gap);
            font-size: 0.9em;
            position: relative;
        }

        .info-label {
            font-weight: bold; color: #bdc3c7; display: block; margin-bottom: 2px; font-size: 0.85em;
        }

        /* Sidebar Inputs Styling */
        .left-panel select {
            background: transparent; border: none; color: white; width: 100%; cursor: pointer; font-family: inherit; font-size: inherit; padding: 0;
        }
        .left-panel option { color: #333; } /* Dark Text for dropdown options */
        
        .radio-group label { margin-right: 10px; cursor: pointer; font-size: 0.9em; }
        .radio-group input { margin-right: 5px; }

        /* Skills */
        .skills-container { display: flex; flex-wrap: wrap; gap: 8px; }
        .skill-tag {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            position: relative;
        }

        /* --- RIGHT PANEL (CONTENT) --- */
        .right-panel {
            flex: 1; /* Takes remaining width */
            padding: 30px var(--content-padding);
            background-color: white;
            display: flex;
            flex-direction: column;
        }

        .right-panel header { margin-bottom: var(--section-gap); }
        
        h1.name { font-size: 2.5em; font-weight: 700; color: var(--primary-color); line-height: 1.2; margin-bottom: 5px; }
        .job-title { font-size: 1.2em; color: var(--accent-color); font-weight: 500; margin-bottom: 0; }

        .section-right { position: relative; margin-bottom: var(--section-gap); }

        .section-title {
            font-size: 1.3em; color: var(--primary-color); text-transform: uppercase;
            border-bottom: 2px solid #eee; padding-bottom: 4px; margin-bottom: 15px; font-weight: 700;
            display: flex; align-items: center; justify-content: space-between;
        }

        /* Entries */
        .entry { margin-bottom: var(--item-gap); position: relative; padding-right: 25px; page-break-inside: avoid; }
        .entry-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px; }
        .entry-title { font-weight: 700; font-size: 1.05em; color: #333; }
        .entry-date { font-size: 0.85em; color: #7f8c8d; font-style: italic; min-width: 100px; text-align: right; }
        .entry-subtitle { font-weight: 500; color: var(--accent-color); font-size: 0.95em; margin-bottom: 5px; }
        .entry-content { font-size: 0.95em; color: #555; line-height: var(--line-height); }

        /* Tables */
        .edu-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .edu-table td { padding: 5px 0; vertical-align: top; }

        /* Footer */
        .footer-sign { display: flex; justify-content: space-between; margin-top: 30px; align-items: flex-end; width: 100%; }
        .sign-line { border-top: 1px solid #333; width: 150px; margin-top: 20px; text-align: center; font-weight: bold; }

        /* --- BUTTON STYLES --- */
        .btn {
            padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 1rem;
            margin-top: 8px; width: 100%; transition: all 0.2s; display: block; text-align: center;
        }
        .btn-blue { background-color: #3498db; color: white; }
        .btn-blue:hover { background-color: #2980b9; }
        .btn-outline-blue { border: 2px solid #3498db; background-color: transparent; color: #3498db; }
        .btn-outline-blue:hover { background-color: #3498db; color: white; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { opacity: 0.9; }

        /* --- UI CONTROLS --- */
        .ui-controls, .add-btn, .remove-btn, .remove-btn-skill { display: none; }
        body.edit-mode .ui-controls { display: block; margin-top: 5px;}
        body.edit-mode .add-btn { display: inline-block; background: var(--accent-color); color: white; border: none; padding: 5px 10px; font-size: 0.7rem; margin-top:5px; cursor: pointer; }

        .remove-btn, .remove-btn-skill {
            position: absolute; background: #e74c3c; color: white; border: none; border-radius: 50%; cursor: pointer; 
            display: none; align-items: center; justify-content: center; z-index: 100; font-weight: bold;
        }
        .remove-btn { top: 0; right: 0; width: 20px; height: 20px; font-size: 10px; }
        .remove-btn-skill { top: -5px; right: -5px; width: 14px; height: 14px; font-size: 9px; }

        /* Custom Section Remove Button */
        .remove-section-btn {
            background: #c0392b; color: white; border: none; padding: 4px 8px; font-size: 0.7rem; cursor: pointer; border-radius: 3px;
        }

        /* Hover Logic */
        body.edit-mode .entry:hover .remove-btn, 
        body.edit-mode .info-item:hover .remove-btn-skill, /* For sidebar items */
        body.edit-mode .skill-tag:hover .remove-btn-skill,
        body.edit-mode tr:hover .remove-btn { display: flex; }

        /* Edit Mode Styles */
        [contenteditable="true"] { border: 1px solid transparent; padding: 1px; min-width: 10px; transition: border-color 0.2s; }
        body.edit-mode [contenteditable="true"] { border-bottom: 1px dashed #ccc; background-color: rgba(255, 255, 200, 0.2); }
        /* Special handling for Sidebar inputs to make them visible */
        .left-panel body.edit-mode [contenteditable="true"] { background-color: rgba(255, 255, 255, 0.15); border-bottom: 1px dashed rgba(255,255,255,0.5); }
        body.edit-mode [contenteditable="true"]:focus { outline: 2px solid var(--accent-color); background-color: white; color: #2c3e50 !important; }
        
        .objective-controls select { width: 100%; padding: 5px; margin-bottom: 5px; }

        /* --- EDITOR PANEL --- */
        .editor-panel {
            width: 250px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            height: fit-content; position: fixed; right: 30px; top: 30px; display: flex; flex-direction: column; gap: 15px; z-index: 1000;
        }
        .editor-panel h3 { color: var(--primary-color); border-bottom: 2px solid var(--accent-color); padding-bottom: 10px; margin-bottom: 5px; }
        .control-group label { display: block; font-size: 0.85rem; font-weight: bold; color: #555; margin-bottom: 5px; }
        .control-group input[type="range"] { width: 100%; cursor: pointer; }

        /* --- PRINT SETTINGS --- */
        @media print {
            @page { margin: 0; }
            body { background: white; padding: 0; display: block; -webkit-print-color-adjust: exact; margin: 0; }
            .editor-panel, .add-btn, .remove-btn, .remove-btn-skill, .remove-section-btn { display: none !important; }
            
            .resume-page { margin: 0; box-shadow: none; width: 100%; min-height: 100vh; padding: 0 !important; }
            
            .left-panel { width: var(--sidebar-width) !important; padding: 30px 20px !important; min-height: 100vh !important; }
            .right-panel { width: auto !important; padding: 30px var(--content-padding) !important; }

            .footer-sign { display: flex !important; flex-direction: row !important; justify-content: space-between !important; width: 100% !important; }
            .entry, .info-item { margin-bottom: var(--item-gap) !important; }
            .section { margin-bottom: var(--section-gap) !important; }
            
            /* Hide Logic for Form Elements */
            input[type='radio'] { display: none; }
            .radio-group label { display: none; } 
            .radio-group label.active-radio { display: inline; }
            select { -webkit-appearance: none; appearance: none; border: none; background: transparent; padding: 0; margin: 0; }
            
            .section, .entry { break-inside: avoid; }
            [contenteditable="true"] { border: none !important; padding: 0 !important; }
        }
        
        @media screen and (max-width: 1100px) {
            body { flex-direction: column-reverse; }
            .editor-panel { width: 100%; position: static; margin-bottom: 20px; }
            .resume-page { margin-right: 0; flex-direction: column; transform: scale(0.9); transform-origin: top center; }
            .left-panel, .right-panel { width: 100%; }
        }
    </style>
</head>

<body class="edit-mode">

    <div class="resume-page" id="resumeArea">

        <div class="left-panel">
            
            <div class="section">
                <h3>Contact</h3>
                <div id="contactList">
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Phone</span>
                        <div contenteditable="true">+1 (555) 123-4567</div>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Email</span>
                        <div contenteditable="true">email@example.com</div>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Address</span>
                        <div contenteditable="true">New York, NY</div>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                </div>
                <button class="add-btn" onclick="addContact()">+ Add Contact</button>
            </div>

            <div class="section">
                <h3>Personal Info</h3>
                
                <div class="info-item">
                    <span class="info-label" contenteditable="true">Father's Name</span>
                    <div contenteditable="true">Mr. John Doe Sr.</div>
                </div>

                <div class="info-item">
                    <span class="info-label" contenteditable="true">Date of Birth</span>
                    <div contenteditable="true">01 Jan 1995</div>
                </div>

                <div class="info-item">
                    <span class="info-label" contenteditable="true">Nationality</span>
                    <div contenteditable="true">Citizen</div>
                </div>

                <div class="info-item">
                    <span class="info-label" contenteditable="true">Languages</span>
                    <div contenteditable="true">English, Spanish</div>
                </div>

                <div class="info-item">
                    <span class="info-label" contenteditable="true">Gender</span>
                    <div class="radio-group">
                        <label class="active-radio"><input type="radio" name="gender" value="Male" checked onclick="updateRadioClasses()"> Male</label>
                        <label><input type="radio" name="gender" value="Female" onclick="updateRadioClasses()"> Female</label>
                        <label><input type="radio" name="gender" value="Other" onclick="updateRadioClasses()"> Other</label>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label" contenteditable="true">Marital Status</span>
                    <select>
                        <option value="Unmarried">Unmarried</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                </div>
            </div>

            <div class="section">
                <h3>Skills</h3>
                <div class="skills-container" id="skillsList">
                    <div class="skill-tag"><span contenteditable="true">HTML</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-tag"><span contenteditable="true">CSS</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-tag"><span contenteditable="true">PHP</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addSkill()">+ Add Skill</button>
            </div>

            <div class="section">
                <h3>Hobbies</h3>
                <div id="hobbiesList">
                    <div class="skill-tag" style="display:block; margin-bottom:5px;"><span contenteditable="true">Reading</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addHobby()">+ Add Hobby</button>
            </div>

        </div> 

        <div class="right-panel">

            <header>
                <h1 class="name" contenteditable="true">JOHN DOE</h1>
                <div class="job-title" contenteditable="true">WEB DEVELOPER</div>
            </header>

            <div class="section-right">
                <div class="section-title">Objective</div>
                <div id="objectiveText" contenteditable="true" style="min-height: 40px; line-height:1.6;">
                    Seeking a full-time position where I can apply my technical skills and contribute to organizational growth.
                </div>
                <div class="objective-controls ui-controls">
                    <select onchange="if(this.value) { document.getElementById('objectiveText').innerText = this.value; this.selectedIndex = 0; }">
                        <option value="">-- Select Objective --</option>
                        <option value="Seeking a full-time position where I can apply my technical skills...">Full Time Growth</option>
                        <option value="To obtain a challenging role that allows me to enhance my skills...">Challenging Role</option>
                        <option value="Looking for an entry-level opportunity to start my career...">Entry Level</option>
                    </select>
                </div>
            </div>

            <div class="section-right">
                <div class="section-title">
                    Experience
                    <div class="ui-controls" id="fresherControl" style="font-size:0.7rem; text-transform: none; position: absolute; right: 0; font-weight: normal;">
                        <label><input type="checkbox" id="fresherCheck" onchange="toggleFresherMode()"> I am a Fresher</label>
                    </div>
                </div>
                <div id="experienceSection">
                    <div class="entry">
                        <div class="entry-header">
                            <div class="entry-title" contenteditable="true">Senior Web Developer</div>
                            <div class="entry-date" contenteditable="true">2020 - Present</div>
                        </div>
                        <div class="entry-subtitle" contenteditable="true">Tech Solutions Inc.</div>
                        <div class="entry-content" contenteditable="true">Led development of multiple web applications using PHP and React.</div>
                        <button class="remove-btn" onclick="removeElement(this.parentElement)">X</button>
                    </div>
                </div>
                <div id="fresherSection" style="display:none; font-style:italic; color:#777; margin-bottom:15px;"><p>Fresher - Seeking entry level opportunities.</p></div>
                <button class="add-btn" id="addExpBtn" onclick="addExperience()">+ Add Experience</button>
            </div>

            <div class="section-right">
                <div class="section-title">Education</div>
                <div id="educationSection">
                    <div class="entry">
                        <div class="entry-header">
                            <div class="entry-title" contenteditable="true">Bachelor of Computer Science</div>
                            <div class="entry-date" contenteditable="true">2014 - 2018</div>
                        </div>
                        <div class="entry-subtitle" contenteditable="true">State University</div>
                        <div class="entry-content" contenteditable="true">CGPA: 8.5/10</div>
                        <button class="remove-btn" onclick="removeElement(this.parentElement)">X</button>
                    </div>
                </div>
                <button class="add-btn" onclick="addEducation()">+ Add Education</button>
            </div>

            <div id="customSectionsContainer"></div>
            <button class="add-btn" style="width: auto; margin-bottom: 20px;" onclick="addCustomSection()">+ Add Custom Section</button>

            <div class="section-right">
                <div class="section-title">Declaration</div>
                <div class="declaration-text" contenteditable="true">
                    I hereby declare that the above-mentioned information is correct to the best of my knowledge and belief.
                </div>
            </div>

            <div class="footer-sign">
                <div class="sign-box"><div contenteditable="true">Date: <?php echo date("d/m/Y"); ?></div></div>
                <div class="sign-box"><div class="sign-line" contenteditable="true">Signature</div></div>
            </div>

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
            <input type="range" min="12" max="18" value="14" oninput="updateVar('--base-font-size', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Sidebar Width</label>
            <input type="range" min="25" max="45" value="35" oninput="updateVar('--sidebar-width', this.value + '%')">
        </div>

        <div class="control-group">
            <label>Content Padding</label>
            <input type="range" min="20" max="60" value="40" oninput="updateVar('--content-padding', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Section Gap</label>
            <input type="range" min="10" max="50" value="25" oninput="updateVar('--section-gap', this.value + 'px')">
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
            updateRadioClasses();
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
                expSection.style.display = 'block'; addExpBtn.style.display = ''; fresherMsg.style.display = 'none';
            }
        }

        function addExperience() {
            const container = document.getElementById('experienceSection');
            const newBlock = document.createElement('div');
            newBlock.className = 'entry';
            newBlock.innerHTML = `
                <div class="entry-header"><div class="entry-title" contenteditable="true">Job Title</div><div class="entry-date" contenteditable="true">Year - Year</div></div>
                <div class="entry-subtitle" contenteditable="true">Company Name</div>
                <div class="entry-content" contenteditable="true">Description...</div>
                <button class="remove-btn" onclick="removeElement(this.parentElement)">X</button>`;
            container.appendChild(newBlock);
            document.getElementById('fresherControl').style.display = 'none';
        }

        function addEducation() {
            const container = document.getElementById('educationSection');
            const newBlock = document.createElement('div');
            newBlock.className = 'entry';
            newBlock.innerHTML = `
                <div class="entry-header"><div class="entry-title" contenteditable="true">Degree</div><div class="entry-date" contenteditable="true">Year</div></div>
                <div class="entry-subtitle" contenteditable="true">University</div>
                <div class="entry-content" contenteditable="true">Grade/CGPA</div>
                <button class="remove-btn" onclick="removeElement(this.parentElement)">X</button>`;
            container.appendChild(newBlock);
        }

        function addContact() {
            const container = document.getElementById('contactList');
            const newDiv = document.createElement('div');
            newDiv.className = 'info-item';
            newDiv.innerHTML = `<span class="info-label" contenteditable="true">New Label</span><div contenteditable="true">Value</div><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
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
            newTag.className = 'skill-tag';
            newTag.style.display = 'block'; newTag.style.marginBottom = '5px';
            newTag.innerHTML = `<span contenteditable="true">New Hobby</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newTag);
        }

        // --- NEW FUNCTION: ADD CUSTOM SECTION ---
        function addCustomSection() {
            const container = document.getElementById('customSectionsContainer');
            const newSection = document.createElement('div');
            newSection.className = 'section-right';
            newSection.innerHTML = `
                <div class="section-title">
                    <span contenteditable="true">New Section</span>
                    <button class="remove-section-btn" onclick="removeElement(this.parentElement.parentElement)" style="display:block;">Remove</button>
                </div>
                <div class="entry">
                    <div class="entry-content" contenteditable="true">Add content here...</div>
                </div>
            `;
            container.appendChild(newSection);
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
            
            // 1. Update Radio Classes
            updateRadioClasses();
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(r => {
                if(r.checked) r.setAttribute('checked', 'checked');
                else r.removeAttribute('checked');
            });

            // 2. Update Selects
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
