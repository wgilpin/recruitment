import React from 'react';
import PropTypes from 'prop-types';
import FetchData from './FetchData';

const propTypes = {
  alt: PropTypes.string,
  walletList: PropTypes.array,
};

const defaultProps = {
};

const styles = {
  div: {
    marginLeft: 12,
    display: 'grid',
    gridTemplateColumns: '1fr 1fr auto 2fr 1fr 1fr',
    gridTemplateRows: 'auto',
    gridRowGap: '12px',
    gridColumnGap: '12px',
    width: '100%',
    padding: '16px',
  },
  amount: {
    gridColumn: 1,
    textAlign: 'left',
    paddingTop: '8px',
  },
  balance: {
    gridColumn: 2,
    textAlign: 'left',
    paddingTop: '8px',
  },
  description: {
    gridColumn: 3,
    textAlign: 'left',
    paddingTop: '8px',
  },
  toWhom: {
    gridColumn: 4,
    textAlign: 'left',
    paddingTop: '8px',
  },
  date: {
    gridColumn: 5,
    textAlign: 'left',
    paddingTop: '8px',
  },
  isOdd: {
    backgroundColor: '#111',
  },
  title: {
    color: 'darkgoldenrod',
  }
}

export default class Wallet extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'wallet',
      walletList: [],
    };
    this.fetch = new FetchData(
      { id: this.props.alt, scope: 'wallet' },
      this.onLoaded,
      this.onError)
  }

  static jsonToWalletList(json) {
    let list = [];
    if (json && json.info) {
      for (let we in json.info) {
        list.push(json.info[we]);
      }
    }
    return list;
  }

  onLoaded = data => {
    let newList = Wallet.jsonToWalletList(data);
    if (newList.length !== (this.state.walletList || []).length) {
      this.setState({ walletList: newList })
    }
  }

  componentDidMount() {
    let fetch = new FetchData(
      { id: this.props.alt, scope: 'wallet' },
      this.onLoaded,
      this.onError
    );
    fetch.get();
  }

  static walletLine(key, { amount, balance, description, second_party_id, date }) {
    let lineStyle =
      key === "Titles" ?
        styles.title :
        (key % 2 === 0 ? styles.isOdd : {});
    let newdate = new Date(date);
    let theDate = date === "DATE" ? date : newdate.toLocaleDateString() + ' ' + newdate.toLocaleTimeString();

    return (
      <React.Fragment>
        <span style={{ ...lineStyle, ...styles.amount }}>{amount.toLocaleString()}</span>
        <span style={{ ...lineStyle, ...styles.balance }}>{balance.toLocaleString()}</span>
        <span style={{ ...lineStyle, ...styles.description }}>{description}</span>
        <span style={{ ...lineStyle, ...styles.toWhom }}>{second_party_id.name}</span>
        <span style={{ ...lineStyle, ...styles.date }}>{theDate}</span>
      </React.Fragment>
    )
  }


  static commarize(num, min=1e3) {
    // from https://gist.github.com/MartinMuzatko/1060fe584d17c7b9ca6e
    // Alter numbers larger than 1k
    if (num >= min) {
      var units = ["k", "M", "B", "T"];
      var order = Math.floor(Math.log(num) / Math.log(1000));
      var unitname = units[order - 1];
      var out = Math.floor(num / Math.pow(1000, order));
      // output number remainder + unitname
      return out + unitname;
    }
    // return formatted original number
    return num.toLocaleString();
  }

  render() {
    let balance = (this.state.walletList[0] || {balance: 0}).balance;
    return (
      <div style={styles.div}>
        <div>Balance {Wallet.commarize(balance)} ISK</div>
        {Wallet.walletLine(
          "Titles",
          {
            amount: "AMOUNT",
            balance: "BALANCE",
            description: "DESCRIPTION",
            second_party_id: {name: "TO WHO"},
            date: "DATE",
          }
        )}
        {this.state.walletList.map((line, idx) => {
          return Wallet.walletLine(idx, line)
        })}
      </div>
    );
  }
}

Wallet.propTypes = propTypes;
Wallet.defaultProps = defaultProps;