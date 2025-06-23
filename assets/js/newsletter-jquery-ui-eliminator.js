/**
 * Newsletter jQuery UI ELIMINATOR - Production Version
 * 
 * Eliminiert jQuery UI komplett aus dem Newsletter-Plugin
 * Ersetzt durch natives HTML5 Drag&Drop System
 */

(function($) {
    'use strict';
    
    let blockDropInProgress = false;
    let draggedBlockId = null;
    let draggedElement = null;
    let currentDropTarget = null;
    let dropIndicator = null;
    let cleanupInterval = null;
    
    // SCHRITT 1: SOFORTIGE JQUERY UI BLOCKIERUNG
    function blockJQueryUICompletely() {
        // WordPress-spezifische Blockierung
        if (window.wp && window.wp.hooks) {
            window.wp.hooks.addFilter('script_loader_tag', 'newsletter_block_jquery_ui', function(tag, handle) {
                if (handle.includes('jquery-ui-sortable') || handle.includes('jquery-ui-draggable')) {
                    return '<!-- jQuery UI ' + handle + ' blocked by Newsletter Plugin -->';
                }
                return tag;
            });
        }
        
        // jQuery UI Methoden deaktivieren
        if (window.jQuery) {
            if ($.fn.sortable) {
                $.fn.sortable = function() { return this; };
            }
            if ($.fn.draggable) {
                $.fn.draggable = function() { return this; };
            }
        }
    }
    
    // Sofort blockieren
    blockJQueryUICompletely();
    
    // SCHRITT 2: AGGRESSIVE BEREINIGUNG
    function aggressiveCleanup() {
        // Entferne alle jQuery UI CSS-Klassen
        const uiSelectors = [
            '[class*="ui-"]',
            '.ui-sortable',
            '.ui-draggable',
            '.ui-droppable',
            '.ui-sortable-handle',
            '.ui-sortable-helper',
            '.ui-sortable-placeholder'
        ];
        
        uiSelectors.forEach(selector => {
            try {
                document.querySelectorAll(selector).forEach(el => {
                    const classes = el.className.split(' ').filter(cls => !cls.startsWith('ui-'));
                    el.className = classes.join(' ');
                });
            } catch (e) {
                // Ignoriere Fehler
            }
        });
    }
    
    // SCHRITT 3: NATIVES DRAG&DROP SYSTEM
    function initCleanDragDrop() {
        // Block-Icons mit nativer HTML5 API einrichten (Neue Blöcke aus Sidebar)
        const blockIcons = document.querySelectorAll('.tnpb-block-icon');
        
        blockIcons.forEach((icon) => {
            if (!icon.hasAttribute('data-drag-setup')) {
                icon.removeAttribute('draggable');
                icon.setAttribute('draggable', 'true');
                icon.setAttribute('data-drag-setup', 'true');
                icon.style.cursor = 'move';
                
                icon.addEventListener('dragstart', handleDragStartNewBlock, false);
                icon.addEventListener('dragend', handleDragEnd, false);
            }
        });
        
        // Vorhandene Blöcke draggable machen (Sortierung)
        const existingBlocks = document.querySelectorAll('.tnpc-row, .tnpc-row-block');
        
        existingBlocks.forEach(block => {
            setupExistingBlockDragging(block);
        });
        
        // Content-Bereich als Drop-Zone (nur einmal)
        const contentArea = document.getElementById('tnpb-content');
        
        if (contentArea && !contentArea.hasAttribute('data-drop-setup')) {
            contentArea.setAttribute('data-drop-setup', 'true');
            
            // Content-Area muss als Drop-Target erkannt werden
            contentArea.style.minHeight = '200px';
            
            contentArea.addEventListener('dragover', handleDragOver, false);
            contentArea.addEventListener('drop', handleDrop, false);
            contentArea.addEventListener('dragleave', handleDragLeave, false);
            contentArea.addEventListener('dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        }
        
        // Drop Here Box für leeren Content
        updateDropHereBox();
    }
    
    // Setup für vorhandene Blöcke (Sortierung)
    function setupExistingBlockDragging(block) {
        if (!block.hasAttribute('data-drag-setup')) {
            block.setAttribute('draggable', 'true');
            block.setAttribute('data-drag-setup', 'true');
            block.style.cursor = 'move';
            
            block.addEventListener('dragstart', handleDragStartExistingBlock, false);
            block.addEventListener('dragend', handleDragEnd, false);
        }
    }
    
    // DRAG&DROP EVENT-HANDLER
    function handleDragStartNewBlock(e) {
        // Neuer Block aus Sidebar
        const blockIcon = e.target.closest('.tnpb-block-icon');
        if (!blockIcon) return;
        
        draggedBlockId = blockIcon.getAttribute('data-id');
        draggedElement = blockIcon;
        
        if (draggedBlockId) {
            e.dataTransfer.setData('text/plain', draggedBlockId);
            e.dataTransfer.setData('block-type', 'new');
            e.dataTransfer.setData('block-id', draggedBlockId);
            e.dataTransfer.effectAllowed = 'copy';
            blockIcon.style.opacity = '0.6';
        }
        
        createDropIndicator();
        updateDropHereBox();
    }
    
    function handleDragStartExistingBlock(e) {
        // Vorhandener Block (Sortierung)
        const blockElement = e.currentTarget;
        
        // Bessere Block-ID-Extraktion
        let blockId = blockElement.getAttribute('data-id');
        if (!blockId) {
            blockId = blockElement.querySelector('[data-id]')?.getAttribute('data-id');
        }
        if (!blockId) {
            // Fallback: Verwende innerHTML-Hash als temporäre ID
            blockId = 'block-' + Math.random().toString(36).substr(2, 9);
        }
        
        draggedBlockId = blockId;
        draggedElement = blockElement;
        
        e.dataTransfer.setData('text/plain', blockId);
        e.dataTransfer.setData('block-type', 'existing');
        e.dataTransfer.setData('block-html', blockElement.outerHTML);
        e.dataTransfer.setData('block-id', blockId);
        e.dataTransfer.effectAllowed = 'move';
        blockElement.style.opacity = '0.6';
        
        createDropIndicator();
    }
    
    function handleDragEnd(e) {
        if (draggedElement) {
            draggedElement.style.opacity = '1';
        }
        
        removeDropIndicator();
        document.querySelectorAll('.tnpc-drop-here').forEach(el => el.remove());
        
        draggedBlockId = null;
        draggedElement = null;
        currentDropTarget = null;
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = 'copy';
        
        const contentArea = document.getElementById('tnpb-content');
        if (!contentArea) return;
        
        const rect = contentArea.getBoundingClientRect();
        const mouseY = e.clientY;
        
        const position = {
            x: rect.left + 10,
            y: mouseY,
            width: rect.width - 20,
            element: null,
            insertBefore: false
        };
        
        // Finde nächsten Block
        const blocks = contentArea.querySelectorAll('.tnpc-row, .tnpc-row-block');
        for (let block of blocks) {
            const blockRect = block.getBoundingClientRect();
            const blockMiddle = blockRect.top + (blockRect.height / 2);
            
            if (mouseY < blockMiddle) {
                position.element = block;
                position.insertBefore = true;
                position.y = blockRect.top - 10;
                break;
            }
        }
        
        if (!position.element && blocks.length > 0) {
            const lastBlock = blocks[blocks.length - 1];
            const lastRect = lastBlock.getBoundingClientRect();
            position.element = lastBlock;
            position.insertBefore = false;
            position.y = lastRect.bottom + 10;
        }
        
        updateDropIndicator(position);
        window.currentDropPosition = position;
    }
    
    function handleDragLeave(e) {
        const contentArea = document.getElementById('tnpb-content');
        if (contentArea && !contentArea.contains(e.relatedTarget)) {
            hideDropIndicator();
        }
    }
    
    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        
        hideDropIndicator();
        
        if (blockDropInProgress) {
            return false;
        }
        
        const blockId = e.dataTransfer.getData('text/plain') || 
                       e.dataTransfer.getData('block-id') || 
                       draggedBlockId;
        const blockType = e.dataTransfer.getData('block-type');
        const blockHtml = e.dataTransfer.getData('block-html');
        
        if (!blockId) {
            return false;
        }
        
        const position = window.currentDropPosition || {
            element: null,
            insertBefore: false,
            x: 0,
            y: e.clientY,
            width: 200
        };
        
        blockDropInProgress = true;
        
        // Unterschiedliche Behandlung für neue vs. bestehende Blöcke
        if (blockType === 'existing' && draggedElement) {
            // Sortierung: Block verschieben
            handleExistingBlockSort(draggedElement, position, function() {
                blockDropInProgress = false;
                draggedBlockId = null;
                window.currentDropPosition = null;
            });
        } else {
            // Neuer Block: Rendern via AJAX
            renderBlockAtPosition(blockId, position, function() {
                blockDropInProgress = false;
                draggedBlockId = null;
                window.currentDropPosition = null;
            });
        }
        
        return true;
    }
    
    // SORTIERUNG BESTEHENDER BLÖCKE
    function handleExistingBlockSort(blockElement, position, callback) {
        const contentArea = document.getElementById('tnpb-content');
        if (!contentArea || !blockElement) {
            if (callback) callback();
            return;
        }
        
        // Block temporär entfernen
        const originalParent = blockElement.parentNode;
        const originalNextSibling = blockElement.nextSibling;
        blockElement.remove();
        
        try {
            // An neuer Position einfügen
            if (position && position.element) {
                if (position.insertBefore) {
                    position.element.parentNode.insertBefore(blockElement, position.element);
                } else {
                    position.element.parentNode.insertBefore(blockElement, position.element.nextSibling);
                }
            } else {
                contentArea.appendChild(blockElement);
            }
            
            // Block wieder draggable machen
            setupExistingBlockDragging(blockElement);
            
        } catch (error) {
            // Bei Fehler: Block an ursprüngliche Position zurück
            if (originalParent) {
                if (originalNextSibling) {
                    originalParent.insertBefore(blockElement, originalNextSibling);
                } else {
                    originalParent.appendChild(blockElement);
                }
            }
        }
        
        if (callback) callback();
    }
    
    // BLOCK-RENDERING (nur für neue Blöcke)
    function renderBlockAtPosition(blockId, position, callback) {
        const contentArea = document.getElementById('tnpb-content');
        if (!contentArea) {
            if (callback) callback();
            return;
        }
        
        // Loading-Indikator
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'tnp-block-loading';
        loadingDiv.style.cssText = 'text-align: center; padding: 15px; background: #f8f9fa; border: 2px dashed #007cba; margin: 8px 0; border-radius: 4px;';
        loadingDiv.innerHTML = '<i class="fa fa-spinner fa-spin" style="color: #007cba;"></i> <span style="margin-left: 8px; color: #007cba; font-weight: 500;">Block wird geladen...</span>';
        
        // An Position einfügen
        if (position && position.element) {
            if (position.insertBefore) {
                position.element.parentNode.insertBefore(loadingDiv, position.element);
            } else {
                position.element.parentNode.insertBefore(loadingDiv, position.element.nextSibling);
            }
        } else {
            contentArea.appendChild(loadingDiv);
        }
        
        // AJAX-Request vorbereiten
        const ajaxUrl = window.ajaxurl || '/wp-admin/admin-ajax.php';
        const nonce = window.tnp_nonce || document.querySelector('input[name="_wpnonce"]')?.value || '';
        
        // Basis-Request-Daten
        let requestData = {
            action: 'tnpc_render',
            id: blockId,
            b: blockId,
            full: 1,
            context_type: window.tnp_context_type || 'composer',
            _wpnonce: nonce
        };
        
        // Versuche globale Optionen hinzuzufügen (falls vorhanden)
        try {
            if (typeof window.tnpc_add_global_options === 'function') {
                const dataArray = [];
                for (const key in requestData) {
                    dataArray.push({name: key, value: requestData[key]});
                }
                window.tnpc_add_global_options(dataArray);
                
                // Daten zurück in Objekt konvertieren
                requestData = {};
                dataArray.forEach(item => {
                    requestData[item.name] = item.value;
                });
            }
        } catch (globalOptionsError) {
            // Ignoriere Fehler bei globalen Optionen
        }
        
        // AJAX-Request senden
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: requestData,
            timeout: 15000,
            dataType: 'html',
            success: function(response) {
                try {
                    if (!response || response.trim() === '') {
                        throw new Error('Leere Response vom Server');
                    }
                    
                    const $newBlock = $(response);
                    
                    if ($newBlock.length === 0) {
                        throw new Error('Keine gültigen Block-Elemente in Response');
                    }
                    
                    $(loadingDiv).replaceWith($newBlock);
                    
                    // Block automatisch draggable machen
                    $newBlock.each(function() {
                        setupExistingBlockDragging(this);
                    });
                    
                    // Original-Handler hinzufügen (falls vorhanden)
                    try {
                        if (typeof $newBlock.add_delete === 'function') {
                            $newBlock.add_delete();
                        }
                        if (typeof $newBlock.add_block_edit === 'function') {
                            $newBlock.add_block_edit();
                        }
                        if (typeof $newBlock.add_block_clone === 'function') {
                            $newBlock.add_block_clone();
                        }
                    } catch (handlerError) {
                        // Ignoriere Handler-Fehler
                    }
                    
                    // Block-Edit automatisch öffnen
                    if ($newBlock.hasClass('tnpc-row-block')) {
                        const editButton = $newBlock.find('.tnpc-row-edit-block');
                        if (editButton.length) {
                            setTimeout(() => editButton.click(), 200);
                        }
                    }
                    
                    if (callback) callback();
                } catch (error) {
                    showBlockError(loadingDiv, 'Fehler beim Verarbeiten des Blocks: ' + error.message);
                    if (callback) callback();
                }
            },
            error: function(xhr, status, error) {
                showBlockError(loadingDiv, `AJAX Fehler: ${status} - ${error}`);
                if (callback) callback();
            }
        });
    }
    
    function showBlockError(loadingElement, errorMessage) {
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = 'padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0;';
        errorDiv.innerHTML = `<strong>⚠️ ${errorMessage}</strong><br><small>Versuchen Sie es erneut oder kontaktieren Sie den Support.</small>`;
        
        if (loadingElement && loadingElement.parentNode) {
            loadingElement.parentNode.replaceChild(errorDiv, loadingElement);
        }
    }
    
    // DROP-INDIKATOR
    function createDropIndicator() {
        if (!dropIndicator) {
            dropIndicator = document.createElement('div');
            dropIndicator.className = 'tnp-drop-indicator';
            dropIndicator.style.cssText = `
                position: absolute;
                background: rgba(0, 124, 186, 0.9);
                border: 2px solid #ffffff;
                border-radius: 4px;
                z-index: 1000;
                display: none;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                color: white;
                text-align: center;
                font-size: 14px;
                font-weight: bold;
                padding: 8px 16px;
                min-height: 10px;
                box-sizing: border-box;
                box-shadow: 0 2px 8px rgba(0, 124, 186, 0.3);
                pointer-events: none;
            `;
            dropIndicator.textContent = 'Block hier platzieren';
        }
        document.body.appendChild(dropIndicator);
    }
    
    function hideDropIndicator() {
        if (dropIndicator) {
            dropIndicator.style.display = 'none';
        }
    }
    
    function removeDropIndicator() {
        if (dropIndicator) {
            dropIndicator.style.display = 'none';
            if (dropIndicator.parentNode) {
                dropIndicator.parentNode.removeChild(dropIndicator);
            }
        }
    }
    
    function updateDropIndicator(position) {
        if (!dropIndicator || !position) return;
        
        dropIndicator.style.display = 'block';
        dropIndicator.style.left = position.x + 'px';
        dropIndicator.style.top = position.y + 'px';
        dropIndicator.style.width = position.width + 'px';
        
        dropIndicator.textContent = 'Block hier platzieren';
    }
    
    // DROP HERE BOX
    function updateDropHereBox() {
        const contentArea = document.getElementById('tnpb-content');
        if (contentArea && contentArea.querySelectorAll('.tnpc-row, .tnpc-row-block').length === 0) {
            const dropHereBox = document.createElement('div');
            dropHereBox.className = 'tnpc-drop-here';
            dropHereBox.style.cssText = `
                text-align: center;
                padding: 40px 20px;
                background: #f8f9fa;
                border: 2px dashed #007cba;
                border-radius: 8px;
                color: #007cba;
                font-size: 16px;
                font-weight: 500;
                margin: 20px 0;
                transition: all 0.3s ease;
            `;
            dropHereBox.innerHTML = `
                <i class="fa fa-arrow-down" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                Drag&Drop blocks here!
            `;
            contentArea.appendChild(dropHereBox);
        }
    }
    
    // KONTINUIERLICHE BEREINIGUNG
    function startContinuousCleanup() {
        if (cleanupInterval) {
            clearInterval(cleanupInterval);
        }
        
        cleanupInterval = setInterval(() => {
            const uiElements = document.querySelectorAll('[class*="ui-"]');
            if (uiElements.length > 0) {
                uiElements.forEach(el => {
                    const classes = el.className.split(' ').filter(cls => !cls.startsWith('ui-'));
                    el.className = classes.join(' ');
                });
            }
            
            // Entferne jQuery UI Event-Handler
            if (window.jQuery) {
                $('.tnpc-row, .tnpc-row-block').off('.ui-sortable');
                $('.tnpb-block-icon').off('.ui-draggable');
            }
        }, 1000);
    }
    
    // INITIALISIERUNG
    $(document).ready(function() {
        aggressiveCleanup();
        initCleanDragDrop();
        startContinuousCleanup();
        
        // Bei dynamischen Inhalten (z.B. neuen Blöcken)
        const observer = new MutationObserver(function(mutations) {
            let shouldReinit = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.classList && (
                                node.classList.contains('tnpc-row') || 
                                node.classList.contains('tnpc-row-block') ||
                                node.querySelector('.tnpc-row, .tnpc-row-block, .tnpb-block-icon')
                            )) {
                                shouldReinit = true;
                            }
                        }
                    });
                }
            });
            
            if (shouldReinit) {
                aggressiveCleanup();
                initCleanDragDrop();
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    
})(jQuery);
