<?php

namespace wp_memory\dashboard;

if (!defined("ABSPATH")) {
    die('We\'re sorry, but you can not directly access this file.');
}






/*
// Informações de depuração
echo '<div style="text-align: left; margin-bottom: 20px; background: #f0f0f0; padding: 10px;">';
echo '<h3>Depuração:</h3>';
echo '<p><strong>WordPress Memory:</strong></p>';
echo '<p>Limite (original): ' . esc_html($ds) . ' (assumido como MB)</p>';
echo '<p>Limite (bytes): ' . esc_html($ds_bytes) . ' bytes</p>';
echo '<p>Uso (original): ' . esc_html($du) . ' (assumido como MB)</p>';
echo '<p>Uso (bytes): ' . esc_html($du_bytes) . ' bytes</p>';
echo '<p>Porcentagem: ' . esc_html($perc_wp) . '%</p>';
echo '<p>Cor: ' . esc_html($color_wp) . '</p>';
echo '<p>ID do contêiner: indicatorContainerWp</p>';
echo '<p><strong>PHP Memory:</strong></p>';
echo '<p>Limite: ' . esc_html($php_memory_limit) . ' (' . ($php_memory_limit == -1 ? 'Sem limite' : esc_html($php_memory_limit_bytes) . ' bytes') . ')</p>';
echo '<p>Uso: ' . esc_html($php_memory_usage ?? 'N/A') . ' bytes</p>';
echo '<p>Porcentagem: ' . esc_html($perc_php ?? 'N/A') . '%</p>';
echo '<p>Cor: ' . esc_html($color_php ?? 'N/A') . '</p>';
echo '<p>ID do contêiner: indicatorContainerPhp</p>';
echo '</div>';
*/

echo '<style>';
echo '  .prg-cont.canvas { width: 125px !important; }';
echo '  #indicatorContainerDefault { display: none !important; }'; // Oculta qualquer contêiner indesejado
echo '</style>';

echo '<div style="display: flex; justify-content: space-between; width: 100%;">';
echo '<div style="width: 50%; text-align: center;">';
echo '<h2>' . esc_attr__('WordPress Memory Usage', 'wp-memory') . '</h2>';
echo '<div class="prg-cont rad-prg" id="indicatorContainerWp" style="width:125px; height:125px; margin: 0 auto;"></div>';
//echo '<div style="color: red; text-align: center; margin-top: 10px;">Debug: Contêiner indicatorContainerWp com valor ' . esc_html($perc_wp) . '%</div>';
echo '</div>';
echo '<div style="width: 50%; text-align: center;">';
echo '<h2>' . esc_attr__('PHP Memory Usage', 'wp-memory') . '</h2>';
echo '<div class="prg-cont rad-prg" id="indicatorContainerPhp" style="width:125px; height:125px; margin: 0 auto;"></div>';
//echo '<div style="color: red; text-align: center; margin-top: 10px;">Debug: Contêiner indicatorContainerPhp com valor ' . esc_html($perc_php) . '%</div>';
echo '</div>';
echo '</div>';

// Inicializar ambos os gráficos em um único script
echo '<script>';
echo 'jQuery(document).ready(function($) {';
echo '  console.log("Iniciando inicialização dos gráficos");';

// Verificar se indicatorContainerDefault existe
echo '  if ($("#indicatorContainerDefault").length) {';
echo '    console.warn("Aviso: Contêiner indicatorContainerDefault detectado no DOM!");';
echo '    $("#indicatorContainerDefault").html("<p style=\"color: red;\">Erro: Contêiner inesperado (indicatorContainerDefault).</p>");';
echo '  }';

// Verificar existência dos contêineres esperados
echo '  console.log("Verificando #indicatorContainerWp: ", $("#indicatorContainerWp").length);';
echo '  console.log("Verificando #indicatorContainerPhp: ", $("#indicatorContainerPhp").length);';

echo '  if (typeof $.fn.radialIndicator === "undefined") {';
echo '    console.error("radialIndicator não está definido. Verifique se a biblioteca está carregada.");';
echo '    $("#indicatorContainerWp, #indicatorContainerPhp").html("<p style=\"color: red;\">Erro: Gráfico não carregado (biblioteca ausente).</p>");';
echo '    return;';
echo '  }';

// Inicializar o gráfico do WordPress
echo '  try {';
echo '    var wpIndicator = $("#indicatorContainerWp").radialIndicator({';
echo '      barWidth: 10,';
echo '      initValue: ' . esc_js($perc_wp) . ',';
echo '      roundCorner: true,';
echo '      percentage: true,';
echo '      radius: 50,';
echo '      barColor: "' . esc_js($color_wp) . '"';
echo '    }).data("radialIndicator");';
echo '    console.log("Gráfico inicializado com sucesso para indicatorContainerWp");';
echo '  } catch (e) {';
echo '    console.error("Erro ao inicializar radialIndicator para indicatorContainerWp: " + e.message);';
echo '    $("#indicatorContainerWp").html("<p style=\"color: red;\">Erro: " + e.message + "</p>");';
echo '  }';

// Inicializar o gráfico do PHP com um atraso
echo '  setTimeout(function() {';
echo '    try {';
echo '      var phpIndicator = $("#indicatorContainerPhp").radialIndicator({';
echo '        barWidth: 10,';
echo '        initValue: ' . esc_js($perc_php) . ',';
echo '        roundCorner: true,';
echo '        percentage: true,';
echo '        radius: 50,';
echo '        barColor: "' . esc_js($color_php) . '"';
echo '      }).data("radialIndicator");';
echo '      console.log("Gráfico inicializado com sucesso para indicatorContainerPhp");';
echo '    } catch (e) {';
echo '      console.error("Erro ao inicializar radialIndicator para indicatorContainerPhp: " + e.message);';
echo '      $("#indicatorContainerPhp").html("<p style=\"color: red;\">Erro: " + e.message + "</p>");';
echo '    }';
echo '  }, 1000);';
echo '});';
echo '</script>';
