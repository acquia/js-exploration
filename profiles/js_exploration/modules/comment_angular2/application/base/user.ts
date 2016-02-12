
/**
 * This class provides the default user variables provided by Drupal.
 * NOTE: This is extended by other specialized classes.
 *
 * //////
 * WARNING: This is a temporary solution untill we find a better way to
 * get Drupal variables into Angular 2 context!
 * //////
 *
 *
 * @see /profiles/js_exploration/themes/baked/templates/user.html.twig
 */
export class BaseUser {

	 ///// Available variables:

 	 /**
 		* content: A list of content items. Use 'content' to print all content, or
    * print a subset such as 'content.field_example'. Fields attached to a user
    * such as 'user_picture' are available as 'content.user_picture'.
 		* @type {string}
 		*/
    protected content: {
      user_picture: string
    };

 	 /**
 		* attributes: HTML attributes for the container element.
 		* @type {string}
 		*/
    protected attributes: string;

 	 /**
 		* user: A Drupal User entity.
 		* @type {string}
 		*/
    protected user: any;

}
