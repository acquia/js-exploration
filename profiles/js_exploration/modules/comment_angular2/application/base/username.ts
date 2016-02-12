
/**
 * This class provides the default username variables provided by Drupal.
 * NOTE: This is extended by other specialized classes.
 *
 * //////
 * WARNING: This is a temporary solution untill we find a better way to
 * get Drupal variables into Angular 2 context!
 * //////
 *
 *
 * @see /profiles/js_exploration/themes/baked/templates/username.html.twig
 */
export class BaseUsername {

	 ///// Available variables:

 	 /**
 		* account: The full account information for the user.
 		* @type {string}
 		*/
    protected account: string;

 	 /**
 		* name: The user's name, sanitized.
 		* @type {string}
 		*/
    protected name: string;

 	 /**
 		* extra: Additional text to append to the user's name, sanitized.
 		* @type {string}
 		*/
    protected extra: string;

 	 /**
 		* link_path: The path or URL of the user's profile page, home page,
    * or other desired page to link to for more information about the user.
 		* @type {string}
 		*/
    protected link_path: string;

 	 /**
 		* link_options: Options to pass to the url() function's $options parameter if
    * linking the user's name to the user's page.
 		* @type {string}
 		*/
    protected link_options: any;

 	 /**
 		* attributes: HTML attributes for the containing element.
 		* @type {string}
 		*/
    protected attributes: any;

}
