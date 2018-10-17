import React from 'react';
import PropTypes from 'prop-types';
import TabBase from './TabBase';

const propTypes = {
  alt: PropTypes.string,
};

const defaultProps = {
};


export default class Wallet extends TabBase {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'wallet',
    };
  }


  componentDidMount(){
    this.get({scope: this.state.scope})
  }

  render() {
    return (
      <React.Fragment>

      </React.Fragment>
    );
  }
}

 Wallet.propTypes = propTypes;
 Wallet.defaultProps = defaultProps;