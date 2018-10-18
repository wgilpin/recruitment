export default class Mocks {
  static mockMail = {
    blacklist: [],
    info:
      [
        {
          from: {
            standing: 0,
            name: "Neni",
            id: 133895430,
          },

          is_read: 1,
          mail_id: 373117004,
          recipient_id: {
            standing: 0,
            name: "Ascendance",
            id: 98409330,
          },

          recipient_type: "corporation",
          subject: "Reminder: Forum usage and to anyone that has issues with :effort:",
          timestamp: "2018-10-17T14:25:00Z",
        },
        {
          from: {
            standing: 0,
            name: "Major Sniper 1",
            id: 299590276,
          },

          is_read: 1,
          mail_id: 373116726,
          recipient_id:
          {
            standing: 0,
            name: "Ascendance",
            id: 98409330,
          },
          recipient_type: "corporation",
          subject: "Another 1DQ scammer",
          timestamp: "2018-10-17T13:54:00Z",
        },
        {
          from:
          {
            standing: 0,
            name: "Major Sniper 2",
            id: 299590276,
          },
          is_read: 1,
          mail_id: 373112421,
          recipient_id:
          {
            standing: 0,
            name: "Ascendance",
            id: 98409330,
          },
          recipient_type: "corporation",
          subject: "Heads up",
          timestamp: "2018-10-17T01:33:00Z",
        },
      ]
  };
  
  static mockWallet = {
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
  };
}