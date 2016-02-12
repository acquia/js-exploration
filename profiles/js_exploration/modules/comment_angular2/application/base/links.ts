
/**
 * This class provides the default links variables provided by Drupal.
 * NOTE: This is extended by other specialized classes.
 *
 * //////
 * WARNING: This is a temporary solution untill we find a better way to
 * get Drupal variables into Angular 2 context!
 * //////
 *
 *
 * @see /profiles/js_exploration/themes/baked/templates/links.html.twig
 */
export class BaseLinks {

	 ///// Available variables:

   /**
   * attributes: Attributes for the UL containing the list of links.
   * @type {any}
   */
   protected attributes: any;

   /**
   * links: Links to be output.
   * @type {any}
   */
   protected links: {
     //title: The link text.
     title: string;

     // href: The link URL. If omitted, the 'title' is shown as a plain text
     // item in the links list. If 'href' is supplied, the entire link is passed
     // to l() as its $options parameter.
     href: string;

     // attributes: (optional) HTML attributes for the anchor, or for the <span>
     // tag if no 'href' is supplied.
     attributs: any;

     // link_key: The link CSS class.
     link_key: string
   }[];

   /**
   * heading: (optional) A heading to precede the links.
   * If the heading is a string, it will be used as the text of the heading and
   * the level will default to 'h2'.
   * @type {string|any}
   */
   protected heading: string | {
    // text: The heading text.
    text: string;

    // level: The heading level (e.g. 'h2', 'h3').
    level: string;

    // attributes: (optional) A keyed list of attributes for the heading.
    attributes: any
   }[];

}
