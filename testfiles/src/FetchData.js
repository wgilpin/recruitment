export default class FetchData {
  constructor(params, onLoaded, onError){
    this.params = FetchData.toParams(params);
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
      .then(res => {
        console.log('response',res);
        // TODO: DEV only
        if (res.type === "opaque") {
          console.log('opaque');
          // it's a CORS problem so we arew on the dev server
          return {
            "blacklist": [],
            "info": [
              {
                "amount": -500000000,
                "balance": 13072213.2106,
                "date": "2018-10-13T19:58:18Z",
                "description": "martijn dammer deposited cash into youdontknow me's account",
                "first_party_id": {
                  "standing": "0",
                  "name": "martijn dammer",
                  "id": 2113085333
                },
                "id": 16038171070,
                "ref_type": "player_donation",
                "second_party_id": {
                  "standing": "0",
                  "name": "youdontknow me",
                  "id": 2113171022
                }
              },
              {
                "amount": -289799403.94,
                "balance": 513072213.2106,
                "context_id": 4975120045,
                "context_id_type": "market_transaction_id",
                "date": "2018-10-13T19:53:23Z",
                "description": "Market escrow release",
                "first_party_id": {
                  "standing": "0",
                  "name": "martijn dammer",
                  "id": 2113085333
                },
                "id": 16038154261,
                "ref_type": "market_escrow",
                "second_party_id": {
                  "standing": "0",
                  "name": "martijn dammer",
                  "id": 2113085333
                }
              },
            ]
          }
        };
        return res.json()
      })
      .then(
        (result) => {
          console.log('fetch last then', result.info);
          (this.onLoaded || Function)(result);
        },
        (error) => {
          console.log('error', error);
          (this.onError || Function)();
        }
      )
  }
}