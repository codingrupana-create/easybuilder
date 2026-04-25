<?php
// Main PHP endpoint for the Resume Builder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_content'])) {
    $content = $_POST['resume_content'];
    $computedStyles = $_POST['computed_styles'];

    $filename = "resume_pro_" . date('Y-m-d_H-i') . ".html";
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Professional Resume</title>
        <style>
            :root { " . $computedStyles . " }
            body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background: #555; }
            
            /* WRAPPER FOR SAVED HTML */
            #resumeArea { width: 21cm; margin: 0 auto; }

            /* PAGE STYLES */
            .resume-page { 
                width: 100%; 
                min-height: 297mm; 
                padding: var(--content-padding); 
                background: white; 
                margin-bottom: 20px; 
                position: relative;
                box-sizing: border-box;
                overflow: hidden;
            }

            /* SECTION STYLING */
            .header-section { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: var(--section-gap); }
            
            .section { margin-bottom: var(--section-gap); }
            .section-title { 
                margin-bottom: var(--item-gap); 
                margin-top: 0; 
                border-left: 4px solid var(--accent-color); 
                padding-left: 10px;
            }

            /* FLEX GAPS */
            #experienceSection, #educationSection { display: flex; flex-direction: column; gap: var(--item-gap); }
            .skills-grid, .hobbies-grid { display: flex; flex-wrap: wrap; gap: var(--item-gap); }
            .contact-bar { display: flex; justify-content: center; gap: var(--item-gap); flex-wrap: wrap; }
            
            /* PERSONAL INFO GRID */
            .personal-info-grid { 
                display: grid; 
                grid-template-columns: 1fr 1fr; 
                gap: var(--item-gap) 40px; 
                width: 100%;
            }
            .info-item { display: flex; align-items: baseline; position: relative; }
            .info-label { font-weight: 600; color: #666; min-width: 140px; margin-right: 10px; }

            /* FOOTER */
            .footer-sign { display: flex !important; justify-content: space-between !important; margin-top: 60px; width: 100%; }

            /* CLEANUP INPUTS FOR PRINT */
            select { appearance: none; -webkit-appearance: none; -moz-appearance: none; border: none; background: transparent; font-family: inherit; font-size: inherit; color: inherit; padding: 0; margin: 0; }
            input[type='radio'] { display: none; }
            .radio-group label { display: none; }
            .radio-group label.active-radio { display: inline; }
            
            /* UTILS */
            .contact-item:not(:last-child)::after { content: '•'; position: absolute; right: calc(var(--item-gap) * -0.6); color: #999; }
            h1, h2, h3, h4, p, div { margin: 0; padding: 0; }
            [contenteditable] { border: none !important; }
            .remove-btn, .remove-btn-skill, .remove-section-btn, .add-btn, .ui-controls, .editor-panel, .btn-dashed { display: none !important; }

            /* PRINT SPECIFIC FIX */
            @media print {
                @page { margin: 0; size: auto; }
                body { background: white; margin: 0; }
                #resumeArea { width: 100%; margin: 0; }
                
                .resume-page { 
                    margin: 0 !important; 
                    border: none !important;
                    height: auto !important; 
                    min-height: auto !important;
                    page-break-after: always !important;
                }
                .resume-page:last-child { page-break-after: auto !important; }
            }
        </style>
    </head>
    <body>
        <div id='resumeArea'>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS VARIABLES */
        :root {
            /* Colors */
            --text-main: #222222;
            --text-light: #666666;
            --border-color: #333333;
            --accent-color: #0056b3; 
            
            /* Dimensions */
            --page-width: 210mm;
            --page-height: 297mm; /* A4 Height */
            
            /* ADJUSTABLE DEFAULTS */
            --base-font-size: 14px;
            --section-gap: 25px;
            --item-gap: 15px;
            --content-padding: 40px;
            --line-height: 1.6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #555;
            color: var(--text-main);
            display: flex;
            flex-direction: row;
            justify-content: center;
            padding: 30px;
            font-size: var(--base-font-size);
        }

        /* --- WRAPPER --- */
        #resumeArea {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px; /* Gap between pages on screen */
        }

        /* --- RESUME PAGE --- */
        .resume-page {
            background: white;
            width: var(--page-width);
            min-height: var(--page-height);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 40px var(--content-padding);
            transition: padding 0.2s ease;
        }

        /* --- HEADER --- */
        .header-section {
            text-align: center;
            border-bottom: 2px solid var(--text-main);
            padding-bottom: 20px;
            margin-bottom: var(--section-gap);
        }

        .header-section h1.name {
            font-size: 2.8em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
            color: var(--text-main);
        }

        .header-section .job-title {
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-light);
            font-weight: 400;
        }

        /* Contact Bar */
        .contact-bar {
            display: flex;
            justify-content: center;
            gap: var(--item-gap); 
            flex-wrap: wrap;
            margin-top: 15px;
            font-size: 0.9em;
            color: var(--text-main);
        }

        .contact-item {
            position: relative;
            display: flex;
            align-items: center;
        }

        .contact-item:not(:last-of-type)::after {
            content: "•";
            position: absolute;
            right: calc(var(--item-gap) * -0.6); 
            color: #ccc;
            font-size: 1.2em;
        }

        /* --- SECTIONS --- */
        .section {
            margin-bottom: var(--section-gap) !important;
            position: relative;
        }

        .section-title {
            font-size: 1.1em;
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 1px solid #eee;
            border-left: 4px solid var(--accent-color);
            padding-left: 10px;
            padding-bottom: 5px;
            margin-bottom: var(--item-gap);
            color: var(--text-main);
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #experienceSection, #educationSection {
            display: flex;
            flex-direction: column;
            gap: var(--item-gap); 
        }

        /* Entries */
        .entry {
            position: relative;
            page-break-inside: avoid;
            padding-right: 25px;
        }

        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .entry-title { font-weight: 700; font-size: 1.05em; color: #000; }
        .entry-date { font-weight: 600; font-size: 0.9em; color: var(--text-main); text-align: right; min-width: 100px; }
        .entry-subtitle { font-style: italic; color: var(--text-light); font-size: 0.95em; margin-bottom: 5px; }
        .entry-content { font-size: 0.95em; line-height: var(--line-height); color: #444; text-align: justify; }

        /* Skills & Hobbies Grid */
        .skills-grid, .hobbies-grid {
            display: flex;
            flex-wrap: wrap;
            gap: var(--item-gap); 
        }
        .skill-item, .hobby-item {
            font-size: 0.95em;
            position: relative;
            background: #f4f4f4;
            padding: 5px 12px;
            border-radius: 4px;
            border-left: 2px solid transparent;
        }
        .skill-item:hover, .hobby-item:hover { border-left-color: var(--accent-color); }

        /* --- PERSONAL INFO SECTION --- */
        .personal-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns */
            gap: var(--item-gap) 40px; 
            margin-top: 10px;
        }
        
        .info-item {
            display: flex;
            align-items: baseline;
            position: relative;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-light);
            min-width: 140px; 
            margin-right: 10px;
        }

        .info-value {
            color: var(--text-main);
            flex: 1;
        }

        /* Form Controls */
        select.info-value {
            font-family: inherit; font-size: inherit; border: 1px solid transparent; background: transparent; width: 100%; cursor: pointer; padding: 0;
        }
        .edit-mode select.info-value { border-bottom: 1px dashed #ccc; }
        
        .radio-group label { margin-right: 10px; cursor: pointer; font-size: 0.95em; }

        /* Footer */
        .footer-sign {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
            align-items: flex-end;
            padding-top: 10px;
        }
        .sign-line { border-top: 1px solid #333; width: 200px; margin-top: 10px; text-align: center; font-weight: bold; font-size: 0.9em; padding-top: 5px; }

        /* --- BUTTONS --- */
        .btn {
            padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 1rem;
            margin-top: 8px; width: 100%; transition: all 0.2s; display: block; text-align: center;
        }
        .btn-blue { background-color: #3498db; color: white; }
        .btn-blue:hover { background-color: #2980b9; }
        .btn-outline-blue { border: 2px solid #3498db; background-color: transparent; color: #3498db; }
        .btn-outline-blue:hover { background-color: #3498db; color: white; }
        .btn-primary { background-color: #333; color: white; }
        .btn-primary:hover { opacity: 0.9; }
        
        .btn-dashed { background: transparent; border: 2px dashed #999; color: #666; font-size: 0.9rem; margin-top: 20px; display: none; width: 100%; }
        .btn-dashed:hover { border-color: #333; color: #333; }

        /* --- UI CONTROLS --- */
        .ui-controls, .add-btn, .remove-btn, .remove-btn-skill, .remove-section-btn { display: none; }
        body.edit-mode .ui-controls { display: block; margin-bottom: 5px;}
        body.edit-mode .add-btn { display: inline-block; background: #333; color: white; border: none; padding: 5px 10px; font-size: 0.7rem; margin-top:10px; cursor: pointer; border-radius: 3px; }
        body.edit-mode .btn-dashed { display: block; }

        .remove-btn, .remove-btn-skill {
            position: absolute; background: #e74c3c; color: white; border: none; border-radius: 50%; cursor: pointer; 
            display: none; align-items: center; justify-content: center; z-index: 100; font-weight: bold;
        }
        .remove-btn { top: 0; right: 0; width: 20px; height: 20px; font-size: 10px; }
        .remove-btn-skill { top: -5px; right: -5px; width: 14px; height: 14px; font-size: 9px; }
        
        .remove-section-btn {
            background: #c0392b; color: white; border: none; padding: 2px 8px; font-size: 0.7rem; cursor: pointer; border-radius: 3px;
        }

        body.edit-mode .entry:hover .remove-btn, 
        body.edit-mode .skill-item:hover .remove-btn-skill,
        body.edit-mode .hobby-item:hover .remove-btn-skill,
        body.edit-mode .contact-item:hover .remove-btn-skill,
        body.edit-mode .info-item:hover .remove-btn-skill,
        body.edit-mode .section:hover .remove-section-btn { display: flex; } 

        [contenteditable="true"] { border: 1px solid transparent; padding: 1px; min-width: 10px; transition: border-color 0.2s; }
        body.edit-mode [contenteditable="true"] { border-bottom: 1px dashed #ccc; background-color: rgba(0, 0, 0, 0.03); }
        body.edit-mode [contenteditable="true"]:focus { outline: 2px solid var(--accent-color); background-color: white; }
        .objective-controls select { width: 100%; padding: 5px; margin-bottom: 5px; }

        /* --- EDITOR PANEL --- */
        .editor-panel {
            width: 250px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            height: fit-content; position: fixed; right: 30px; top: 30px; display: flex; flex-direction: column; gap: 15px; z-index: 1000;
        }
        .editor-panel h3 { color: #333; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 5px; }
        .control-group label { display: block; font-size: 0.85rem; font-weight: bold; color: #555; margin-bottom: 5px; }
        .control-group input[type="range"] { width: 100%; cursor: pointer; }

        /* --- STRICT PRINT FIX --- */
        @media print {
            @page {
                margin: 0 !important; /* Removes Browser Headers/Footers & Margins */
                size: auto; 
            }
            
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                height: 100%;
                background: white;
            }

            /* DISABLE FLEX IN PRINT to avoid ghost pages */
            body, #resumeArea {
                display: block !important;
                width: 100% !important;
                height: auto !important;
                gap: 0 !important;
            }
            
            .resume-page {
                width: 100% !important;
                /* Force Exact A4 Height to prevent spillover */
                height: 297mm !important; 
                margin: 0 !important;
                padding: 40px var(--content-padding) !important;
                
                /* Strict Page Breaking */
                page-break-after: always !important;
                break-after: page !important;
                
                box-shadow: none !important;
                border: none !important;
                overflow: hidden !important; /* Clip any overflowing content */
            }

            /* Prevent blank page after the last page */
            .resume-page:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
                height: auto !important; /* Allow last page to be natural height if needed */
            }
            
            /* Hiding UI Controls */
            .editor-panel, .add-btn, .remove-btn, .remove-btn-skill, .remove-section-btn, .btn-dashed, .ui-controls { 
                display: none !important; 
            }
            
            /* FIX 2-COLUMN GRID IN PRINT */
            .personal-info-grid { 
                display: grid !important; 
                grid-template-columns: 1fr 1fr !important; 
                gap: var(--item-gap) 40px !important; 
                width: 100% !important;
            }
            
            /* Hide Form Elements logic */
            input[type='radio'] { display: none; }
            .radio-group label { display: none; } 
            .radio-group label.active-radio { display: inline; }
            select { -webkit-appearance: none; -moz-appearance: none; appearance: none; border: none; background: transparent; padding: 0; }

            .entry, .section, .info-item { break-inside: avoid; }
            [contenteditable="true"] { border: none !important; padding: 0 !important; }
        }
        
        @media screen and (max-width: 1100px) {
            body { flex-direction: column-reverse; }
            .editor-panel { width: 100%; position: static; margin-bottom: 20px; }
            .resume-page { margin-right: 0; transform: scale(0.9); transform-origin: top center; margin-bottom: 20px;}
        }
    </style>
</head>

<body class="edit-mode">

    <div id="resumeArea">

        <div class="resume-page" id="page1">
            <div class="header-section">
                <h1 class="name" contenteditable="true">ALEXANDER SMITH</h1>
                <div class="job-title" contenteditable="true">SENIOR SOFTWARE ENGINEER</div>
                
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
            </div>

            <div class="section">
                <div class="section-title">Professional Summary</div>
                <div class="profile-text" contenteditable="true" style="line-height: var(--line-height);">
                    Dedicated and efficient full stack developer with 6+ years of experience in application layers, presentation layers, and databases. Certified in both F.E. and B.E. technologies. Seeking to further improve HTML5 and CSS3 skills as the future full stack developer at TechHub.
                </div>
                <div class="ui-controls" style="margin-top:5px; background:#f9f9f9; padding:5px; font-size:0.8rem;">
                    <select onchange="if(this.value) { this.parentElement.previousElementSibling.innerText = this.value; this.selectedIndex = 0; }">
                        <option value="">-- Quick Text --</option>
                        <option value="Experienced professional seeking a challenging role in...">General Professional</option>
                        <option value="Recent graduate with a strong academic background in...">Fresher / Graduate</option>
                    </select>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Core Competencies</div>
                <div class="skills-grid" id="skillsList">
                    <div class="skill-item"><span contenteditable="true">Project Management</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">Strategic Planning</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">Data Analysis</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">Python & SQL</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">Team Leadership</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addSkill()">+ Add Skill</button>
            </div>

            <div class="section">
                <div class="section-title">
                    Experience
                    <div class="ui-controls" id="fresherControl" style="font-size:0.7rem; text-transform: none; display:inline-block; font-weight: normal; margin-left:10px;">
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
                    <div class="entry">
                        <div class="entry-header">
                            <span class="entry-title" contenteditable="true">Master of Computer Science</span>
                            <span class="entry-date" contenteditable="true">2018 - 2020</span>
                        </div>
                        <div class="entry-subtitle" contenteditable="true">University of Technology</div>
                        <div class="entry-content" contenteditable="true">Major in Artificial Intelligence. CGPA: 3.8/4.0</div>
                        <button class="remove-btn" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    <div class="entry">
                        <div class="entry-header">
                            <span class="entry-title" contenteditable="true">Bachelor of Science</span>
                            <span class="entry-date" contenteditable="true">2014 - 2018</span>
                        </div>
                        <div class="entry-subtitle" contenteditable="true">State College</div>
                        <div class="entry-content" contenteditable="true">Minor in Mathematics.</div>
                        <button class="remove-btn" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                </div>
                <button class="add-btn" onclick="addEducation()">+ Add Education</button>
            </div>
            
            <div class="section">
                <div class="section-title">Languages</div>
                <div class="skills-grid" id="languageList">
                    <div class="skill-item"><span contenteditable="true">English (Native)</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                    <div class="skill-item"><span contenteditable="true">Spanish (B2)</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addLanguage()">+ Add Language</button>
            </div>

            <div class="section">
                <div class="section-title">Hobbies & Interests</div>
                <div class="hobbies-grid" id="hobbiesList">
                     <div class="hobby-item"><span contenteditable="true">Photography</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                     <div class="hobby-item"><span contenteditable="true">Traveling</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                     <div class="hobby-item"><span contenteditable="true">Chess</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button></div>
                </div>
                <button class="add-btn" onclick="addHobby()">+ Add Hobby</button>
            </div>

            <div id="extraSectionsPage1"></div>
            <button class="btn btn-dashed" onclick="addCustomSection('extraSectionsPage1')">+ Add Custom Section (Page 1)</button>
        </div>

        <div class="resume-page" id="page2">
            
            <div style="border-bottom:1px solid #ccc; padding-bottom:10px; margin-bottom:20px; color:#999; text-transform:uppercase; font-size:0.8em; letter-spacing:1px;">
                Page 2 - Personal Details & Declaration
            </div>

            <div class="section">
                <div class="section-title">Personal Information</div>
                <div class="personal-info-grid" id="personalInfoList">
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Father's Name</span>
                        <span class="info-value" contenteditable="true">Mr. John Doe Sr.</span>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Date of Birth</span>
                        <span class="info-value" contenteditable="true">01 Jan 1995</span>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Nationality</span>
                        <span class="info-value" contenteditable="true">Citizen</span>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Gender</span>
                        <div class="radio-group info-value">
                            <label class="active-radio"><input type="radio" name="gender" value="Male" checked onclick="updateRadioClasses()"> Male</label>
                            <label><input type="radio" name="gender" value="Female" onclick="updateRadioClasses()"> Female</label>
                            <label><input type="radio" name="gender" value="Other" onclick="updateRadioClasses()"> Other</label>
                        </div>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Marital Status</span>
                        <select class="info-value">
                            <option value="Unmarried">Unmarried</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label" contenteditable="true">Address</span>
                        <span class="info-value" contenteditable="true">123 Main St, New York, NY 10001</span>
                        <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
                    </div>
                </div>
                <button class="add-btn" onclick="addPersonalInfo()">+ Add Info Field</button>
            </div>

            <div id="extraSectionsPage2"></div>
            <button class="btn btn-dashed" onclick="addCustomSection('extraSectionsPage2')">+ Add Custom Section (Page 2)</button>

            <div class="section" style="margin-top: 50px;">
                <div class="section-title">Declaration</div>
                <div class="profile-text" contenteditable="true" style="font-style: italic; font-size: 0.9em; color: #666; line-height: 1.8;">
                    I hereby declare that the above-mentioned information is correct to the best of my knowledge and belief. I bear the responsibility for the correctness of the above-mentioned particulars.
                </div>
            </div>

            <div class="footer-sign">
                <div class="sign-box"><div contenteditable="true">Place: New York</div><div contenteditable="true">Date: <?php echo date("d/m/Y"); ?></div></div>
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
            <input type="range" min="12" max="16" value="14" oninput="updateVar('--base-font-size', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Content Width</label>
            <input type="range" min="30" max="80" value="40" oninput="updateVar('--content-padding', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Section Gap</label>
            <input type="range" min="15" max="50" value="25" oninput="updateVar('--section-gap', this.value + 'px')">
        </div>

        <div class="control-group">
            <label>Item Gap</label>
            <input type="range" min="5" max="40" value="15" oninput="updateVar('--item-gap', this.value + 'px')">
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
                expSection.style.display = 'flex'; /* Flex to maintain gap */
                addExpBtn.style.display = ''; fresherMsg.style.display = 'none';
            }
        }

        function addExperience() {
            const container = document.getElementById('experienceSection');
            const newBlock = document.createElement('div');
            newBlock.className = 'entry';
            newBlock.innerHTML = `
                <div class="entry-header"><div class="entry-title" contenteditable="true">Job Title</div><div class="entry-date" contenteditable="true">Year - Year</div></div>
                <div class="entry-subtitle" contenteditable="true">Company Name | Location</div>
                <div class="entry-content" contenteditable="true">Description...</div>
                <button class="remove-btn" onclick="removeElement(this.parentElement)">x</button>`;
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
                <div class="entry-content" contenteditable="true">Grade</div>
                <button class="remove-btn" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newBlock);
        }

        function addContact() {
            const container = document.getElementById('contactList');
            const newDiv = document.createElement('div');
            newDiv.className = 'contact-item';
            newDiv.innerHTML = `<span contenteditable="true">New Contact</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.insertBefore(newDiv, container.lastElementChild); // Insert before button
        }

        function addSkill() {
            const container = document.getElementById('skillsList');
            const newTag = document.createElement('div');
            newTag.className = 'skill-item';
            newTag.innerHTML = `<span contenteditable="true">New Skill</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newTag);
        }
        
        function addLanguage() {
            const container = document.getElementById('languageList');
            const newTag = document.createElement('div');
            newTag.className = 'skill-item';
            newTag.innerHTML = `<span contenteditable="true">New Language</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newTag);
        }

        function addHobby() {
            const container = document.getElementById('hobbiesList');
            const newTag = document.createElement('div');
            newTag.className = 'hobby-item';
            newTag.innerHTML = `<span contenteditable="true">New Hobby</span><button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>`;
            container.appendChild(newTag);
        }

        function addPersonalInfo() {
            const container = document.getElementById('personalInfoList');
            const newDiv = document.createElement('div');
            newDiv.className = 'info-item';
            newDiv.innerHTML = `
                <span class="info-label" contenteditable="true">New Label</span>
                <span class="info-value" contenteditable="true">Value</span>
                <button class="remove-btn-skill" onclick="removeElement(this.parentElement)">x</button>
            `;
            container.appendChild(newDiv);
        }

        function addCustomSection(containerId) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'section';
            div.innerHTML = `
                <div class="section-title">
                    <span contenteditable="true">NEW SECTION</span>
                    <button class="remove-section-btn" onclick="removeElement(this.parentElement.parentElement)">Remove</button>
                </div>
                <div class="entry">
                    <div class="entry-content" contenteditable="true">Add content here...</div>
                </div>
            `;
            container.appendChild(div);
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

            // 2. Selects
            const selects = document.querySelectorAll('select.info-value');
            selects.forEach(sel => {
                const val = sel.value;
                sel.setAttribute('data-selected', val);
                Array.from(sel.options).forEach(opt => {
                    if(opt.value === val) opt.setAttribute('selected', 'selected');
                    else opt.removeAttribute('selected');
                });
            });

            const style = getComputedStyle(document.documentElement);
            let cssVars = "";
            ['--base-font-size', '--section-gap', '--content-padding', '--item-gap', '--line-height'].forEach(v => {
                cssVars += `${v}: ${style.getPropertyValue(v)}; `;
            });
            
            document.getElementById('computedStylesInput').value = cssVars;
            // Capture the entire Wrapper ID "resumeArea" to capture both pages
            document.getElementById('resumeContentInput').value = document.getElementById('resumeArea').innerHTML;
            
            if (wasEditing) body.classList.add('edit-mode');
        }
    </script>
<script src="template_switcher.js?v=data-sync-4"></script>
</body>
</html>
