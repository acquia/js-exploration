var x = document.querySelector('#block-baked-content > article > div.node__content > section')
x.innerHTML = '<angular-comments-block> angular come on</angular-comments-block>'

console.log(x);

import {Component} from 'angular2/core';
import {CommentItem} from'./comment-item';
import {CommentsService} from '../core/comments.service'
import {Http, HTTP_PROVIDERS} from 'angular2/http';
import {FieldComment} from './field-comment';
import {CommonCommentFields} './common-comment-fields';

@Component({
	// selector: '#block-baked-content > article > div.node__content > section',
	selector: 'angular-comments-block',
	providers: [CommentsService, HTTP_PROVIDERS],
	directives:[CommentItem],
	template: `
	<p>Total Comments so far </p>
<hr/>
<div *ngFor="#comment of comments">
<comment-item></comment-item>
</div>
<hr/>
<angular2-comment-field></angular2-comment-field>

`
	,
	styles: [`
		section { background: yellow; }
	`],
	directives: [FieldComment, CommentItem]
})
export class CommentsBlock  {
public comments:Object=[]
	constructor(_commentsService: CommentsService) {

		this._commentsService = _commentsService
	}


ngOnInit(){
	this.getComments();
//console.log(comments)	
}

getComments(){
	this._commentsService.getComments(2).subscribe(
data =>{this.comments = data},
// the second argument is a function which runs on error
err => console.error(err),
// the third argument is a function which runs on completion
() => console.log(this.comments)
		)

}

//		this.comments = _commentsService.getComments(2)
//console.log(this.comments)
	

	
}
