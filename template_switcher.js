function initResumeTemplateTools() {
    if (window.__resumeTemplateToolsReady) return;
    window.__resumeTemplateToolsReady = true;
    document.body.classList.add('resume-template-active');

    // 1. Add "Switch Template" button to editor panel
    const editorPanel = document.querySelector('.editor-panel');
    if (editorPanel) {
        const switchBtn = document.createElement('button');
        switchBtn.className = 'btn btn-primary btn-switch-template';
        switchBtn.style.backgroundColor = '#2563eb';
        switchBtn.style.color = '#fff';
        switchBtn.style.marginTop = '15px';
        switchBtn.style.marginBottom = '15px';
        switchBtn.style.width = '100%';
        switchBtn.style.padding = '10px';
        switchBtn.style.border = 'none';
        switchBtn.style.borderRadius = '4px';
        switchBtn.style.cursor = 'pointer';
        switchBtn.style.fontWeight = 'bold';
        switchBtn.textContent = 'Switch Template';
        switchBtn.onclick = openTemplateModal;
        
        // Insert at the top of the panel after the h3 or simply as first button
        const h3 = editorPanel.querySelector('h3');
        if (h3) {
            h3.insertAdjacentElement('afterend', switchBtn);
        } else {
            editorPanel.prepend(switchBtn);
        }
    }

    // 2. Add Modal UI
    createTemplateModal();

    // 3. Inject data if exists in sessionStorage
    injectSavedData();

    // 4. Override PDF Download
    overrideSaveForPDF();

    // 5. Keep radio choices clean in preview mode and PDF export
    injectRadioDisplayStyles();
    syncActiveRadioLabels();
    document.addEventListener('change', (event) => {
        if (event.target && event.target.matches('.radio-group input[type="radio"]')) {
            syncActiveRadioLabels(event.target.closest('.radio-group'));
        }
    });

    // 6. Initialize Mobile Responsiveness
    initMobileResponsiveness();
}

function initMobileResponsiveness() {
    if (window.__mobileResponsivenessDone) return;
    window.__mobileResponsivenessDone = true;

    const style = document.createElement('style');
    style.textContent = `
        @media screen and (max-width: 768px) {
            body.resume-template-active.resume-template-mobile {
                --resume-mobile-panel-height: min(48vh, 420px);
                padding: 14px 12px calc(var(--resume-mobile-panel-height) + 24px) !important;
                margin: 0 !important;
                overflow-x: hidden !important;
                display: block !important;
                background: linear-gradient(180deg, #f8fbff 0%, #eef3f8 55%, #e6edf5 100%) !important;
            }
            .resume-mobile-shell {
                width: 100%;
                display: block;
                overflow-x: auto;
                overflow-y: visible;
                -webkit-overflow-scrolling: touch;
            }
            .resume-mobile-stage {
                position: relative;
                display: block;
                overflow: hidden;
                margin: 0 auto;
                transform-origin: top center;
            }
            body.resume-template-mobile .resume-page {
                box-shadow: 0 18px 42px rgba(15, 23, 42, 0.14) !important;
            }
            body.resume-template-mobile .editor-panel {
                width: auto !important;
                position: fixed !important;
                inset: auto 0 0 0 !important;
                border-radius: 24px 24px 0 0 !important;
                min-height: 220px !important;
                height: auto !important;
                max-height: var(--resume-mobile-panel-height) !important;
                padding: 20px 18px calc(18px + env(safe-area-inset-bottom)) !important;
                box-shadow: 0 -18px 48px rgba(15, 23, 42, 0.18) !important;
                z-index: 99999 !important;
                overflow-y: auto !important;
                background: rgba(255, 255, 255, 0.98) !important;
                box-sizing: border-box !important;
                border-top: 1px solid rgba(148, 163, 184, 0.28) !important;
                backdrop-filter: blur(14px);
            }
            body.resume-template-mobile .editor-panel::before {
                content: '';
                display: block;
                width: 56px;
                height: 5px;
                border-radius: 999px;
                background: #cbd5e1;
                margin: 0 auto 16px;
            }
            body.resume-template-mobile .editor-panel h3 {
                font-size: 1.05rem;
                margin-top: 0;
                margin-bottom: 10px;
            }
            body.resume-template-mobile .editor-panel .btn,
            body.resume-template-mobile .editor-panel button {
                min-height: 44px;
                padding: 12px 14px !important;
                font-size: 0.95rem !important;
                border-radius: 12px !important;
                margin-bottom: 10px !important;
            }
            body.resume-template-mobile .editor-panel .btn-switch-template {
                background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
                box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            }
            body.resume-template-mobile .editor-panel .control-group label {
                font-size: 0.82rem;
                letter-spacing: 0.01em;
                color: #475569;
            }
            body.resume-template-mobile .editor-panel input[type="range"] {
                width: 100%;
                accent-color: #2563eb;
            }
            body.resume-template-mobile .editor-panel select,
            body.resume-template-mobile .editor-panel input,
            body.resume-template-mobile .editor-panel textarea {
                width: 100%;
            }
        }
    `;
    document.head.appendChild(style);

    function getResumeRoot() {
        const resumeArea = document.getElementById('resumeArea');
        if (resumeArea) return resumeArea;

        const resumeContainer = document.querySelector('.resume-container');
        if (resumeContainer) return resumeContainer;

        const resumeWrapper = document.querySelector('.resume-wrapper');
        if (resumeWrapper) return resumeWrapper;

        const pages = Array.from(document.querySelectorAll('.resume-page'));
        if (!pages.length) return null;
        if (pages.length === 1) return pages[0];

        let parent = pages[0].parentElement;
        while (parent && parent !== document.body) {
            const pageChildren = Array.from(parent.children).filter((child) => child.classList && child.classList.contains('resume-page'));
            if (pageChildren.length >= 2) return parent;
            parent = parent.parentElement;
        }

        return pages[0];
    }

    function ensureMobileStage(root) {
        let shell = document.querySelector('.resume-mobile-shell');
        let stage = document.querySelector('.resume-mobile-stage');

        if (!shell || !stage) {
            shell = document.createElement('div');
            shell.className = 'resume-mobile-shell';

            stage = document.createElement('div');
            stage.className = 'resume-mobile-stage';
            shell.appendChild(stage);

            root.parentNode.insertBefore(shell, root);
        }

        if (root.parentElement !== stage) {
            stage.appendChild(root);
        }

        return stage;
    }

    function applyMobileDesktopLayoutFixes() {
        if (document.querySelector('.left-panel') && document.querySelector('.right-panel')) {
            document.querySelectorAll('.resume-page').forEach((page) => {
                page.style.display = 'flex';
                page.style.flexDirection = 'row';
                page.style.marginRight = '0';
            });
            document.querySelectorAll('.left-panel').forEach((panel) => {
                panel.style.width = 'var(--sidebar-width, 35%)';
            });
            document.querySelectorAll('.right-panel').forEach((panel) => {
                panel.style.width = 'auto';
                panel.style.flex = '1';
            });
        }

        if (document.querySelector('.resume-body') && document.querySelector('.left-sidebar') && document.querySelector('.main-content')) {
            document.querySelectorAll('.resume-body').forEach((section) => {
                section.style.display = 'flex';
                section.style.flexDirection = 'row';
                section.style.flex = '1';
            });
            document.querySelectorAll('.left-sidebar').forEach((sidebar) => {
                sidebar.style.width = '30%';
            });
            document.querySelectorAll('.main-content').forEach((content) => {
                content.style.width = 'auto';
                content.style.flex = '1';
            });
        }

        if (document.querySelector('.content-row') && document.querySelector('.sidebar') && document.querySelector('.main-content')) {
            document.querySelectorAll('.content-row').forEach((row) => {
                row.style.display = 'flex';
                row.style.flexDirection = 'row';
                row.style.backgroundSize = '100% 100%';
            });
            document.querySelectorAll('.content-row .sidebar').forEach((sidebar) => {
                sidebar.style.width = 'var(--sidebar-width, 32%)';
            });
            document.querySelectorAll('.content-row .main-content').forEach((content) => {
                content.style.width = 'auto';
                content.style.flex = '1';
            });
        } else if (document.querySelector('.resume-page > .sidebar') && document.querySelector('.resume-page > .main-content')) {
            document.querySelectorAll('.resume-page').forEach((page) => {
                page.style.display = 'flex';
                page.style.flexDirection = 'row';
            });
            document.querySelectorAll('.resume-page > .sidebar').forEach((sidebar) => {
                sidebar.style.width = 'var(--sidebar-width, 35%)';
            });
            document.querySelectorAll('.resume-page > .main-content').forEach((content) => {
                content.style.width = 'auto';
                content.style.flex = '1';
            });
        }
    }

    function enableCustomPinchZoom(root, stage, shell) {
        if (window.__customPinchZoomInitialized) return;
        window.__customPinchZoomInitialized = true;

        let metaViewport = document.querySelector('meta[name="viewport"]');
        if (!metaViewport) {
            metaViewport = document.createElement('meta');
            metaViewport.name = 'viewport';
            document.head.appendChild(metaViewport);
        }
        metaViewport.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0';

        document.addEventListener('touchmove', function(e) {
            if (e.touches.length > 1) e.preventDefault();
        }, { passive: false });

        let initialDistance = 0;
        let initialScaleAtTouch = 1;

        shell.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                initialDistance = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                initialScaleAtTouch = window.__currentCustomScale || window.__baseFitScale || 1;
            }
        }, {passive: false});

        shell.addEventListener('touchmove', function(e) {
            if (e.touches.length === 2) {
                e.preventDefault();
                const currentDistance = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                if (initialDistance > 0) {
                    const scaleMod = currentDistance / initialDistance;
                    const minScale = window.__baseFitScale || 0.2;
                    let newScale = Math.max(minScale, Math.min(initialScaleAtTouch * scaleMod, 2.5));
                    
                    window.__currentCustomScale = newScale;
                    root.style.transform = 'scale(' + newScale + ')';
                    
                    const ow = parseFloat(stage.dataset.origWidth || 794);
                    const oh = parseFloat(stage.dataset.origHeight || 1122);
                    stage.style.width = Math.round(ow * newScale) + 'px';
                    stage.style.height = Math.round(oh * newScale) + 'px';
                }
            }
        }, {passive: false});

        shell.addEventListener('touchend', function(e) {
            if (e.touches.length < 2) initialDistance = 0;
        });
    }

    function clearMobileStyles(root, stage) {
        document.body.classList.remove('resume-template-mobile');

        if (root) {
            root.style.position = '';
            root.style.left = '';
            root.style.top = '';
            root.style.transform = '';
            root.style.transformOrigin = '';
            root.style.margin = '';
        }

        document.querySelectorAll('.resume-page, .resume-body, .content-row, .left-panel, .right-panel, .left-sidebar, .main-content, .sidebar').forEach((element) => {
            element.style.display = '';
            element.style.flexDirection = '';
            element.style.flex = '';
            element.style.width = '';
            element.style.marginRight = '';
            element.style.backgroundSize = '';
        });

        if (stage) {
            stage.style.width = '';
            stage.style.height = '';
        }
    }

    function adjustMobileScale() {
        const root = getResumeRoot();
        const stage = root ? ensureMobileStage(root) : null;
        if (!root || !stage) return;

        if (window.innerWidth > 768) {
            clearMobileStyles(root, stage);
            return;
        }

        document.body.classList.add('resume-template-mobile');
        applyMobileDesktopLayoutFixes();

        root.style.position = 'relative';
        root.style.left = 'auto';
        root.style.top = 'auto';
        root.style.transform = 'none';
        root.style.transformOrigin = 'top left';
        root.style.margin = '0';

        const pages = Array.from(root.matches('.resume-page') ? [root] : root.querySelectorAll('.resume-page'));
        const pageWidths = pages.map((page) => {
            const rect = page.getBoundingClientRect();
            const computedWidth = parseFloat(window.getComputedStyle(page).width);
            return rect.width || computedWidth || 0;
        });

        const targetWidth = Math.max(
            root.scrollWidth || 0,
            root.getBoundingClientRect().width || 0,
            pageWidths.length ? Math.max.apply(null, pageWidths) : 0,
            794
        );
        const targetHeight = Math.max(
            root.scrollHeight || 0,
            root.getBoundingClientRect().height || 0,
            root.offsetHeight || 0,
            1122
        );
        const availableWidth = Math.max(280, window.innerWidth - 24);
        const scale = Math.min(1, availableWidth / targetWidth);

        stage.dataset.origWidth = targetWidth;
        stage.dataset.origHeight = targetHeight;
        window.__baseFitScale = scale;

        if (window.__currentCustomScale) {
            stage.style.width = Math.round(targetWidth * window.__currentCustomScale) + 'px';
            stage.style.height = Math.round(targetHeight * window.__currentCustomScale) + 'px';
            root.style.transform = 'scale(' + window.__currentCustomScale + ')';
        } else {
            stage.style.width = Math.round(targetWidth * scale) + 'px';
            stage.style.height = Math.round(targetHeight * scale) + 'px';
            root.style.transform = 'scale(' + scale + ')';
        }

        root.style.position = 'absolute';
        root.style.left = '0';
        root.style.top = '0';

        const shell = document.querySelector('.resume-mobile-shell');
        if (shell) enableCustomPinchZoom(root, stage, shell);
    }
    
    window.addEventListener('resize', adjustMobileScale);
    window.addEventListener('orientationchange', () => setTimeout(adjustMobileScale, 150));

    const mobileObserver = new MutationObserver(() => {
        clearTimeout(window.__resumeMobileMutationTimer);
        window.__resumeMobileMutationTimer = setTimeout(adjustMobileScale, 90);
    });
    mobileObserver.observe(document.body, { childList: true, subtree: true, characterData: true });

    setTimeout(adjustMobileScale, 100);
}

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", initResumeTemplateTools);
} else {
    setTimeout(initResumeTemplateTools, 0);
}

let html2PdfLoadPromise = null;

function overrideSaveForPDF() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        if (!isResumeSaveForm(form) || form.dataset.pdfDownloadBound === 'true') return;

        form.dataset.pdfDownloadBound = 'true';
        form.addEventListener('submit', downloadResumePDF, true);
    });

    document.querySelectorAll('[data-pdf-download], button, input[type="button"], input[type="submit"]').forEach(control => {
        const text = (control.innerText || control.value || '').toLowerCase();
        if (!text.includes('save') || !text.includes('download') || control.dataset.pdfDownloadBound === 'true') return;

        control.dataset.pdfDownloadBound = 'true';
        if (control.tagName === 'BUTTON') {
            control.type = 'button';
        }
        control.addEventListener('click', downloadResumePDF);
    });

    ensurePDFButtonExists();
}

function isResumeSaveForm(form) {
    const onsubmitAttr = form.getAttribute('onsubmit') || '';
    const hasResumeContent = !!form.querySelector('input[name="resume_content"]');
    const hasSaveButton = Array.from(form.querySelectorAll('button, input[type="submit"]')).some(control => {
        const text = (control.innerText || control.value || '').toLowerCase();
        return text.includes('save') && text.includes('download');
    });

    return hasResumeContent || hasSaveButton || /prepareSave|save/i.test(onsubmitAttr);
}

function ensurePDFButtonExists() {
    const hasSaveButton = Array.from(document.querySelectorAll('button, input[type="submit"], a')).some(control => {
        const text = (control.innerText || control.value || '').toLowerCase();
        return text.includes('save') && text.includes('download');
    });
    if (hasSaveButton) return;

    const panel = document.querySelector('.editor-panel, .ui-controls');
    if (!panel) return;

    const printButton = Array.from(panel.querySelectorAll('button, a')).find(control => {
        const text = (control.innerText || control.value || '').toLowerCase();
        return text.includes('print');
    });

    const pdfButton = document.createElement('button');
    pdfButton.type = 'button';
    pdfButton.className = printButton ? printButton.className : 'btn';
    pdfButton.textContent = 'Save / Download';
    pdfButton.style.background = '#27ae60';
    pdfButton.style.color = '#fff';
    pdfButton.style.marginBottom = '10px';
    pdfButton.addEventListener('click', downloadResumePDF);

    if (printButton) {
        printButton.insertAdjacentElement('beforebegin', pdfButton);
    } else {
        panel.appendChild(pdfButton);
    }
}

function downloadResumePDF(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        if (typeof event.stopImmediatePropagation === 'function') {
            event.stopImmediatePropagation();
        }
    }

    const form = event && event.target && event.target.closest ? event.target.closest('form') : null;
    const btn = (event && event.submitter) || (form && form.querySelector('button[type="submit"], input[type="submit"]')) || (event && event.currentTarget);
    execPDFDownload(btn);
    return false;
}

async function execPDFDownload(btn) {
    const originalText = btn ? (btn.innerText || btn.value || 'Save / Download') : 'Save / Download';
    setButtonBusy(btn, true, originalText);

    const originalBodyClass = document.body.className;
    const wasEditMode = document.body.classList.contains('edit-mode');
    let restoreStyles = null;
    let restoreMobileLayout = null;

    try {
        syncActiveRadioLabels();
        const prepareDelay = runTemplatePrepareSave() ? 650 : 150;
        syncActiveRadioLabels();
        document.body.classList.add('pdf-exporting');
        document.body.classList.remove('edit-mode');
        restoreStyles = applyPDFCleanupStyles();

        // ── MOBILE FIX ──────────────────────────────────────────────────────────
        // html2canvas uses getBoundingClientRect() to measure the element. On mobile,
        // the resume root is inside a scaled/transformed container, so its bounding
        // rect is zero-height. We clone the element, temporarily append the clone
        // directly to <body> at position:static so html2canvas sees the true dimensions,
        // then remove it after capture. The original DOM is untouched.
        const isMobile = window.innerWidth <= 1024;
        let mobileClone = null;
        let mobileCloneWrapper = null;
        let originalGetPDFElement = null;
        if (isMobile) {
            const origRoot = getPDFExportElement();
            if (origRoot) {
                // Create a hidden off-screen wrapper at A4 width
                mobileCloneWrapper = document.createElement('div');
                mobileCloneWrapper.style.cssText = `
                    position: absolute !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 794px !important;
                    min-height: 1122px;
                    overflow: visible !important;
                    visibility: visible !important;
                    opacity: 0.001 !important;
                    z-index: -9999 !important;
                    pointer-events: none !important;
                    background: white;
                `;

                // Clone the resume and reset its inline transform/scale
                mobileClone = origRoot.cloneNode(true);
                mobileClone.style.transform        = 'none';
                mobileClone.style.transformOrigin  = '';
                mobileClone.style.position         = 'relative';
                mobileClone.style.top              = '';
                mobileClone.style.left             = '';
                mobileClone.style.width            = '794px';
                mobileClone.style.minHeight        = '1122px';
                mobileClone.style.margin           = '0';

                mobileCloneWrapper.appendChild(mobileClone);
                document.body.appendChild(mobileCloneWrapper);
                void mobileClone.offsetHeight; // force reflow

                // Override function so html2canvas captures the clone
                originalGetPDFElement = window.getPDFExportElement;
                window.getPDFExportElement = function() { return mobileClone; };
            }
        }
        // ── END MOBILE FIX ──────────────────────────────────────────────────────

        restoreMobileLayout = () => {
            if (mobileCloneWrapper) { mobileCloneWrapper.remove(); mobileCloneWrapper = null; }
            if (originalGetPDFElement) { window.getPDFExportElement = originalGetPDFElement; originalGetPDFElement = null; }
            mobileClone = null;
        };

        await delay(prepareDelay);
        if (isMobile) await delay(500); // extra reflow time for clone

        syncActiveRadioLabels();
        document.body.classList.remove('edit-mode');
        await loadHtml2Pdf();


        const element = getPDFExportElement();
        await waitForImages(element);

        const opt = {
            margin:       0,
            filename:     getPDFFilename(),
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                logging: false,
                backgroundColor: '#ffffff',
                scrollX: 0,
                scrollY: 0,
                windowWidth: 794  // Always render at true A4 width regardless of viewport
            },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait', compress: true },
            pagebreak:    { mode: ['css', 'legacy'] }
        };

        const worker = html2pdf().set(opt).from(element).toCanvas().toPdf();
        const canvas = await worker.get('canvas');
        const pdf = await worker.get('pdf');
        removeTrailingBlankPdfPages(pdf, canvas, element);
        
        let nativeBridge = window.saveNativePDF || (window.parent && window.parent.saveNativePDF);
        if (nativeBridge) {
            console.log("Routing PDF to native Android Filesystem...");
            const base64Str = await worker.output('datauristring');
            nativeBridge(base64Str, getPDFFilename());
        } else {
            await worker.save();
        }
    } catch (err) {
        console.error(err);
        alert("An error occurred while generating the PDF. Please check your internet connection and try again.");
    } finally {
        if (restoreMobileLayout) restoreMobileLayout();
        if (restoreStyles) restoreStyles();
        document.body.className = originalBodyClass;
        if (wasEditMode) document.body.classList.add('edit-mode');
        setButtonBusy(btn, false, originalText);
    }
}

function setButtonBusy(btn, isBusy, originalText) {
    if (!btn) return;

    if (btn.tagName === 'INPUT') {
        btn.value = isBusy ? 'Generating PDF...' : originalText;
    } else {
        btn.innerText = isBusy ? 'Generating PDF...' : originalText;
    }
    btn.disabled = isBusy;
}

function runTemplatePrepareSave() {
    if (typeof window.prepareSave !== 'function') return false;

    try {
        window.prepareSave();
        return true;
    } catch (err) {
        console.warn('prepareSave failed before PDF export:', err);
        return false;
    }
}

function getPDFExportElement() {
    return document.getElementById('resumeArea')
        || document.getElementById('exportContainer')
        || document.querySelector('.resume-page')
        || document.querySelector('.resume-container')
        || document.body;
}

function getPDFFilename() {
    const title = (document.title || 'resume').replace(/resume builder/ig, 'resume').trim();
    const cleanTitle = title || 'resume';
    const stamp = new Date().toISOString().slice(0, 16).replace(/[-T:]/g, '');
    return sanitizeFilename(cleanTitle + '_' + stamp) + '.pdf';
}

function sanitizeFilename(value) {
    return value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '')
        .substring(0, 80) || 'resume';
}

function applyPDFCleanupStyles() {
    const cleanupStyle = document.createElement('style');
    cleanupStyle.id = 'pdf-download-cleanup-style';
    cleanupStyle.textContent = `
        body.pdf-exporting {
            background: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body.pdf-exporting .editor-panel,
        body.pdf-exporting .ui-controls,
        body.pdf-exporting .add-btn,
        body.pdf-exporting .remove-btn,
        body.pdf-exporting .remove-btn-skill,
        body.pdf-exporting .del-btn,
        body.pdf-exporting .btn-dashed,
        body.pdf-exporting .fresher-controls,
        body.pdf-exporting .profile-text-overlay,
        body.pdf-exporting input[type="file"],
        body.pdf-exporting input[type="radio"] {
            display: none !important;
        }
        body.pdf-exporting .radio-group label {
            display: none !important;
        }
        body.pdf-exporting .radio-group label.active-radio {
            display: inline !important;
        }
        body.pdf-exporting #exportContainer,
        body.pdf-exporting #resumeArea {
            gap: 0 !important;
            margin: 0 !important;
        }
        body.pdf-exporting .resume-container,
        body.pdf-exporting .resume-page {
            box-shadow: none !important;
            margin: 0 !important;
            transform: none !important;
            position: relative !important;
            top: auto !important;
            left: auto !important;
        }
        body.pdf-exporting .resume-mobile-stage,
        body.pdf-exporting .resume-mobile-shell {
            overflow: visible !important;
            width: auto !important;
            height: auto !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body.pdf-exporting [contenteditable],
        body.pdf-exporting input,
        body.pdf-exporting select,
        body.pdf-exporting textarea {
            outline: none !important;
            box-shadow: none !important;
        }
    `;
    document.head.appendChild(cleanupStyle);

    return () => {
        cleanupStyle.remove();
    };
}

function removeTrailingBlankPdfPages(pdf, canvas, element) {
    if (!pdf || !canvas || typeof pdf.getNumberOfPages !== 'function' || typeof pdf.deletePage !== 'function') {
        return;
    }

    const pageSize = pdf.internal && pdf.internal.pageSize;
    const pageWidth = pageSize && typeof pageSize.getWidth === 'function' ? pageSize.getWidth() : 210;
    const pageHeight = pageSize && typeof pageSize.getHeight === 'function' ? pageSize.getHeight() : 297;
    const pageHeightPx = Math.max(1, Math.floor(canvas.width * pageHeight / pageWidth));
    const meaningfulPageCount = getMeaningfulContentPageCount(element, canvas, pageHeightPx);

    while (pdf.getNumberOfPages() > meaningfulPageCount) {
        pdf.deletePage(pdf.getNumberOfPages());
    }

    while (pdf.getNumberOfPages() > 1 && isCanvasPageBlank(canvas, pdf.getNumberOfPages(), pageHeightPx)) {
        pdf.deletePage(pdf.getNumberOfPages());
    }
}

function getMeaningfulContentPageCount(element, canvas, pageHeightPx) {
    if (!element || !canvas || !element.getBoundingClientRect) return 1;

    const elementRect = element.getBoundingClientRect();
    if (!elementRect.width) return 1;

    const scale = canvas.width / elementRect.width;
    const pageHeightCss = pageHeightPx / scale;
    const contentBottom = getMeaningfulContentBottom(element);
    if (!contentBottom || !Number.isFinite(contentBottom)) return 1;

    const contentHeight = Math.max(0, contentBottom - elementRect.top);
    const tolerancePx = 10;
    return Math.max(1, Math.ceil(Math.max(0, contentHeight - tolerancePx) / pageHeightCss));
}

function getMeaningfulContentBottom(element) {
    let bottom = 0;
    const walker = document.createTreeWalker(
        element,
        NodeFilter.SHOW_TEXT | NodeFilter.SHOW_ELEMENT,
        {
            acceptNode(node) {
                if (node.nodeType === Node.TEXT_NODE) {
                    return node.textContent.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                }

                if (node.nodeType !== Node.ELEMENT_NODE) return NodeFilter.FILTER_REJECT;
                if (node.matches('script, style, template, .editor-panel, .ui-controls, .add-btn, .remove-btn, .remove-btn-skill, .del-btn, .btn-dashed')) {
                    return NodeFilter.FILTER_REJECT;
                }
                if (node.matches('img, svg, canvas, video, select, textarea, input:not([type="hidden"]):not([type="radio"]):not([type="file"])')) {
                    return NodeFilter.FILTER_ACCEPT;
                }

                return NodeFilter.FILTER_SKIP;
            }
        }
    );

    while (walker.nextNode()) {
        const node = walker.currentNode;
        const rects = node.nodeType === Node.TEXT_NODE ? getTextNodeRects(node) : node.getClientRects();
        Array.from(rects).forEach(rect => {
            if (rect.width > 0 && rect.height > 0) {
                bottom = Math.max(bottom, rect.bottom);
            }
        });
    }

    return bottom;
}

function getTextNodeRects(textNode) {
    const range = document.createRange();
    range.selectNodeContents(textNode);
    const rects = Array.from(range.getClientRects());
    if (typeof range.detach === 'function') {
        range.detach();
    }
    return rects;
}

function isCanvasPageBlank(canvas, pageNumber, pageHeightPx) {
    const context = canvas.getContext('2d', { willReadFrequently: true });
    if (!context) return false;

    const startY = Math.floor((pageNumber - 1) * pageHeightPx);
    if (startY >= canvas.height) return true;

    const sliceHeight = Math.min(pageHeightPx, canvas.height - startY);
    if (sliceHeight <= 0) return true;

    const imageData = context.getImageData(0, startY, canvas.width, sliceHeight).data;
    const totalPixels = canvas.width * sliceHeight;
    const sampleStep = Math.max(4, Math.floor(totalPixels / 90000)) * 4;
    let nonBlankPixels = 0;
    let sampledPixels = 0;

    for (let i = 0; i < imageData.length; i += sampleStep) {
        sampledPixels++;
        const r = imageData[i];
        const g = imageData[i + 1];
        const b = imageData[i + 2];
        const a = imageData[i + 3];

        if (a > 12 && (r < 246 || g < 246 || b < 246)) {
            nonBlankPixels++;
        }
    }

    if (sampledPixels === 0) return true;

    const nonBlankRatio = nonBlankPixels / sampledPixels;
    return nonBlankRatio < 0.0015;
}

function injectRadioDisplayStyles() {
    if (document.getElementById('radio-display-cleanup-style')) return;

    const style = document.createElement('style');
    style.id = 'radio-display-cleanup-style';
    style.textContent = `
        body.resume-template-active:not(.edit-mode) .radio-group input[type="radio"] {
            display: none !important;
        }
        body.resume-template-active:not(.edit-mode) .radio-group label {
            display: none !important;
        }
        body.resume-template-active:not(.edit-mode) .radio-group label.active-radio {
            display: inline !important;
        }
    `;
    document.head.appendChild(style);
}

function syncActiveRadioLabels(root = document) {
    const groups = root && root.classList && root.classList.contains('radio-group')
        ? [root]
        : Array.from(root.querySelectorAll ? root.querySelectorAll('.radio-group') : []);

    groups.forEach(group => {
        const radios = group.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            const label = radio.closest('label');
            if (!label) return;

            label.classList.toggle('active-radio', radio.checked);
            if (radio.checked) {
                radio.setAttribute('checked', 'checked');
            } else {
                radio.removeAttribute('checked');
            }
        });
    });
}

function loadHtml2Pdf() {
    if (typeof html2pdf !== 'undefined') return Promise.resolve();
    if (html2PdfLoadPromise) return html2PdfLoadPromise;

    const sources = [
        'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js',
        'https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js'
    ];

    html2PdfLoadPromise = new Promise((resolve, reject) => {
        const loadSource = (index) => {
            if (index >= sources.length) {
                reject(new Error('Unable to load html2pdf.js'));
                return;
            }

            const script = document.createElement('script');
            script.src = sources[index];
            script.onload = () => resolve();
            script.onerror = () => {
                script.remove();
                loadSource(index + 1);
            };
            document.head.appendChild(script);
        };

        loadSource(0);
    });

    return html2PdfLoadPromise;
}

function waitForImages(root) {
    const images = Array.from(root.querySelectorAll('img')).filter(img => !img.complete);
    if (!images.length) return Promise.resolve();

    return Promise.all(images.map(img => new Promise(resolve => {
        img.onload = img.onerror = resolve;
    })));
}

function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

window.downloadResumePDF = downloadResumePDF;
window.execPDFDownload = execPDFDownload;
window.syncActiveRadioLabels = syncActiveRadioLabels;


// Create Modal for Template Selection
async function createTemplateModal() {
    let cardsHTML = '<div style="text-align:center; padding: 20px;">Loading Templates...</div>';
    
    const modalHTML = `
        <div id="templateSwitchModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:99999; justify-content:center; align-items:center;">
            <div style="background:#fff; width:90%; max-width:1000px; height:80vh; border-radius:10px; padding:20px; display:flex; flex-direction:column;">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ccc; padding-bottom:10px; margin-bottom:20px;">
                    <h2 style="margin:0; color:#333;">Choose a New Template</h2>
                    <button onclick="document.getElementById('templateSwitchModal').style.display='none'" style="background:none; border:none; font-size:24px; cursor:pointer; color:black;">&times;</button>
                </div>
                <!-- Template Grid -->
                <div id="templateGridContainer" style="flex:1; overflow-y:auto; display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:20px; padding-right:10px;">
                    ${cardsHTML}
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    try {
        const response = await fetch('get_templates.php');
        const templates = await response.json();
        const grid = document.getElementById('templateGridContainer');
        grid.innerHTML = getTemplateCards(templates);
    } catch(e) {
        document.getElementById('templateGridContainer').innerHTML = '<div style="color:red; grid-column: 1 / -1;">Failed to load templates.</div>';
    }
}

function getTemplateCards(templates) {
    
    return templates.map(t => `
        <div onclick="switchTemplateTo('${t.file}')" style="border:2px solid #eee; border-radius:8px; padding:15px; text-align:center; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.borderColor='#3498db'; this.style.backgroundColor='#f8f9fa';" onmouseout="this.style.borderColor='#eee'; this.style.backgroundColor='transparent';">
            <div style="font-size:40px; margin-bottom:10px;">${t.icon}</div>
            <h4 style="margin:0; color:#333;">${t.name}</h4>
        </div>
    `).join('');
}

function openTemplateModal() {
    document.getElementById('templateSwitchModal').style.display = 'flex';
}

function switchTemplateTo(templateFile) {
    const resumeData = extractResumeData();
    sessionStorage.setItem('resumeSyncData', JSON.stringify(resumeData));
    sessionStorage.setItem('resumeSyncPending', 'true');
    window.location.href = templateFile;
}

// ------ EXTRACTION LOGIC ------
function extractResumeData() {
    return {
        name: getElemText('h1.name, h1.header-name'),
        jobTitle: getElemText('.job-title, .header-title'),
        profile: getElemText('#profileText, #objectiveText'),
        contacts: extractList('#contactList, .contact-bar', '[contenteditable="true"]'),
        personal: extractPersonal('#personalList, #personalSection'),
        experience: extractBlocks('#experienceList, #experienceSection, #expList'),
        education: extractBlocks('#educationList, #eduTable, #educationSection, #eduList'),
        skills: extractList('#skillsList, #skillsSection', '[contenteditable="true"]'),
        hobbies: extractList('#hobbiesList, #hobbiesSection', '[contenteditable="true"]')
    };
}

function getElemText(selector) {
    const el = document.querySelector(selector);
    return el ? el.innerText.trim() : '';
}

function extractList(containerSelector, elementSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return [];
    const items = container.querySelectorAll(elementSelector);
    let arr = [];
    items.forEach(item => {
        if(!item.classList.contains('personal-label')) {
             arr.push(item.innerText.trim());
        }
    });
    return arr.filter(t => t.length > 0 && t !== '•');
}

function extractPersonal(containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return [];
    const items = container.querySelectorAll('.personal-item');
    let arr = [];
    items.forEach(item => {
        const labelEl = item.querySelector('.personal-label');
        const valEls = item.querySelectorAll('[contenteditable="true"]');
        let valEl = null;
        valEls.forEach(v => {
            if(v !== labelEl) valEl = v;
        });

        if (labelEl && valEl) {
            arr.push({
                label: labelEl.innerText.replace(':', '').trim(),
                value: valEl.innerText.trim()
            });
        }
    });
    return arr;
}

function extractBlocks(containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return [];
    // Only fetch primary items that have editables
    const blocks = container.querySelectorAll('.entry, tr, .timeline-item');
    let arr = [];
    blocks.forEach(block => {
        const edits = block.querySelectorAll('[contenteditable="true"]');
        if (edits.length > 0) {
            let texts = [];
            edits.forEach(e => texts.push(e.innerText.trim()));
            arr.push(texts);
        }
    });
    return arr;
}

// ------ INJECTION LOGIC ------
function injectSavedData() {
    if (sessionStorage.getItem('resumeSyncPending') !== 'true') return;
    
    sessionStorage.removeItem('resumeSyncPending');
    
    const dataStr = sessionStorage.getItem('resumeSyncData');
    if (!dataStr) return;
    
    const data = JSON.parse(dataStr);
    console.log("Injecting Data:", data);
    
    setElemText('h1.name, h1.header-name', data.name);
    setElemText('.job-title, .header-title', data.jobTitle);
    setElemText('#profileText, #objectiveText', data.profile);
    
    injectList('#contactList, .contact-bar', data.contacts, 'addContact');
    injectList('#skillsList, #skillsSection', data.skills, 'addSkill');
    injectList('#hobbiesList, #hobbiesSection', data.hobbies, 'addHobby');
    
    injectPersonal('#personalList, #personalSection', data.personal, 'addPersonal');
    
    injectBlocks('#experienceList, #experienceSection, #expList', data.experience, 'addExperience');
    injectBlocks('#educationList, #eduTable, #educationSection, #eduList', data.education, 'addEducation');
}

function setElemText(selector, text) {
    if (!text) return;
    const el = document.querySelector(selector);
    if (el) el.innerText = text;
}

function injectList(containerSelector, dataArr, addFuncName) {
    if (!dataArr || dataArr.length === 0) return;
    const container = document.querySelector(containerSelector);
    if (!container) return;
    
    const parentChildren = Array.from(container.children);
    parentChildren.forEach(child => {
        if (!child.classList.contains('add-btn')) {
             if(child.querySelector('.remove-btn, .remove-btn-skill')) {
                 child.remove();
             } else if (child.hasAttribute('contenteditable') || child.querySelector('[contenteditable="true"]')) {
                 child.remove();
             } else if (child.tagName === 'SPAN' || child.tagName === 'DIV') {
                 child.remove();
             }
        }
    });
    
    for(let i=0; i<dataArr.length; i++) {
        if(typeof window[addFuncName] === 'function') {
            window[addFuncName]();
        }
    }
    
    setTimeout(() => {
        let items = [];
        container.querySelectorAll('[contenteditable="true"]').forEach(item => {
            if(!item.classList.contains('personal-label')) {
                items.push(item);
            }
        });
        for(let i=0; i<Math.min(dataArr.length, items.length); i++) {
            let cleanVal = dataArr[i];
            if(items[i].innerText.trim() === '•') continue;
            if(items[i].innerText.startsWith('•') && !dataArr[i].startsWith('•')) cleanVal = '• ' + cleanVal;
            items[i].innerText = cleanVal;
        }
    }, 100);
}

function injectPersonal(containerSelector, dataArr, addFuncName) {
    if (!dataArr || dataArr.length === 0) return;
    const container = document.querySelector(containerSelector);
    if (!container) return;
    
    const parentChildren = Array.from(container.querySelectorAll('.personal-item'));
    parentChildren.forEach(child => child.remove());
    
    for(let i=0; i<dataArr.length; i++) {
        if(typeof window[addFuncName] === 'function') window[addFuncName]();
    }
    
    setTimeout(() => {
        const items = container.querySelectorAll('.personal-item');
        for(let i=0; i<Math.min(dataArr.length, items.length); i++) {
            const labelEl = items[i].querySelector('.personal-label');
            const valEls = items[i].querySelectorAll('[contenteditable="true"]');
            let valEl = null;
            valEls.forEach(v => { if(v !== labelEl) valEl = v; });
            
            if(labelEl) labelEl.innerText = dataArr[i].label + (labelEl.innerText.includes(':') ? ':' : '');
            if(valEl) valEl.innerText = dataArr[i].value;
        }
    }, 100);
}

function injectBlocks(containerSelector, dataArr, addFuncName) {
    if (!dataArr || dataArr.length === 0) return;
    const container = document.querySelector(containerSelector);
    if (!container) return;
    
    const blocks = container.querySelectorAll('.entry, tr, .timeline-item');
    blocks.forEach(b => b.remove()); // remove existing
    
    for(let i=0; i<dataArr.length; i++) {
        if(typeof window[addFuncName] === 'function') window[addFuncName]();
    }
    
    setTimeout(() => {
        const newBlocks = container.querySelectorAll('.entry, tr, .timeline-item');
        for(let i=0; i<Math.min(dataArr.length, newBlocks.length); i++) {
            const edits = newBlocks[i].querySelectorAll('[contenteditable="true"]');
            for(let j=0; j<Math.min(dataArr[i].length, edits.length); j++) {
                edits[j].innerText = dataArr[i][j];
            }
        }
    }, 100);
}

// ------ ROBUST CROSS-TEMPLATE DATA SYNC ------
// Later declarations intentionally replace the original narrow sync helpers above.
const RESUME_SYNC_SELECTORS = {
    name: 'h1.name, h1.header-name, .name[contenteditable="true"], h1[contenteditable="true"]',
    jobTitle: '.job-title[contenteditable="true"], .header-title[contenteditable="true"], h2[contenteditable="true"]',
    profile: '#profileText, #objectiveText, .profile-text[contenteditable="true"]',
    contacts: '#contactList, #contactContainer, .contact-bar, .header-contact, .header-right',
    personal: '#personalList, #personalSection, #personalInfoList, #personalContainer, #personalTable',
    experience: '#experienceList, #experienceSection, #expList, #expContainer, #workList',
    education: '#educationList, #educationSection, #eduList, #eduTable, #eduContainer',
    skills: '#skillsList, #skillsSection, #skillList, #skillsContainer',
    hobbies: '#hobbiesList, #hobbiesSection, #hobbyList',
    languages: '#languageList, #langList',
    declaration: '#declarationSection, .declaration-text'
};

const RESUME_SYNC_CONTROL_SELECTOR = [
    '.editor-panel',
    '.ui-controls',
    '.fresher-controls',
    '.profile-text-overlay',
    '.add-btn',
    '.remove-btn',
    '.remove-btn-skill',
    '.del-btn',
    '.btn-dashed',
    'button',
    'script',
    'style'
].join(', ');

function extractResumeData() {
    syncActiveRadioLabels();
    const personal = extractPersonal(RESUME_SYNC_SELECTORS.personal, ['personal', 'details']);
    const languages = extractList(RESUME_SYNC_SELECTORS.languages, ['languages']) || extractPersonalListValue(personal, ['languages']);

    return {
        name: getElementText(findFirstUsableElement(RESUME_SYNC_SELECTORS.name)),
        jobTitle: getElementText(findFirstUsableElement(RESUME_SYNC_SELECTORS.jobTitle)),
        profile: getElementText(findProfileElement()),
        contacts: extractList(RESUME_SYNC_SELECTORS.contacts, ['contact']),
        personal,
        experience: extractBlocks(RESUME_SYNC_SELECTORS.experience, ['experience', 'work']),
        education: extractBlocks(RESUME_SYNC_SELECTORS.education, ['education', 'qualification']),
        skills: extractList(RESUME_SYNC_SELECTORS.skills, ['skills', 'competencies', 'expertise']),
        hobbies: extractList(RESUME_SYNC_SELECTORS.hobbies, ['hobbies', 'interests']),
        languages,
        declaration: extractDeclaration(),
        fresher: extractFresherState()
    };
}

function getElemText(selector) {
    return getElementText(document.querySelector(selector)) || '';
}

function getElementText(el) {
    return el ? normalizeText(el.innerText || el.textContent || '') : null;
}

function extractList(containerSelector, fallbackTitles) {
    const container = findResumeContainer(containerSelector, fallbackTitles);
    if (!container) return null;

    const items = getListItemElements(container);
    const values = items.length
        ? items.map(item => getListItemValue(item))
        : getValueElements(container).map(item => getElementText(item));

    return values
        .map(value => stripListMarker(value))
        .filter(value => value !== null && value.length > 0);
}

function extractPersonal(containerSelector, fallbackTitles) {
    const container = findResumeContainer(containerSelector, fallbackTitles);
    if (!container) return null;

    const items = getPersonalItemElements(container);
    return items.map(item => {
        const labelEl = findLabelElement(item);
        const label = labelEl ? normalizePersonalLabel(getElementText(labelEl)) : '';
        const value = getPersonalValue(item, labelEl);

        return {
            label,
            value: value || ''
        };
    }).filter(item => item.label || item.value);
}

function extractBlocks(containerSelector, fallbackTitles) {
    const container = findResumeContainer(containerSelector, fallbackTitles);
    if (!container) return null;

    return getBlockElements(container).map(block => {
        return getValueElements(block).map(el => normalizeText(el.innerText || el.textContent || ''));
    }).filter(values => values.length > 0);
}

function extractDeclaration() {
    const container = findResumeContainer(RESUME_SYNC_SELECTORS.declaration, ['declaration']);
    if (!container) return null;

    const editables = getValueElements(container).filter(el => !isHeadingLike(el));
    const values = editables.map(el => getElementText(el)).filter(Boolean);
    return values.length ? values : null;
}

function extractFresherState() {
    const checkbox = document.getElementById('fresherCheck');
    if (!checkbox) return null;

    const fresherSection = document.getElementById('fresherSection');
    return {
        checked: !!checkbox.checked,
        text: fresherSection ? getElementText(fresherSection) : null
    };
}

function extractPersonalListValue(personal, labels) {
    if (!Array.isArray(personal)) return null;

    const item = personal.find(row => labels.some(label => labelsMatch(row.label, label)));
    if (!item || !item.value) return null;

    return normalizeText(item.value)
        .split(/[,;|/]+/)
        .map(value => value.trim())
        .filter(Boolean);
}

function injectSavedData() {
    if (sessionStorage.getItem('resumeSyncPending') !== 'true') return;

    sessionStorage.removeItem('resumeSyncPending');

    const dataStr = sessionStorage.getItem('resumeSyncData');
    if (!dataStr) return;

    let data = null;
    try {
        data = JSON.parse(dataStr);
    } catch (err) {
        console.warn('Unable to parse saved resume data:', err);
        return;
    }

    console.log("Injecting Data:", data);

    setElementText(findFirstUsableElement(RESUME_SYNC_SELECTORS.name), data.name);
    setElementText(findFirstUsableElement(RESUME_SYNC_SELECTORS.jobTitle), data.jobTitle);
    setElementText(findProfileElement(), data.profile);

    injectList(RESUME_SYNC_SELECTORS.contacts, ['contact'], data.contacts, [
        'addContact',
        'addContactLine'
    ]);
    injectList(RESUME_SYNC_SELECTORS.skills, ['skills', 'competencies', 'expertise'], data.skills, [
        'addSkill',
        { name: 'addListItem', args: getContainerIdArgs }
    ]);
    injectList(RESUME_SYNC_SELECTORS.hobbies, ['hobbies', 'interests'], data.hobbies, [
        'addHobby',
        { name: 'addListItem', args: getContainerIdArgs }
    ]);
    injectList(RESUME_SYNC_SELECTORS.languages, ['languages'], data.languages, [
        'addLanguage',
        { name: 'addListItem', args: getContainerIdArgs }
    ]);

    injectPersonal(RESUME_SYNC_SELECTORS.personal, ['personal', 'details'], data.personal, [
        'addPersonal',
        'addPersonalInfo',
        'addPersonalDetail',
        'addPersonalRow'
    ]);

    injectBlocks(RESUME_SYNC_SELECTORS.experience, ['experience', 'work'], data.experience, [
        'addExperience',
        'addWork',
        { name: 'addListItem', args: getContainerIdArgs }
    ]);
    injectBlocks(RESUME_SYNC_SELECTORS.education, ['education', 'qualification'], data.education, [
        'addEducation',
        'addEducationRow',
        'addEdu',
        { name: 'addListItem', args: getContainerIdArgs }
    ]);

    injectDeclaration(data.declaration);

    setTimeout(() => {
        injectFresherState(data.fresher);
        syncActiveRadioLabels();
    }, 200);
}

function setElemText(selector, text) {
    setElementText(document.querySelector(selector), text);
}

function setElementText(el, text) {
    if (!el || text === null || typeof text === 'undefined') return;
    el.innerText = text;
}

function injectList(containerSelector, fallbackTitles, dataArr, addCandidates) {
    if (!Array.isArray(dataArr)) return;

    const container = findResumeContainer(containerSelector, fallbackTitles);
    if (!container) return;

    const existingItems = getListItemElements(container);
    const templateItem = existingItems.length ? existingItems[existingItems.length - 1].cloneNode(true) : null;
    while (existingItems.length > dataArr.length) {
        const item = existingItems.pop();
        if (item) item.remove();
    }

    for (let i = existingItems.length; i < dataArr.length; i++) {
        callFirstAddFunction(addCandidates, container);
    }

    setTimeout(() => {
        let items = getListItemElements(container);
        while (items.length < dataArr.length) {
            appendClonedItem(container, templateItem, 'div');
            items = getListItemElements(container);
        }

        for (let i = 0; i < dataArr.length; i++) {
            setListItemValue(items[i], dataArr[i]);
        }
    }, 100);
}

function injectPersonal(containerSelector, fallbackTitles, dataArr, addCandidates) {
    if (!Array.isArray(dataArr)) return;

    const container = findResumeContainer(containerSelector, fallbackTitles);
    if (!container) return;

    const existingItems = getPersonalItemElements(container);
    const templateItem = existingItems.length ? existingItems[existingItems.length - 1].cloneNode(true) : null;
    while (existingItems.length > dataArr.length) {
        const item = existingItems.pop();
        if (item) item.remove();
    }

    for (let i = existingItems.length; i < dataArr.length; i++) {
        callFirstAddFunction(addCandidates, container);
    }

    setTimeout(() => {
        let items = getPersonalItemElements(container);
        while (items.length < dataArr.length) {
            appendClonedItem(container, templateItem, 'div');
            items = getPersonalItemElements(container);
        }

        items = matchPersonalTargets(items, dataArr);
        for (let i = 0; i < dataArr.length; i++) {
            setPersonalItemValue(items[i], dataArr[i]);
        }
    }, 100);
}

function injectBlocks(containerSelector, fallbackTitles, dataArr, addCandidates) {
    if (!Array.isArray(dataArr)) return;

    const container = findResumeContainer(containerSelector, fallbackTitles);
    if (!container) return;

    const existingBlocks = getBlockElements(container);
    const templateBlock = existingBlocks.length ? existingBlocks[existingBlocks.length - 1].cloneNode(true) : null;
    while (existingBlocks.length > dataArr.length) {
        const block = existingBlocks.pop();
        if (block) block.remove();
    }

    for (let i = existingBlocks.length; i < dataArr.length; i++) {
        callFirstAddFunction(addCandidates, container);
    }

    setTimeout(() => {
        let blocks = getBlockElements(container);
        while (blocks.length < dataArr.length) {
            appendClonedItem(container, templateBlock, getFallbackBlockTag(container));
            blocks = getBlockElements(container);
        }

        for (let i = 0; i < dataArr.length; i++) {
            const edits = getValueElements(blocks[i]);
            for (let j = 0; j < Math.min(dataArr[i].length, edits.length); j++) {
                setElementText(edits[j], dataArr[i][j]);
            }
        }
    }, 100);
}

function injectDeclaration(values) {
    if (!Array.isArray(values)) return;

    const container = findResumeContainer(RESUME_SYNC_SELECTORS.declaration, ['declaration']);
    if (!container) return;

    const editables = getValueElements(container).filter(el => !isHeadingLike(el));
    for (let i = 0; i < Math.min(values.length, editables.length); i++) {
        setElementText(editables[i], values[i]);
    }
}

function injectFresherState(state) {
    if (!state || typeof state.checked !== 'boolean') return;

    const checkbox = document.getElementById('fresherCheck');
    if (!checkbox) return;

    checkbox.checked = state.checked;

    const fresherSection = document.getElementById('fresherSection');
    if (fresherSection && state.text) {
        const target = fresherSection.querySelector('[contenteditable="true"]') || fresherSection;
        setElementText(target, state.text);
    }

    if (typeof window.toggleFresherMode === 'function') {
        window.toggleFresherMode();
    } else if (fresherSection) {
        fresherSection.style.display = state.checked ? 'block' : 'none';
    }
}

function findFirstUsableElement(selector) {
    return Array.from(document.querySelectorAll(selector)).find(el => {
        return !isInControlArea(el) && !isHeadingLike(el);
    }) || null;
}

function findProfileElement() {
    return findFirstUsableElement(RESUME_SYNC_SELECTORS.profile)
        || findEditableInSection(['profile', 'summary', 'objective', 'about']);
}

function findResumeContainer(selector, fallbackTitles) {
    const direct = Array.from(document.querySelectorAll(selector)).find(el => !isInControlArea(el));
    if (direct) return direct;
    return findSectionContainer(fallbackTitles || []);
}

function findEditableInSection(titleWords) {
    const section = findSectionContainer(titleWords);
    if (!section) return null;

    return getValueElements(section).find(el => !isHeadingLike(el)) || null;
}

function findSectionContainer(titleWords) {
    if (!titleWords || !titleWords.length) return null;

    const headings = Array.from(document.querySelectorAll('.section-title, .section-header, h2, h3, h4'));
    const heading = headings.find(el => {
        const text = normalizeText(el.innerText || el.textContent || '').toLowerCase();
        return text && titleWords.some(word => text.includes(word));
    });
    if (!heading || isInControlArea(heading)) return null;

    return heading.closest('.section, .sidebar-section, .col-half, .col-left, .col-right, aside, section')
        || heading.parentElement
        || null;
}

function getValueElements(root) {
    if (!root) return [];

    const selector = '[contenteditable="true"], [contenteditable=true]';
    const values = [];
    if (root.matches && root.matches(selector) && !isInControlArea(root)) {
        values.push(root);
    }

    root.querySelectorAll(selector).forEach(el => {
        if (!isInControlArea(el)) values.push(el);
    });

    return values;
}

function getListItemElements(container) {
    if (!container) return [];

    const children = Array.from(container.children || []).filter(child => {
        return isContentItem(child) && hasItemValue(child) && !looksLikePersonalItem(child);
    });
    if (children.length) return children;

    const nestedItems = Array.from(container.querySelectorAll('li, .contact-item, .contact-line, .contact-row, .skill-item, .skill-tag, .grid-item')).filter(item => {
        return item.parentElement !== container || hasItemValue(item);
    }).filter(item => !isInControlArea(item) && hasItemValue(item));
    if (nestedItems.length) return nestedItems;

    return getValueElements(container);
}

function getPersonalItemElements(container) {
    if (!container) return [];

    const itemSelector = '.personal-item, .info-item, .details-row, .sidebar-item';
    const items = Array.from(container.querySelectorAll(itemSelector)).filter(item => {
        return !isInControlArea(item) && (findLabelElement(item) || hasItemValue(item));
    });
    if (items.length) return items;

    return Array.from(container.children || []).filter(child => {
        return isContentItem(child) && (findLabelElement(child) || getValueElements(child).length >= 2 || child.querySelector('select, .radio-group'));
    });
}

function getBlockElements(container) {
    if (!container) return [];

    const tableBody = container.matches && container.matches('table') ? container.querySelector('tbody') : null;
    const root = tableBody || container;
    const selector = '.entry, .timeline-item, tr, li';

    const blocks = Array.from(root.querySelectorAll(selector)).filter(block => {
        return !isInControlArea(block) && getValueElements(block).length > 0 && !block.closest('thead');
    });
    if (blocks.length) return blocks;

    return Array.from(root.children || []).filter(child => {
        return isContentItem(child) && getValueElements(child).length > 0;
    });
}

function getListItemValue(item) {
    if (!item) return '';
    if (item.matches && item.matches('[contenteditable="true"], [contenteditable=true]')) {
        return getElementText(item) || '';
    }

    const editables = getValueElements(item).filter(el => !isHeadingLike(el) && !isLabelLike(el));
    if (editables.length) return getElementText(editables[0]) || '';

    return getChoiceValue(item) || '';
}

function getPersonalValue(item, labelEl) {
    const choice = getChoiceValue(item);
    if (choice) return choice;

    const valueEl = item.querySelector('.personal-value[contenteditable="true"], .info-value[contenteditable="true"], .details-value[contenteditable="true"]')
        || Array.from(getValueElements(item)).find(el => el !== labelEl && !isLabelLike(el));

    return getElementText(valueEl) || '';
}

function getChoiceValue(root) {
    const checkedRadio = root.querySelector('input[type="radio"]:checked');
    if (checkedRadio) {
        const label = checkedRadio.closest('label');
        return normalizeText(checkedRadio.value || (label ? label.innerText : ''));
    }

    const activeLabel = root.querySelector('.radio-group label.active-radio');
    if (activeLabel) return normalizeText(activeLabel.innerText || '');

    const select = root.querySelector('select');
    if (select) {
        const selectedOption = select.options[select.selectedIndex];
        return normalizeText((selectedOption && (selectedOption.text || selectedOption.value)) || select.value || select.getAttribute('value') || '');
    }

    return '';
}

function setListItemValue(item, value) {
    if (!item) return;

    if (item.matches && item.matches('[contenteditable="true"], [contenteditable=true]')) {
        setElementText(item, formatForTarget(item, value));
        return;
    }

    const target = getValueElements(item).find(el => !isHeadingLike(el) && !isLabelLike(el));
    if (target) {
        setElementText(target, formatForTarget(target, value));
        return;
    }

    setChoiceValue(item, value);
}

function setPersonalItemValue(item, data) {
    if (!item || !data) return;

    const labelEl = findLabelElement(item);
    if (labelEl && data.label !== null && typeof data.label !== 'undefined') {
        const currentLabel = getElementText(labelEl) || '';
        setElementText(labelEl, data.label + (currentLabel.includes(':') ? ':' : ''));
    }

    if (setChoiceValue(item, data.value)) return;

    const valueEl = item.querySelector('.personal-value[contenteditable="true"], .info-value[contenteditable="true"], .details-value[contenteditable="true"]')
        || Array.from(getValueElements(item)).find(el => el !== labelEl && !isLabelLike(el));

    if (valueEl) {
        setElementText(valueEl, data.value || '');
    } else {
        setElementText(insertEditableValue(item), data.value || '');
    }
}

function matchPersonalTargets(items, dataArr) {
    const available = items.slice();
    return dataArr.map(data => {
        const matchedIndex = available.findIndex(item => labelsMatch(data.label, getElementText(findLabelElement(item))));
        if (matchedIndex >= 0) {
            return available.splice(matchedIndex, 1)[0];
        }
        return available.shift();
    });
}

function setChoiceValue(root, value) {
    if (!root || value === null || typeof value === 'undefined') return false;

    const radios = Array.from(root.querySelectorAll('input[type="radio"]'));
    if (radios.length) {
        const matched = radios.find(radio => choiceMatches(value, radio.value))
            || radios.find(radio => {
                const label = radio.closest('label');
                return label && choiceMatches(value, label.innerText);
            });

        if (matched) {
            matched.checked = true;
            syncActiveRadioLabels(matched.closest('.radio-group') || root);
            return true;
        }
    }

    const select = root.querySelector('select');
    if (select) {
        const option = Array.from(select.options).find(opt => choiceMatches(value, opt.value) || choiceMatches(value, opt.text));
        if (option) {
            select.value = option.value;
            select.setAttribute('value', option.value);
            return true;
        }
    }

    return false;
}

function findLabelElement(item) {
    return item.querySelector('.personal-label, .info-label, .sidebar-label, .details-label')
        || Array.from(getValueElements(item)).find(isLabelLike)
        || null;
}

function callFirstAddFunction(candidates, container) {
    for (const candidate of candidates || []) {
        const name = typeof candidate === 'string' ? candidate : candidate.name;
        const fn = window[name];
        if (typeof fn !== 'function') continue;

        const args = typeof candidate === 'object' && candidate.args
            ? candidate.args(container)
            : [];
        if (args === null) continue;

        try {
            fn.apply(window, args);
            return true;
        } catch (err) {
            console.warn(`Could not call ${name} while syncing resume data:`, err);
        }
    }

    return false;
}

function appendClonedItem(container, templateItem, fallbackTag) {
    const item = templateItem ? templateItem.cloneNode(true) : document.createElement(fallbackTag || 'div');
    if (!templateItem) {
        item.setAttribute('contenteditable', 'true');
        item.innerText = '';
    }

    const tableBody = container.matches && container.matches('table') ? container.querySelector('tbody') : null;
    const target = tableBody || container;
    target.appendChild(item);
    return item;
}

function insertEditableValue(item) {
    const valueEl = document.createElement('span');
    valueEl.setAttribute('contenteditable', 'true');

    const firstButton = item.querySelector('button, .remove-btn, .remove-btn-skill, .del-btn');
    if (firstButton && firstButton.parentElement === item) {
        item.insertBefore(valueEl, firstButton);
    } else {
        item.appendChild(valueEl);
    }

    return valueEl;
}

function getContainerIdArgs(container) {
    return container && container.id ? [container.id] : null;
}

function getFallbackBlockTag(container) {
    if (!container) return 'div';
    if (container.matches && container.matches('table')) return 'tr';
    if (container.matches && /^(UL|OL)$/i.test(container.tagName)) return 'li';
    return 'div';
}

function hasItemValue(item) {
    return getValueElements(item).length > 0 || !!item.querySelector('select, .radio-group, input[type="radio"]');
}

function isContentItem(el) {
    if (!el || isInControlArea(el)) return false;
    if (el.matches('button, script, style, template, thead, tbody, tfoot')) return false;
    if (el.classList.contains('add-btn') || el.classList.contains('ui-controls') || el.classList.contains('fresher-controls')) return false;
    return hasItemValue(el);
}

function isInControlArea(el) {
    return !!(el && el.closest && el.closest(RESUME_SYNC_CONTROL_SELECTOR));
}

function looksLikePersonalItem(el) {
    return !!(el && el.matches && el.matches('.personal-item, .info-item, .details-row, .sidebar-item'));
}

function isLabelLike(el) {
    if (!el || !el.classList) return false;
    return el.classList.contains('personal-label')
        || el.classList.contains('info-label')
        || el.classList.contains('sidebar-label')
        || el.classList.contains('details-label')
        || el.classList.contains('sign-label');
}

function isHeadingLike(el) {
    if (!el || !el.classList) return false;
    return el.classList.contains('section-title')
        || el.classList.contains('section-header')
        || el.classList.contains('sidebar-section-title')
        || el.classList.contains('main-heading');
}

function normalizeText(value) {
    return String(value || '').replace(/\u00a0/g, ' ').replace(/\s+/g, ' ').trim();
}

function normalizePersonalLabel(value) {
    return normalizeText(value).replace(/:+$/g, '').trim();
}

function stripListMarker(value) {
    if (value === null || typeof value === 'undefined') return null;
    return normalizeText(value).replace(/^([\u2022\-*]\s*)+/, '').trim();
}

function formatForTarget(target, value) {
    const source = normalizeText(value);
    const current = normalizeText(target ? target.innerText || target.textContent || '' : '');
    const marker = current.match(/^([\u2022\-*]\s+)/);
    if (marker && source && !source.match(/^[\u2022\-*]\s+/)) {
        return marker[1] + source;
    }
    return source;
}

function choiceMatches(left, right) {
    return normalizeChoice(left) === normalizeChoice(right);
}

function labelsMatch(left, right) {
    const leftLabel = normalizeLabel(left);
    const rightLabel = normalizeLabel(right);
    return !!leftLabel && !!rightLabel && leftLabel === rightLabel;
}

function normalizeChoice(value) {
    let text = normalizeText(value).toLowerCase();
    text = text.replace(/[^a-z0-9]+/g, '');
    if (['single', 'unmarried', 'unmarried'].includes(text)) return 'unmarried';
    if (['married'].includes(text)) return 'married';
    return text;
}

function normalizeLabel(value) {
    let text = normalizeText(value).toLowerCase().replace(/[^a-z0-9]+/g, '');
    if (['dob', 'born', 'birthdate', 'dateofbirth'].includes(text)) return 'dateofbirth';
    if (['language', 'languages', 'languageproficiency'].includes(text)) return 'languages';
    if (['fathername', 'fathersname'].includes(text)) return 'fathername';
    if (['marital', 'maritalstatus'].includes(text)) return 'maritalstatus';
    if (['location', 'address', 'currentaddress'].includes(text)) return 'address';
    return text;
}
