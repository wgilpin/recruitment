import React from 'react';
import PropTypes from 'prop-types';
import RoundImage from './RoundImage';
import walletImg from './images/wallet.png';
import assetsImg from './images/assets.png';
import mailImg from './images/mail.png';
import blueprintImg from './images/blueprints.png';

const propTypes = {
    onTabChange: PropTypes.func,
};

const defaultProps = {};

const headerStyle = {
  div: {
    width: 50*8,
    display: 'grid',
    gridTemplateColumns: '1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr',
    gridTemplateRows: 'auto',
    gridColumnGap: '20px',
  },
  span: {
    size: 14,
    paddingLeft: 6,
  }
};

export default class TabsHeader extends React.Component {
  constructor(props) {
    super(props);
    console.log('cons',this);
    this.state = {};
  }

  showTab = (name) => {
    //open the tab
    console.log('tabsHeader click ');

    if (this.props.onTabChange){
      console.log('tabsHeader click handled ');

      this.props.onTabChange(name);
    }
  }

  render() {
    return (
      <div style={headerStyle.div}>
        <RoundImage style={{gridColumn: 2}} size={40} src={walletImg} onClick={this.showTab} id="wallet"></RoundImage>
        <RoundImage style={{gridColumn: 3}} size={40} src={assetsImg} onClick={this.showTab} id="assets"></RoundImage>
        <RoundImage style={{gridColumn: 4}} size={40} src={mailImg} onClick={this.showTab} id='titles'></RoundImage>
        <RoundImage style={{gridColumn: 5}} size={40} src={mailImg} onClick={this.showTab} id='bookmarks'></RoundImage>
        <RoundImage style={{gridColumn: 6}} size={40} src={blueprintImg} onClick={this.showTab} id='blueprints'></RoundImage>
        <RoundImage style={{gridColumn: 7}} size={40} src={mailImg} onClick={this.showTab} id='mail'></RoundImage>
      </div>
    );
  }
}

TabsHeader.propTypes = propTypes;
TabsHeader.defaultProps = defaultProps;