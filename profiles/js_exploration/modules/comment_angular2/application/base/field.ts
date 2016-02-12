
/**
 * This class provides the default field variables provided by Drupal.
 * NOTE: This is extended by other specialized classes.
 *
 * //////
 * WARNING: This is a temporary solution untill we find a better way to
 * get Drupal variables into Angular 2 context!
 * //////
 *
 *
 * @see /profiles/js_exploration/themes/baked/templates/field.html.twig
 */
export class BaseField {

	 ///// Available variables:

   /**
   * attributes: HTML attributes for the containing element.
   * @type {any}
   */
   protected attributes: any;

   /**
   * label_hidden: Whether to show the field label or not.
   * @type {boolean}
   */
   protected label_hidden: boolean;

   /**
   * title_attributes: HTML attributes for the title.
   * @type {any}
   */
   protected title_attributes: any;

   /**
   * label: The label for the field.
   * @type {string}
   */
   protected label: string;

   /**
   * multiple: TRUE if a field can contain multiple items.
   * @type {boolean}
   */
   protected multiple: boolean;

   /**
   * items: List of all the field items. Each item contains:
   *   - attributes: List of HTML attributes for each item.
   *   - content: The field item's content.
   * @type {any}
   */
   protected items: {
     attributes: any;
     content: any;
   }[];

   /**
   * entity_type: The entity type to which the field belongs.
   * @type {any}
   */
   protected entity_type: any;

   /**
   * field_name: The name of the field.
   * @type {string}
   */
   protected field_name: string;

   /**
   * field_type: The type of the field.
   * @type {string}
   */
   protected field_type: string;

   /**
   * label_display: The display settings for the label.
   * @type {string}
   */
   protected label_display: string;

}
