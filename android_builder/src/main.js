import { Filesystem, Directory } from '@capacitor/filesystem';

// Expose the native PDF saving function to the global window
window.saveNativePDF = async function(base64Data, filename) {
    try {
        console.log("Capacitor Native Bridge: Saving PDF...");
        
        // Ensure base64 string doesn't have the data URL prefix if html2pdf includes it
        const prefixIndex = base64Data.indexOf('base64,');
        if (prefixIndex !== -1) {
            base64Data = base64Data.substring(prefixIndex + 7);
        }

        const result = await Filesystem.writeFile({
            path: filename,
            data: base64Data,
            directory: Directory.Documents, // Native Android Documents folder
        });
        
        console.log('PDF saved to Native Filesystem URI:', result.uri);
        alert('Resume saved successfully to Documents folder!');
    } catch (e) {
        console.error('Unable to write native file', e);
        alert('Failed to save the PDF natively on this device. Error: ' + e.message);
    }
};

console.log("Capacitor Initialized. Native bridge is ready.");
