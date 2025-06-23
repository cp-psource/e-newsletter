/**
 * Newsletter jQuery UI ELIMINATOR - Production Version
 * 
 * ELIMI      // S       fu                }
        
        // Block-Icons mit nativer HTML5 API einrichten    
        // Block-Icons mit nativer HTML5 API einrichtenisInitialized) {
            return;
        }
        
        // Block-Icons mit nativer HTML5 API einrichtennitCleanDragDrop() {
        if (isInitialized) {
            return;
        }
        
        // Block-Icons mit nativer HTML5 API einrichtenisInitialized) {
            return;
        }
        
        // Block-Icons mit nativer HTML5 API einrichten: CLEAN DRAG&DROP SYSTEM (HTML5 NATIVE)
    function initCleanDragDrop() {
        if (isInitialized) {
            return;
        }
        
        // Block-Icons mit nativer HTML5 API einrichten initCleanDragDrop() {
        if (isInitialized) {
            return;
        }Query UI komplett aus dem Newsletter-Plugin
 * Ersetzt durch natives HTML5 Drag&Drop System
 * 
 * Version: 3.0.0 (Production)
 * Datum: 2024-12-19
 */

(function($) {
    'use strict';
    
    // GLOBALE VARIABLEN
    let isInitialized = false;
    let blockDropInProgress = false;
    let cleanupInterval = null;
    
    // GLOBALE KOMPATIBILITÄTSVARIABLEN FÜR ANDERE SCRIPTS
    window.tnp_nonce = window.tnp_nonce || newsletter_admin_vars.nonce || '';
    window.ajaxurl = window.ajaxurl || newsletter_admin_vars.ajax_url || '';
    
    // SCHRITT 1: SOFORTIGES BLOCKIEREN ALLER JQUERY UI FUNKTIONEN
    function blockAllJQueryUI() {
        // Alle jQuery UI Funktionen mit No-Op überschreiben
        const jqueryUIFunctions = [
            'draggable', 'sortable', 'droppable', 'resizable', 
            'accordion', 'autocomplete', 'button', 'datepicker',
            'dialog', 'menu', 'progressbar', 'selectmenu',
            'slider', 'spinner', 'tabs', 'tooltip'
        ];
        
        jqueryUIFunctions.forEach(func => {
            $.fn[func] = function(options) {
                return this; // Chaining beibehalten
            };
        });
        
        // jQuery UI Widget Factory blockieren
        if ($.widget) {
            $.widget = function() {
                return {};
            };
        }
        
        // Global jQuery UI blockieren (falls andere Plugins es laden)
        if (window.jQuery && window.jQuery.ui) {
            window.jQuery.ui = {
                version: "BLOCKED",
                mouse: function() { return {}; },
                widget: function() { return {}; }
            };
        }
    }
    
    // SCHRITT 2: AGGRESSIVE BEREINIGUNG ALLER UI-RESTE
    function aggressiveCleanup() {
        // Alle UI-Klassen entfernen
        $('[class*="ui-"]').each(function() {
            const element = this;
            const classes = element.className.split(' ').filter(cls => !cls.startsWith('ui-'));
            element.className = classes.join(' ');
        });
        
        // Alle jQuery UI Data-Attribute entfernen
        $('[data-ui-widget], [data-ui-draggable], [data-ui-sortable]').removeData();
        
        // Event-Handler von potentiell problematischen Elementen entfernen
        $('.tnpb-block-icon, #tnpb-content, .tnpc-row-block').off('mousedown mouseup mousemove dragstart dragend');
    }
    
    // SCHRITT 3: CLEAN DRAG&DROP SYSTEM (HTML5 NATIVE)
    function initCleanDragDrop() {
        if (isInitialized) {
            // System bereits initialisiert
            return;
        }
        
        console.log('� Initialisiere CLEAN HTML5 Drag&Drop System...');
        
        // Block-Icons mit nativer HTML5 API einrichten
        document.querySelectorAll('.tnpb-block-icon').forEach(icon => {
            // Entferne alle vorherigen Event-Handler
            icon.removeAttribute('draggable');
            
            // Setze draggable neu
            icon.setAttribute('draggable', 'true');
            icon.style.cursor = 'move';
            
            // Clean Event-Handler hinzufügen
            icon.addEventListener('dragstart', handleDragStart, false);
            icon.addEventListener('dragend', handleDragEnd, false);
        });
        
        // Content-Bereich als saubere Drop-Zone
        const contentArea = document.getElementById('tnpb-content');
        if (contentArea) {
            // Alle vorherigen Event-Handler entfernen
            contentArea.removeEventListener('dragover', handleDragOver);
            contentArea.removeEventListener('drop', handleDrop);
            
            // Clean Event-Handler hinzufügen
            contentArea.addEventListener('dragover', handleDragOver, false);
            contentArea.addEventListener('drop', handleDrop, false);
            contentArea.addEventListener('dragleave', handleDragLeave, false);
        }
        
        isInitialized = true;
    }
    
    // SCHRITT 4: ERWEITERTE EVENT-HANDLER MIT POSITIONIERUNG
    let draggedBlockId = null;
    let draggedElement = null;
    let dropIndicator = null;
    let currentDropTarget = null;
    
    function handleDragStart(e) {
        if (blockDropInProgress) {
            e.preventDefault();
            return false;
        }
        
        draggedBlockId = this.dataset.id;
        draggedElement = this;
        
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', draggedBlockId);
        
        // Visual Feedback
        this.style.opacity = '0.5';
        
        // Drop-Indikator erstellen
        createDropIndicator();
        
        // "Drop Here" Box hinzufügen wenn Content-Bereich leer ist
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
    
    function handleDragEnd(e) {
        // Visual Feedback zurücksetzen
        if (draggedElement) {
            draggedElement.style.opacity = '1';
        }
        
        // Drop-Indikator entfernen
        removeDropIndicator();
        
        // "Drop Here" Box entfernen
        document.querySelectorAll('.tnpc-drop-here').forEach(el => el.remove());
        
        // Cleanup
        draggedBlockId = null;
        draggedElement = null;
        currentDropTarget = null;
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        // Position für Drop-Indikator berechnen
        const dropPosition = calculateDropPosition(e);
        updateDropIndicator(dropPosition);
        
        // Erweiterte Drop-Zone: auch Bereiche außerhalb des Content-Bereichs akzeptieren
        const contentArea = document.getElementById('tnpb-content');
        if (contentArea) {
            const rect = contentArea.getBoundingClientRect();
            const tolerance = 50; // 50px Toleranz außerhalb des Bereichs
            
            const isInExtendedZone = (
                e.clientX >= (rect.left - tolerance) &&
                e.clientX <= (rect.right + tolerance) &&
                e.clientY >= (rect.top - tolerance) &&
                e.clientY <= (rect.bottom + tolerance)
            );
            
            if (isInExtendedZone) {
                e.dataTransfer.dropEffect = 'move';
            }
        }
    }
    
    function handleDragLeave(e) {
        // Nur verstecken wenn wir wirklich die Drop-Zone verlassen
        const contentArea = document.getElementById('tnpb-content');
        if (contentArea && !contentArea.contains(e.relatedTarget)) {
            hideDropIndicator();
        }
    }
    
    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Drop-Indikator verstecken
        hideDropIndicator();
        
        if (blockDropInProgress) {
            return false;
        }
        
        const blockId = e.dataTransfer.getData('text/plain') || draggedBlockId;
        
        if (!blockId) {
            return false;
        }
        
        blockDropInProgress = true;
        const position = calculateDropPosition(e);
        
        // Block sofort rendern
        renderBlockAtPosition(blockId, position, function() {
            blockDropInProgress = false;
            draggedBlockId = null;
        });
        
        return true;
    }
    
    // DROP-INDIKATOR UND EINFACHE INFOBOX FUNKTIONEN
    function createDropIndicator() {
        if (!dropIndicator) {
            dropIndicator = document.createElement('div');
            dropIndicator.className = 'tnp-drop-indicator';
            dropIndicator.style.cssText = `
                position: absolute;
                background: #f8f9fa;
                border: 2px dashed #007cba;
                border-radius: 4px;
                z-index: 1000;
                display: none;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                color: #007cba;
                text-align: center;
                font-size: 14px;
                padding: 12px;
                min-height: 20px;
                box-sizing: border-box;
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
        
        // Text vereinfachen - immer gleich
        dropIndicator.textContent = 'Block hier platzieren';
    }
    
    // POSITION BERECHNUNG - BENUTZERFREUNDLICH MIT TOLERANZ
    function calculateDropPosition(e) {
        const contentArea = document.getElementById('tnpb-content');
        if (!contentArea) return null;
        
        const rect = contentArea.getBoundingClientRect();
        const existingBlocks = contentArea.querySelectorAll('.tnpc-row, .tnpc-row-block');
        
        let insertPosition = {
            element: null,
            insertBefore: false,
            x: rect.left,
            y: e.clientY,
            width: rect.width - 20
        };
        
        // Vor dem ersten Block einfügen (obere 40% des ersten Blocks)
        if (existingBlocks.length > 0) {
            const firstBlock = existingBlocks[0];
            const firstRect = firstBlock.getBoundingClientRect();
            const toleranceZone = firstRect.height * 0.4;
            
            if (e.clientY < (firstRect.top + toleranceZone)) {
                insertPosition.element = firstBlock;
                insertPosition.insertBefore = true;
                insertPosition.y = firstRect.top - 10;
                return insertPosition;
            }
        }
        
        // Zwischen Blöcken finden (30% Toleranz-Zonen)
        for (let i = 0; i < existingBlocks.length; i++) {
            const block = existingBlocks[i];
            const blockRect = block.getBoundingClientRect();
            const blockHeight = blockRect.height;
            const toleranceZone = blockHeight * 0.3;
            
            // Oberer Bereich: einfügen VOR diesem Block
            if (e.clientY >= (blockRect.top - toleranceZone) && 
                e.clientY <= (blockRect.top + toleranceZone)) {
                insertPosition.element = block;
                insertPosition.insertBefore = true;
                insertPosition.y = blockRect.top - 10;
                break;
            }
            
            // Unterer Bereich: einfügen NACH diesem Block
            if (e.clientY >= (blockRect.bottom - toleranceZone) && 
                e.clientY <= (blockRect.bottom + toleranceZone)) {
                
                const nextBlock = existingBlocks[i + 1];
                if (nextBlock) {
                    const nextRect = nextBlock.getBoundingClientRect();
                    if (e.clientY > (nextRect.top - toleranceZone)) {
                        continue;
                    }
                }
                
                insertPosition.element = block;
                insertPosition.insertBefore = false;
                insertPosition.y = blockRect.bottom + 10;
                break;
            }
        }
        
        // Am Ende einfügen wenn keine Position gefunden
        if (!insertPosition.element && existingBlocks.length > 0) {
            const lastBlock = existingBlocks[existingBlocks.length - 1];
            const lastRect = lastBlock.getBoundingClientRect();
            insertPosition.y = lastRect.bottom + 10;
        } else if (existingBlocks.length === 0) {
            insertPosition.y = rect.top + 20;
        }
        
        return insertPosition;
    }
    
    // POSITIONS-BASIERTES BLOCK-RENDERING
    function renderBlockAtPosition(blockId, position, callback) {
        const contentArea = document.getElementById('tnpb-content');
        if (!contentArea) {
            if (callback) callback();
            return;
        }
        
        // Loading-Indikator an spezifischer Position
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'tnp-block-loading';
        loadingDiv.style.cssText = 'text-align: center; padding: 15px; background: #f8f9fa; border: 2px dashed #007cba; margin: 8px 0; border-radius: 4px;';
        loadingDiv.innerHTML = '<i class="fa fa-spinner fa-spin" style="color: #007cba;"></i> <span style="margin-left: 8px; color: #007cba; font-weight: 500;">Block wird geladen...</span>';
        
        // An korrekter Position einfügen
        if (position && position.element) {
            if (position.insertBefore) {
                position.element.parentNode.insertBefore(loadingDiv, position.element);
            } else {
                position.element.parentNode.insertBefore(loadingDiv, position.element.nextSibling);
            }
        } else {
            contentArea.appendChild(loadingDiv);
        }
        
        // AJAX-Request mit Fallback-Prüfungen
        const ajaxUrl = window.ajaxurl || '/wp-admin/admin-ajax.php';
        const nonce = window.tnp_nonce || '';
        
        if (!ajaxUrl) {
            showBlockError(loadingDiv, 'AJAX URL nicht verfügbar');
            if (callback) callback();
            return;
        }
        
        const requestData = {
            action: 'tnpc_render',
            id: blockId,
            b: blockId,
            full: 1,
            context_type: window.tnp_context_type || '',
            _wpnonce: nonce
        };
        
        // Debug: Zeige was gesendet wird
        if (window.location.search.includes('debug=1')) {
            console.log('Block-Rendering:', blockId, requestData);
        }
        
        // Globale Optionen hinzufügen (wie im Original)
        if (typeof window.tnpc_add_global_options === 'function') {
            const dataArray = [];
            for (const key in requestData) {
                dataArray.push({name: key, value: requestData[key]});
            }
            window.tnpc_add_global_options(dataArray);
            
            // Daten zurück in Object konvertieren
            const updatedData = {};
            dataArray.forEach(item => {
                updatedData[item.name] = item.value;
            });
            Object.assign(requestData, updatedData);
        }
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: requestData,
            timeout: 10000,
            dataType: 'html',
            success: function(response) {
                if (window.location.search.includes('debug=1')) {
                    console.log('Block-Response:', response);
                }
                try {
                    const $newBlock = $(response);
                    $(loadingDiv).replaceWith($newBlock);
                    
                    // Original-Handler hinzufügen (wie im composer.js)
                    if (typeof $newBlock.add_delete === 'function') {
                        $newBlock.add_delete();
                    }
                    if (typeof $newBlock.add_block_edit === 'function') {
                        $newBlock.add_block_edit();
                    }
                    if (typeof $newBlock.add_block_clone === 'function') {
                        $newBlock.add_block_clone();
                    }
                    
                    // Block-Edit automatisch öffnen (wie im Original)
                    if ($newBlock.hasClass('tnpc-row-block')) {
                        const editButton = $newBlock.find('.tnpc-row-edit-block');
                        if (editButton.length) {
                            editButton.click();
                        }
                    }
                    
                    setupCleanBlockHandlers($newBlock);
                    makeBlockSortable($newBlock);
                    if (callback) callback();
                } catch (error) {
                    if (window.location.search.includes('debug=1')) {
                        console.error('Block-Processing Error:', error);
                    }
                    showBlockError(loadingDiv, 'Fehler beim Verarbeiten des Blocks');
                    if (callback) callback();
                }
            },
            error: function(xhr, status, error) {
                if (window.location.search.includes('debug=1')) {
                    console.error('AJAX Error:', {xhr, status, error, url: ajaxUrl, data: requestData});
                }
                showBlockError(loadingDiv, `AJAX Fehler: ${status}`);
                if (callback) callback();
            }
        });
    }
    
    // BLOCK SORTIERBAR MACHEN (HTML5 Native)
    function makeBlockSortable($block) {
        const blockElement = $block[0];
        if (!blockElement) return;
        
        blockElement.setAttribute('draggable', 'true');
        blockElement.style.cursor = 'move';
        
        blockElement.addEventListener('dragstart', function(e) {
            if (blockDropInProgress) {
                e.preventDefault();
                return false;
            }
            
            this.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.outerHTML);
            e.dataTransfer.setData('application/x-tnp-block-sort', 'true');
            this.classList.add('tnp-block-being-moved');
            createDropIndicator();
        });
        
        blockElement.addEventListener('dragend', function(e) {
            this.style.opacity = '1';
            this.classList.remove('tnp-block-being-moved');
            removeDropIndicator();
        });
    }
    
    // CONTENT-BEREICH FÜR BLOCK-SORTIERUNG
    function enhanceContentAreaForSorting() {
        const contentArea = document.getElementById('tnpb-content');
        if (!contentArea) return;
        
        contentArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            
            const isBlockSort = e.dataTransfer.types.includes('application/x-tnp-block-sort');
            if (isBlockSort) {
                e.dataTransfer.dropEffect = 'move';
                const dropPosition = calculateDropPosition(e);
                updateDropIndicator(dropPosition);
            }
        });
        
        contentArea.addEventListener('drop', function(e) {
            const isBlockSort = e.dataTransfer.types.includes('application/x-tnp-block-sort');
            if (!isBlockSort) return;
            
            e.preventDefault();
            e.stopPropagation();
            
            const movingBlock = document.querySelector('.tnp-block-being-moved');
            if (!movingBlock) return;
            
            const dropPosition = calculateDropPosition(e);
            
            if (dropPosition && dropPosition.element) {
                if (dropPosition.insertBefore) {
                    dropPosition.element.parentNode.insertBefore(movingBlock, dropPosition.element);
                } else {
                    dropPosition.element.parentNode.insertBefore(movingBlock, dropPosition.element.nextSibling);
                }
            } else {
                this.appendChild(movingBlock);
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
    
    // ERWEITERTE BLOCK EVENT-HANDLER SETUP
    function setupCleanBlockHandlers($block) {
        try {
            if (typeof $block.add_delete === 'function') {
                $block.add_delete();
            }
            
            if (typeof $block.add_block_edit === 'function') {
                $block.add_block_edit();
            }
            
            if (typeof $block.add_block_clone === 'function') {
                $block.add_block_clone();
            }
            
            makeBlockSortable($block);
            
            if ($block.hasClass('tnpc-row-block')) {
                setTimeout(() => {
                    const $editButton = $block.find('.tnpc-row-edit-block');
                    if ($editButton.length > 0) {
                        $editButton.trigger('click');
                    }
                }, 200);
            }
        } catch (error) {
            // Stille Fehlerbehandlung
        }
    }
    
    // ERWEITERTE INITIALISIERUNG
    function initCleanDragDrop() {
        if (isInitialized) return;
        
        const blockIcons = document.querySelectorAll('.tnpb-block-icon');
        
        blockIcons.forEach(icon => {
            icon.removeAttribute('draggable');
            icon.setAttribute('draggable', 'true');
            icon.style.cursor = 'move';
            icon.addEventListener('dragstart', handleDragStart, false);
            icon.addEventListener('dragend', handleDragEnd, false);
        });
        
        const contentArea = document.getElementById('tnpb-content');
        if (contentArea) {
            contentArea.removeEventListener('dragover', handleDragOver);
            contentArea.removeEventListener('drop', handleDrop);
            contentArea.addEventListener('dragover', handleDragOver, false);
            contentArea.addEventListener('drop', handleDrop, false);
        }
        
        enhanceContentAreaForSorting();
        
        const existingBlocks = document.querySelectorAll('#tnpb-content .tnpc-row, #tnpb-content .tnpc-row-block');
        existingBlocks.forEach(block => {
            makeBlockSortable($(block));
        });
        
        isInitialized = true;
    }
    
    // SCHRITT 8: KONTINUIERLICHE BEREINIGUNG UND ÜBERWACHUNG
    function startContinuousCleanup() {
        // Stoppe vorherigen Cleanup
        if (cleanupInterval) {
            clearInterval(cleanupInterval);
        }
        
        cleanupInterval = setInterval(() => {
            // Neue UI-Klassen entfernen
            const uiElements = document.querySelectorAll('[class*="ui-"]');
            if (uiElements.length > 0) {
                uiElements.forEach(el => {
                    const classes = el.className.split(' ').filter(cls => !cls.startsWith('ui-'));
                    el.className = classes.join(' ');
                });
            }
        }, 1000);
    }
    
    // INITIALIZATION SEQUENCE
    function initializeEliminator() {
        blockAllJQueryUI();
        
        $(document).ready(function() {
            setTimeout(() => {
                aggressiveCleanup();
                
                setTimeout(() => {
                    initCleanDragDrop();
                    
                    setTimeout(() => {
                        if (!isInitialized) {
                            initCleanDragDrop();
                        }
                        startContinuousCleanup();
                    }, 500);
                }, 100);
            }, 50);
        });
    }
    
    // SOFORTIGE INITIALISIERUNG
    blockAllJQueryUI();
    initializeEliminator();
    
})(jQuery);
