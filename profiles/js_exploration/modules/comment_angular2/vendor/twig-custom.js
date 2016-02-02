console.log('twig-custom.js loaded');

/**
 * @Todo Implement this filter.
 */
Twig.extendFilter('clean_class', function(value) {
	return value;
});

/**
 * @Todo Implement this filter.
 */
Twig.extendFilter('without', function(value) {
  return value;
});

Twig.extendFunction('addClass', function(classes) {
  return 'class="' + classes.join(' ') + '"';
});

Twig.extendFunction('attach_library', function() {
  return '';
});
