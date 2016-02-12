import {Component} from 'angular2/core';
import {BaseField} from '../base/field';

@Component({
  selector: 'angular2-content',

  // EXCEPTION: TwigException: Cannot extend an inline template.
  // templateUrl: '/profiles/js_exploration/themes/baked/templates/field--text-with-summary.html.twig',

  templateUrl: '/profiles/js_exploration/themes/baked/templates/field.html.twig'
})
export class Content extends BaseField {

  constructor() {
    super();

    this.field_name = 'comment-body';
    this.field_type = 'text-long';
    this.label_display = 'hidden';
    this.label_hidden = false;
    this.attributes = {
      addClass: (cls) => ` class="${ cls.join(' ') }" `,
      toString: () => [
        'property="schema:text"',
        'data-quickedit-field-id="comment/2/comment_body/en/full"',
        'class="clearfix text-formatted field field__item"'
      ].join(' ')
    };
    this.multiple = true;
    this.items = [{
      item: {
        attributes: {
          addClass: (cls) => ` class="${cls}" `
        }
      }
      content: `
      <p>Viral organic ugh pickled squid authentic.
      Try-hard selfies cornhole hammock flannel, dreamcatcher chillwave yuccie before they sold out.
      Disrupt mlkshk food truck four loko kinfolk.
      Scenester VHS pinterest cardigan meh.
      Selvage lumbersexual pop-up, food truck affogato four loko fanny pack four dollar toast leggings drinking vinegar gastropub master cleanse +1 skateboard.
      Kale chips beard thundercats austin. Health goth single-origin coffee kale chips, next level sartorial scenester jean shorts vice four loko squid ugh tattooed pork belly.</p>
      `
    }]

  }
}
