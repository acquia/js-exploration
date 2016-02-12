
/**
 * This class provides the default (common) comments variables provided by Drupal.
 * NOTE: This is extended by other specialized classes.
 *
 * //////
 * WARNING: This is a temporary solution untill we find a better way to
 * get Drupal variables into Angular 2 context!
 * //////
 *
 *
 * @see /profiles/js_exploration/themes/baked/templates/comment.html.twig
 */
export class BaseComment {

	 ///// Available variables:

	 /**
		* author: Comment author. Can be a link or plain text.
		* @type {string}
		*/
		protected author: string;

	 /**
	  * content: The content- related items for the comment display.
	  * Use {{ content }} to print them all, or print a subset such as
	 	* {{ content.field_example }}. Use the following code to temporarily suppress
		* the printing of a given child element:
	 	*   @code
	 	*   {{ content | without('field_example') }}
	 	*   @endcode
	  * @type {Object}
	  */
		protected content: any;

		/**
		 * created: Formatted date and time for when the comment was created.
		 * Preprocess functions can reformat it by calling format_date() with the
		 * desired parameters on the 'comment.created' variable.
		 * @type {string}
		 */
		protected created: string;

		/**
		 * changed: Formatted date and time for when the comment was last changed.
		 * Preprocess functions can reformat it by calling format_date() with the
		 * desired parameters on the 'comment.changed' variable.
		 * @type {string}
		 */
		protected changed: string;

		/**
		 * permalink: Comment permalink.
		 * @type {string}
		 */
		protected permalink: string;

		/**
		 * submitted: Submission information created from author and created
		 * during template_preprocess_comment().
		 * @type {string}
		 */
		protected submitted: string;

		/**
		 * user_picture: The comment author's profile picture.
		 * @type {string}
		 */
		protected user_picture: string;

		/**
		 * status: Comment status.Possible values are:
 		 *   unpublished, published, or preview.
		 * @type {string}
		 */
		protected status: string;

		/**
		 * title: Comment title, linked to the comment.
		 * @type {string}
		 */
		protected title: string;

		/**
		 * attributes: HTML attributes for the containing element.
		 *   The attributes.class may contain one or more of the following classes:
		 *   - comment: The current template type; e.g., 'theming hook'.
		 *   - by-anonymous: Comment by an unregistered user.
		 *   - by-{entity-type}-author: Comment by the author of the parent entity,
		 *     eg. by-node-author.
		 *   - preview: When previewing a new or edited comment.
		 *   The following applies only to viewers who are registered users:
		 *   - unpublished: An unpublished comment visible only to administrators.
 		 *
		 * @type {Object}
		 */
		protected attributes: any = {
				'class': '',
				addClass: (args) => {
					args = args.join ? args : [args];
					return ` class="${args.join(' ')}" `;
				},
				toString: () => ''
		};

		/**
		 * title_prefix: Additional output populated by modules, intended to be
		 * displayed in front of the main title tag that appears in the template.
		 * @type {string}
		 */
		protected title_prefix: string;

		/**
		 * title_suffix: Additional output populated by modules, intended to be
		 * displayed after the main title tag that appears in the template.
		 * @type {string}
		 */
		protected title_suffix: string;

		/**
		 * title_attributes: Same as attributes, except applied to the main title
		 * tag that appears in the template.
		 * @type {Object}
		 */
		protected title_attributes: any = {
			'class': '',
			addClass: (args) => {
				args = args.join ? args : [args];
				return ` class="${args.join(' ')}" `;
			},
			toString: () => ''
		};

		/**
		 * content_attributes: List of classes for the styling of the comment content.
		 * @type {string[]}
		 */
		protected content_attributes: any = {
			'class': '',
			addClass: (args) => {
				args = args.join ? args : [args];
				return ` class="${args.join(' ')}" `;
			},
			toString: () => ''
		};

		/**
		 * threaded: A flag indicating whether the comments are threaded or not.
		 * @type {boolean}
		 */
		protected threaded: boolean;

	  ///// These variables are provided to give context about the parent comment (if any).

		/**
		 * comment_parent: Full parent comment entity (if any).
		 * @type {string}
		 */
		protected comment_parent: string;

		/**
		 * parent_author: Equivalent to author for the parent comment.
		 * @type {string}
		 */
		protected parent_author: string;

		/**
		 * parent_created: Equivalent to created for the parent comment.
		 * @type {string}
		 */
		protected parent_created: string;

		/**
		 * parent_changed: Equivalent to changed for the parent comment.
		 * @type {string}
		 */
		protected parent_changed: string;

		/**
		 * parent_title: Equivalent to title for the parent comment.
		 * @type {string}
		 */
		protected parent_title: string;

		/**
		 * parent_permalink: Equivalent to permalink for the parent comment.
		 * @type {string}
		 */
		protected parent_permalink: string;

		/**
		 * parent: A text string of parent comment submission information created from
		 * 'parent_author' and 'parent_created' during template_preprocess_comment().
     * This information is presented to help screen readers follow lengthy
		 * discussion threads.You can hide this from sighted users using the class visually - hidden.
		 * @type {string}
		 */
		protected parent: string;

		//// These two variables are provided for context:

		/**
		 * comment: Full comment object.
		 * @type {string}
		 */
		protected comment: any = {
			owner: {
				annonymous: true
			}
		};

		/**
		 * entity: Entity the comments are attached to.
		 * @type {string}
		 */
		protected entity: any = {
			getOwnerId: () => 1,
			getEntityTypeId: () => 1
		};
		protected commented_entity: any = {
			getOwnerId: () => 1,
			getEntityTypeId: () => 1
		};

}
