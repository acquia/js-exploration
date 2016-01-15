<?php

/**
 * Override or insert variables into the maintenance page template.
 */
function ember_preprocess_maintenance_page(&$vars) {
  // While markup for normal pages is split into page.tpl.php and html.tpl.php,
  // the markup for the maintenance page is all in the single
  // maintenance-page.tpl.php template. So, to have what's done in
  // ember_preprocess_html() also happen on the maintenance page, it has to be
  // called here.
  ember_preprocess_html($vars);
}
/**
 * Implements hook_html_head_alter().
 */
function ember_html_head_alter(&$head_elements) {
  $head_elements['viewport'] = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width,initial-scale=1'),
  );
}

/**
 * Override or insert variables into the html template.
 */
function ember_preprocess_html(&$vars) {
  // Add conditional CSS for IE8 and below.
  drupal_add_css(path_to_theme() . '/css/ie/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'weight' => 999, 'preprocess' => FALSE));
  // Add conditional CSS for IE7 and below.
  drupal_add_css(path_to_theme() . '/css/ie/ie7.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'weight' => 999, 'preprocess' => FALSE));
  // Add conditional CSS for IE6.
  drupal_add_css(path_to_theme() . '/css/ie/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 6', '!IE' => FALSE), 'weight' => 999, 'preprocess' => FALSE));
}

/**
 * Display the list of available node types for node creation.
 */
function ember_node_add_list($variables) {
  $content = $variables['content'];
  $output = '';
  if ($content) {
    $output = '<ul class="admin-list">';
    foreach ($content as $type) {
      $output .= '<li class="clearfix">';
      $content = '<span class="label">' . check_plain($type->name) . '</span>';
      $content .= '<div class="description">' . filter_xss_admin($type->description) . '</div>';
      $options['html'] = TRUE;
      $output .= l($content, 'node/add/' . $type->type, $options);
      $output .= '</li>';
    }
    $output .= '</ul>';
  }
  else {
    $output = '<p>' . t('You have not created any content types yet. Go to the <a href="@create-content">content type creation page</a> to add a new content type.', array('@create-content' => url('admin/structure/types/add'))) . '</p>';
  }
  return $output;
}

/**
 * Overrides theme_admin_block_content().
 *
 * Uses an unordered list markup in both compact and extended mode.
 */
function ember_admin_block_content($variables) {
  $content = $variables['content'];
  $output = '';
  if (!empty($content)) {
    $output = system_admin_compact_mode() ? '<ul class="admin-list compact">' : '<ul class="admin-list">';
    foreach ($content as $item) {
      $output .= '<li>';
      $content = '<span class="label">' . filter_xss_admin($item['title']) . '</span>';
      $options = $item['localized_options'];
      $options['html'] = TRUE;
      if (isset($item['description']) && !system_admin_compact_mode()) {
        $content .= '<div class="description">' . filter_xss_admin($item['description']) . '</div>';
      }
      $output .= l($content, $item['href'], $options);
      $output .= '</li>';
    }
    $output .= '</ul>';
  }
  return $output;
}

/**
 * Implements hook_css_alter().
 */
function ember_css_alter(&$css) {
  // Use ember's vertical tabs style instead of the default one.
  if (isset($css['misc/vertical-tabs.css'])) {
    $css['misc/vertical-tabs.css']['data'] = drupal_get_path('theme', 'ember') . '/css/vertical-tabs.css';
  }
  if (isset($css['misc/vertical-tabs-rtl.css'])) {
    $css['misc/vertical-tabs-rtl.css']['data'] = drupal_get_path('theme', 'ember') . '/css/vertical-tabs-rtl.css';
  }
  // Use ember's jQuery UI theme style instead of the default one.
  if (isset($css['misc/ui/jquery.ui.theme.css'])) {
    $css['misc/ui/jquery.ui.theme.css']['data'] = drupal_get_path('theme', 'ember') . '/css/jquery.ui.theme.css';
  }
}
