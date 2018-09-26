blueprints = {};

blueprints.click = function() {
  if (global.id) {
    var scope = 'blueprints';
    $.post({
      url: 'Pullpage.php',
      data: { id: global.id, scope: scope },
      dataType: 'json',
      success: blueprints.onLoad,
    });
    return;
  }
  alert('No character selected\n\nPlease choose an alt');
};

blueprints.onLoad = function(result) {
  console.log(result);

  blueprints.bpDom = $('#BLUEPINTS')
    .parent()
    .find('.content');

  // create html via template
  templates.prepareAndApply(
    './templates/blueprints.hbs.hbs',
    'blueprint',
    blueprints.bpDom,
    { result: result }
  );

  $('blueprint').each(function(idx, el) {
    $(el).on('click', function() {
      $(this).classList.toggle('active');
      var content = this.nextElementSibling;
      content.style.display =
        content.style.display === 'block' ? 'none' : 'block';
    });
  });
};
