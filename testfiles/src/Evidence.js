import React from 'react';
import TabsHeader from './TabsHeader';
import Alts from './Alts';
import Wallet from './Wallet';
import Mail from './Mail';

const styles = {
  div: {
    marginLeft: 12,
    display: 'grid',
    gridTemplateColumns: '100 auto',
    gridTemplateRows: '100 auto',
    gridColumnGap: '12px',
    gridTemplateAreas: `
      "blurb tabHeader"
      "alts tabBody"`
  },
  alts: {
    gridArea: 'alts'
  },
  tabHeader: {
    gridArea: 'tabHeader',
    marginTop: 12,
  },
};

export default class Evidence extends React.Component {
  loadTab = (id) => {
    debugger;
    let nullState = {
      showWallet: false,
      showMail: false,
    }
    switch(id){
      case 'wallet':
        this.setState({...nullState, showWallet: true});
        break;
      case 'mail':
        this.setState({...nullState, showMail: true});
        break;
      default:
        this.setState(nullState);
    }
  }
  render() {
    return (
      <div style={styles.div}>
        <TabsHeader style={styles.tabHeader} onClick={this.loadTab}></TabsHeader>
        <Alts
          style={styles.alts}
          onChange={this.loadTab}
        ></Alts>
        <div style={{gridArea: 'tabBody'}}>
          {(this.state || {}).showWallet && <Wallet></Wallet>}
          {(this.state || {}).showMail && <Mail></Mail>}
        </div>

      </div>
    );
  }
}
