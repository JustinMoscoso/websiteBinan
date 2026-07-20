/**
 * Quill Editor Initialization for Admin Pages
 * This file contains reusable Quill editor initialization code
 */

// Quill toolbar options - consistent across all pages
const quillToolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'align': '' }, { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    ['link'],
    ['clean']
];

// Quill editor instances storage
const quillInstances = {};

/**
 * Initialize Quill editor for a specific element
 * @param {string} elementId - The ID of the element to initialize Quill on
 * @param {string} instanceName - Unique name for this Quill instance
 * @param {Object} options - Additional Quill options (optional)
 */
function initQuillEditor(elementId, instanceName, options = {}) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.warn(`Element with ID '${elementId}' not found`);
        return null;
    }

    // Default options
    const defaultOptions = {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    };

    // Merge options
    const quillOptions = { ...defaultOptions, ...options };

    // Initialize Quill
    const quill = new Quill(`#${elementId}`, quillOptions);
    
    // Store instance
    quillInstances[instanceName] = quill;
    
    return quill;
}

/**
 * Get Quill instance by name
 * @param {string} instanceName - Name of the Quill instance
 * @returns {Object|null} Quill instance or null if not found
 */
function getQuillInstance(instanceName) {
    return quillInstances[instanceName] || null;
}

/**
 * Set content in Quill editor
 * @param {string} instanceName - Name of the Quill instance
 * @param {string} content - HTML content to set
 */
function setQuillContent(instanceName, content) {
    const quill = getQuillInstance(instanceName);
    if (quill) {
        quill.root.innerHTML = content || '';
    }
}

/**
 * Get content from Quill editor
 * @param {string} instanceName - Name of the Quill instance
 * @returns {string} HTML content from the editor
 */
function getQuillContent(instanceName) {
    const quill = getQuillInstance(instanceName);
    return quill ? quill.root.innerHTML : '';
}

/**
 * Initialize Quill editors for a specific page
 * @param {Object} config - Configuration object with editor definitions
 */
function initPageQuillEditors(config) {
    if (!config || !config.editors) {
        console.warn('No editor configuration provided');
        return;
    }

    config.editors.forEach(editor => {
        const { elementId, instanceName, modalId, options, shouldInit } = editor;
        
        // Initialize when modal is shown
        $(`#${modalId}`).on('shown.bs.modal', function () {
            // Check if we should initialize this editor
            if (shouldInit && typeof shouldInit === 'function') {
                if (!shouldInit()) {
                    return; // Don't initialize if shouldInit returns false
                }
            }
            
            if (!getQuillInstance(instanceName)) {
                initQuillEditor(elementId, instanceName, options);
            }
        });
    });
}

/**
 * Setup form submission handlers for Quill content
 * @param {Object} config - Configuration object with form handlers
 */
function setupQuillFormHandlers(config) {
    if (!config || !config.formHandlers) {
        console.warn('No form handler configuration provided');
        return;
    }

    // Store the handlers for later use
    window.quillFormHandlers = config.formHandlers;
}

/**
 * Update hidden inputs with Quill content before form submission
 * Call this function before submitting forms that contain Quill editors
 */
function updateQuillFormContent() {
    if (!window.quillFormHandlers) {
        return;
    }

    window.quillFormHandlers.forEach(handler => {
        const { instanceName, hiddenInputId } = handler;
        const content = getQuillContent(instanceName);
        if (hiddenInputId && content !== undefined) {
            $(`#${hiddenInputId}`).val(content);
        }
    });
}

/**
 * Setup edit content population for Quill editors
 * @param {Object} config - Configuration object with edit handlers
 */
function setupQuillEditHandlers(config) {
    if (!config || !config.editHandlers) {
        console.warn('No edit handler configuration provided');
        return;
    }

    config.editHandlers.forEach(handler => {
        const { modalId, instanceName, contentField } = handler;
        
        $(`#${modalId}`).on('shown.bs.modal', function () {
            const quill = getQuillInstance(instanceName);
            if (quill && contentField) {
                // Get content from the hidden input or data attribute
                const content = $(`#${contentField}`).val() || '';
                setQuillContent(instanceName, content);
            }
        });
    });
}

/**
 * Clear all Quill instances
 */
function clearQuillInstances() {
    Object.keys(quillInstances).forEach(key => {
        delete quillInstances[key];
    });
}

/**
 * Destroy Quill editor instance
 * @param {string} instanceName - Name of the Quill instance to destroy
 */
function destroyQuillInstance(instanceName) {
    const quill = getQuillInstance(instanceName);
    if (quill) {
        // Remove the Quill instance
        delete quillInstances[instanceName];
    }
}

// Export functions for use in other scripts
window.QuillManager = {
    initQuillEditor,
    getQuillInstance,
    setQuillContent,
    getQuillContent,
    initPageQuillEditors,
    setupQuillFormHandlers,
    setupQuillEditHandlers,
    updateQuillFormContent,
    clearQuillInstances,
    destroyQuillInstance,
    quillToolbarOptions
}; 