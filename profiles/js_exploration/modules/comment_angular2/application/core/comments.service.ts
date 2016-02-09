import {COMMENTS} from './comments-mock'
import {Injectable} from 'angular2/core';
import {Http, HTTP_PROVIDERS} from 'angular2/http';

@Injectable()
export class CommentsService {

	constructor(http:Http){
this.http=http;
//this.myComments=myComments
	}

	getComments(nodeID:number) {

		return this.http.get(`http://js-exploration.dd:8083/node/${nodeID}/comments?_format=json`)
		


			// .subscribe(result=> {
			// 	 this.comments = result.json()
			// 	console.log(this.comments[0].comment_body[0].value)
			// 	console.log(`http://js-exploration.dd:8083/node/${nodeID}/comments?_format=json`)
			// return this.comments
			// )}



//return COMMENTS

	}
}