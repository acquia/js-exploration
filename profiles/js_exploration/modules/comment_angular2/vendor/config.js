System.config({
  // set our baseURL reference path
  baseURL: '/',
  //use typescript for compilation
  transpiler: 'typescript',
  //typescript compiler options
  typescriptOptions: {
    emitDecoratorMetadata: true
  },
  
  defaultJSExtensions: false,

  //map tells the System loader where to look for things
  map: {
    'application': '/profiles/js_exploration/modules/comment_angular2/application',
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