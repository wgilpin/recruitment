import React from 'react';
import PropTypes from 'prop-types';
import TabBase from './TabBase';

const propTypes = {
  alt: PropTypes.string,
};

const defaultProps = {};

export default class Mail extends TabBase {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'mail',
    };
  }

  componentDidMount() {
    this.get({ scope: this.state.scope });
  }

  render() {
    return <React.Fragment />;
  }
}

Mail.propTypes = propTypes;
Mail.defaultProps = defaultProps;
