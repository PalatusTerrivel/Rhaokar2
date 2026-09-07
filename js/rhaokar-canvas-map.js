/**
 * Rhaokar HTML5 Canvas Hexcrawl Engine
 */

(function($) {
	'use strict';

	var canvas = null;
	var ctx = null;

	var viewMode = 'global'; // 'global' ou 'subhex'
	var selectedMacroHexId = null;

	var mapData = null;
	var mappedHexes = {};
	var terrains = {};
	var matrix = {};

	// Pan & Zoom
	var scale = 0.45;
	var panX = 40;
	var panY = 40;
	var isDragging = false;
	var startX = 0;
	var startY = 0;

	// Dimensões do Hexágono Macro (Pointy-topped)
	var hexRadius = 32;
	var hexW = hexRadius * Math.sqrt(3); // ~55.42
	var hexH = hexRadius * 2;            // 64

	// Imagens pré-carregadas
	var loadedImages = {};
	var terrainImageUrls = {
		'agua_profunda': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-AguaSemFundo.gif',
		'agua_rasa': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-AguaSemFundo.gif',
		'bosque': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Bosque.gif',
		'bosque_nevado': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-BosqueNevado.gif',
		'floresta': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Floresta.gif',
		'floresta_nevada': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-FlorestaNevada.gif',
		'montanha': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Montanha.gif',
		'montanha_nevada': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-MontanhaNevada.gif',
		'planicie': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Planicie.gif',
		'planicie_nevada': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-PlanicieNevada.gif',
		'deserto': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Deserto.gif',
		'selva': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Selva.gif',
		'montanha_com_floresta': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-MontanhaFloresta.gif',
		'montanha_com_floresta_nevada': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-MontanhaFlorestaNevada.gif',
		'montanha_com_selva': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-MontanhaSelva.gif',
		'pantano': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Pantano.gif',
		'vulcao': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-Vulcao.gif',
		'mana_wastes': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Tile-ErmosDeMana.gif',
		'aetheomir': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Aetheomir.png',
		'catarna': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Catarna.png',
		'cordileiras_vermelhas': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Cordilheiras_Vermelhas.png',
		'demios': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Demios.png',
		'galorfindil': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Galorfindil.png',
		'gliorith': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Gliorith.png',
		'huntston': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Huntston.png',
		'kaareth': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Kaareth.png',
		'koba': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Koba.png',
		'mao_do_aqueronte': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Mao_do_Aqueronte.png',
		'montanha_de_ferro': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Montanha_de_Ferro.png',
		'montanha_de_mithral': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Montanha_de_Mithral.png',
		'orcshardhaven': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Orcshardhaven.png',
		'shogunato': 'https://rhaokar.com.br/wp-content/uploads/2026/05/Shogunato.png'
	};

	var terrainColors = {
		'agua_profunda': '#0032bb',
		'agua_rasa': '#007fd4',
		'bosque': '#56cf70',
		'bosque_nevado': '#6fb174',
		'floresta': '#2e5f3d',
		'floresta_nevada': '#467a4e',
		'montanha': '#89c7f0',
		'montanha_nevada': '#a3d8f5',
		'planicie': '#79f093',
		'planicie_nevada': '#a9caab',
		'deserto': '#f7ffd4',
		'selva': '#069e4a',
		'montanha_com_floresta': '#689d81',
		'montanha_com_floresta_nevada': '#7da0a8',
		'montanha_com_selva': '#508960',
		'pantano': '#4c7452',
		'vulcao': '#b53726',
		'mana_wastes': '#434a4e'
	};

	function preloadImages() {
		$.each(terrainImageUrls, function(key, url) {
			var img = new Image();
			img.onload = function() {
				loadedImages[key] = img;
				requestRedraw();
			};
			img.src = url;
		});
	}

	function drawHexagonPath(context, cx, cy, radius) {
		context.beginPath();
		for (var i = 0; i < 6; i++) {
			var angle = (Math.PI / 3) * i - (Math.PI / 2); // Pointy-topped
			var hx = cx + radius * Math.cos(angle);
			var hy = cy + radius * Math.sin(angle);
			if (i === 0) context.moveTo(hx, hy);
			else context.lineTo(hx, hy);
		}
		context.closePath();
	}

	function renderMap() {
		if (!ctx || !canvas) return;

		// Limpa o Canvas
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		ctx.fillStyle = '#0a0d12';
		ctx.fillRect(0, 0, canvas.width, canvas.height);

		if (viewMode === 'global') {
			renderGlobalMap();
		}
	}

	function renderGlobalMap() {
		var hexesObj = (mapData && mapData.hexes) ? mapData.hexes : {};

		ctx.save();
		ctx.translate(panX, panY);
		ctx.scale(scale, scale);

		$.each(hexesObj, function(hexId, hex) {
			var q = parseInt(hex.q, 10) || 0;
			var r = Math.abs(parseInt(hex.r, 10)) || 0;
			var type = hex.type || 'planicie';

			// Fórmulas geométricas do Hexágono Pointy-topped:
			var cx = q * hexW + ((r % 2 !== 0) ? (hexW / 2) : 0) + hexW;
			var cy = r * (hexH * 0.75) + hexRadius + 20;

			var cleanId = hexId.toUpperCase();
			var isMapped = !!mappedHexes[cleanId];

			// 1. Desenha o preenchimento (Textura ou Cor)
			drawHexagonPath(ctx, cx, cy, hexRadius - 0.5);

			ctx.save();
			ctx.clip();

			if (loadedImages[type]) {
				var img = loadedImages[type];
				ctx.drawImage(img, cx - hexRadius, cy - hexRadius, hexRadius * 2, hexRadius * 2);
			} else {
				ctx.fillStyle = terrainColors[type] || '#2a3b4c';
				ctx.fill();
			}
			ctx.restore();

			// 2. Desenha o Contorno do Hexágono
			drawHexagonPath(ctx, cx, cy, hexRadius);
			if (isMapped) {
				ctx.strokeStyle = '#ffd700';
				ctx.lineWidth = 2.5;
				ctx.stroke();

				ctx.fillStyle = 'rgba(255, 215, 0, 0.18)';
				ctx.fill();
			} else {
				ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
				ctx.lineWidth = 1;
				ctx.stroke();
			}

			// 3. Desenha o Texto da Coordenada (ex: U7)
			if (scale > 0.35) {
				ctx.fillStyle = '#ffffff';
				ctx.font = 'bold ' + Math.max(9, Math.round(11 / scale)) + 'px sans-serif';
				ctx.textAlign = 'center';
				ctx.textBaseline = 'middle';
				ctx.shadowColor = '#000000';
				ctx.shadowBlur = 4;
				ctx.fillText(cleanId, cx, cy);
				ctx.shadowBlur = 0;
			}
		});

		ctx.restore();
	}

	function getMousePos(e) {
		var rect = canvas.getBoundingClientRect();
		var clientX = e.clientX || (e.touches && e.touches[0].clientX);
		var clientY = e.clientY || (e.touches && e.touches[0].clientY);
		return {
			x: clientX - rect.left,
			y: clientY - rect.top
		};
	}

	function handleCanvasClick(e) {
		if (viewMode !== 'global') return;

		var pos = getMousePos(e);
		var mouseX = (pos.x - panX) / scale;
		var mouseY = (pos.y - panY) / scale;

		var hexesObj = (mapData && mapData.hexes) ? mapData.hexes : {};
		var clickedHexId = null;

		$.each(hexesObj, function(hexId, hex) {
			var q = parseInt(hex.q, 10) || 0;
			var r = Math.abs(parseInt(hex.r, 10)) || 0;

			var cx = q * hexW + ((r % 2 !== 0) ? (hexW / 2) : 0) + hexW;
			var cy = r * (hexH * 0.75) + hexRadius + 20;

			var dist = Math.hypot(mouseX - cx, mouseY - cy);
			if (dist <= hexRadius) {
				clickedHexId = hexId.toUpperCase();
				return false; // Break
			}
		});

		if (clickedHexId) {
			if (mappedHexes[clickedHexId]) {
				openSubhexModal(clickedHexId, mappedHexes[clickedHexId]);
			} else {
				showUnexploredNotice(clickedHexId);
			}
		}
	}

	function openSubhexModal(hexId, hexInfo) {
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

	function showUnexploredNotice(hexId) {
		$('#rhaokar-modal-hex-title').html('<i class="dashicons dashicons-location"></i> Hexágono ' + hexId + ' (Não Explorado)');
		$('#rhaokar-subtile-grid-container').html('<div class="p-4 text-center text-muted"><p style="font-size:2.5rem; margin-bottom:10px;">🧭</p><p class="text-light">Esta região ainda não foi catalogada pelos exploradores e aventureiros de Rhaokar.</p><p class="small text-warning">Você pode mapear este hexágono no Painel do WordPress > Hexcrawl Rhaokar > Mapear Novo Hex!</p></div>');
		$('#rhaokar-subtile-detail-title').html('🔍 Região Inexplorada');
		$('#rhaokar-subtile-detail-content').html('<em class="text-muted d-block text-center mt-4">Nenhum detalhe cadastrado para esta região.</em>');

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

	function requestRedraw() {
		requestAnimationFrame(renderMap);
	}

	function setupEvents() {
		var canvasEl = $(canvas);

		canvasEl.on('mousedown touchstart', function(e) {
			if (e.type === 'mousedown' && e.which !== 1) return;
			isDragging = true;

			var pos = getMousePos(e);
			startX = pos.x - panX;
			startY = pos.y - panY;
		});

		$(document).on('mousemove touchmove', function(e) {
			if (!isDragging || !canvas) return;
			var pos = getMousePos(e);

			panX = pos.x - startX;
			panY = pos.y - startY;
			requestRedraw();
		});

		$(document).on('mouseup touchend touchcancel', function() {
			if (isDragging) {
				isDragging = false;
			}
		});

		canvas.addEventListener('wheel', function(e) {
			e.preventDefault();
			var zoomFactor = 1.12;
			var oldScale = scale;

			if (e.deltaY < 0) {
				scale = Math.min(scale * zoomFactor, 2.2);
			} else {
				scale = Math.max(scale / zoomFactor, 0.25);
			}

			var pos = getMousePos(e);
			panX = pos.x - (pos.x - panX) * (scale / oldScale);
			panY = pos.y - (pos.y - panY) * (scale / oldScale);

			requestRedraw();
		}, { passive: false });

		canvasEl.on('click', function(e) {
			handleCanvasClick(e);
		});

		$('#rhaokar-zoom-in').on('click', function() {
			scale = Math.min(scale * 1.25, 2.2);
			requestRedraw();
		});

		$('#rhaokar-zoom-out').on('click', function() {
			scale = Math.max(scale / 1.25, 0.25);
			requestRedraw();
		});

		$('#rhaokar-zoom-reset').on('click', function() {
			scale = 0.45;
			panX = 40;
			panY = 40;
			requestRedraw();
		});
	}

	function initRhaokarCanvasEngine() {
		canvas = document.getElementById('rhaokarHexCanvas');
		if (!canvas) return;

		ctx = canvas.getContext('2d');

		if (window.rhaokarHexData) {
			mappedHexes = window.rhaokarHexData.mappedHexes || {};
			terrains    = window.rhaokarHexData.terrains || {};
			matrix      = window.rhaokarHexData.matrix || {};
		}

		if (window.rhaokarMacroMapData) {
			if (typeof window.rhaokarMacroMapData === 'string') {
				try {
					mapData = JSON.parse(window.rhaokarMacroMapData);
				} catch (e) {
					console.error("Erro ao analisar rhaokarMacroMapData:", e);
				}
			} else {
				mapData = window.rhaokarMacroMapData;
			}
		}

		preloadImages();
		setupEvents();
		requestRedraw();
	}

	$(document).ready(function() {
		initRhaokarCanvasEngine();
	});

	window.initRhaokarCanvasEngine = initRhaokarCanvasEngine;

})(jQuery);
