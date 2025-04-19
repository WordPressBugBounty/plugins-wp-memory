<?php

namespace wp_memory\dashboard;

/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 09:07:38
 */
if (!defined("ABSPATH")) {
    die('We\'re sorry, but you can not directly access this file.');
}

// global $wpmemory_memory;

//$wpmemory_memory = wpmemory_check_memory();
$wpmemory_memory = wpmemory_check_memory();

// var_dump($wpmemory_memory);
//var_dump($wpmemory_memory['usage']);


//var_export($wpmemory_memory);
//display form

// Cálculo para WordPress Memory Usage
$ds = $wpmemory_memory["wp_limit"]; // Limite de memória do WordPress
$du = $wpmemory_memory["usage"]; // Uso atual

// Assumir que $ds e $du estão em megabytes e converter para bytes
$ds_bytes = $ds * 1024 * 1024; // Converter MB para bytes
$du_bytes = $du * 1024 * 1024; // Converter MB para bytes
$free = $ds_bytes - $du_bytes;
$perc_wp = number_format((100 * $du_bytes) / $ds_bytes, 0);
if ($perc_wp > 100) {
    $perc_wp = 100;
}

// Definir a cor para WordPress
$color_wp = "#029E26"; // Verde
if ($perc_wp > 50) {
    $color_wp = "#F7D301"; // Amarelo
}
if ($perc_wp > 70) {
    $color_wp = "#ff0000"; // Vermelho
}

// Cálculo para PHP Memory Usage
$php_memory_limit = ini_get('memory_limit');
if ($php_memory_limit == -1) {
    $perc_php = 0; // Sem limite de memória
    $color_php = "#029E26"; // Verde padrão
} else {
    $php_memory_limit_bytes = wp_memory_convertToBytes($php_memory_limit);
    $php_memory_usage = memory_get_usage();
    $perc_php = number_format((100 * $php_memory_usage) / $php_memory_limit_bytes, 0);
    if ($perc_php > 100) {
        $perc_php = 100;
    }
    // Definir a cor para PHP
    $color_php = "#029E26"; // Verde
    if ($perc_php > 50) {
        $color_php = "#F7D301"; // Amarelo
    }
    if ($perc_php > 70) {
        $color_php = "#ff0000"; // Vermelho
    }
}
echo '<div class="wrap-wpmemory ">' . "\n";
echo '<h2 class="title">PHP and WordPress Memory</h2>' . "\n";
echo '<p class="description">' .
    esc_attr__(
        "This plugin check For High Memory Usage and also include the result in the Tools => Site Health Page. Our Premium version can modify the necessary files to increase your WP Memory Limit and PHP Memory without risking accidental file modifications.",
        "wp-memory"
    );

// ----------------------------------------------------------------------------------





require_once "circle_memory.php";




// -----------------------------------------------------------------------------------




/////////////////////

echo "<br />";


echo '<div style="background-color: #0073aa; color: white; padding: 10px;">';

$mb = "MB";
echo "<b>";

// WordPress Memory Limit
echo "WordPress Memory Limit (*): " .
    esc_attr($wpmemory_memory["wp_limit"]) .
    esc_attr($mb) .
    "    |   ";

// Calcular porcentagem para WordPress
$perc_wp = number_format((100 * $wpmemory_memory["usage"]) / $wpmemory_memory["wp_limit"], 0);

// Your usage now (WordPress)
if ($perc_wp > 70) {
    echo '<span style="color:' . esc_attr($wpmemory_memory["color"]) . ';">';
}
echo esc_attr__("Your Memory usage now", "wp-memory") .
    ": " .
    esc_attr($wpmemory_memory["usage"]) .
    "MB    ";
if ($perc_wp > 70) {
    echo "</span>";
}

// PHP Memory Limit
echo "|   " .
    esc_attr__("PHP Memory", "wp-memory") .
    " (**): " .
    esc_attr($wpmemory_memory["limit"]) .
    "MB";


// PHP Memory Usage
/*
$php_memory_usage_bytes = memory_get_usage();
$php_memory_usage_mb = number_format($php_memory_usage_bytes / (1024 * 1024), 2); // Converter bytes para MB
$php_memory_limit = ini_get('memory_limit');
$php_memory_limit_bytes = $php_memory_limit === '-1' ? PHP_INT_MAX : convertToBytes($php_memory_limit);
$perc_php = $php_memory_limit === '-1' ? 0 : number_format((100 * $php_memory_usage_bytes) / $php_memory_limit_bytes, 0);
*/


//$memory = wpmemory_check_memory();

//var_dump($wpmemory_memory['usage']);

$php_memory_usage_mb = (string) $wpmemory_memory['usage'];

//var_dump($php_memory_usage_mb);

$php_memory_limit = $wpmemory_memory['limit'] . 'M'; // sufixo opcional
$php_memory_limit_bytes = wp_memory_convertToBytes($php_memory_limit);
$perc_php = $wpmemory_memory['percent'];




// Adicionar uso de memória do PHP
if ($perc_php > 70) {
    echo '<span style="color:#ff0000;">'; // Vermelho para >70%
}

/*
echo "    |   ";


echo esc_attr__("PHP usage now", "wp-memory") .
    ": " .
    esc_html($php_memory_usage_mb) .
    "MB    ";
    */
if ($perc_php > 70) {
    echo "</span>";
}






echo "</b>";
echo '</div>';

echo "</center>";
// echo "<hr>";




if ($perc_wp > 70 or $free < 30) {
    echo '<h2 style="color: red;">';
    echo esc_attr__(
        "Our plugin cannot function properly because your WordPress memory limit is too low. Your site will experience serious issues, even if you deactivate our plugin.",
        "wpmemory"
    );
    echo "</h2>";
}

echo '<div style="font-size: 14px;">';






echo "<br />";
echo '<center>';
echo '<p class="description">' .
    esc_attr__("Understanding Memory Usage in WordPress.", "wp-memory");

echo "</p>";

?>


<img src="<?php echo esc_attr(WPMEMORYIMAGES); ?>/demo-memory.gif" alt="Demo GIF Image" style="width: 50%;" />
</center>
<br>
<br>
<br>




<?php

// begin 2025

echo '<div class="wpmemory-usage-wrapper">';
echo '<div class="wpmemory-section">';
echo '<h1 class="wpmemory-title">';

echo esc_attr__(
    "To comprehend the entire process of memory usage in WordPress, you need to grasp three key points:",
    "wp-memory"
);
echo '</h1>';
echo '<ol class="wpmemory-list">';
echo '<li><strong>' . esc_attr__("Total server memory (HARDWARE MEMORY):", "wp-memory") . '</strong><br>';
echo esc_attr__(
    "Server memory refers to the total physical memory of your server and can only be increased through physical intervention, which should be requested from your hosting provider. Look the TAB Hardware Memory above.",
    "wp-memory"
) . '</li>';
echo '<li><strong>' . esc_attr__("PHP Memory**:", "wp-memory") . '</strong><br>';
echo esc_attr__(
    "PHP is the main programming language in which WordPress was built.PHP Memory is usually defined in the php.ini file (the default configuration file) located outside your WordPress environment. It must be lower than the Server memory (point 1).",
    "wp-memory"
) . '<br>';
echo '(**)';


echo esc_attr__("Instructions to increase PHP Memory", "wp-memory");




echo '<br>';

echo '<a href="https://wpmemory.com/php-memory-limit/" class="button button-primary">' .
    esc_attr__("Click Here to learn more", "wp-memory") . '</a>';



echo '</li>';


echo '<li><strong>' . esc_attr__("WordPress Memory Limit*:", "wp-memory") . '</strong><br>';
echo esc_attr__(
    "WP Memory Limit is the maximum limit WordPress allows for each user and script of your site.",
    "wp-memory"
) . ' ';
if ($wpmemory_memory["wp_limit"] > $wpmemory_memory["limit"]) {
    echo '<span style="color: red;">' . esc_attr__(
        "It must be lower than the PHP memory (point 2).",
        "wp-memory"
    ) . '</span>';
} else {
    echo esc_attr__(
        "It must be lower than the PHP memory (point 2).",
        "wp-memory"
    );
}
echo '<br>';
echo '<div class="wpmemory-note">';
echo esc_attr__("Note: a higher limit is not always better.", "wp-memory") . ' ';
echo esc_attr__(
    "The WP_MEMORY_LIMIT sets the maximum memory a WordPress instance can use. Setting it too high can cause resource exhaustion on servers with multiple instances, leading to instability and inability to handle requests from users or bots.",
    "wp-memory"
) . ' ';
echo esc_attr__(
    "Maintaining memory usage at an appropriate level requires ongoing monitoring.",
    "wp-memory"
);
echo '</div>';



echo '(*)';
echo esc_attr__("Instructions to increase  WP Memory Limit", "wp-memory");




echo '<br>';

echo '<a href="https://wpmemory.com/fix-low-memory-limit/" class="button button-primary">' .
    esc_attr__("Click Here to learn more", "wp-memory") . '</a>';










echo '</ol>';
echo '</div>';

echo '<div class="wpmemory-section">';
echo '<h2>' . esc_attr__(
    "How to Tell if Your Site Needs a Shot of more Memory",
    "wp-memory"
) . '</h2>';
echo '<p>' . esc_attr__("If you got", "wp-memory") .
    esc_attr__(
        "Fatal error: Allowed memory size of xxx bytes exhausted",
        "wp-memory"
    ) . esc_attr__("or", "wp-memory") . ' ' .
    esc_attr__(
        "if your site is behaving slowly, or pages fail to load, you get random white screens of death or 500 internal server error you may need more memory. Several things consume memory, such as WordPress itself, the plugins installed, the theme you're using and the site content.",
        "wp-memory"
    ) . '</p>';
echo '<p>' . esc_attr__(
    "Basically, the more content and features you add to your site, the bigger your memory limit has to be. if you're only running a small site with basic functions without a Page Builder and Theme Options (for example the native Twenty twenty) maybe you don’t need make memory adjustments. However, once you use a Premium WordPress theme and you start encountering unexpected issues, it may be time to adjust your memory limit to meet the standards for a modern WordPress installation.",
    "wp-memory"
) . '</p>';
echo '<p>' . esc_attr__(
    "Increase the WP Memory Limit is a standard practice in WordPress and you find instructions also in the official WordPress documentation (Increasing memory allocated to PHP).",
    "wp-memory"
) . '</p>';
echo '<p>';
echo '<a href="https://wpmemory.com/" class="wpmemory-button-link">' .
    esc_attr__("Plugin Site", "wp-memory") . '</a> ';
echo '<a href="https://wpmemory.com/help/" class="wpmemory-button-link">' .
    esc_attr__("Online Guide", "wp-memory") . '</a> ';
echo '<a href="https://billminozzi.com/dove/" class="wpmemory-button-link">' .
    esc_attr__("Support Page", "wp-memory") . '</a> ';
echo '<a href="https://siterightaway.net/troubleshooting/" class="wpmemory-button-link">' .
    esc_attr__("Troubleshooting Page", "wp-memory") . '</a>';
echo '</p>';
echo '</div>';
echo '</div>';
// end 2025


echo "<br>";
echo "<br>";
echo "</div>";

echo "</div>";

// Função para converter memory_limit para bytes
function wp_memory_convertToBytes($memoryLimit)
{
    $memoryLimit = trim($memoryLimit);
    $last = strtolower($memoryLimit[strlen($memoryLimit) - 1]);
    $memoryLimit = (int) $memoryLimit;
    switch ($last) {
        case 'g':
            $memoryLimit *= 1024 * 1024 * 1024;
            break;
        case 'm':
            $memoryLimit *= 1024 * 1024;
            break;
        case 'k':
            $memoryLimit *= 1024;
            break;
    }
    return $memoryLimit;
}
