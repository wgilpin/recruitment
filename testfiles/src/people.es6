class People {
  constructor() {
    $('.Profilepic').on('click', this.click);
  }

  click () {
    // store list index
    window.global.id = $(this).attr('id');
    // store selected char name
    window.global.myName = $(this).find('.char-name').html()
    console.log(window.global.id);
    $('.allcontent')
      .find('.content div')
      .hide();
    $('.Profilepic').removeClass('active');
    $('.collapsible').removeClass('active');
    $(this).addClass('active');

    mail.resetState();
    assets.resetState();
    wallet.resetState();
  };
}

