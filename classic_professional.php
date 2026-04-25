<?php
// Main PHP endpoint for the Resume Builder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_content'])) {
    $content = $_POST['resume_content'];
    $computedStyles = $_POST['computed_styles'];

    $filename = "resume_classic_professional_" . date('Y-m-d_H-i') . ".html";
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Classic Professional Resume</title>
        <style>
            :root { " . $computedStyles . " }
            body { font-family: 'Times New Roman', Times, serif; margin: 0; padding: 0; }
            .resume-container { width: 21cm; margin: 0 auto; }
            
            /* PDF PRINT STYLES */
            .resume-page { 
                width: 100%; 
                background: white;
                padding: var(--content-padding) !important;
            }
            
            .header-section { text-align: center; border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 15px; }
            .contact-bar { justify-content: center !important; }
            
            /* GAPS */
            .entry, .skill-item, .contact-item { margin-bottom: var(--item-gap) !important; }
            .section { margin-bottom: var(--section-gap) !important; }
            
            /* FOOTER */
            .footer-sign { display: flex !important; justify-content: space-between !important; margin-top: 30px; width: 100%; border-top: 1px solid #ccc; padding-top: 10px; }

            /* HIDE FORM ELEMENTS */
            input[type='radio'] { display: none; }
            .radio-group label { display: none; } 
            .radio-group label.active-radio { display: inline; }
            select { -webkit-appearance: none; appearance: none; border: none; background: transparent; padding: 0; margin: 0; font-family: inherit; font-size: inherit; }
            
            h1, h2, h3, h4, p, div { margin: 0; padding: 0; }
            [contenteditable] { border: none !important; }
            .remove-btn, .remove-btn-skill, .add-btn, .ui-controls, .editor-panel { display: none !important; }
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
    <title>Classic Professional Resume</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            /* Colors */
            --primary-color: #000000;
            --text-main: #000000;

            /* Layout */
            --page-width: 210mm;
            --page-height: 297mm;
            --content-padding: 40px;
            --section-gap: 25px;
            --item-gap: 15px;
            --base-font-size: 13px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Merriweather', 'Times New Roman', serif;
            background-color: #555;
            display: flex;
            justify-content: center;
            padding: 20px;
            font-size: var(--base-font-size);
            color: var(--text-main);
        }

        .resume-page {
            background: white;
            width: var(--page-width);
            min-height: var(--page-height);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            padding: var(--content-padding);
            position: relative;
        }

        /* --- HEADER --- */
        .header-section {
            text-align: center;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: var(--section-gap);
        }

        .header-name {
            font-size: 2.2rem;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 1px;
            color: var(--primary-color);
        }

        .contact-bar {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 0.9em;
        }

        .contact-item {
            position: relative;
            padding-right: 15px;
        }

        .contact-item:not(:last-child)::after {
            content: "•";
            position: absolute;
            right: 0px;
            top: 0;
            color: #666;
        }
        
        /* HEADER TITLE STYLE */
        .header-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 2px;
            margin-bottom: var(--item-gap);
        }

        /* --- SECTIONS --- */
        .section {
            margin-bottom: var(--section-gap);
        }

        .section-title {
            text-transform: uppercase;
            border-bottom: 1px solid var(--primary-color);
            font-weight: 700;
            font-size: 1.1em;
            margin-bottom: 15px;
            padding-bottom: 2px;
            color: var(--primary-color);
            position: relative;
        }

        .entry {
            margin-bottom: var(--item-gap);
            position: relative;
        }

        .entry-header {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 1em;
        }

        .entry-company {
            font-style: italic;
            margin-bottom: 5px;
        }

        .entry-content {
            line-height: 1.5;
            text-align: justify;
        }

        /* --- SKILLS & PERSONAL --- */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .skill-item,
        .personal-item {
            position: relative;
        }

        .personal-label {
            font-weight: bold;
            margin-right: 5px;
        }
        
        /* Apply gaps to specific areas as requested */
        .personal-item { margin-bottom: var(--item-gap); }
        .header-name { margin-bottom: var(--item-gap); }
        .contact-bar { margin-bottom: var(--item-gap); }
        #profileText { margin-bottom: var(--item-gap); }

        /* FOOTER */
        .footer-sign {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 10px;
            /* border-top: 1px solid #000; Removed as requested */
            align-items: flex-end;
        }

        .sign-box {
            text-align: center;
            font-size: 0.9em;
        }

        .sign-line {
            border-top: 1px solid var(--text-main);
            width: 180px;
            margin-top: 30px;
            font-weight: bold;
        }

        /* --- CONTROLS --- */
        .editor-panel {
            width: 200px;
            background: white;
            padding: 15px;
            position: fixed;
            top: 20px;
            right: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            z-index: 100;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            cursor: pointer;
        }

        .remove-btn {
            position: absolute;
            right: -15px;
            top: 0;
            color: white;
            background: red;
            font-weight: bold;
            cursor: pointer;
            display: none;
            font-family: sans-serif;
            border: none;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            align-items: center;
            justify-content: center;
        }

        body.edit-mode .entry:hover .remove-btn,
        body.edit-mode .contact-item:hover .remove-btn,
        body.edit-mode .skill-item:hover .remove-btn,
        body.edit-mode .personal-item:hover .remove-btn {
            display: flex;
        }

        [contenteditable]:focus {
            background: #eee;
            outline: none;
        }

        /* UI Controls */
        .ui-controls {
            margin-top: 10px;
            display: none;
            font-family: sans-serif;
        }

        body.edit-mode .ui-controls {
            display: block;
        }

        select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            background: transparent;
            /* For print */
            font-family: inherit;
            font-size: inherit;
        }

        @media print {
            select {
                -webkit-appearance: none;
                appearance: none;
                border: none;
                padding: 0;
            }
        }

        /* Gender Radio Handling */
        .radio-group label {
            cursor: pointer;
            margin-right: 10px;
        }

        /* Hide radio inputs but keep labels visible in edit mode */
        input[type='radio'] {
            margin-right: 5px;
        }

        @media print {
            select {
                -webkit-appearance: none;
                appearance: none;
                border: none;
                padding: 0;
            }

            /* In print, hide unchecked radios */
            .radio-group label {
                display: none;
            }

            .radio-group label.active-radio {
                display: inline-block;
            }

            input[type='radio'] {
                display: none;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .editor-panel,
            .add-btn,
            .remove-btn,
            .ui-controls {
                display: none !important;
            }

            .resume-page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                min-height: 100vh;
                padding: var(--content-padding) !important;
            }
        }
    </style>
</head>

<body class="edit-mode">
    <div class="resume-page" id="resumeArea">

        <header class="header-section">
            <h1 class="header-name" contenteditable="true">William P. Henderson</h1>
            <div class="header-title" contenteditable="true">Creative Director</div>
            <div class="contact-bar" id="contactList">
                <div class="contact-item"><span contenteditable="true">123 Business Rd, New York, NY</span><button
                        class="remove-btn" onclick="removeEl(this.parentElement)">x</button></div>
                <div class="contact-item"><span contenteditable="true">(555) 123-4567</span><button class="remove-btn"
                        onclick="removeEl(this.parentElement)">x</button></div>
                <div class="contact-item"><span contenteditable="true">william.henderson@email.com</span><button
                        class="remove-btn" onclick="removeEl(this.parentElement)">x</button></div>
            </div>
            <button class="add-btn" onclick="addContact()">+ Add Contact</button>
        </header>

        <div class="section">
            <div class="section-title">Professional Summary</div>
            <div contenteditable="true" id="profileText" style="line-height: 1.5;">
                Results-oriented Senior Manager with over 15 years of experience in business operations and strategy.
                Proven track record of improving efficiency and driving revenue growth.
            </div>
            <div class="ui-controls">
                <select onchange="updateProfile(this.value); this.selectedIndex=0;">
                    <option value="">-- Quick Profile Text --</option>
                    <option value="Dedicated professional with 5+ years of experience in...">Experience 5+</option>
                    <option value="Motivated fresher seeking an entry-level position...">Fresher</option>
                </select>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Personal Details</div>
            <div id="personalList" class="skills-grid">
                <div class="personal-item">
                    <span class="personal-label" contenteditable="true">Father's Name:</span>
                    <span contenteditable="true">Mr. Robert Smith</span>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
                <div class="personal-item">
                    <span class="personal-label" contenteditable="true">DOB:</span>
                    <span contenteditable="true">01/01/1980</span>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
                <div class="personal-item">
                    <span class="personal-label" contenteditable="true">Nationality:</span>
                    <span contenteditable="true">American</span>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
                <div class="personal-item">
                    <span class="personal-label" contenteditable="true">Languages:</span>
                    <span contenteditable="true">English, Spanish</span>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
                <div class="personal-item">
                    <span class="personal-label" contenteditable="true">Marital Status:</span>
                    <select style="display:inline-block; width:auto; border:none; border-bottom:1px solid #ccc;">
                        <option value="Unmarried">Unmarried</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
                <div class="personal-item">
                    <span class="personal-label" contenteditable="true">Gender:</span>
                    <span class="radio-group" style="display:inline;">
                        <label class="active-radio"><input type="radio" name="gender" value="Male" checked
                                onchange="updateRadio(this)"> Male</label>
                        <label><input type="radio" name="gender" value="Female" onchange="updateRadio(this)">
                            Female</label>
                    </span>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
            </div>
            <button class="add-btn" onclick="addPersonal()">+ Add Detail</button>
        </div>

        <div class="section">
            <div class="section-title">
                Experience
                <div class="ui-controls" id="fresherControl"
                    style="font-size:0.8rem; text-transform: none; position: absolute; right: 0; top: -5px; font-weight: normal; color: #555;">
                    <label><input type="checkbox" id="fresherCheck" onchange="toggleFresherMode()"> I am a
                        Fresher</label>
                </div>
            </div>
            <div id="experienceList">
                <div class="entry">
                    <div class="entry-header">
                        <span contenteditable="true">Senior Operations Manager</span>
                        <span contenteditable="true">2018 - Present</span>
                    </div>
                    <div class="entry-company" contenteditable="true">Global Corp Industries, New York, NY</div>
                    <div class="entry-content" contenteditable="true">
                        • Oversaw daily operations for a team of 50+ employees.<br>
                        • Implemented new strategic initiatives increasing productivity by 25%.
                    </div>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
            </div>
            <div id="fresherSection"
                style="display:none; text-align:center; font-style:italic; color:#777; margin-bottom:15px;">
                <p>Fresher - Seeking entry level opportunities.</p>
            </div>
            <button class="add-btn" id="addExpBtn" onclick="addExperience()">+ Add Position</button>
        </div>

        <div class="section">
            <div class="section-title">Education</div>
            <div id="educationList">
                <div class="entry">
                    <div class="entry-header">
                        <span contenteditable="true">Master of Business Administration</span>
                        <span contenteditable="true">2016</span>
                    </div>
                    <div class="entry-company" contenteditable="true">Harvard Business School</div>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
            </div>
            <button class="add-btn" onclick="addEducation()">+ Add Education</button>
        </div>

        <div class="skills-grid">
            <div class="section">
                <div class="section-title">Core Competencies</div>
                <div id="skillsList">
                    <div class="skill-item"><span contenteditable="true">• Project Management</span><button
                            class="remove-btn" onclick="removeEl(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">• Strategic Planning</span><button
                            class="remove-btn" onclick="removeEl(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addSkill()">+ Add Skill</button>
            </div>
            <div class="section">
                <div class="section-title">Hobbies</div>
                <div id="hobbiesList">
                    <div class="skill-item"><span contenteditable="true">• Golf</span><button class="remove-btn"
                            onclick="removeEl(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">• Reading History</span><button
                            class="remove-btn" onclick="removeEl(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addHobby()">+ Add Hobby</button>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Declaration</div>
            <div contenteditable="true">
                I hereby declare that the above-mentioned information is correct to the best of my knowledge and belief.
            </div>
        </div>

        <button class="add-btn"
            style="background: transparent; border: 2px dashed #ccc; padding: 10px; width: 100%; margin-bottom: 20px; cursor: pointer; color: #777;"
            onclick="addCustomSection()">+ Add Custom Section</button>

        <div class="footer-sign">
            <div class="sign-box">
                <div contenteditable="true">Date: <?php echo date("d/m/Y"); ?></div>
            </div>
            <div class="sign-box">
                <div class="sign-line" contenteditable="true">Signature</div>
            </div>
        </div>

    </div>

    <div class="editor-panel">
        <h3>Design Controls</h3>
        <button class="btn" style="margin-bottom:10px;" onclick="document.body.classList.toggle('edit-mode')">Toggle
            Preview</button>

        <label style="display:block; font-weight:bold; font-size:0.9em;">Core Colors</label>
        <div style="display:flex; gap:10px; margin-bottom:10px;">
            <div>
                <span style="font-size:0.8em">Primary</span>
                <input type="color" value="#000000" onchange="updateVar('--primary-color', this.value)"
                    style="width:100%">
            </div>
            <div>
                <span style="font-size:0.8em">Text</span>
                <input type="color" value="#000000" onchange="updateVar('--text-main', this.value)" style="width:100%">
            </div>
        </div>

        <label style="display:block; font-weight:bold; font-size:0.9em; margin-top:5px;">Font Size</label>
        <input type="range" min="10" max="18" value="13" oninput="updateVar('--base-font-size', this.value+'px')">

        <label style="display:block; font-weight:bold; font-size:0.9em; margin-top:5px;">Section Gap</label>
        <input type="range" min="10" max="60" value="25" oninput="updateVar('--section-gap', this.value+'px')">

        <label style="display:block; font-weight:bold; font-size:0.9em; margin-top:5px;">Content Padding</label>
        <input type="range" min="10" max="80" value="40" oninput="updateVar('--content-padding', this.value+'px')">

        <label style="display:block; font-weight:bold; font-size:0.9em; margin-top:5px;">Item Gap</label>
        <input type="range" min="5" max="30" value="15" oninput="updateVar('--item-gap', this.value+'px')">

        <form method="POST" onsubmit="return false" style="margin-top:20px;">
            <input type="hidden" name="resume_content" id="resumeContent">
            <input type="hidden" name="computed_styles" id="computedStyles">
            <button type="button" data-pdf-download="true" onclick="downloadResumePDF(event)" class="btn" style="background:#27ae60;">Save / Download</button>
        </form>
        <button class="btn" style="background:#3498db; margin-top:10px;" onclick="window.print()">Print PDF</button>
    </div>

    <script>
        function updateVar(name, val) { document.documentElement.style.setProperty(name, val); }

        function removeEl(el) { if (confirm('Delete?')) el.remove(); }

        function updateProfile(text) { if (text) document.getElementById('profileText').innerText = text; }

        function addContact() {
            const d = document.createElement('div'); d.className = 'contact-item';
            d.innerHTML = `<span contenteditable="true">New Contact</span><button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>`;
            document.getElementById('contactList').insertBefore(d, document.getElementById('contactList').lastElementChild);
        }

        function addExperience() {
            const d = document.createElement('div'); d.className = 'entry';
            d.innerHTML = `<div class="entry-header"><span contenteditable="true">Title</span><span contenteditable="true">Year</span></div><div class="entry-company" contenteditable="true">Company</div><div class="entry-content" contenteditable="true">• Duty 1...</div><button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>`;
            document.getElementById('experienceList').appendChild(d);
        }

        function addEducation() {
            const d = document.createElement('div'); d.className = 'entry';
            d.innerHTML = `<div class="entry-header"><span contenteditable="true">Degree</span><span contenteditable="true">Year</span></div><div class="entry-company" contenteditable="true">School</div><button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>`;
            document.getElementById('educationList').appendChild(d);
        }

        function addSkill() {
            const d = document.createElement('div'); d.className = 'skill-item';
            d.innerHTML = `<span contenteditable="true">• New Skill</span><button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>`;
            document.getElementById('skillsList').appendChild(d);
        }

        function addHobby() {
            const d = document.createElement('div'); d.className = 'skill-item';
            d.innerHTML = `<span contenteditable="true">• New Hobby</span><button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>`;
            document.getElementById('hobbiesList').appendChild(d);
        }

        function addPersonal() {
            const d = document.createElement('div'); d.className = 'personal-item';
            d.innerHTML = `<span class="personal-label" contenteditable="true">Label:</span><span contenteditable="true">Value</span><button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>`;
            document.getElementById('personalList').appendChild(d);
        }

        function addCustomSection() {
            const sec = document.createElement('div'); sec.className = 'section';
            sec.innerHTML = `
                <div class="section-title" contenteditable="true">New Section</div>
                <div class="entry">
                    <div class="entry-content" contenteditable="true">Enter details here...</div>
                    <button class="remove-btn" onclick="removeEl(this.parentElement)">x</button>
                </div>
                <button class="remove-btn" style="top:-25px; right:0; background:darkred;" onclick="removeEl(this.parentElement)">Remove Section</button>
            `;
            const btn = document.querySelector('button[onclick="addCustomSection()"]');
            btn.parentNode.insertBefore(sec, btn);
        }

        function updateRadio(input) {
            const group = input.closest('.radio-group');
            group.querySelectorAll('label').forEach(l => l.classList.remove('active-radio'));
            input.parentElement.classList.add('active-radio');
        }

        function toggleFresherMode() {
            const isFresher = document.getElementById('fresherCheck').checked;
            const expList = document.getElementById('experienceList');
            const fresherMsg = document.getElementById('fresherSection');
            const addBtn = document.getElementById('addExpBtn');

            if (isFresher) {
                expList.style.display = 'none';
                fresherMsg.style.display = 'block';
                addBtn.style.display = 'none';
            } else {
                expList.style.display = 'block';
                fresherMsg.style.display = 'none';
                addBtn.style.display = 'block';
            }
        }

        function prepareSave() {
            document.body.classList.remove('edit-mode');
            const style = getComputedStyle(document.documentElement);
            let cssVars = "";
            ['--primary-color', '--text-main', '--base-font-size', '--content-padding', '--section-gap', '--item-gap'].forEach(k => cssVars += `${k}: ${style.getPropertyValue(k)}; `);
            document.getElementById('computedStyles').value = cssVars;

            // Handle Radios and Selects
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(r => {
                if (r.checked) {
                    r.setAttribute('checked', 'checked');
                    r.parentElement.classList.add('active-radio');
                } else {
                    r.removeAttribute('checked');
                    r.parentElement.classList.remove('active-radio');
                }
            });

            const selects = document.querySelectorAll('select');
            selects.forEach(s => {
                const opts = s.querySelectorAll('option');
                opts.forEach(o => {
                    if (o.value === s.value) o.setAttribute('selected', 'selected');
                    else o.removeAttribute('selected');
                });
            });

            document.getElementById('resumeContent').value = document.getElementById('resumeArea').innerHTML;
            setTimeout(() => document.body.classList.add('edit-mode'), 500);
        }
    </script>
<script src="template_switcher.js?v=data-sync-4"></script>
</body>

</html>
