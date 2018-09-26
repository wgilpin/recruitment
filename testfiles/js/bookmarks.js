bookmarks = {};

bookmarks.click = function() {
  if (global.id) {
    var scope = 'bookmarks';
    $.post({
      url: 'Pullpage.php',
      data: { id: global.id, scope: scope },
      dataType: 'json',
      success: bookmarks.onLoad,
    });
    return;
  }
  alert('No character selected\n\nPlease choose an alt');
};

bookmarks.onLoad = function(result) {
  global.mailState == 1;
  console.log(result);

  var bmDom = $('#BOOKMARKS')
    .parent()
    .find('.content');

  $('.bookmarks').each(function(idx, el) {
    el.on('click', function() {
      this.classList.toggle('active');

      var content = this.nextElementSibling;
      content.style.display =
        content.style.display === 'block' ? 'none' : 'block';
    });
  });

  // create html via template
  templates.prepareAndApply('./templates/bookmarkList.hbs', 'bookmark', bmDom, {
    result: result,
  });
};
