import os, re
import json

directory = r'c:\xampp\htdocs\resumeantisingle2'
templates = [f for f in os.listdir(directory) if f.endswith('.php') and f not in ('index.php', 'dashboard.php')]

patterns = {
    'name': re.compile(r'<h1[^>]*class=["\']([^"\']*name[^"\']*)["\'][^>]*>'),
    'job_title': re.compile(r'<div[^>]*class=["\']([^"\']*(?:job-title|header-title)[^"\']*)["\'][^>]*>'),
    'contact': re.compile(r'id=["\']contactList["\']|class=["\']contact-bar["\']'),
    'profile': re.compile(r'id=["\']profileText["\']|id=["\']objectiveText["\']'),
    'experience': re.compile(r'id=["\']experienceList["\']|id=["\']experienceSection["\']|id=["\']expList["\']'),
    'education': re.compile(r'id=["\']educationList["\']|id=["\']eduTable["\']|id=["\']educationSection["\']'),
    'skills': re.compile(r'id=["\']skillsList["\']|id=["\']skillsSection["\']'),
    'hobbies': re.compile(r'id=["\']hobbiesList["\']|id=["\']hobbiesSection["\']'),
    'personal': re.compile(r'id=["\']personalList["\']|id=["\']personalSection["\']')
}

results = {k: set() for k in patterns}
missing = {k: [] for k in patterns}

for t in templates:
    path = os.path.join(directory, t)
    with open(path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        for key, pattern in patterns.items():
            match = pattern.search(content)
            if match:
                if key in ['name', 'job_title']:
                    results[key].add(match.group(1))
                else:
                    results[key].add('found')
            else:
                missing[key].append(t)

print("Results:")
for k, val in results.items():
    print(f"{k} found variations: {val}")

print("\nMissing:")
for k, val in missing.items():
    if val:
        print(f"{k} missing in: {val}")
