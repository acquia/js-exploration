import {Component, ViewChild} from 'angular2/core';
import {FormAction} from './form-action';

@Component({
	selector: 'angular2-form-children',
	templateUrl: '/profiles/js_exploration/themes/baked/templates/container.html.twig',
  directives: [FormAction]
})
export class FormChildren {

  @ViewChild('textArea') textArea;

	constructor() {
		this.attributes = [
      '',
      'class="field--type-text-long field--name-comment-body field--widget-text-textarea js-form-wrapper form-wrapper"',
      'data-drupal-selector="edit-comment-body-wrapper"',
      'id="edit-comment-body-wrapper"'
      ''
    ].join(' ');
		this.children = `
    <div class="js-form-item form-item js-form-type-textarea form-type-textarea js-form-item-comment-body-0-value form-item-comment-body-0-value">
      <label for="edit-comment-body-0-value" class="js-form-required form-required">Comment</label>
      <div class="form-textarea-wrapper">
        <textarea #textArea class="js-text-full text-full form-textarea required resize-vertical" data-drupal-selector="edit-comment-body-0-value" id="edit-comment-body-0-value" name="comment_body[0][value]" rows="5" cols="60" placeholder="" aria-required="true" data-editor-active-text-format="basic_html"></textarea>
      </div>
    </div>
    <angular2-form-action></angular2-form-action>
    `;
    this.has_parent = true;
	}

  ngAfterViewInit() {
    Drupal.editorAttach(this.textArea.nativeElement, drupalSettings.editor.formats.basic_html);
  }

}
