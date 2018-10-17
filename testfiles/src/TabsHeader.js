import React from 'react';
import PropTypes from 'prop-types';
import RoundImage from './RoundImage';
import walletImg from './images/wallet.png';
import assetsImg from './images/assets.png';
import mailImg from './images/mail.png';
import blueprintImg from './images/blueprints.png';

const propTypes = {
    onClick: PropTypes.func,
};

const defaultProps = {};

const headerStyle = {
  div: {
    width: 40*8,
    display: 'grid',
    gridTemplateColumns: '1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr',
    gridTemplateRows: 'auto',
    gridColumnGap: '12px',
  },
  span: {
    size: 14,
    paddingLeft: 6,
  }
};

export default class TabsHeader extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  showTab(name){
    //open the tab
    if (this.onClick){
      this.onClick(name);
    }
  }

  render() {
    return (
      <div style={headerStyle.div}>
        <RoundImage style={{gridColumn: 2}} src={walletImg} onClick={this.showTab} id="wallet"></RoundImage>
        <RoundImage style={{gridColumn: 3}} src={assetsImg} onClick={this.showTab} id="assets"></RoundImage>
        <RoundImage style={{gridColumn: 4}} src={mailImg} onClick={this.showTab} id='titles'></RoundImage>
        <RoundImage style={{gridColumn: 5}} src={mailImg} onClick={this.showTab} id='bookmarks'></RoundImage>
        <RoundImage style={{gridColumn: 6}} src={blueprintImg} onClick={this.showTab} id='blueprints'></RoundImage>
        <RoundImage style={{gridColumn: 7}} src={mailImg} onClick={this.showTab} id='mail'></RoundImage>
      </div>
    );
  }
}

 TabsHeader.propTypes = propTypes;
 TabsHeader.defaultProps = defaultProps;