import {COMMENTS} from './comments-mock'
import {Injectable, Location} from 'angular2/core';
import {Http, HTTP_PROVIDERS} from 'angular2/http';

@Injectable()
export class CommentsService {

	constructor(http:Http){
	this.http=http;
	}

	getComments(nodeID:number) {
		
		var url: string = `/node/${nodeID}/comments?_format=json`
		return this.http.get(url)	

	}
}