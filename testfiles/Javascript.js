var id;
$(document).ready(function() {
  var mail = 0;
  $('.Profilepic').on('click', people.click);

  $('#LOGIN').on('click', function() {
    var scope = 'login';
    $.ajax({
      type: 'POST',
      url: 'Pullpage.php',
      data: { id: global.id, scope: scope },
      dataType: 'json',
      success: function(result) {
        console.log(result);
        var html = '<div>';

        Object.keys(result).forEach(function(key) {
          html += '<p>' + key + ' : ' + result[key] + '</p>';
          console.log(key, result[key]);
        });
        html += '</div>';
        $('#LOGIN')
          .parent()
          .find('.content')
          .html(html);
      },
    });
  });

  $('#WALLET').on('click', wallet.load);

  $('#ASSETS').on('click', function() {
    var scope = 'assets';
    $.ajax({
      type: 'POST',
      url: 'Pullpage.php',
      data: { id: id, scope: scope },
      dataType: 'json',
      success: function(result) {
        console.log(result);
        var html = '<div>';

        Object.keys(result).forEach(function(key) {
          html += '<p>' + JSON.stringify(result[key]) + '</p>';
          console.log(key, result[key]);
        });
        html += '</div>';
        $('#ASSETS')
          .parent()
          .find('.content')
          .html(html);
      },
    });
  });

  $('#TITELS').on('click', function() {
    var scope = 'titles';
    $.ajax({
      type: 'POST',
      url: 'Pullpage.php',
      data: { id: id, scope: scope },
      dataType: 'json',
      success: function(result) {
        console.log(result);
        var html = '<div>';

        Object.keys(result).forEach(function(key) {
          html += '<p>' + key + ' : ' + result[key] + '</p>';
          console.log(key, result[key]);
        });
        html += '</div>';
        $('#TITELS')
          .parent()
          .find('.content')
          .html(html);
      },
    });
  });

  $('#MAIL').on('click', function() {
    var type = '';
    var scope = 'mail';
    console.log(mail);
    if (mail < 1) {
      $.ajax({
        type: 'POST',
        url: 'Pullpage.php',
        data: { id: id, scope: scope },
        dataType: 'json',
        success: function(result) {
          mail = 1;
          console.log(result);
          var html = '<div>';
          Object.keys(result).forEach(function(key) {
            if (
              result[key].recipients[0].recipient_type == 'corporation' ||
              result[key].recipients[0].recipient_type == 'alliance'
            ) {
              type = result[key].recipients[0].recipient_id;
            } else {
              type = '';
            }

            html += '<div class="fancy">';
            html +=
              '<button id="' +
              result[key].mail_id +
              '" onclick="mailpull(\'' +
              result[key].mail_id +
              '\')"  class="mails"><div class="subject">' +
              result[key].subject +
              '</div><div class="from">  From ' +
              type +
              '  </div><div class="name">' +
              result[key].from +
              '</div></button>';
            html +=
              '<div class="allinside _' +
              result[key].mail_id +
              '" id="mail" style="display: none;">';
            html += '</div>';
            html += '</div>';
            console.log(key, result[key]);
          });

          $('#MAIL')
            .parent()
            .find('.content')
            .html(html);
          var coll = document.getElementsByClassName('mails');
          var i;
          for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener('click', function() {
              this.classList.toggle('active');

              var content = this.nextElementSibling;
              if (content.style.display === 'block') {
                content.style.display = 'none';
              } else {
                content.style.display = 'block';
              }
            });
          }
        },
      });
    } else {
      console.log(mail);
    }
  });

  $('#BLUEPINTS').on('click', function() {
    var x;
    var scope = 'blueprints';
    $.ajax({
      type: 'POST',
      url: 'Pullpage.php',
      data: { id: id, scope: scope },
      dataType: 'json',
      success: function(result) {
        console.log(result);
        var html = '<div>';
        Object.keys(result).forEach(function(key) {
          html += '<div class="fancy">';
          html +=
            '<button id="blueprint" class="blueprint">' +
            result[key].type_id +
            '</button>';
          html += '<div class="allinside" style="display: none;">';
          html += JSON.stringify(result[key], null, 4);
          // console.log(json[key].type_id);
          html += '</div>';
          html += '</div>';
          console.log(key, result[key]);
        });
        html += '</div>';
        $('#BLUEPINTS')
          .parent()
          .find('.content')
          .html(html);
        var coll = document.getElementsByClassName('blueprint');
        var i;
        for (i = 0; i < coll.length; i++) {
          coll[i].addEventListener('click', function() {
            this.classList.toggle('active');

            var content = this.nextElementSibling;
            if (content.style.display === 'block') {
              content.style.display = 'none';
            } else {
              content.style.display = 'block';
            }
          });
        }
      },
    });
  });

  $('#BOOKMARKS').on('click', function() {
    var scope = 'bookmarks';
    $.ajax({
      type: 'POST',
      url: 'Pullpage.php',
      data: { id: id, scope: scope },
      dataType: 'json',
      success: function(result) {
        mail == 1;
        console.log(result);
        var html = '<div>';
        Object.keys(result).forEach(function(key) {
          html += '<div class="fancy">';
          html +=
            '<button id="mail" class="bookmarks"><div class="subject">' +
            result[key].location_id +
            '</div><div class="from"></div><div class="name">' +
            result[key].creator_id +
            '</div></button>';
          html += '<div class="allinside" style="display: none;">';
          html += JSON.stringify(result[key], null, 4);
          html += '</div>';
          html += '</div>';
          console.log(key, result[key]);
        });
        $('#BOOKMARKS')
          .parent()
          .find('.content')
          .html(html);
        var coll = document.getElementsByClassName('bookmarks');
        var i;
        for (i = 0; i < coll.length; i++) {
          coll[i].addEventListener('click', function() {
            this.classList.toggle('active');

            var content = this.nextElementSibling;
            if (content.style.display === 'block') {
              content.style.display = 'none';
            } else {
              content.style.display = 'block';
            }
          });
        }
      },
    });
  });

  var coll = document.getElementsByClassName('collapsible');
  var i;
  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener('click', function() {
      this.classList.toggle('active');

      var content = this.nextElementSibling;
      if (content.style.display === 'block') {
        content.style.display = 'none';
      } else {
        content.style.display = 'block';
      }
    });
  }
});

function mailpull(MailID) {
  var Malm = '';
  var scope = 'mail';

  // console.log(id);
  // console.log(MailID);
  // console.log(scope);
  $.ajax({
    type: 'POST',
    url: 'Pullpage.php',
    data: { id: id, scope: scope, MailID: MailID },
    dataType: 'json',
    success: function(result) {
      console.log(result);
      Malm += '<div>';
      Malm += result.body;

      Malm += '</div>';
      console.log(Malm);
      $('._' + MailID).html(Malm);
    },
  });
}
