<?php
/**
 * Template Name: Single Personagem - Rhaokar
 * Template Post Type: personagem
 */

get_header();

// Funções Auxiliares de Cálculo de Regras D&D 3.5
if ( ! function_exists( 'rhaokar_dnd35_bonus_sum' ) ) {
	function rhaokar_dnd35_bonus_sum( $modifiers ) {
		if ( ! is_array( $modifiers ) || empty( $modifiers ) ) {
			return 0;
		}
		$max_bonuses = array();
		$inerente_sum = 0;
		$sem_tipo_sum = 0;

		foreach ( $modifiers as $mod ) {
			$val = intval( $mod['valor'] ?? 0 );
			$type = strtolower( trim( $mod['tipo'] ?? 'sem_tipo' ) );

			if ( $type === 'sem_tipo' ) {
				$sem_tipo_sum += $val;
			} elseif ( $type === 'inerente' ) {
				$inerente_sum += $val;
			} else {
				if ( ! isset( $max_bonuses[ $type ] ) || $val > $max_bonuses[ $type ] ) {
					$max_bonuses[ $type ] = $val;
				}
			}
		}
		if ( $inerente_sum > 5 ) {
			$inerente_sum = 5;
		}
		return array_sum( $max_bonuses ) + $inerente_sum + $sem_tipo_sum;
	}
}

if ( ! function_exists( 'rhaokar_dnd35_clean_num' ) ) {
	function rhaokar_dnd35_clean_num( $val, $default = 0 ) {
		if ( $val === null || $val === '' || $val === false ) {
			return $default;
		}
		if ( is_numeric( $val ) ) {
			return intval( $val );
		}
		if ( is_string( $val ) ) {
			$cleaned = preg_replace( '/[^\d\-]/', '', $val );
			return ( $cleaned !== '' ) ? intval( $cleaned ) : $default;
		}
		return intval( $val );
	}
}

if ( ! function_exists( 'rhaokar_dnd35_mod' ) ) {
	function rhaokar_dnd35_mod( $total ) {
		return (int) floor( ( $total - 10 ) / 2 );
	}
}

if ( ! function_exists( 'rhaokar_dnd35_size_mod' ) ) {
	function rhaokar_dnd35_size_mod( $size_name ) {
		$size_name = strtolower( trim( $size_name ) );
		switch ( $size_name ) {
			case 'diminuto': return 4;
			case 'miúda':
			case 'miúdo':
			case 'miudo': return 2;
			case 'pequeno':
			case 'pequena': return 1;
			case 'médio':
			case 'média':
			case 'medio': return 0;
			case 'grande': return -1;
			case 'enorme': return -2;
			case 'imenso':
			case 'imensa': return -4;
			case 'colossal': return -8;
			default: return 0;
		}
	}
}

/**
 * Tabela Oficial de XP D&D 3.5: XP(N) = N * (N - 1) / 2 * 1000
 */
if ( ! function_exists( 'rhaokar_dnd35_xp_for_level' ) ) {
	function rhaokar_dnd35_xp_for_level( $level ) {
		if ( $level <= 1 ) {
			return 0;
		}
		return (int) ( ( $level * ( $level - 1 ) / 2 ) * 1000 );
	}
}

if ( ! function_exists( 'rhaokar_dnd35_ca_variados' ) ) {
	function rhaokar_dnd35_ca_variados( $variados ) {
		if ( ! is_array( $variados ) || empty( $variados ) ) {
			return array( 'total' => 0, 'touch' => 0, 'flat_footed' => 0 );
		}
		$max_types = array();
		$dodge_sum = 0;
		$sem_tipo_sum = 0;

		foreach ( $variados as $v ) {
			$val = intval( $v['valor'] ?? 0 );
			$type = strtolower( trim( $v['tipo'] ?? 'sem_tipo' ) );
			if ( $type === 'esquiva' ) {
				$dodge_sum += $val;
			} elseif ( $type === 'sem_tipo' ) {
				$sem_tipo_sum += $val;
			} else {
				if ( ! isset( $max_types[ $type ] ) || $val > $max_types[ $type ] ) {
					$max_types[ $type ] = $val;
				}
			}
		}
		$total = array_sum( $max_types ) + $dodge_sum + $sem_tipo_sum;
		$touch = $total;
		$flat_footed = array_sum( $max_types ) + $sem_tipo_sum;

		return array(
			'total'       => $total,
			'touch'       => $touch,
			'flat_footed' => $flat_footed,
		);
	}
}

if ( ! function_exists( 'rhaokar_dnd35_save_variados' ) ) {
	function rhaokar_dnd35_save_variados( $variados ) {
		if ( ! is_array( $variados ) || empty( $variados ) ) {
			return 0;
		}
		$max_types = array();
		$sem_tipo_sum = 0;

		foreach ( $variados as $v ) {
			$val = intval( $v['valor'] ?? 0 );
			$type = strtolower( trim( $v['tipo'] ?? 'sem_tipo' ) );
			if ( $type === 'sem_tipo' ) {
				$sem_tipo_sum += $val;
			} else {
				if ( ! isset( $max_types[ $type ] ) || $val > $max_types[ $type ] ) {
					$max_types[ $type ] = $val;
				}
			}
		}
		return array_sum( $max_types ) + $sem_tipo_sum;
	}
}

if ( ! function_exists( 'rhaokar_dnd35_pericia_variados' ) ) {
	function rhaokar_dnd35_pericia_variados( $variados ) {
		if ( ! is_array( $variados ) || empty( $variados ) ) {
			return 0;
		}
		$max_types = array();
		$sum_all = 0;

		foreach ( $variados as $v ) {
			$val = floatval( $v['valor'] ?? 0 );
			$type = strtolower( trim( $v['tipo'] ?? 'sem_tipo' ) );
			if ( in_array( $type, array( 'circunstancia', 'sinergia', 'sem_tipo' ), true ) ) {
				$sum_all += $val;
			} else {
				if ( ! isset( $max_types[ $type ] ) || $val > $max_types[ $type ] ) {
					$max_types[ $type ] = $val;
				}
			}
		}
		return array_sum( $max_types ) + $sum_all;
	}
}

/**
 * Análise Detalhada dos Bônus do Atributo (Verifica quais são somados e quais são ignorados)
 */
if ( ! function_exists( 'rhaokar_dnd35_attribute_breakdown' ) ) {
	function rhaokar_dnd35_attribute_breakdown( $base, $racial, $outros ) {
		$analysis = array();
		$max_by_type = array();
		$inerente_accumulated = 0;

		if ( is_array( $outros ) && ! empty( $outros ) ) {
			foreach ( $outros as $mod ) {
				$val = intval( $mod['valor'] ?? 0 );
				$type = strtolower( trim( $mod['tipo'] ?? 'sem_tipo' ) );
				if ( ! in_array( $type, array( 'sem_tipo', 'inerente' ), true ) ) {
					if ( ! isset( $max_by_type[ $type ] ) || $val > $max_by_type[ $type ] ) {
						$max_by_type[ $type ] = $val;
					}
				}
			}

			$applied_max_types = array();

			foreach ( $outros as $mod ) {
				$val = intval( $mod['valor'] ?? 0 );
				$type = strtolower( trim( $mod['tipo'] ?? 'sem_tipo' ) );
				$origem = ! empty( $mod['origem'] ) ? esc_html( $mod['origem'] ) : 'Não informada';
				$type_name = ucfirst( $type );

				if ( $type === 'sem_tipo' ) {
					$analysis[] = array(
						'origem'   => $origem,
						'tipo'     => 'Sem Tipo',
						'valor'    => $val,
						'status'   => 'applied',
						'motivo'   => 'Acumula livremente com todos os bônus.',
						'efetivo'  => $val,
					);
				} elseif ( $type === 'inerente' ) {
					$space_left = 5 - $inerente_accumulated;
					if ( $space_left > 0 ) {
						$added = min( $val, $space_left );
						$inerente_accumulated += $added;
						if ( $added === $val ) {
							$analysis[] = array(
								'origem'   => $origem,
								'tipo'     => 'Inerente',
								'valor'    => $val,
								'status'   => 'applied',
								'motivo'   => 'Acumula com outros inerentes até o teto máximo de +5.',
								'efetivo'  => $val,
							);
						} else {
							$analysis[] = array(
								'origem'   => $origem,
								'tipo'     => 'Inerente',
								'valor'    => $val,
								'status'   => 'partial',
								'motivo'   => "Limitado pelo teto máximo de +5 (somado apenas +{$added}).",
								'efetivo'  => $added,
							);
						}
					} else {
						$analysis[] = array(
							'origem'   => $origem,
							'tipo'     => 'Inerente',
							'valor'    => $val,
							'status'   => 'ignored',
							'motivo'   => 'Ignorado: teto máximo de +5 para bônus inerente já foi atingido.',
							'efetivo'  => 0,
						);
					}
				} else {
					if ( isset( $max_by_type[ $type ] ) && $val === $max_by_type[ $type ] && ! isset( $applied_max_types[ $type ] ) ) {
						$applied_max_types[ $type ] = true;
						$analysis[] = array(
							'origem'   => $origem,
							'tipo'     => $type_name,
							'valor'    => $val,
							'status'   => 'applied',
							'motivo'   => 'Maior valor deste tipo de bônus.',
							'efetivo'  => $val,
						);
					} else {
						$analysis[] = array(
							'origem'   => $origem,
							'tipo'     => $type_name,
							'valor'    => $val,
							'status'   => 'ignored',
							'motivo'   => "Ignorado: não acumula (já existe um bônus de {$type_name} igual ou maior na ficha).",
							'efetivo'  => 0,
						);
					}
				}
			}
		}

		return $analysis;
	}
}

/**
 * Análise Detalhada dos Bônus da Perícia (Verifica quais são somados e quais são ignorados)
 */
if ( ! function_exists( 'rhaokar_dnd35_pericia_breakdown' ) ) {
	function rhaokar_dnd35_pericia_breakdown( $variados ) {
		$analysis = array();
		$max_by_type = array();

		if ( is_array( $variados ) && ! empty( $variados ) ) {
			foreach ( $variados as $mod ) {
				$val = floatval( $mod['valor'] ?? 0 );
				$type = strtolower( trim( $mod['tipo'] ?? 'sem_tipo' ) );
				if ( ! in_array( $type, array( 'circunstancia', 'sinergia', 'sem_tipo' ), true ) ) {
					if ( ! isset( $max_by_type[ $type ] ) || $val > $max_by_type[ $type ] ) {
						$max_by_type[ $type ] = $val;
					}
				}
			}

			$applied_max_types = array();
			foreach ( $variados as $mod ) {
				$val = floatval( $mod['valor'] ?? 0 );
				$type = strtolower( trim( $mod['tipo'] ?? 'sem_tipo' ) );
				$origem = ! empty( $mod['origem'] ) ? esc_html( $mod['origem'] ) : 'Não informada';
				$type_name = ucfirst( $type );

				if ( in_array( $type, array( 'circunstancia', 'sinergia', 'sem_tipo' ), true ) ) {
					$analysis[] = array(
						'origem'   => $origem,
						'tipo'     => $type_name,
						'valor'    => $val,
						'status'   => 'applied',
						'motivo'   => 'Acumula livremente com todos os bônus nesta perícia.',
						'efetivo'  => $val,
					);
				} else {
					if ( isset( $max_by_type[ $type ] ) && $val == $max_by_type[ $type ] && ! isset( $applied_max_types[ $type ] ) ) {
						$applied_max_types[ $type ] = true;
						$analysis[] = array(
							'origem'   => $origem,
							'tipo'     => $type_name,
							'valor'    => $val,
							'status'   => 'applied',
							'motivo'   => 'Maior valor deste tipo de bônus.',
							'efetivo'  => $val,
						);
					} else {
						$analysis[] = array(
							'origem'   => $origem,
							'tipo'     => $type_name,
							'valor'    => $val,
							'status'   => 'ignored',
							'motivo'   => "Ignorado: não acumula (já existe bônus {$type_name} igual ou maior nesta perícia).",
							'efetivo'  => 0,
						);
					}
				}
			}
		}

		return $analysis;
	}
}

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	$sistema = get_post_meta( $post_id, 'sistema_personagem', true );
	if ( ! $sistema ) {
		$sistema = 'dnd35';
	}

	// ==================== EXTRAÇÃO DOS DADOS D&D 3.5 ====================
	$nome = get_post_meta( $post_id, 'dnd35_nome', true );
	if ( ! $nome ) {
		$nome = get_the_title();
	}
	$raca = get_post_meta( $post_id, 'dnd35_raca', true );
	$tendencia = get_post_meta( $post_id, 'dnd35_tendencia', true );
	$divindade = get_post_meta( $post_id, 'dnd35_divindade', true );
	$tamanho = get_post_meta( $post_id, 'dnd35_tamanho', true ) ?: 'Médio';
	$imagem_id = get_post_meta( $post_id, 'dnd35_imagem', true );
	$imagem_url = $imagem_id ? wp_get_attachment_image_url( $imagem_id, 'full' ) : get_the_post_thumbnail_url( $post_id, 'full' );
	$classes_raw = get_post_meta( $post_id, 'dnd35_classes', true );
	$descricao = get_post_meta( $post_id, 'dnd35_descricao', true );
	$historico = get_post_meta( $post_id, 'dnd35_historico', true );
	$status = get_post_meta( $post_id, 'dnd35_status', true ) ?: 'ativo';

	// Nível Total e Lista de Classes
	$nivel_total = 0;
	$classes_str_arr = array();
	if ( is_array( $classes_raw ) && ! empty( $classes_raw ) ) {
		foreach ( $classes_raw as $c ) {
			$lvl = intval( $c['nivel_classe'] ?? 0 );
			$nivel_total += $lvl;
			if ( ! empty( $c['nome_classe'] ) ) {
				$classes_str_arr[] = esc_html( $c['nome_classe'] ) . ' ' . $lvl;
			}
		}
	}
	$classes_str = implode( ' / ', $classes_str_arr );

	// CÁLCULO E VALIDAÇÃO DE XP (D&D 3.5)
	$lvl_for_xp = max( 1, $nivel_total );
	$min_xp_for_lvl = rhaokar_dnd35_xp_for_level( $lvl_for_xp );
	$next_lvl_xp = rhaokar_dnd35_xp_for_level( $lvl_for_xp + 1 );

	$raw_xp = get_post_meta( $post_id, 'dnd35_xpatual', true );
	$xp_atual = ( $raw_xp !== '' && $raw_xp !== false ) ? rhaokar_dnd35_clean_num( $raw_xp, $min_xp_for_lvl ) : $min_xp_for_lvl;

	// Regra: O XP não pode ser menor que o mínimo necessário para o nível total acumulado do personagem
	if ( $xp_atual < $min_xp_for_lvl ) {
		$xp_atual = $min_xp_for_lvl;
	}

	$xp_needed = max( 0, $next_lvl_xp - $xp_atual );
	$xp_range = $next_lvl_xp - $min_xp_for_lvl;
	$xp_progress = ( $xp_range > 0 ) ? min( 100, max( 0, round( ( ( $xp_atual - $min_xp_for_lvl ) / $xp_range ) * 100 ) ) ) : 100;

	// Cor, Gradiente Dinâmico e Badge da Barra de XP com base na % de progresso
	if ( $xp_progress >= 100 ) {
		$xp_bar_style = 'background-color: #52c41a !important; background-image: linear-gradient(90deg, #389e0d 0%, #52c41a 50%, #73d13d 100%) !important; box-shadow: 0 0 14px #52c41a;';
		$xp_badge_style = 'background-color: #52c41a !important; color: #ffffff !important; font-weight: bold; box-shadow: 0 0 8px rgba(82, 196, 26, 0.6);';
		$xp_badge_label = 'PRONTO P/ SUBIR DE NÍVEL!';
	} elseif ( $xp_progress >= 66 ) {
		$xp_bar_style = 'background-color: #fadb14 !important; background-image: linear-gradient(90deg, #d4b106 0%, #fadb14 50%, #ffec3d 100%) !important;';
		$xp_badge_style = 'background-color: #fadb14 !important; color: #111111 !important; font-weight: bold;';
		$xp_badge_label = "Progresso: {$xp_progress}% (Faltam " . number_format( $xp_needed, 0, ',', '.' ) . ' XP)';
	} elseif ( $xp_progress >= 33 ) {
		$xp_bar_style = 'background-color: #fa8c16 !important; background-image: linear-gradient(90deg, #d46b08 0%, #fa8c16 50%, #ffa940 100%) !important;';
		$xp_badge_style = 'background-color: #fa8c16 !important; color: #ffffff !important; font-weight: bold;';
		$xp_badge_label = "Progresso: {$xp_progress}% (Faltam " . number_format( $xp_needed, 0, ',', '.' ) . ' XP)';
	} else {
		$xp_bar_style = 'background-color: #1890ff !important; background-image: linear-gradient(90deg, #096dd9 0%, #1890ff 50%, #40a9ff 100%) !important;';
		$xp_badge_style = 'background-color: #1890ff !important; color: #ffffff !important; font-weight: bold;';
		$xp_badge_label = "Progresso: {$xp_progress}% (Faltam " . number_format( $xp_needed, 0, ',', '.' ) . ' XP)';
	}

	// ATRIBUTOS (For, Des, Con, Int, Sab, Car)
	$attrs = array( 'for', 'des', 'con', 'int', 'sab', 'car' );
	$attr_names = array(
		'for' => 'Força',
		'des' => 'Destreza',
		'con' => 'Constituição',
		'int' => 'Inteligência',
		'sab' => 'Sabedoria',
		'car' => 'Carisma',
	);

	$attr_data = array();
	foreach ( $attrs as $a ) {
		$base = intval( get_post_meta( $post_id, "dnd35_{$a}_base", true ) ?: 10 );
		$racial = intval( get_post_meta( $post_id, "dnd35_{$a}_racial", true ) ?: 0 );
		$outros = get_post_meta( $post_id, "dnd35_{$a}_outros", true );
		$outros_total = rhaokar_dnd35_bonus_sum( $outros );
		$total = $base + $racial + $outros_total;
		$mod = rhaokar_dnd35_mod( $total );
		$breakdown = rhaokar_dnd35_attribute_breakdown( $base, $racial, $outros );

		$attr_data[ $a ] = array(
			'name'      => $attr_names[ $a ],
			'base'      => $base,
			'racial'    => $racial,
			'outros'    => $outros,
			'total'     => $total,
			'mod'       => $mod,
			'breakdown' => $breakdown,
		);
	}

	// Estatísticas de Defesa
	$pv = get_post_meta( $post_id, 'dnd35_pv', true );
	$deslocamento = get_post_meta( $post_id, 'dnd35_deslocamento', true );
	$rd = get_post_meta( $post_id, 'dnd35_reducao_dano', true );
	$rm = get_post_meta( $post_id, 'dnd35_resistencia_magia', true );

	// Equipamentos Armadura & Escudo (equipados)
	$armaduras = get_post_meta( $post_id, 'dnd35_armaduras', true );
	$escudos = get_post_meta( $post_id, 'dnd35_escudos', true );

	$armadura_bonus = 0;
	$max_des_armadura = null;
	if ( is_array( $armaduras ) ) {
		foreach ( $armaduras as $arm ) {
			if ( ! empty( $arm['em_uso'] ) ) {
				$armadura_bonus = intval( $arm['bonus_ca'] ?? 0 );
				if ( isset( $arm['bonus_max_des'] ) && $arm['bonus_max_des'] !== '' ) {
					$max_des_armadura = intval( $arm['bonus_max_des'] );
				}
				break;
			}
		}
	}

	$escudo_bonus = 0;
	if ( is_array( $escudos ) ) {
		foreach ( $escudos as $esc ) {
			if ( ! empty( $esc['em_uso'] ) ) {
				$escudo_bonus = intval( $esc['bonus_ca'] ?? 0 );
				break;
			}
		}
	}

	// Mod Destreza Efetivo
	$des_mod = $attr_data['des']['mod'];
	$effective_des_mod = ( $max_des_armadura !== null ) ? min( $des_mod, $max_des_armadura ) : $des_mod;
	$size_mod = rhaokar_dnd35_size_mod( $tamanho );

	// CA
	$ca_natural = intval( get_post_meta( $post_id, 'dnd35_ca_natural', true ) ?: 0 );
	$ca_deflexao = intval( get_post_meta( $post_id, 'dnd35_ca_deflexao', true ) ?: 0 );
	$ca_variados_raw = get_post_meta( $post_id, 'dnd35_ca_variados', true );
	$ca_variados = rhaokar_dnd35_ca_variados( $ca_variados_raw );

	$ca_total = 10 + $armadura_bonus + $escudo_bonus + $effective_des_mod + $size_mod + $ca_natural + $ca_deflexao + $ca_variados['total'];
	$ca_toque = 10 + $effective_des_mod + $size_mod + $ca_deflexao + $ca_variados['touch'];
	$ca_surpresa = 10 + $armadura_bonus + $escudo_bonus + $size_mod + $ca_natural + $ca_deflexao + $ca_variados['flat_footed'];

	// BBA e Ataques
	$bba_raw = get_post_meta( $post_id, 'dnd35_bba_repeater', true );
	$bba_total = 0;
	if ( is_array( $bba_raw ) ) {
		foreach ( $bba_raw as $b ) {
			$bba_total += intval( $b['bonus'] ?? 0 );
		}
	}

	$ataque_corpo_a_corpo = $bba_total + $attr_data['for']['mod'] + $size_mod;
	$ataque_distancia = $bba_total + $attr_data['des']['mod'] + $size_mod;

	// SAVES (Fortitude, Reflexos, Vontade)
	$saves_config = array(
		'fort' => array( 'name' => 'Fortitude', 'default_attr' => 'con', 'alt_key' => 'fortitude' ),
		'ref'  => array( 'name' => 'Reflexos', 'default_attr' => 'des', 'alt_key' => 'reflexos' ),
		'von'  => array( 'name' => 'Vontade', 'default_attr' => 'sab', 'alt_key' => 'vontade' ),
	);

	$saves_data = array();
	foreach ( $saves_config as $s_key => $s_conf ) {
		$base_raw = get_post_meta( $post_id, "dnd35_{$s_key}_base", true );
		if ( empty( $base_raw ) ) {
			$base_raw = get_post_meta( $post_id, "dnd35_{$s_conf['alt_key']}_base", true );
		}

		$base_sum = 0;
		$base_list = array();
		if ( is_array( $base_raw ) && ! empty( $base_raw ) ) {
			foreach ( $base_raw as $br ) {
				$val = intval( $br['bonus'] ?? $br['valor'] ?? $br['bonus_base'] ?? 0 );
				$base_sum += $val;
				if ( ! empty( $br['classe_origem'] ) || $val > 0 ) {
					$base_list[] = array(
						'classe' => esc_html( $br['classe_origem'] ?? 'Classe' ),
						'bonus'  => $val,
					);
				}
			}
		}

		$attr_key = get_post_meta( $post_id, "dnd35_{$s_key}_atributo", true );
		if ( empty( $attr_key ) ) {
			$attr_key = get_post_meta( $post_id, "dnd35_{$s_conf['alt_key']}_atributo", true );
		}
		if ( empty( $attr_key ) ) {
			$attr_key = $s_conf['default_attr'];
		}
		$attr_mod = $attr_data[ $attr_key ]['mod'] ?? 0;

		$var_raw = get_post_meta( $post_id, "dnd35_{$s_key}_variados", true );
		if ( empty( $var_raw ) ) {
			$var_raw = get_post_meta( $post_id, "dnd35_{$s_conf['alt_key']}_variados", true );
		}
		$var_sum = rhaokar_dnd35_save_variados( $var_raw );
		$save_total = $base_sum + $attr_mod + $var_sum;

		$saves_data[ $s_key ] = array(
			'name'      => $s_conf['name'],
			'base'      => $base_sum,
			'base_list' => $base_list,
			'attr_key'  => strtoupper( $attr_key ),
			'attr_mod'  => $attr_mod,
			'variados'  => $var_raw,
			'var_sum'   => $var_sum,
			'total'     => $save_total,
		);
	}

	// Repeaters Variados
	$armas = get_post_meta( $post_id, 'dnd35_armas', true );
	$pericias = get_post_meta( $post_id, 'dnd35_pericias', true );
	$talentos = get_post_meta( $post_id, 'dnd35_talentos', true );
	$tracos_raciais = get_post_meta( $post_id, 'dnd35_tracos_raciais', true );
	$caracteristicas_classe = get_post_meta( $post_id, 'dnd35_caracteristicas_classe', true );
	$equipamentos = get_post_meta( $post_id, 'dnd35_equipamento', true );
	$montarias = get_post_meta( $post_id, 'dnd35_montaria', true );
	$familiares = get_post_meta( $post_id, 'dnd35_familiar', true );
	$companheiros = get_post_meta( $post_id, 'dnd35_companheiro_animal', true );
	$seguidores = get_post_meta( $post_id, 'dnd35_seguidor', true );
	$bases = get_post_meta( $post_id, 'dnd35_bases', true );
	$recursos = get_post_meta( $post_id, 'dnd35_recursos', true );
	$grimorio = get_post_meta( $post_id, 'dnd35_grimorio', true );
	$espacos_magia = get_post_meta( $post_id, 'dnd35_espacos_magia', true );
	$magias_decoradas = get_post_meta( $post_id, 'dnd35_magias_decoradas', true );
	$notas = get_post_meta( $post_id, 'dnd35_notas', true );
	?>

<style>
/* Estilos Failsafe da Ficha de D&D 3.5 em Rhaokar */
.ficha-dnd35-container {
	background: #141619;
	color: #e0e6ed;
	font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
	border: 2px solid #b8860b;
	border-radius: 8px;
	padding: 25px;
	box-shadow: 0 10px 30px rgba(0,0,0,0.8);
	margin-top: 20px;
	margin-bottom: 40px;
}
.ficha-header {
	border-bottom: 2px solid #b8860b;
	padding-bottom: 15px;
	margin-bottom: 20px;
}
.ficha-title {
	font-family: 'Cinzel', Georgia, serif;
	color: #ffd700;
	font-weight: 700;
	text-shadow: 0 2px 4px rgba(0,0,0,0.9);
}
.ficha-box {
	background: #1e2228;
	border: 1px solid #3a424d;
	border-radius: 6px;
	padding: 12px 15px;
	margin-bottom: 15px;
}
.ficha-box-title {
	font-weight: 700;
	color: #e6c667;
	text-transform: uppercase;
	font-size: 0.85rem;
	letter-spacing: 1px;
	margin-bottom: 8px;
	border-bottom: 1px solid #3a424d;
	padding-bottom: 4px;
}
.stat-box {
	background: #252a32;
	border: 1px solid #4a5463;
	border-radius: 6px;
	text-align: center;
	padding: 8px;
}
.stat-val {
	font-size: 1.4rem;
	font-weight: bold;
	color: #ffd700;
}
.stat-mod {
	font-size: 1.1rem;
	color: #52c41a;
	font-weight: bold;
}
.stat-lbl {
	font-size: 0.75rem;
	color: #a0aec0;
	text-transform: uppercase;
}
.badge-dnd {
	background: #b8860b;
	color: #111;
	font-weight: bold;
	font-size: 0.75rem;
	padding: 3px 8px;
	border-radius: 4px;
}
.table-dnd {
	color: #e0e6ed;
	font-size: 0.9rem;
}
.table-dnd th {
	background: #2a3038;
	color: #ffd700;
	border-color: #3a424d;
}
.table-dnd td {
	border-color: #2e353e;
}
.btn-attr-detail {
	background: rgba(184, 134, 11, 0.25);
	border: 1px solid #b8860b;
	color: #ffd700;
	font-size: 0.7rem;
	padding: 2px 7px;
	border-radius: 4px;
	transition: all 0.2s ease;
	cursor: pointer;
	display: inline-block;
}
.btn-attr-detail:hover {
	background: #b8860b;
	color: #111;
	text-decoration: none;
}

/* MODAL SOBREPOSIÇÃO MODERNA E TOTALMENTE OCULTA POR PADRÃO */
.rhaokar-modal-backdrop {
	display: none !important;
	position: fixed !important;
	top: 0 !important;
	left: 0 !important;
	width: 100vw !important;
	height: 100vh !important;
	background: rgba(0, 0, 0, 0.85) !important;
	z-index: 999999 !important;
	align-items: center !important;
	justify-content: center !important;
	padding: 20px !important;
	box-sizing: border-box !important;
}
.rhaokar-modal-backdrop.rhaokar-open {
	display: flex !important;
}
.rhaokar-modal-dialog {
	background: #1e2228 !important;
	color: #e0e6ed !important;
	border: 2px solid #b8860b !important;
	border-radius: 8px !important;
	max-width: 780px !important;
	width: 100% !important;
	max-height: 85vh !important;
	overflow-y: auto !important;
	padding: 20px !important;
	box-shadow: 0 15px 40px rgba(0,0,0,0.95) !important;
	position: relative !important;
}
.rhaokar-modal-header {
	display: flex !important;
	justify-content: space-between !important;
	align-items: center !important;
	border-bottom: 1px solid #3a424d !important;
	padding-bottom: 10px !important;
	margin-bottom: 15px !important;
}
.rhaokar-modal-close {
	background: none !important;
	border: none !important;
	color: #ffd700 !important;
	font-size: 1.6rem !important;
	cursor: pointer !important;
	line-height: 1 !important;
}
.rhaokar-modal-close:hover {
	color: #ff4d4f !important;
}
</style>

<script id="rhaokar-modal-toggle-script">
function rhaokarOpenModal(id) {
	var el = document.getElementById(id);
	if (el) {
		el.classList.add('rhaokar-open');
		document.body.style.overflow = 'hidden';
	}
}
function rhaokarCloseModal(id) {
	var el = document.getElementById(id);
	if (el) {
		el.classList.remove('rhaokar-open');
		document.body.style.overflow = '';
	}
}
document.addEventListener('click', function(e) {
	if (e.target && e.target.classList.contains('rhaokar-modal-backdrop')) {
		e.target.classList.remove('rhaokar-open');
		document.body.style.overflow = '';
	}
});
</script>

<div class="container ficha-dnd35-container">

	<!-- BOTÃO DE VOLTAR AO HALL DOS HERÓIS E VILÕES -->
	<div class="mb-3 text-left">
		<a href="https://rhaokar.com.br/o-hall-dos-herois-e-viloes/" class="btn btn-outline-warning btn-sm shadow-sm" style="border-color: #b8860b; color: #ffd700; background: rgba(0,0,0,0.45); border-radius: 6px; padding: 6px 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
			<i class="dashicons dashicons-arrow-left-alt" style="font-size: 1.1rem; line-height: 1;"></i> Voltar ao Hall dos Heróis e Vilões
		</a>
	</div>

	<?php if ( $sistema === 'pf1' ) : ?>
		<!-- ALERTA DE SISTEMA PATHFINDER 1E SE SELECIONADO -->
		<div class="alert alert-info">
			<h4><i class="dashicons dashicons-info"></i> Ficha em Modo Pathfinder 1e</h4>
			<p>Esta ficha está configurada para Pathfinder 1e. O resumo de alterações será configurado em breve.</p>
		</div>
	<?php endif; ?>

	<!-- CABEÇALHO DA FICHA -->
	<div class="row ficha-header align-items-center">
		<div class="col-md-3 text-center mb-3 mb-md-0">
			<?php if ( $imagem_url ) : ?>
				<img src="<?php echo esc_url( $imagem_url ); ?>" alt="<?php echo esc_attr( $nome ); ?>" class="img-fluid rounded border border-warning shadow" style="max-height: 220px; object-fit: cover;">
			<?php else : ?>
				<div class="p-4 bg-dark rounded border border-secondary text-muted">
					<i class="dashicons dashicons-format-image display-4"></i>
					<div>Sem Foto</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="col-md-9">
			<div class="d-flex justify-content-between align-items-center flex-wrap">
				<h1 class="ficha-title mb-0"><?php echo esc_html( $nome ); ?></h1>
				<div class="d-flex align-items-center" style="gap: 6px;">
					<span class="badge-dnd"><?php echo ( $sistema === 'pf1' ) ? 'Pathfinder 1e' : 'D&D 3.5'; ?></span>
					<?php if ( $status === 'morto' ) : ?>
						<span class="badge badge-danger px-2 py-1" style="font-size: 0.75rem; font-weight: bold; background-color: #ff4d4f !important;">💀 MORTO</span>
					<?php elseif ( $status === 'aposentado' ) : ?>
						<span class="badge badge-warning px-2 py-1" style="font-size: 0.75rem; font-weight: bold; background-color: #faad14 !important; color: #111 !important;">🟡 APOSENTADO</span>
					<?php else : ?>
						<span class="badge badge-success px-2 py-1" style="font-size: 0.75rem; font-weight: bold; background-color: #52c41a !important;">🟢 ATIVO</span>
					<?php endif; ?>
				</div>
			</div>
			<p class="text-warning mb-2">
				<strong><?php echo esc_html( $classes_str ?: 'Sem Classe' ); ?></strong> (Nível Total: <strong><?php echo esc_html( $nivel_total ); ?></strong>)
			</p>

			<!-- BARRA E DETALHES DE PONTOS DE EXPERIÊNCIA (XP DINÂMICA) -->
			<div class="p-2 bg-dark rounded border border-secondary mb-3">
				<div class="d-flex justify-content-between align-items-center mb-1 flex-wrap small">
					<span class="mr-2">
						<strong class="text-warning">XP ATUAL:</strong>
						<span class="text-light font-weight-bold" style="font-size: 1.05rem;"><?php echo number_format( $xp_atual, 0, ',', '.' ); ?> XP</span>
					</span>
					<span class="mr-2">
						<strong class="text-info">PRÓXIMO NÍVEL (Nível <?php echo $lvl_for_xp + 1; ?>):</strong>
						<span><?php echo number_format( $next_lvl_xp, 0, ',', '.' ); ?> XP</span>
					</span>
					<span class="badge py-1 px-2" style="<?php echo $xp_badge_style; ?>">
						<?php echo esc_html( $xp_badge_label ); ?>
					</span>
				</div>
				<div class="progress" style="height: 14px; background-color: #1a1d22 !important; border-radius: 6px; overflow: hidden; border: 1px solid #3a424d;">
					<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $xp_progress; ?>%; <?php echo $xp_bar_style; ?>" aria-valuenow="<?php echo $xp_progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-6 col-md-3 mb-2">
					<small class="text-muted d-block">RAÇA</small>
					<strong><?php echo esc_html( $raca ?: '-' ); ?></strong>
				</div>
				<div class="col-6 col-md-3 mb-2">
					<small class="text-muted d-block">TENDÊNCIA</small>
					<strong><?php echo esc_html( $tendencia ?: '-' ); ?></strong>
				</div>
				<div class="col-6 col-md-3 mb-2">
					<small class="text-muted d-block">DIVINDADE</small>
					<strong><?php echo esc_html( $divindade ?: '-' ); ?></strong>
				</div>
				<div class="col-6 col-md-3 mb-2">
					<small class="text-muted d-block">TAMANHO</small>
					<strong><?php echo esc_html( $tamanho ); ?> (<?php echo ( $size_mod >= 0 ? '+' : '' ) . $size_mod; ?>)</strong>
				</div>
			</div>
		</div>
	</div>

	<!-- BLOCO PRINCIPAL DE ESTATÍSTICAS E ATRIBUTOS -->
	<div class="row">
		<!-- ATRIBUTOS DE HABILIDADE (ESQUERDA) -->
		<div class="col-md-4">
			<div class="ficha-box">
				<div class="ficha-box-title d-flex justify-content-between align-items-center">
					<span>Atributos de Habilidade</span>
					<small class="text-muted" style="font-size: 0.65rem;">Clique em "Ver Bônus"</small>
				</div>
				
				<?php foreach ( $attr_data as $key => $at ) : ?>
					<div class="stat-box mb-2">
						<div class="d-flex justify-content-between align-items-center">
							<span class="stat-lbl text-left font-weight-bold" style="font-size: 0.9rem; color: #ffd700;">
								<?php echo esc_html( $at['name'] ); ?>
							</span>
							<div class="d-flex align-items-center">
								<span class="stat-val mr-2"><?php echo esc_html( $at['total'] ); ?></span>
								<span class="stat-mod mr-2"><?php echo ( $at['mod'] >= 0 ? '+' : '' ) . esc_html( $at['mod'] ); ?></span>
								<button type="button" class="btn-attr-detail" onclick="rhaokarOpenModal('modal-attr-<?php echo esc_attr( $key ); ?>')">
									🔍 Ver Bônus
								</button>
							</div>
						</div>
						<small class="text-muted d-block text-left" style="font-size: 0.7rem;">
							Base: <?php echo $at['base']; ?> | Racial: <?php echo ( $at['racial'] >= 0 ? '+' : '' ) . $at['racial']; ?> | Outros Efetivos: <?php echo ( $at['total'] - $at['base'] - $at['racial'] >= 0 ? '+' : '' ) . ( $at['total'] - $at['base'] - $at['racial'] ); ?>
						</small>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- DEFESA, CA, ATAQUE E RESISTÊNCIAS (DIREITA) -->
		<div class="col-md-8">
			<!-- PONTOS DE VIDA & DEFESAS -->
			<div class="row">
				<div class="col-6 col-md-3 mb-3">
					<div class="stat-box">
						<div class="stat-lbl">Pontos de Vida (PV)</div>
						<div class="stat-val text-danger"><?php echo esc_html( $pv ?: '0' ); ?></div>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-3">
					<div class="stat-box">
						<div class="stat-lbl">Deslocamento</div>
						<div class="stat-val text-info"><?php echo esc_html( $deslocamento ?: '9m' ); ?></div>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-3">
					<div class="stat-box">
						<div class="stat-lbl">Redução Dano (RD)</div>
						<div class="stat-val text-light"><?php echo esc_html( $rd ?: '-' ); ?></div>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-3">
					<div class="stat-box">
						<div class="stat-lbl">Resist. Magia (RM)</div>
						<div class="stat-val text-warning"><?php echo esc_html( $rm ?: '-' ); ?></div>
					</div>
				</div>
			</div>

			<!-- CLASSE DE ARMADURA (CA) -->
			<div class="ficha-box">
				<div class="ficha-box-title">Classe de Armadura (CA)</div>
				<div class="row text-center align-items-center">
					<div class="col-4 border-right border-secondary">
						<span class="stat-lbl d-block">CA TOTAL</span>
						<span class="stat-val text-warning display-4 font-weight-bold"><?php echo esc_html( $ca_total ); ?></span>
					</div>
					<div class="col-4 border-right border-secondary">
						<span class="stat-lbl d-block">CA TOQUE</span>
						<span class="stat-val text-info" style="font-size: 1.6rem;"><?php echo esc_html( $ca_toque ); ?></span>
					</div>
					<div class="col-4">
						<span class="stat-lbl d-block">CA SURPRESA</span>
						<span class="stat-val text-muted" style="font-size: 1.6rem;"><?php echo esc_html( $ca_surpresa ); ?></span>
					</div>
				</div>
				<hr class="border-secondary my-2">
				<small class="text-muted d-block">
					<strong>Composição da CA:</strong> Base 10 + Armadura (+<?php echo $armadura_bonus; ?>) + Escudo (+<?php echo $escudo_bonus; ?>) + Des (+<?php echo $effective_des_mod; ?>) + Tam (+<?php echo $size_mod; ?>) + Nat (+<?php echo $ca_natural; ?>) + Deflexão (+<?php echo $ca_deflexao; ?>) + Variados (+<?php echo $ca_variados['total']; ?>)
				</small>
			</div>

			<!-- BBA E COMBATE -->
			<div class="ficha-box">
				<div class="ficha-box-title">Bônus Base de Ataque & Combate</div>
				<div class="row text-center">
					<div class="col-4">
						<span class="stat-lbl d-block">BBA TOTAL</span>
						<span class="stat-val text-light"><?php echo ( $bba_total >= 0 ? '+' : '' ) . $bba_total; ?></span>
					</div>
					<div class="col-4">
						<span class="stat-lbl d-block">CORPO A CORPO</span>
						<span class="stat-val text-success"><?php echo ( $ataque_corpo_a_corpo >= 0 ? '+' : '' ) . $ataque_corpo_a_corpo; ?></span>
						<small class="d-block text-muted" style="font-size: 0.65rem;">BBA + FOR + TAM</small>
					</div>
					<div class="col-4">
						<span class="stat-lbl d-block">À DISTÂNCIA</span>
						<span class="stat-val text-info"><?php echo ( $ataque_distancia >= 0 ? '+' : '' ) . $ataque_distancia; ?></span>
						<small class="d-block text-muted" style="font-size: 0.65rem;">BBA + DES + TAM</small>
					</div>
				</div>
			</div>

			<!-- TESTES DE RESISTÊNCIA -->
			<div class="ficha-box">
				<div class="ficha-box-title">Testes de Resistência (Saves)</div>
				<div class="row text-center">
					<?php foreach ( $saves_data as $sv ) : ?>
						<div class="col-4">
							<span class="stat-lbl d-block"><?php echo esc_html( $sv['name'] ); ?></span>
							<span class="stat-val text-warning" style="font-size: 1.5rem;">
								<?php echo ( $sv['total'] >= 0 ? '+' : '' ) . esc_html( $sv['total'] ); ?>
							</span>
							<small class="d-block text-muted" style="font-size: 0.7rem;">
								Base: +<?php echo $sv['base']; ?> | <?php echo $sv['attr_key']; ?>: <?php echo ( $sv['attr_mod'] >= 0 ? '+' : '' ) . $sv['attr_mod']; ?> | Var: +<?php echo $sv['var_sum']; ?>
							</small>
							<?php if ( ! empty( $sv['base_list'] ) ) : ?>
								<div class="mt-1" style="font-size: 0.65rem; color: #a0aec0;">
									<?php foreach ( $sv['base_list'] as $bl ) : ?>
										<div><?php echo esc_html( $bl['classe'] ); ?>: +<?php echo esc_html( $bl['bonus'] ); ?></div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- ARMAS -->
	<?php if ( is_array( $armas ) && ! empty( $armas ) ) : ?>
		<div class="ficha-box mt-3">
			<div class="ficha-box-title">Ataques & Armas</div>
			<div class="table-responsive">
				<table class="table table-dark table-striped table-dnd mb-0">
					<thead>
						<tr>
							<th>Nome da Arma</th>
							<th>Ataque</th>
							<th>Dano</th>
							<th>Crítico</th>
							<th>Alcance</th>
							<th>Tipo</th>
							<th>Especial / Munição</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $armas as $arma ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $arma['nome_arma'] ?? '-' ); ?></strong></td>
								<td class="text-success font-weight-bold"><?php echo esc_html( $arma['bonus_ataque'] ?? '-' ); ?></td>
								<td class="text-warning font-weight-bold"><?php echo esc_html( $arma['dano'] ?? '-' ); ?></td>
								<td><?php echo esc_html( $arma['decisivo'] ?? '-' ); ?></td>
								<td><?php echo esc_html( $arma['alcance'] ?? '-' ); ?></td>
								<td><?php echo esc_html( $arma['tipo_dano'] ?? '-' ); ?></td>
								<td>
									<?php echo esc_html( $arma['propriedades_especiais'] ?? '' ); ?>
									<?php echo ! empty( $arma['municao'] ) ? ' (' . esc_html( $arma['municao'] ) . ')' : ''; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>

	<!-- PERÍCIAS COM BOTÃO DE DETALHAMENTO EM TODAS AS LINHAS -->
	<?php if ( is_array( $pericias ) && ! empty( $pericias ) ) : ?>
		<div class="ficha-box mt-3">
			<div class="ficha-box-title d-flex justify-content-between align-items-center">
				<span>Perícias</span>
				<small class="text-muted" style="font-size: 0.65rem;">Clique em "Ver Bônus" para auditar qualquer perícia</small>
			</div>
			<div class="table-responsive">
				<table class="table table-dark table-striped table-dnd mb-0">
					<thead>
						<tr>
							<th>Perícia</th>
							<th>Classe</th>
							<th>Atributo Chave</th>
							<th>Mod. Atrib.</th>
							<th>Graduação</th>
							<th>Outros Bônus</th>
							<th>TOTAL</th>
							<th>Auditoria</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $pericias as $p_idx => $p ) : ?>
							<?php
							$p_nome = $p['nome_pericia'] ?? 'Perícia';
							$p_classe = $p['classe_pericia'] ?? '-';
							$p_attr_key = strtolower( trim( $p['atributo_chave'] ?? 'nenhum' ) );
							$p_attr_mod = ( $p_attr_key !== 'nenhum' && isset( $attr_data[ $p_attr_key ] ) ) ? $attr_data[ $p_attr_key ]['mod'] : 0;
							$p_grad = floatval( $p['graduacao'] ?? 0 );
							$p_var = rhaokar_dnd35_pericia_variados( $p['variados'] ?? array() );
							$p_total = $p_attr_mod + $p_grad + $p_var;
							$p_breakdown = rhaokar_dnd35_pericia_breakdown( $p['variados'] ?? array() );
							?>
							<tr>
								<td><strong><?php echo esc_html( $p_nome ); ?></strong></td>
								<td><small class="text-muted"><?php echo esc_html( $p_classe ); ?></small></td>
								<td><?php echo esc_html( strtoupper( $p_attr_key ) ); ?></td>
								<td><?php echo ( $p_attr_mod >= 0 ? '+' : '' ) . $p_attr_mod; ?></td>
								<td><?php echo esc_html( $p_grad ); ?></td>
								<td>+<?php echo esc_html( $p_var ); ?></td>
								<td class="text-warning font-weight-bold" style="font-size: 1.1rem;">
									<?php echo ( $p_total >= 0 ? '+' : '' ) . esc_html( $p_total ); ?>
								</td>
								<td>
									<button type="button" class="btn-attr-detail" onclick="rhaokarOpenModal('modal-pericia-<?php echo $p_idx; ?>')">
										🔍 Ver Bônus
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>

	<!-- TALENTOS & HABILIDADES DA RAÇA/CLASSE -->
	<div class="row mt-3">
		<!-- TALENTOS -->
		<div class="col-md-6 mb-3">
			<div class="ficha-box h-100">
				<div class="ficha-box-title">Talentos</div>
				<?php if ( is_array( $talentos ) && ! empty( $talentos ) ) : ?>
					<ul class="list-unstyled mb-0">
						<?php foreach ( $talentos as $t ) : ?>
							<li class="mb-2 pb-2 border-bottom border-secondary">
								<strong class="text-warning"><?php echo esc_html( $t['origem'] ?? 'Talento' ); ?> (Nível <?php echo esc_html( $t['nivel'] ?? '1' ); ?>):</strong>
								<span><?php echo esc_html( $t['descricao'] ?? '' ); ?></span>
								<?php if ( ! empty( $t['livro'] ) ) : ?>
									<small class="text-muted d-block">Livro: <?php echo esc_html( $t['livro'] ); ?></small>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<em class="text-muted">Nenhum talento cadastrado.</em>
				<?php endif; ?>
			</div>
		</div>

		<!-- CARACTERÍSTICAS DE CLASSE E RAÇA -->
		<div class="col-md-6 mb-3">
			<div class="ficha-box h-100">
				<div class="ficha-box-title">Traços Raciais & Características de Classe</div>
				<?php if ( is_array( $tracos_raciais ) && ! empty( $tracos_raciais ) ) : ?>
					<h6 class="text-info mt-2">Traços Raciais:</h6>
					<ul>
						<?php foreach ( $tracos_raciais as $tr ) : ?>
							<li><?php echo esc_html( $tr['descricao'] ?? '' ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( is_array( $caracteristicas_classe ) && ! empty( $caracteristicas_classe ) ) : ?>
					<h6 class="text-info mt-2">Características de Classe:</h6>
					<ul>
						<?php foreach ( $caracteristicas_classe as $cc ) : ?>
							<li>
								<strong><?php echo esc_html( $cc['classe'] ?? '' ); ?>:</strong>
								<?php echo esc_html( $cc['descricao'] ?? '' ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- COMPANHEIROS, SEGUIDORES & BASES -->
	<?php if ( ! empty( $montarias ) || ! empty( $familiares ) || ! empty( $companheiros ) || ! empty( $seguidores ) || ! empty( $bases ) ) : ?>
		<div class="ficha-box mt-3">
			<div class="ficha-box-title">Companheiros, Aliados & Propriedades</div>
			<div class="row">
				<?php if ( ! empty( $montarias ) ) : ?>
					<div class="col-md-4 mb-2">
						<strong class="text-warning">Montarias:</strong>
						<?php foreach ( $montarias as $m ) : ?>
							<p class="small text-light mb-1"><?php echo esc_html( $m['dados_montaria'] ?? '' ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $familiares ) ) : ?>
					<div class="col-md-4 mb-2">
						<strong class="text-warning">Familiar:</strong>
						<?php foreach ( $familiares as $fam ) : ?>
							<p class="small text-light mb-1"><?php echo esc_html( $fam['dados_familiar'] ?? '' ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $companheiros ) ) : ?>
					<div class="col-md-4 mb-2">
						<strong class="text-warning">Companheiro Animal:</strong>
						<?php foreach ( $companheiros as $ca ) : ?>
							<p class="small text-light mb-1"><?php echo esc_html( $ca['dados_companheiro'] ?? '' ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $seguidores ) ) : ?>
					<div class="col-md-4 mb-2">
						<strong class="text-warning">Seguidor (Liderança):</strong>
						<?php foreach ( $seguidores as $seg ) : ?>
							<p class="small text-light mb-1"><?php echo esc_html( $seg['dados_seguidor'] ?? '' ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $bases ) ) : ?>
					<div class="col-md-4 mb-2">
						<strong class="text-warning">Bases & Fortalezas:</strong>
						<?php foreach ( $bases as $b ) : ?>
							<p class="small text-light mb-1"><?php echo esc_html( $b['dados_base'] ?? '' ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<!-- MAGIAS & CONJURAÇÃO -->
	<?php if ( ! empty( $espacos_magia ) || ! empty( $grimorio ) || ! empty( $magias_decoradas ) ) : ?>
		<div class="ficha-box mt-3">
			<div class="ficha-box-title">Magias & Conjuração</div>
			
			<?php if ( is_array( $espacos_magia ) && ! empty( $espacos_magia ) ) : ?>
				<h6 class="text-info">Espaços de Magia Diários:</h6>
				<div class="row mb-3 text-center">
					<?php foreach ( $espacos_magia as $em ) : ?>
						<div class="col-3 col-md-2 mb-2">
							<div class="stat-box p-1">
								<small class="stat-lbl d-block">Nível <?php echo esc_html( $em['nivel_magia'] ?? '0' ); ?></small>
								<strong class="text-warning"><?php echo esc_html( $em['usos_diarios'] ?? '0' ); ?>/dia</strong>
								<small class="d-block text-muted" style="font-size: 0.65rem;">CD <?php echo esc_html( $em['cd'] ?? '10' ); ?></small>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $magias_decoradas ) ) : ?>
				<h6 class="text-info mt-2">Magias Decoradas / Preparadas:</h6>
				<p class="text-light small"><?php echo nl2br( esc_html( $magias_decoradas ) ); ?></p>
			<?php endif; ?>

			<?php if ( is_array( $grimorio ) && ! empty( $grimorio ) ) : ?>
				<h6 class="text-info mt-2">Grimório / Magias Conhecidas:</h6>
				<ul>
					<?php foreach ( $grimorio as $g ) : ?>
						<li class="small"><?php echo esc_html( $g['dados_magia'] ?? '' ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- RECURSOS, EQUIPAMENTO E HISTÓRICO -->
	<div class="row mt-3">
		<div class="col-md-6 mb-3">
			<div class="ficha-box h-100">
				<div class="ficha-box-title">Equipamentos & Recursos</div>
				<?php if ( ! empty( $recursos ) ) : ?>
					<h6 class="text-warning">Recursos / Tesouros:</h6>
					<p class="text-light small"><?php echo nl2br( esc_html( $recursos ) ); ?></p>
				<?php endif; ?>

				<?php if ( is_array( $equipamentos ) && ! empty( $equipamentos ) ) : ?>
					<h6 class="text-warning mt-2">Lista de Equipamentos:</h6>
					<ul class="mb-0">
						<?php foreach ( $equipamentos as $eq ) : ?>
							<li class="small">
								<strong><?php echo esc_html( $eq['nome_item'] ?? '' ); ?></strong>
								<?php echo ! empty( $eq['local_guardado'] ) ? ' (' . esc_html( $eq['local_guardado'] ) . ')' : ''; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>

		<div class="col-md-6 mb-3">
			<div class="ficha-box h-100">
				<div class="ficha-box-title">Histórico & Notas</div>
				<?php if ( ! empty( $historico ) ) : ?>
					<h6 class="text-info">Histórico:</h6>
					<p class="text-light small"><?php echo nl2br( esc_html( $historico ) ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $notas ) ) : ?>
					<h6 class="text-info mt-2">Notas Adicionais:</h6>
					<p class="text-light small"><?php echo nl2br( esc_html( $notas ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>

</div>

<!-- MODAIS SOBREPOSTOS DE ATRIBUTOS (FORA DO FLUXO PRINCIPAL) -->
<?php foreach ( $attr_data as $key => $at ) : ?>
	<div class="rhaokar-modal-backdrop" id="modal-attr-<?php echo esc_attr( $key ); ?>">
		<div class="rhaokar-modal-dialog">
			<div class="rhaokar-modal-header">
				<h5 class="m-0 font-weight-bold text-warning">
					<i class="dashicons dashicons-calculator"></i> Detalhamento de Bônus: <?php echo esc_html( $at['name'] ); ?>
				</h5>
				<button type="button" class="rhaokar-modal-close" onclick="rhaokarCloseModal('modal-attr-<?php echo esc_attr( $key ); ?>')">&times;</button>
			</div>
			<div class="rhaokar-modal-body">
				<div class="row text-center mb-3">
					<div class="col-3">
						<small class="text-muted d-block">BASE</small>
						<strong class="h4 text-light"><?php echo esc_html( $at['base'] ); ?></strong>
					</div>
					<div class="col-3">
						<small class="text-muted d-block">MOD. RACIAL</small>
						<strong class="h4 text-info"><?php echo ( $at['racial'] >= 0 ? '+' : '' ) . esc_html( $at['racial'] ); ?></strong>
					</div>
					<div class="col-3">
						<small class="text-muted d-block">TOTAL FINAL</small>
						<strong class="h4 text-warning"><?php echo esc_html( $at['total'] ); ?></strong>
					</div>
					<div class="col-3">
						<small class="text-muted d-block">MODIFICADOR</small>
						<strong class="h4 text-success"><?php echo ( $at['mod'] >= 0 ? '+' : '' ) . esc_html( $at['mod'] ); ?></strong>
					</div>
				</div>

				<h6 class="text-warning border-bottom border-secondary pb-1">Auditoria de Modificadores (Outros Bônus):</h6>
				<?php if ( ! empty( $at['breakdown'] ) ) : ?>
					<div class="table-responsive">
						<table class="table table-dark table-striped table-sm mb-0 small">
							<thead>
								<tr>
									<th>Origem</th>
									<th>Tipo de Bônus</th>
									<th>Valor Informado</th>
									<th>Status na Soma</th>
									<th>Explicação da Regra</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $at['breakdown'] as $b_item ) : ?>
									<tr>
										<td><strong><?php echo esc_html( $b_item['origem'] ); ?></strong></td>
										<td><?php echo esc_html( $b_item['tipo'] ); ?></td>
										<td>+<?php echo esc_html( $b_item['valor'] ); ?></td>
										<td>
											<?php if ( $b_item['status'] === 'applied' ) : ?>
												<span class="badge badge-success">✅ SOMADO (+<?php echo $b_item['efetivo']; ?>)</span>
											<?php elseif ( $b_item['status'] === 'partial' ) : ?>
												<span class="badge badge-warning">⚠️ PARCIAL (+<?php echo $b_item['efetivo']; ?> de +<?php echo $b_item['valor']; ?>)</span>
											<?php else : ?>
												<span class="badge badge-danger">❌ IGNORADO (+0)</span>
											<?php endif; ?>
										</td>
										<td class="text-muted"><?php echo esc_html( $b_item['motivo'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else : ?>
					<em class="text-muted d-block my-2">Nenhum bônus adicional cadastrado para este atributo.</em>
				<?php endif; ?>
			</div>
			<div class="text-right mt-3">
				<button type="button" class="btn btn-secondary btn-sm" onclick="rhaokarCloseModal('modal-attr-<?php echo esc_attr( $key ); ?>')">Fechar</button>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<!-- MODAIS SOBREPOSTOS DE PERÍCIAS (FORA DO FLUXO PRINCIPAL) -->
<?php if ( is_array( $pericias ) && ! empty( $pericias ) ) : ?>
	<?php foreach ( $pericias as $p_idx => $p ) : ?>
		<?php
		$p_nome = $p['nome_pericia'] ?? 'Perícia';
		$p_classe = $p['classe_pericia'] ?? '-';
		$p_attr_key = strtolower( trim( $p['atributo_chave'] ?? 'nenhum' ) );
		$p_attr_mod = ( $p_attr_key !== 'nenhum' && isset( $attr_data[ $p_attr_key ] ) ) ? $attr_data[ $p_attr_key ]['mod'] : 0;
		$p_grad = floatval( $p['graduacao'] ?? 0 );
		$p_var = rhaokar_dnd35_pericia_variados( $p['variados'] ?? array() );
		$p_total = $p_attr_mod + $p_grad + $p_var;
		$p_breakdown = rhaokar_dnd35_pericia_breakdown( $p['variados'] ?? array() );
		?>
		<div class="rhaokar-modal-backdrop" id="modal-pericia-<?php echo $p_idx; ?>">
			<div class="rhaokar-modal-dialog">
				<div class="rhaokar-modal-header">
					<h5 class="m-0 font-weight-bold text-warning">
						<i class="dashicons dashicons-calculator"></i> Detalhamento da Perícia: <?php echo esc_html( $p_nome ); ?>
					</h5>
					<button type="button" class="rhaokar-modal-close" onclick="rhaokarCloseModal('modal-pericia-<?php echo $p_idx; ?>')">&times;</button>
				</div>
				<div class="rhaokar-modal-body">
					<div class="row text-center mb-3">
						<div class="col-3">
							<small class="text-muted d-block">GRADUAÇÃO</small>
							<strong class="h4 text-light"><?php echo esc_html( $p_grad ); ?></strong>
						</div>
						<div class="col-3">
							<small class="text-muted d-block">MOD. ATRIBUTO (<?php echo esc_html( strtoupper( $p_attr_key ) ); ?>)</small>
							<strong class="h4 text-info"><?php echo ( $p_attr_mod >= 0 ? '+' : '' ) . esc_html( $p_attr_mod ); ?></strong>
						</div>
						<div class="col-3">
							<small class="text-muted d-block">OUTROS BÔNUS</small>
							<strong class="h4 text-warning">+<?php echo esc_html( $p_var ); ?></strong>
						</div>
						<div class="col-3">
							<small class="text-muted d-block">TOTAL FINAL</small>
							<strong class="h4 text-success"><?php echo ( $p_total >= 0 ? '+' : '' ) . esc_html( $p_total ); ?></strong>
						</div>
					</div>

					<h6 class="text-warning border-bottom border-secondary pb-1">Auditoria de Bônus Variados nesta Perícia:</h6>
					<?php if ( ! empty( $p_breakdown ) ) : ?>
						<div class="table-responsive">
							<table class="table table-dark table-striped table-sm mb-0 small">
								<thead>
									<tr>
										<th>Origem</th>
										<th>Tipo de Bônus</th>
										<th>Valor Informado</th>
										<th>Status na Soma</th>
										<th>Explicação da Regra</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $p_breakdown as $b_item ) : ?>
										<tr>
											<td><strong><?php echo esc_html( $b_item['origem'] ); ?></strong></td>
											<td><?php echo esc_html( $b_item['tipo'] ); ?></td>
											<td>+<?php echo esc_html( $b_item['valor'] ); ?></td>
											<td>
												<?php if ( $b_item['status'] === 'applied' ) : ?>
													<span class="badge badge-success">✅ SOMADO (+<?php echo $b_item['efetivo']; ?>)</span>
												<?php else : ?>
													<span class="badge badge-danger">❌ IGNORADO (+0)</span>
												<?php endif; ?>
											</td>
											<td class="text-muted"><?php echo esc_html( $b_item['motivo'] ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else : ?>
						<em class="text-muted d-block my-2">Nenhum bônus variado adicional cadastrado para esta perícia.</em>
					<?php endif; ?>
				</div>
				<div class="text-right mt-3">
					<button type="button" class="btn btn-secondary btn-sm" onclick="rhaokarCloseModal('modal-pericia-<?php echo $p_idx; ?>')">Fechar</button>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php
endwhile;
get_footer();
