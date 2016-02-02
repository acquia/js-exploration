System.config({
  // set our baseURL reference path
  baseURL: '/profiles/js_exploration/modules/comment_angular2',
  //use typescript for compilation
  transpiler: 'typescript',
  //typescript compiler options
  typescriptOptions: {
    emitDecoratorMetadata: true
  },
  
  defaultJSExtensions: false,

  //map tells the System loader where to look for things
  map: {
    'angular2/*': 'angular2/*.ts'
  },
  // packages defines our app package
  packages: {
    'application': {
      main: 'app.ts',
      defaultExtension: 'ts'
    }
  }
});