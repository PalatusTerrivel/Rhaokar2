/**
 * Rhaokar HexMap Interactive Engine
 */

function rhaokarCloseSubhexModal() {
	var modal = document.getElementById('rhaokar-subhex-modal');
	if (modal) {
		modal.classList.remove('rhaokar-open');
		document.body.style.overflow = '';
	}
}

jQuery(document).ready(function($) {

	// Inicializa o Mapa Global StuQuery HexMap
	if ($('#hexmap-8').length && typeof S !== 'undefined' && S.hexmap) {
		var hexmap = S.hexmap('hexmap-8');
		hexmap.setLayout('even-r');
		hexmap.positionHexes();

		var mappedData = (typeof rhaokarHexData !== 'undefined' && rhaokarHexData.mappedHexes) ? rhaokarHexData.mappedHexes : {};

		hexmap.setContent(function(id, hex) {
			var hexCode = id.toUpperCase();
			var isMapped = (typeof mappedData[hexCode] !== 'undefined');

			var extraClass = isMapped ? ' rhaokar-mapped-hex' : '';
			var tooltipMsg = isMapped ? ('📜 Hex ' + hexCode + ' (MAPEADO - Clique para explorar)') : ('Hex ' + hexCode + ' (Não explorado)');

			return '<div class="' + hex.type + ' batata' + extraClass + '" data-hex-id="' + hexCode + '" data-toggle="tooltip" title="' + tooltipMsg + '"><span>' + hex.q + ',' + Math.abs(hex.r) + '</span></div>';
		});

		// Ativa Tooltips do Bootstrap se disponível
		if ($.fn.tooltip) {
			$('[data-toggle="tooltip"]').tooltip();
		}

		// Evento de Clique nos Hexágonos Globais
		$('#hexmap-8').on('click', '.batata', function(e) {
			e.preventDefault();
			var hexId = $(this).attr('data-hex-id');
			if (!hexId) return;

			if (mappedData[hexId]) {
				openRhaokarSubhexModal(hexId, mappedData[hexId]);
			} else {
				alert('🧭 Hexágono ' + hexId + ' ainda não foi explorado nas campanhas.');
			}
		});
	}

	/**
	 * Abre o Modal Medieval com a grade dos 37 Sub-tiles
	 */
	function openRhaokarSubhexModal(hexId, hexInfo) {
		$('#rhaokar-modal-hex-title').html('<i class="dashicons dashicons-location"></i> Hexágono Mapeado: ' + hexId);

		var matrix = rhaokarHexData.matrix || {};
		var terrains = rhaokarHexData.terrains || {};
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

		// Limpa painel de detalhes inicial
		$('#rhaokar-subtile-detail-title').html('🔍 Selecione um Sub-tile');
		$('#rhaokar-subtile-detail-content').html('<em class="text-muted d-block text-center mt-4">Clique em qualquer um dos 37 sub-tiles ao lado para ver o terreno, locais, NPCs e rumores.</em>');

		// Evento de Clique nos Sub-tiles
		$('#rhaokar-subtile-grid-container').off('click', '.subtile-hex-item').on('click', '.subtile-hex-item', function() {
			$('.subtile-hex-item').removeClass('selected-subtile');
			$(this).addClass('selected-subtile');

			var code = $(this).attr('data-tile-code');
			var tData = tilesData[code] || {};
			renderSubtileDetail(code, tData, terrains);
		});

		var modal = document.getElementById('rhaokar-subhex-modal');
		if (modal) {
			modal.classList.add('rhaokar-open');
			document.body.style.overflow = 'hidden';
		}
	}

	/**
	 * Renderiza as informações do Sub-tile no painel lateral do modal
	 */
	function renderSubtileDetail(code, tData, terrains) {
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

});
