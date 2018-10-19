import Mocks from "./Mocks";

export default class FetchData {
  constructor(params, onLoaded, onError) {
    this.params = FetchData.toParams(params);
    this.scope = params.scope;
    this.onLoaded = onLoaded;
    this.onError = onError;
  }

  static toParams(obj) {
    let res = [];
    for (let key in obj) {
      res.push(`${key}=${encodeURI(obj[key])}`);
    }
    return res.join('&');
  }

  get() {
    console.log(this.params);
    fetch(
      "https://ascee.droeftoeters.com/testfiles/Pullpage.php",
      {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/x-www-form-urlencoded',
          'Access-Control-Allow-Origin': '*',
        },
        mode: 'no-cors',
        body: this.params,
      })
      .then(function(res){
        console.log('response', res);
        // TODO: DEV server only
        if (res.type === "opaque") {
          console.log('opaque');
          // it's a CORS problem so we are on the dev server
          switch(this.scope){
            case 'mail':
              return Mocks.mockMail;
            case 'wallet':
              return Mocks.mockWallet;
            case 'skill':
              return Mocks.mockSkills;
            case 'bookmarks':
              return Mocks.mockBookmarks;
            default:
              return null;
          }
        };
        return res.json()
      }.bind(this))
      .then(
        (result) => {
          console.log('fetch last then', result);
          (this.onLoaded || Function)(result);
        },
        (error) => {
          console.log('error', error);
          (this.onError || Function)();
        }
      )
  }
}