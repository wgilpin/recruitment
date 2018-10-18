import React from 'react';
import PropTypes from 'prop-types';
import FetchData from './FetchData';

const propTypes = {
  alt: PropTypes.string,
};

const defaultProps = {};

export default class Mail extends FetchData {
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
