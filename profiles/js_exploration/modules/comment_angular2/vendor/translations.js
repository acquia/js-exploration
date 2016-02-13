// Create a fake Drupal object to allow Drupal load translations for Angular2 TwigTranslatePipe
var Drupal = {
  t: function(){}
};
Drupal.t('Delete');
Drupal.t('Edit');
Drupal.t('Reply');
Drupal.t('Translate');
Drupal.t('Comment');
Drupal.t('@count days');
Drupal.t('Save');
Drupal.t('Preview');
