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
    gridTemplateColumns: '1fr 1fr auto 1fr',
    gridTemplateRows: 'auto',
    gridRowGap: '12px',
    gridColumnGap: '12px',
    width: '100%',
    padding: '16px',
  },
  amount: {
    gridColumn: 1,
    textAlign: 'left',
    //margin: '8px',
    paddingTop: '8px',
  },
  balance: {
    gridColumn: 2,
    textAlign: 'left',
    //margin: '8px',
    paddingTop: '8px',
  },
  description: {
    gridColumn: 3,
    textAlign: 'left',
    height: '36px',
    //margin: '8px',
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

  static walletLine(key, { amount, balance, description }) {
    let lineStyle =
      key === "Titles" ?
        styles.title :
        (key % 2 === 0 ? styles.isOdd : {});
    console.log(key, lineStyle)
    return (
      <React.Fragment>
        <span style={{ ...lineStyle, ...styles.amount }}>{amount.toLocaleString()}</span>
        <span style={{ ...lineStyle, ...styles.balance }}>{balance.toLocaleString()}</span>
        <span style={{ ...lineStyle, ...styles.description }}>{description}</span>
      </React.Fragment>
    )
  }


  static commarize(min) {
    // from https://gist.github.com/MartinMuzatko/1060fe584d17c7b9ca6e
    min = min || 1e3;
    // Alter numbers larger than 1k
    if (this >= min) {
      var units = ["k", "M", "B", "T"];
      var order = Math.floor(Math.log(this) / Math.log(1000));
      var unitname = units[order - 1];
      var num = Math.floor(this / Math.pow(1000, order));
      // output number remainder + unitname
      return num + unitname;
    }
    // return formatted original number
    return min.toLocaleString();
  }

  render() {
    return (
      <div style={styles.div}>
        {Wallet.walletLine(
          "Titles",
          {
            amount: "AMOUNT",
            balance: "BALANCE",
            description: "DESCRIPTION"
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