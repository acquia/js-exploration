import {Pipe, PipeTransform} from 'angular2/core';

@Pipe({
  name: 't'
})
export class TwigTranslatePipe implements PipeTransform {
  transform(value: string, args: string[]): any {
    return drupalTranslations.strings[""][value];
  }
}
