class Templates {
  constructor() {
    this.items = {};
    // return odd if index is odd, even if even
    Handlebars.registerHelper('oddEven', (index, odd, even) => index % 2 == 0 ? even : odd);

    // format an ISK value with commas etc
    Handlebars.registerHelper('isk', (amount) => this.formatMoney(amount));

    // return posClass if value > 0, else negClass
    Handlebars.registerHelper('posNeg', (value, posClass, negClass) => value > 0 ? posClass : negClass);

    // pretty format a date in the local locale
    Handlebars.registerHelper('dateFormat', (date) => {
      let newdate = new Date(date);
      return newdate.toLocaleDateString() + ' ' + newdate.toLocaleTimeString();
    });

    // Add method to prototype. this allows you to use this function on numbers and strings directly
    Number.prototype.commarize = this.commarize;
    String.prototype.commarize = this.commarize;
  }

  // compile a template and store locally
  prepare(templateFile, templateId) {
    // templateFile: url
    // templateId: string label for the template in the list

    // don't prepare if already done
    if (this.items[templateId]) return;
    return $.get(templateFile, (source) => {
      this.items[templateId] = Handlebars.compile(source);
    });
  };

  // apply a template given a context object
  apply(domId, templateId, data) {
    // domId: form jQuery $(..) or find()
    // templateId: string stored id when we complied it
    // data: json object
    let html = this.items[templateId](data);

    domId.html(html);
  };

  // shortcut to apply and prep
  prepareAndApply(templateFile, templateId, domId, data) {
    // don't prepare if already done
    if (this.items[templateId]) {
      try {
        this.apply(domId, templateId, data);
        return;
      } catch (e) {
        console.error(e);
      }
    }

    // first time
    this.prepare(templateFile, templateId).then(() => {
      try {
        this.apply(domId, templateId, data);
        return;
      } catch (e) {
        console.error(e);
      }
    });
  };



  // format a money value
  formatMoney(n, c, d, t) {
    // n: currency amount
    // c: char comma
    // d: char decimal point
    // from https://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-dollars-currency-string-in-javascript
    c = isNaN((c = Math.abs(c))) ? 2 : c;
    d = d == undefined ? '.' : d;
    t = t == undefined ? ',' : t;
    let s = n < 0 ? '-' : '',
      i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
      j = (j = i.length) > 3 ? j % 3 : 0;

    return (
      s +
      (j ? i.substr(0, j) + t : '') +
      i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) +
      (c
        ? d +
        Math.abs(n - i)
          .toFixed(c)
          .slice(2)
        : '')
    );
  };

  commarize(min) {
    // from https://gist.github.com/MartinMuzatko/1060fe584d17c7b9ca6e
    min = min || 1e3;
    // Alter numbers larger than 1k
    if (this >= min) {
      let units = ["k", "M", "B", "T"];

      let order = Math.floor(Math.log(this) / Math.log(1000));

      let unitname = units[(order - 1)];
      let num = Math.floor(this / (Math.pow(1000, order)));

      // output number remainder + unitname
      return num + unitname
    }

    // return formatted original number
    return this.toLocaleString()
  }



}