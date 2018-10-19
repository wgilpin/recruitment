export default class Mocks {
  static mockSkills = {
    blacklist: [],
    queue:
      [
        {
          finish_date: "2018-10-25T20:31:02Z",
          finished_level: 5,
          level_end_sp: 512000,
          level_start_sp: 90510,
          queue_position: 3,
          skill_id:
          {
            standing: 0,
            id: 43702,
            type_id: 43702,
            name: "Ice Harvesting Drone Operation",
            description: "Skill at controlling ice harvesting drones. 5% reduction in ice harvesting drone cycle time per level.",
            multiplier: 2,
            groupName: "Drones",
            primaryAttribute: "Memory",
            secondaryAttribute: "Perception",
          },
          start_date: "2018-10-15T22:23:14Z",
          training_start_sp: 90510,
        }
      ],
    skills:
      {
        "22536": {
          active_skill_level: 5,
          skill_id:
          {
            standing: 0,
            id: 22536,
            type_id: 22536,
            name: "Mining Foreman",
            description: "Basic proficiency at boosting the mining capabilities of allied ships. Grants a 10% bonus to the duration of Mining Foreman Burst effects per level.",
            multiplier: 2,
            groupName: "Fleet Support",
            primaryAttribute: "Charisma",
            secondaryAttribute: "Willpower",
          },
          skillpoints_in_skill: 512000,
          trained_skill_level: 5,
        },
        "22541":
        {
          active_skill_level: 4,
          skill_id:
          {
            standing: 0,
            id: 22541,
            type_id: 22541,
            name: "Mining Drone Specialization",
            description: "Advanced proficiency at controlling mining drones. 2% bonus to the mining yield and max velocity of drones requiring Mining Drone Specialization per level.",
            multiplier: 5,
            groupName: "Drones",
            primaryAttribute: "Memory",
            secondaryAttribute: "Perception",
          },
          skillpoints_in_skill: 505410,
          trained_skill_level: 4,
        },
        "43702":
        {
          active_skill_level: 4,
          skill_id:
          {
            standing: 0,
            id: 43702,
            type_id: 43702,
            name: "Ice Harvesting Drone Operation",
            description: "Skill at controlling ice harvesting drones. 5% reduction in ice harvesting drone cycle time per level.",
            multiplier: 2,
            groupName: "Drones",
            primaryAttribute: "Memory",
            secondaryAttribute: "Perception",
          },
          skillpoints_in_skill: 90510,
          trained_skill_level: 4,
        }
      }
  };

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