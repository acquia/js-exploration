//Need help how the angular-comments-block DOM tag can be placed in the template

var x = document.querySelector('#block-baked-content > article > div.node__content > section')
x.innerHTML = '<angular-comments-block> angular come on</angular-comments-block>'



import {Component, Pipe} from 'angular2/core';
import {AsyncPipe} from "angular2/pipes";
import {CommentItem} from'./comment-item';
import {CommentsService} from '../core/comments.service'
import {Http, HTTP_PROVIDERS} from 'angular2/http';
import {FieldComment} from './field-comment';
import {CommonCommentFields} './common-comment-fields';
import {ViewEncapsulation} from 'angular2/core';


@Component({
	selector: 'angular-comments-block',
	providers: [CommentsService, HTTP_PROVIDERS],
	directives:[CommentItem],
	template: `
		<div class="angular-comment-block">
			<h2> Comments -via Angular</h2>
			<comment-item  *ngFor='#comment of comments '[comment]="comment"></comment-item>
		</div>
		<hr/>
		<angular2-comment-field></angular2-comment-field>
	`,
	styles: [`
		.angular-comment-block { border:thin solid #ccc; background:#eaeaea; }
	`],
	encapsulation: ViewEncapsulation.Emulated,
	directives: [FieldComment, CommentItem]
})
export class CommentsBlock  {
	public comments: Object;
	constructor(_commentsService: CommentsService) {

		this._commentsService = _commentsService
	}

	ngOnInit(){
		this.getComments();
	}

	getComments(){

	// hardcoding the page node id, will make it dynamic later
	 this._commentsService.getComments(1)
	 	.subscribe(data => { this.comments=data})
	}
}