<?php
// Main PHP endpoint for the Resume Builder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_content'])) {
    $content = $_POST['resume_content'];
    $computedStyles = $_POST['computed_styles'];

    $filename = "resume_gurpinder_pro_" . date('Y-m-d_H-i') . ".html";
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Professional Resume</title>
        <style>
            :root { " . $computedStyles . " }
            body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: #fff; }
            
            /* GLOBAL PRINT STYLES */
            .resume-wrapper { width: 210mm; margin: 0 auto; }
            .resume-page { 
                width: 100%; 
                min-height: 297mm; 
                padding: var(--content-padding); 
                background: white; 
                position: relative;
                box-sizing: border-box;
                overflow: hidden;
            }

            /* HIDE UI ELEMENTS IN SAVED FILE */
            .remove-btn, .add-btn, .ui-controls, .editor-panel, .page-label, .fresher-controls, .btn-dashed { display: none !important; }

            .ui-controls { margin-top: 10px; display: none; font-family: sans-serif; }
            body.edit-mode .ui-controls { display: block; }

            /* DESIGN SPECIFIC FROM IMAGES */
            .main-heading { text-align: center; text-decoration: underline; font-weight: bold; font-size: 1.4rem; margin-bottom: var(--section-gap); text-transform: uppercase; }
            
            .contact-block { margin-bottom: var(--section-gap); line-height: 1.5; text-align: left; }
            .contact-block .name { font-weight: bold; font-size: 1.3rem; margin-bottom: 5px; display: block; }
            .contact-line { display: block; position: relative; }
            .contact-line a { color: blue; text-decoration: underline; }

            .section-header { 
                background-color: #f0f0f0; 
                padding: 6px 10px; 
                font-weight: bold; 
                font-size: 1.05em; 
                margin-bottom: var(--item-gap); 
                margin-top: var(--section-gap);
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
                display: flex; justify-content: space-between; align-items: center;
            }

            /* 2-COLUMN GRID FOR HOBBIES/SKILLS */
            .grid-2-col { display: flex; gap: 20px; justify-content: space-between; }
            .col-half { width: 48%; }

            /* LIST STYLES */
            ul.custom-list { padding-left: 20px; margin: 0; }
            ul.custom-list li { margin-bottom: var(--item-gap); list-style-type: none; position: relative; }
            ul.custom-list li::before { content: '➢'; position: absolute; left: -20px; font-size: 0.8rem; top: 2px;}

            /* PERSONAL DETAILS GRID */
            .details-table { display: table; width: 100%; border-spacing: 0 var(--item-gap); }
            .details-row { display: table-row; }
            .details-label { display: table-cell; font-weight: bold; width: 180px; vertical-align: top; }
            .details-colon { display: table-cell; width: 20px; vertical-align: top; font-weight: bold; }
            .details-value { display: table-cell; vertical-align: top; }

            /* DECLARATION & SIGNATURE */
            .declaration-text { text-align: justify; margin-bottom: 60px; line-height: 1.6; margin-top: var(--item-gap); }
            .footer-sign { display: flex !important; justify-content: space-between !important; margin-top: 50px; width: 100%; align-items: flex-end; }
            .sign-label { font-weight: bold; margin-bottom: 30px; }

            /* CLEAN INPUTS */
            h1, h2, h3, h4, p, div { margin: 0; padding: 0; }
            [contenteditable] { border: none !important; }
            select { appearance: none; -webkit-appearance: none; border: none; background: transparent; font-family: inherit; font-size: inherit; padding: 0; }
            input[type='radio'] { display: none; }
            .radio-group label { display: none; }
            .radio-group label.active-radio { display: inline; }
        </style>
    </head>
    <body>
        <div class='resume-wrapper'>
            " . $content . "
        </div>
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
    <title>Professional Resume Builder</title>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* CSS VARIABLES */
        :root {
            --text-main: #000000;
            --page-width: 210mm;
            --page-height: 297mm;
            --base-font-size: 14px;
            --content-padding: 45px;
            --line-height: 1.6;

            /* DYNAMIC GAPS */
            --section-gap: 25px;
            --item-gap: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #555;
            color: var(--text-main);
            display: flex;
            flex-direction: row;
            justify-content: center;
            padding: 20px;
            font-size: var(--base-font-size);
        }

        /* --- EDITOR CONTAINER --- */
        #exportContainer {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* --- RESUME PAGE CARD --- */
        .resume-page {
            background: white;
            width: var(--page-width);
            height: var(--page-height);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
            padding: var(--content-padding);
            position: relative;
            overflow: hidden;
        }

        /* --- SPECIFIC DESIGN STYLES --- */
        .main-heading {
            text-align: center;
            text-decoration: underline;
            font-weight: bold;
            font-size: 1.5em;
            margin-bottom: var(--section-gap);
            text-transform: uppercase;
        }

        .contact-block {
            margin-bottom: var(--section-gap);
            line-height: 1.5;
        }

        .contact-block .name {
            font-weight: bold;
            font-size: 1.3em;
            margin-bottom: 5px;
            display: block;
        }

        .contact-line {
            display: block;
            position: relative;
            margin-bottom: 2px;
        }

        .contact-line a {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        .section-header {
            background-color: #f0f0f0;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 1.05em;
            margin-bottom: var(--item-gap);
            margin-top: var(--section-gap);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Two Column Layout */
        .grid-2-col {
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }

        .col-half {
            width: 48%;
        }

        /* Fresher Controls (Hidden in print) */
        .fresher-controls {
            font-size: 0.8rem;
            font-weight: normal;
            display: none;
        }

        .fresher-controls label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* List Styling */
        ul.custom-list {
            padding-left: 20px;
            margin: 0;
        }

        ul.custom-list li {
            margin-bottom: var(--item-gap);
            list-style-type: none;
            position: relative;
        }

        ul.custom-list li::before {
            content: '➢';
            position: absolute;
            left: -20px;
            font-size: 0.8em;
            top: 2px;
        }

        /* Personal Details Grid */
        .details-table {
            display: table;
            width: 100%;
            border-spacing: 0 var(--item-gap);
        }

        .details-row {
            display: table-row;
        }

        .details-label {
            display: table-cell;
            font-weight: bold;
            width: 180px;
            vertical-align: top;
        }

        .details-colon {
            display: table-cell;
            width: 20px;
            vertical-align: top;
            font-weight: bold;
        }

        .details-value {
            display: table-cell;
            vertical-align: top;
        }

        /* Declaration & Footer */
        .declaration-text {
            text-align: justify;
            margin-bottom: 60px;
            line-height: 1.6;
            margin-top: var(--item-gap);
        }

        .footer-sign {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            width: 100%;
            align-items: flex-end;
        }

        .sign-label {
            font-weight: bold;
            margin-right: 5px;
            margin-bottom: 30px;
            display: block;
        }

        /* UI Buttons */
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.2s;
            display: block;
            text-align: center;
            margin-top: 8px;
        }

        .btn-blue {
            background-color: #3498db;
            color: white;
        }

        .btn-outline-blue {
            border: 2px solid #3498db;
            background-color: transparent;
            color: #3498db;
        }

        .btn-primary {
            background-color: #333;
            color: white;
        }

        .btn-dashed {
            background: transparent;
            border: 2px dashed #999;
            color: #666;
            font-size: 0.9rem;
            margin-top: 15px;
            width: 100%;
            display: none;
            cursor: pointer;
            padding: 8px;
        }

        /* Edit Mode specific */
        [contenteditable="true"]:focus {
            outline: 2px solid #3498db;
            background: rgba(52, 152, 219, 0.1);
        }

        .remove-btn {
            color: red;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1em;
            margin-left: 10px;
            display: none;
            vertical-align: middle;
        }

        .add-btn {
            background: #eee;
            border: 1px dashed #999;
            width: 100%;
            padding: 5px;
            cursor: pointer;
            margin-top: 5px;
            display: none;
            font-size: 0.8rem;
        }

        body.edit-mode .remove-btn,
        body.edit-mode .add-btn,
        body.edit-mode .btn-dashed {
            display: inline-block;
        }

        body.edit-mode select {
            border-bottom: 1px dashed #ccc;
        }

        /* Form Controls hidden styling */
        select {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: inherit;
            cursor: pointer;
            padding: 0;
            width: 100%;
        }

        .radio-group label {
            margin-right: 15px;
            cursor: pointer;
        }

        /* Editor Panel */
        .editor-panel {
            width: 250px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            height: fit-content;
            position: fixed;
            right: 30px;
            top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
        }

        /* --- PRINT SETTINGS --- */
        @media print {
            @page {
                margin: 0;
                size: A4;
            }

            body {
                background: white;
                padding: 0;
                display: block;
                margin: 0;
            }

            /* Hide ALL UI elements */
            .editor-panel,
            .add-btn,
            .remove-btn,
            .page-label,
            #editToggleBtn,
            .btn,
            .fresher-controls,
            .btn-dashed {
                display: none !important;
            }

            #exportContainer {
                gap: 0;
                display: block;
            }

            .resume-page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                height: 100%;
                min-height: 297mm;
                page-break-after: always;
                padding: var(--content-padding) !important;
                overflow: visible;
                border: none;
            }

            .resume-page:last-child {
                page-break-after: auto;
            }

            /* Clean Form Elements */
            input[type='radio'] {
                display: none;
            }

            .radio-group label {
                display: none;
            }

            .radio-group label.active-radio {
                display: inline;
            }

            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }
        }

        @media screen and (max-width: 1100px) {
            body {
                flex-direction: column-reverse;
                align-items: center;
            }

            .editor-panel {
                width: 100%;
                position: static;
                margin-bottom: 20px;
            }

            .resume-page {
                transform: scale(0.9);
                transform-origin: top center;
                margin-bottom: -50px;
            }
        }
    </style>
</head>

<body class="edit-mode">

    <div id="exportContainer">

        <div class="resume-page" id="page1">

            <div class="main-heading" contenteditable="true">CURRICULUM VITAE</div>

            <div class="contact-block">
                <span class="name" contenteditable="true">Gurpinder Singh</span>

                <div id="contactContainer">
                    <div class="contact-line">
                        <span contenteditable="true">Village Chak Duhewala</span>
                        <span class="remove-btn" onclick="removeItem(this)">x</span>
                    </div>
                    <div class="contact-line">
                        <span contenteditable="true">Sri Muktsar Sahib, Punjab (152026)</span>
                        <span class="remove-btn" onclick="removeItem(this)">x</span>
                    </div>
                    <div class="contact-line">
                        (M): <span contenteditable="true">+91 - 8968081700</span>
                        <span class="remove-btn" onclick="removeItem(this)">x</span>
                    </div>
                    <div class="contact-line">
                        Email.ID – <a contenteditable="true">gurpinderrajput135@gmail.com</a>
                        <span class="remove-btn" onclick="removeItem(this)">x</span>
                    </div>
                </div>
                <button class="add-btn" onclick="addContactLine()" style="width: auto; margin-top: 5px;">+ Add Contact
                    Field</button>
            </div>

            <hr style="border: 1px solid black; margin-bottom: var(--section-gap);">

            <div class="section-header">Career Objective</div>
            <div contenteditable="true" id="profileText"
                style="margin-bottom: var(--item-gap); line-height: 1.5; text-align: justify;">
                To be associated with a progressive organization that gives me exposure and to be a part of team work
                with dynamism towards the growth of organization leading to self-satisfaction.
            </div>
            <div class="ui-controls">
                <select onchange="updateProfile(this.value); this.selectedIndex=0;"
                    style="width: auto; border: 1px solid #ddd; padding: 5px;">
                    <option value="">-- Quick Profile Text --</option>
                    <option value="Dedicated professional with 5+ years of experience in...">Experience 5+</option>
                    <option value="Motivated fresher seeking an entry-level position...">Fresher</option>
                    <option value="Results-oriented Senior Manager with over 15 years of experience...">Senior Manager
                    </option>
                </select>
            </div>

            <div class="section-header">Education Qualification</div>
            <ul class="custom-list" id="eduList">
                <li>
                    <span contenteditable="true">10TH passed from P.S.E.B in 2018.</span>
                    <span class="remove-btn" onclick="removeItem(this)">x</span>
                </li>
                <li>
                    <span contenteditable="true">12TH passed from P.S.E.B in 2020.</span>
                    <span class="remove-btn" onclick="removeItem(this)">x</span>
                </li>
                <li>
                    <span contenteditable="true">BCA passed out from PU CHD in 2023.</span>
                    <span class="remove-btn" onclick="removeItem(this)">x</span>
                </li>
            </ul>
            <button class="add-btn" onclick="addListItem('eduList')">+ Add Education</button>

            <div class="section-header">
                Other Experience
                <div class="fresher-controls" id="fresherControlArea">
                    <label><input type="checkbox" id="fresherCheck" onchange="toggleFresherMode()"> I am a
                        Fresher</label>
                </div>
            </div>

            <ul class="custom-list" id="expList">
                <li>
                    <span contenteditable="true">8th Months Sales Experience in HDB.</span>
                    <span class="remove-btn" onclick="removeItem(this, 'expList')">x</span>
                </li>
            </ul>

            <div id="fresherSection" style="display:none; font-style:italic; color:#666;">
                <p contenteditable="true">Fresher - Seeking entry level opportunities to utilize my skills.</p>
            </div>

            <button class="add-btn" id="addExpBtn" onclick="addListItem('expList')">+ Add Experience</button>

            <div class="grid-2-col" style="margin-top: var(--section-gap);">
                <div class="col-half">
                    <div class="section-header">Hobbies</div>
                    <ul class="custom-list" id="hobbyList">
                        <li><span contenteditable="true">Listen Music</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">Reading Story Books</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">To Interact with People to have the discussions on Common
                                Issues.</span><span class="remove-btn" onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">To watch motivational shows.</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">Excellent human relation skills.</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">Ability to work successfully in a team and motivating people to
                                work effetely.</span><span class="remove-btn" onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">Creative thinking and confidence to utilize the available
                                resources to their full extent.</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                    </ul>
                    <button class="add-btn" onclick="addListItem('hobbyList')">+ Add Hobby</button>
                </div>

                <div class="col-half">
                    <div class="section-header">Skills</div>
                    <ul class="custom-list" id="skillList">
                        <li><span contenteditable="true">Team Management</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                        <li><span contenteditable="true">Communication</span><span class="remove-btn"
                                onclick="removeItem(this)">x</span></li>
                    </ul>
                    <button class="add-btn" onclick="addListItem('skillList')">+ Add Skill</button>
                </div>
            </div>

        </div>
        <div class="resume-page" id="page2">

            <div class="section-header" style="margin-top: 0;">PERSONAL DETAILS</div>

            <div class="details-table" id="personalTable">
                <div class="details-row">
                    <div class="details-label" contenteditable="true">Date of Birth</div>
                    <div class="details-colon">:</div>
                    <div class="details-value" contenteditable="true">01 - 01 - 2003</div>
                    <div class="details-value" style="width: 30px;"><span class="remove-btn"
                            onclick="this.parentElement.parentElement.remove()">x</span></div>
                </div>
                <div class="details-row">
                    <div class="details-label" contenteditable="true">Gender</div>
                    <div class="details-colon">:</div>
                    <div class="details-value">
                        <div class="radio-group">
                            <label class="active-radio"><input type="radio" name="gender" value="Male" checked
                                    onclick="updateRadioClasses()"> Male</label>
                            <label><input type="radio" name="gender" value="Female" onclick="updateRadioClasses()">
                                Female</label>
                        </div>
                    </div>
                    <div class="details-value"><span class="remove-btn"
                            onclick="this.parentElement.parentElement.remove()">x</span></div>
                </div>
                <div class="details-row">
                    <div class="details-label" contenteditable="true">Nationality</div>
                    <div class="details-colon">:</div>
                    <div class="details-value" contenteditable="true">Indian</div>
                    <div class="details-value"><span class="remove-btn"
                            onclick="this.parentElement.parentElement.remove()">x</span></div>
                </div>
                <div class="details-row">
                    <div class="details-label" contenteditable="true">Language Proficiency</div>
                    <div class="details-colon">:</div>
                    <div class="details-value" contenteditable="true">English, Hindi & Punjabi</div>
                    <div class="details-value"><span class="remove-btn"
                            onclick="this.parentElement.parentElement.remove()">x</span></div>
                </div>
                <div class="details-row">
                    <div class="details-label" contenteditable="true">Father Name</div>
                    <div class="details-colon">:</div>
                    <div class="details-value" contenteditable="true">Om Parkash</div>
                    <div class="details-value"><span class="remove-btn"
                            onclick="this.parentElement.parentElement.remove()">x</span></div>
                </div>
                <div class="details-row">
                    <div class="details-label" contenteditable="true">Marital Status</div>
                    <div class="details-colon">:</div>
                    <div class="details-value">
                        <select onchange="this.setAttribute('value', this.value)">
                            <option value="Un-married">Un-married</option>
                            <option value="Married">Married</option>
                        </select>
                    </div>
                    <div class="details-value"><span class="remove-btn"
                            onclick="this.parentElement.parentElement.remove()">x</span></div>
                </div>
            </div>
            <button class="add-btn" onclick="addPersonalRow()">+ Add Personal Detail</button>

            <div id="extraSectionsArea"></div>

            <button class="btn-dashed" onclick="addCustomSection()">+ Add New Section (e.g. Certifications)</button>

            <div class="section-header">DECLARATION</div>
            <div class="declaration-text" contenteditable="true">
                I confirm myself having adequate knowledge about the work mentioned in the resume. I am also confident
                of my ability to work in a team.
            </div>

            <div class="footer-sign">
                <div class="sign-block">
                    <div class="sign-label">Date:</div>
                    <div contenteditable="true" style="margin-top: 20px;">Place: Sri Muktsar Sahib</div>
                </div>
                <div class="sign-block" style="text-align: right;">
                    <div class="sign-label" style="margin-bottom: 20px;">Signature: <span contenteditable="true"
                            style="font-weight: normal;">Gurpinder Singh</span></div>
                </div>
            </div>

        </div>
    </div>
    <div class="editor-panel">
        <h3>Design Controls</h3>

        <div class="control-group">
            <button class="btn btn-outline-blue" onclick="toggleEditMode()" id="editToggleBtn">Lock / Preview</button>
        </div>

        <div class="control-group">
            <label>Font Size</label>
            <input type="range" min="12" max="18" value="14" oninput="updateVar('--base-font-size', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Section Gap</label>
            <input type="range" min="10" max="60" value="25" oninput="updateVar('--section-gap', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Item Gap</label>
            <input type="range" min="5" max="30" value="12" oninput="updateVar('--item-gap', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Page Padding</label>
            <input type="range" min="20" max="60" value="45"
                oninput="updateVar('--content-padding', this.value + 'px')">
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
        // ON LOAD
        window.onload = function () {
            updateRadioClasses();
            checkExperienceEmpty();
        };

        function updateVar(variable, value) {
            document.documentElement.style.setProperty(variable, value);
        }

        function updateProfile(text) { if (text) document.getElementById('profileText').innerText = text; }

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

        function addListItem(listId) {
            const list = document.getElementById(listId);
            const li = document.createElement('li');
            li.innerHTML = `<span contenteditable="true">New Item...</span><span class="remove-btn" onclick="removeItem(this, '${listId}')">x</span>`;
            list.appendChild(li);

            // Auto-hide fresher mode if adding experience
            if (listId === 'expList') {
                const fresherCheck = document.getElementById('fresherCheck');
                fresherCheck.checked = false;
                toggleFresherMode();
                document.getElementById('fresherControlArea').style.display = 'none';
            }
        }

        function removeItem(btn, listId) {
            if (confirm('Delete this item?')) {
                const item = btn.closest('.contact-line') || btn.closest('li') || btn.closest('.details-row') || btn.closest('.custom-section-block');
                item.remove();
                if (listId === 'expList') {
                    checkExperienceEmpty();
                }
            }
        }

        function addContactLine() {
            const container = document.getElementById('contactContainer');
            const div = document.createElement('div');
            div.className = 'contact-line';
            div.innerHTML = `<span contenteditable="true">New Contact Info</span> <span class="remove-btn" onclick="removeItem(this)">x</span>`;
            container.appendChild(div);
        }

        function checkExperienceEmpty() {
            const list = document.getElementById('expList');
            const controlArea = document.getElementById('fresherControlArea');
            if (list && list.children.length === 0) {
                controlArea.style.display = 'block';
            } else if (list) {
                controlArea.style.display = 'none';
            }
        }

        function toggleFresherMode() {
            const isFresher = document.getElementById('fresherCheck').checked;
            const expList = document.getElementById('expList');
            const addBtn = document.getElementById('addExpBtn');
            const fresherSection = document.getElementById('fresherSection');

            if (isFresher) {
                expList.style.display = 'none';
                addBtn.style.display = 'none';
                fresherSection.style.display = 'block';
            } else {
                expList.style.display = 'block';
                addBtn.style.display = 'inline-block';
                fresherSection.style.display = 'none';
            }
        }

        function addPersonalRow() {
            const container = document.getElementById('personalTable');
            const row = document.createElement('div');
            row.className = 'details-row';
            row.innerHTML = `
                <div class="details-label" contenteditable="true">Label</div>
                <div class="details-colon">:</div>
                <div class="details-value" contenteditable="true">Value</div>
                <div class="details-value"><span class="remove-btn" onclick="removeItem(this)">x</span></div>
            `;
            container.appendChild(row);
        }

        function addCustomSection() {
            const container = document.getElementById('extraSectionsArea');
            const div = document.createElement('div');
            div.className = 'custom-section-block';
            div.style.position = 'relative';
            div.innerHTML = `
                <div class="section-header">
                    <span contenteditable="true">NEW HEADER</span>
                    <button class="remove-btn" onclick="removeItem(this)" style="display:inline-block; font-size:0.8rem; background:white; color:red; padding:0 5px;">Remove Section</button>
                </div>
                <ul class="custom-list">
                    <li><span contenteditable="true">Detail 1</span></li>
                    <li><span contenteditable="true">Detail 2</span></li>
                </ul>
            `;
            container.appendChild(div);
        }

        function updateRadioClasses() {
            const allRadios = document.querySelectorAll('input[name="gender"]');
            allRadios.forEach(radio => {
                const label = radio.parentElement;
                if (radio.checked) {
                    label.classList.add('active-radio');
                    radio.setAttribute('checked', 'checked');
                } else {
                    label.classList.remove('active-radio');
                    radio.removeAttribute('checked');
                }
            });
        }

        function prepareSave() {
            const body = document.body;
            const wasEditing = body.classList.contains('edit-mode');

            if (wasEditing) body.classList.remove('edit-mode');
            updateRadioClasses();

            const selects = document.querySelectorAll('select');
            selects.forEach(sel => {
                const val = sel.value;
                Array.from(sel.options).forEach(opt => {
                    if (opt.value === val) opt.setAttribute('selected', 'selected');
                    else opt.removeAttribute('selected');
                });
            });

            const style = getComputedStyle(document.documentElement);
            let cssVars = "";
            ['--base-font-size', '--content-padding', '--section-gap', '--item-gap'].forEach(v => {
                cssVars += `${v}: ${style.getPropertyValue(v)}; `;
            });

            document.getElementById('computedStylesInput').value = cssVars;
            document.getElementById('resumeContentInput').value = document.getElementById('exportContainer').innerHTML;

            if (wasEditing) body.classList.add('edit-mode');
        }
    </script>
<script src="template_switcher.js?v=data-sync-4"></script>
</body>

</html>
