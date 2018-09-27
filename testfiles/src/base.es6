'use strict';



class Base {
  constructor(scope, selector) {
    this.states = {
      loaded: 1,
      unloaded: 2
    };
    this.scope = scope;
    this.state = this.states.unloaded;
    this.dom = $(selector).parent().find('.content');;
  }

  onClick(data, cb) {
    if (window.global.id) {
      return this.get(data).done(cb);
    }
    alert('No character selected\n\nPlease choose an alt');
  };

  onLoaded(context, templateName) {
    console.log(context);
    this.state = this.states.loaded;

    // create html via template
    window.templates.prepareAndApply(
      './templates/{0}.hbs'.format(templateName),
      templateName,
      this.dom,
      { result: context }
    );
  };

  get(data) {
    data = { ...data, scope: this.scope, id: window.global.id };
    return $.post({
      url: 'Pullpage.php',
      data,
      dataType: 'json',
    });
  }

  resetState(){
    this.state = this.states.unloaded;
  }
}