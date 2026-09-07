/**
 * Rhaokar HexMap Standalone Engine - Hexcrawl Interativo sem Dependências Externas
 */

(function($) {
	'use strict';

	var mapData = null;
	var mappedHexes = {};
	var terrains = {};
	var matrix = {};

	var scale = 1;
	var panX = 0;
	var panY = 0;
	var isDragging = false;
	var startX = 0;
	var startY = 0;

	// Configuração do Tamanho dos Hexágonos (pointy-topped)
	var hexW = 56;
	var hexH = 64.66; // 56 * 2 / sqrt(3)

	function closeSubhexModal() {
		var modal = document.getElementById('rhaokar-subhex-modal');
		if (modal) {
			modal.classList.remove('rhaokar-open');
			document.body.style.overflow = '';
		}
	}
	window.rhaokarCloseSubhexModal = closeSubhexModal;

	function initRhaokarEngine() {
		var viewport = $('#rhaokar-hex-viewport');
		var canvas = $('#rhaokar-hex-canvas');
		if (!viewport.length || !canvas.length) return;

		if (window.rhaokarHexData) {
			mappedHexes = window.rhaokarHexData.mappedHexes || {};
			terrains    = window.rhaokarHexData.terrains || {};
			matrix      = window.rhaokarHexData.matrix || {};
		}

		if (window.rhaokarMacroMapData && window.rhaokarMacroMapData.hexes) {
			mapData = window.rhaokarMacroMapData;
		} else {
			var jsonSource = $('#rhaokar-map-json-data').text() || '{}';
			try {
				mapData = JSON.parse(jsonSource.trim());
			} catch (e) {
				console.error("Erro ao analisar dados do mapa Rhaokar:", e);
				mapData = { layout: "odd-r", hexes: {} };
			}
		}

		renderMacroMap(canvas, mapData.hexes || {});

		setupPanAndZoom(viewport, canvas);

		setupControls(viewport, canvas);
	}

	function renderMacroMap(canvas, hexesObj) {
		canvas.empty();

		var minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
		var fragment = document.createDocumentFragment();

		$.each(hexesObj, function(hexId, hex) {
			var q = parseInt(hex.q, 10) || 0;
			var r = Math.abs(parseInt(hex.r, 10)) || 0;
			var type = hex.type || 'planicie';

			// odd-r pointy-topped formula:
			var left = q * hexW + ((r % 2 !== 0) ? (hexW / 2) : 0);
			var top = r * (hexH * 0.75);

			if (left < minX) minX = left;
			if (top < minY) minY = top;
			if (left + hexW > maxX) maxX = left + hexW;
			if (top + hexH > maxY) maxY = top + hexH;

			var cleanId = hexId.toUpperCase();
			var isMapped = !!mappedHexes[cleanId];
			var mappedClass = isMapped ? ' rhaokar-mapped-hex' : '';

			var hexEl = document.createElement('div');
			hexEl.className = 'rhaokar-macro-hex ' + type + mappedClass;
			hexEl.style.left = left + 'px';
			hexEl.style.top = top + 'px';
			hexEl.setAttribute('data-hex-id', cleanId);

			var tooltipText = isMapped 
				? ('📜 Hexágono ' + cleanId + ' (MAPEADO - Clique para explorar)') 
				: ('Hexágono ' + cleanId + ' (Não explorado)');
			hexEl.setAttribute('title', tooltipText);
			hexEl.setAttribute('data-toggle', 'tooltip');

			var span = document.createElement('span');
			span.innerText = cleanId;
			hexEl.appendChild(span);

			if (isMapped) {
				var badge = document.createElement('div');
				badge.className = 'rhaokar-hex-mapped-badge';
				badge.innerHTML = '📜';
				hexEl.appendChild(badge);
			}

			fragment.appendChild(hexEl);
		});

		canvas[0].appendChild(fragment);

		var totalWidth = (maxX > 0) ? (maxX + 100) : 3200;
		var totalHeight = (maxY > 0) ? (maxY + 100) : 2800;
		canvas.css({ width: totalWidth + 'px', height: totalHeight + 'px' });

		// Centraliza e ajusta o zoom inicial para caber na tela
		var viewport = $('#rhaokar-hex-viewport');
		var vpW = viewport.width() || 1000;
		var vpH = viewport.height() || 600;

		scale = Math.min(vpW / totalWidth, vpH / totalHeight);
		scale = Math.max(0.35, Math.min(scale, 0.85));

		panX = (vpW - (totalWidth * scale)) / 2;
		panY = (vpH - (totalHeight * scale)) / 2;

		updateTransform(canvas[0]);

		canvas.off('click', '.rhaokar-macro-hex').on('click', '.rhaokar-macro-hex', function(e) {
			e.preventDefault();
			var id = $(this).attr('data-hex-id');
			if (!id) return;

			if (mappedHexes[id]) {
				openRhaokarSubhexModal(id, mappedHexes[id]);
			} else {
				showUnexploredNotice(id);
			}
		});

		if ($.fn.tooltip) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	}

	function showUnexploredNotice(hexId) {
		$('#rhaokar-modal-hex-title').html('<i class="dashicons dashicons-location"></i> Hexágono ' + hexId + ' (Não Explorado)');
		$('#rhaokar-subtile-grid-container').html('<div class="p-4 text-center text-muted"><p style="font-size:2rem; margin-bottom:10px;">🧭</p><p>Esta região ainda não foi catalogada pelos exploradores e aventureiros de Rhaokar.</p><p class="small text-warning">Você pode mapear este hexágono no Painel do WordPress > Hexcrawl Rhaokar > Mapear Novo Hex!</p></div>');
		$('#rhaokar-subtile-detail-title').html('🔍 Região Inexplorada');
		$('#rhaokar-subtile-detail-content').html('<em class="text-muted d-block text-center mt-4">Nenhum detalhe cadastrado para esta região.</em>');

		var modal = document.getElementById('rhaokar-subhex-modal');
		if (modal) {
			modal.classList.add('rhaokar-open');
			document.body.style.overflow = 'hidden';
		}
	}

	function updateTransform(canvas) {
		canvas.style.transform = 'translate(' + panX + 'px, ' + panY + 'px) scale(' + scale + ')';
	}

	function setupPanAndZoom(viewport, canvas) {
		var rawCanvas = canvas[0];
		var rawViewport = viewport[0];

		viewport.on('mousedown touchstart', function(e) {
			if (e.type === 'mousedown' && e.which !== 1) return;
			isDragging = true;
			viewport.addClass('is-dragging');

			var pageX = e.pageX || (e.originalEvent.touches && e.originalEvent.touches[0].pageX);
			var pageY = e.pageY || (e.originalEvent.touches && e.originalEvent.touches[0].pageY);

			startX = pageX - panX;
			startY = pageY - panY;
		});

		$(document).on('mousemove touchmove', function(e) {
			if (!isDragging) return;
			var pageX = e.pageX || (e.originalEvent.touches && e.originalEvent.touches[0].pageX);
			var pageY = e.pageY || (e.originalEvent.touches && e.originalEvent.touches[0].pageY);

			panX = pageX - startX;
			panY = pageY - startY;
			updateTransform(rawCanvas);
		});

		$(document).on('mouseup touchend touchcancel', function() {
			if (isDragging) {
				isDragging = false;
				viewport.removeClass('is-dragging');
			}
		});

		rawViewport.addEventListener('wheel', function(e) {
			e.preventDefault();
			var zoomFactor = 1.1;
			var oldScale = scale;

			if (e.deltaY < 0) {
				scale = Math.min(scale * zoomFactor, 2.5);
			} else {
				scale = Math.max(scale / zoomFactor, 0.35);
			}

			var rect = rawViewport.getBoundingClientRect();
			var mouseX = e.clientX - rect.left;
			var mouseY = e.clientY - rect.top;

			panX = mouseX - (mouseX - panX) * (scale / oldScale);
			panY = mouseY - (mouseY - panY) * (scale / oldScale);

			updateTransform(rawCanvas);
		}, { passive: false });
	}

	function setupControls(viewport, canvas) {
		var rawCanvas = canvas[0];

		$('#rhaokar-zoom-in').on('click', function() {
			scale = Math.min(scale * 1.25, 2.5);
			updateTransform(rawCanvas);
		});

		$('#rhaokar-zoom-out').on('click', function() {
			scale = Math.max(scale / 1.25, 0.35);
			updateTransform(rawCanvas);
		});

		$('#rhaokar-zoom-reset').on('click', function() {
			scale = 0.85;
			panX = 20;
			panY = 20;
			updateTransform(rawCanvas);
		});
	}

	function openRhaokarSubhexModal(hexId, hexInfo) {
		$('#rhaokar-modal-hex-title').html('<i class="dashicons dashicons-location"></i> Hexágono Mapeado: ' + hexId);

		var tilesData = hexInfo.tiles || {};
		var gridHtml = '';

		$.each(matrix, function(rowName, tileArray) {
			gridHtml += '<div class="subtile-grid-row">';
			$.each(tileArray, function(idx, tileCode) {
				var tData = tilesData[tileCode] || {};
				var typeClass = tData.type || 'planicie';
				var typeLabel = terrains[typeClass] || typeClass;

				gridHtml += '<div class="subtile-hex-item ' + typeClass + '" data-tile-code="' + tileCode + '" title="' + tileCode + ': ' + typeLabel + '"><span>' + tileCode + '</span></div>';
			});
			gridHtml += '</div>';
		});

		$('#rhaokar-subtile-grid-container').html(gridHtml);

		$('#rhaokar-subtile-detail-title').html('🔍 Selecione um Sub-tile');
		$('#rhaokar-subtile-detail-content').html('<em class="text-muted d-block text-center mt-4">Clique em qualquer um dos 37 sub-tiles ao lado para ver o terreno, locais, NPCs e rumores.</em>');

		$('#rhaokar-subtile-grid-container').off('click', '.subtile-hex-item').on('click', '.subtile-hex-item', function() {
			$('.subtile-hex-item').removeClass('selected-subtile');
			$(this).addClass('selected-subtile');

			var code = $(this).attr('data-tile-code');
			var tData = tilesData[code] || {};
			renderSubtileDetail(code, tData);
		});

		var modal = document.getElementById('rhaokar-subhex-modal');
		if (modal) {
			modal.classList.add('rhaokar-open');
			document.body.style.overflow = 'hidden';
		}
	}

	function renderSubtileDetail(code, tData) {
		var typeClass = tData.type || 'planicie';
		var typeName  = terrains[typeClass] || typeClass;
		var loc       = tData.localizacao || '— Não especificada';

		$('#rhaokar-subtile-detail-title').html('📍 Sub-tile ' + code + ' (' + typeName + ')');

		var html = '';
		html += '<div class="mb-2 p-2 rounded" style="background:#191c21; border-left:3px solid #ffd700;">';
		html += '<strong class="text-warning d-block">Localização:</strong>';
		html += '<span>' + loc + '</span>';
		html += '</div>';

		if (tData.npcs) {
			html += '<div class="mb-2 p-2 rounded" style="background:#191c21; border-left:3px solid #17a2b8;">';
			html += '<strong class="text-info d-block">👤 NPCs Notáveis:</strong>';
			html += '<span style="white-space:pre-wrap;">' + tData.npcs + '</span>';
			html += '</div>';
		}

		if (tData.monstros) {
			html += '<div class="mb-2 p-2 rounded" style="background:#191c21; border-left:3px solid #dc3545;">';
			html += '<strong class="text-danger d-block">🐉 Monstros & Perigos:</strong>';
			html += '<span style="white-space:pre-wrap;">' + tData.monstros + '</span>';
			html += '</div>';
		}

		if (tData.rumores) {
			html += '<div class="mb-2 p-2 rounded" style="background:#191c21; border-left:3px solid #ffc107;">';
			html += '<strong class="text-warning d-block">📜 Rumores & Pistas:</strong>';
			html += '<span style="white-space:pre-wrap;">' + tData.rumores + '</span>';
			html += '</div>';
		}

		if (tData.ruinas) {
			html += '<div class="mb-2 p-2 rounded" style="background:#191c21; border-left:3px solid #28a745;">';
			html += '<strong class="text-success d-block">🏰 Ruínas & Pontos de Interesse:</strong>';
			html += '<span style="white-space:pre-wrap;">' + tData.ruinas + '</span>';
			html += '</div>';
		}

		if (!tData.npcs && !tData.monstros && !tData.rumores && !tData.ruinas) {
			html += '<em class="text-muted d-block mt-3">Nenhum detalhe adicional cadastrado para este sub-tile.</em>';
		}

		$('#rhaokar-subtile-detail-content').html(html);
	}

	$(document).ready(function() {
		initRhaokarEngine();
	});

	window.initRhaokarHexMapEngine = initRhaokarEngine;

})(jQuery);
