import React from 'react';
import TabsHeader from './TabsHeader';
import Alts from './Alts';
import Wallet from './Wallet';
import Mail from './Mail';

const styles = {
  div: {
    marginLeft: 12,
    display: 'grid',
    gridTemplateColumns: '200 auto',
    gridTemplateRows: '100 row2-end 40',
    gridColumnGap: '12px',
    gridTemplateAreas: '". tabHeader" "alts tabBody"',
  },
  alts: {
    gridArea: 'alts',
    width: 200,
  },
  tabHeader: {
    gridArea: 'tabHeader',
    marginTop: 12,
  },
tabBody: {
    gridArea: 'tabBody',
  },
};

export default class Evidence extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      showWallet: false,
      showMail: false,
    };
  }

  changeTab = (tabId) => {
    console.log('evidence click ');
    let nullState = {
      ...this.state,
      showWallet: false,
      showMail: false,
    }
    switch (tabId) {
      case 'wallet':
        this.setState({ ...nullState, showWallet: true });
        break;
      case 'mail':
        this.setState({ ...nullState, showMail: true });
        break;
      default:
        this.setState(nullState);
    }
  }

  changeAlt = (altId) => {
    console.log('change alt', altId);
    this.setState({ currentAlt: altId });
  }

  render() {
    return (
      <div style={styles.div}>
        <div style={styles.tabHeader}>
          <TabsHeader  arb="xxx" onTabChange={this.changeTab}></TabsHeader>
        </div>
        <div style={styles.alts}>
          <Alts
            onAltSelect={this.changeAlt}
          ></Alts>
        </div>
        <div style={{ gridArea: 'tabBody' }}>
          {(this.state || {}).showWallet &&
            <Wallet style={styles.tabBody} alt={this.state.currentAlt}></Wallet>}
          {(this.state || {}).showMail &&
            <Mail style={styles.tabBody} alt={this.state.currentAlt}></Mail>}
        </div>

      </div>
    );
  }
}
