<?php

/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:38:04
 */
if (!defined('ABSPATH')) {
  die('We\'re sorry, but you can not directly access this file.');
}
?>

<div id="wp-memory-logo">

  <img src="<?php echo esc_url(WPMEMORYIMAGES); ?>/logo.png" width="200">
</div>
<?php
if (isset($_GET['tab']))
  $active_tab = sanitize_text_field($_GET['tab']);
else
  $active_tab = 'dashboard';

?>
<h2 class="nav-tab-wrapper">
  <a href="tools.php?page=wp_memory_admin_page&tab=dashboard" class="nav-tab">Dashboard</a>
  <a href="tools.php?page=wp_memory_admin_page&tab=hardware" class="nav-tab">Hardware Memory</a>
  <!--  <a href="tools.php?page=wp_memory_admin_page&tab=settings" class="nav-tab">Settings</a> -->
  <a href="tools.php?page=wp_memory_admin_page&tab=log" class="nav-tab">Log</a>
  <a href="tools.php?page=wp_memory_admin_page&tab=errors" class="nav-tab">Server Errors</a>

  <a href="tools.php?page=wp_memory_admin_page&tab=tools" class="nav-tab">More Tools</a>

  <?php
  echo '<a href="tools.php?page=wp_memory_admin_page&tab=premium" class="nav-tab" style="color:red;">Premium</a>';
  ?>


</h2>
<?php

echo '<div id="wpmemory-dashboard-wrap">';
echo '<div id="wpmemory-dashboard-left">';

if ($active_tab == 'phpmemory') {
  require_once(WPMEMORYPATH . 'dashboard/memory-fix-php.php');
} elseif ($active_tab == 'wpmemory') {
  require_once(esc_url(WPMEMORYPATH) . 'dashboard/memory-fix.php');
} elseif ($active_tab == 'premium') {
  require_once(WPMEMORYPATH . 'dashboard/premium.php');
} elseif ($active_tab == 'tools') {
  require_once(WPMEMORYPATH . 'dashboard/more-tools.php');
} elseif ($active_tab == 'errors') {
  require_once(WPMEMORYPATH . 'dashboard/errors.php');
} elseif ($active_tab == 'settings') {
  // require_once(WPMEMORYPATH . 'dashboard/settings.php');
} elseif ($active_tab == 'log') {
  require_once(WPMEMORYPATH . 'dashboard/log.php');
} elseif ($active_tab == 'hardware') {
  require_once(WPMEMORYPATH . 'dashboard/hardware.php');
} elseif ($active_tab == 'wizard') {
  require_once(WPMEMORYPATH . 'dashboard/wizard/wizard.php');
} else {
  require_once(WPMEMORYPATH . 'dashboard/dashboard.php');
}

echo '</div> <!-- "wpmemory-dashboard-left"> -->';
echo '<div id="wpmemory-dashboard-right">';
echo '<div id="wpmemory-containerright-dashboard">';
require_once(WPMEMORYPATH . 'dashboard/mybanners.php');
echo '</div>';
echo '</div> <!-- "wpmemory-dashboard-right"> -->';
echo '</div> <!-- "wpmemory-dashboard-wrap"> -->';



////////////////////////////////////

function wpmemory_isShellEnabled()
{
  if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(', ', ini_get('disable_functions'))))) {
    $returnVal = shell_exec('cat /proc/cpuinfo');
    if (!empty($returnVal)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function wpmemory_format_filesize_kB($kiloBytes)
{
  if (($kiloBytes / pow(1024, 4)) > 1) {
    return number_format_i18n(($kiloBytes / pow(1024, 4)), 0) . ' ' . __('PB', 'wp-memory');
  } elseif (($kiloBytes / pow(1024, 3)) > 1) {
    return number_format_i18n(($kiloBytes / pow(1024, 3)), 0) . ' ' . __('TB', 'wp-memory');
  } elseif (($kiloBytes / pow(1024, 2)) > 1) {
    return number_format_i18n(($kiloBytes / pow(1024, 2)), 0) . ' ' . __('GB', 'wp-memory');
  } elseif (($kiloBytes / 1024) > 1) {
    return number_format_i18n($kiloBytes / 1024, 0) . ' ' . __('MB', 'wp-memory');
  } elseif ($kiloBytes >= 0) {
    return number_format_i18n($kiloBytes / 1, 0) . ' ' . __('KB', 'wp-memory');
  } else {
    return __('Unknown', 'wp-memory');
  }
}

?>