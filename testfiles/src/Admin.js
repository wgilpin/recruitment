import React from 'react';
import PropTypes from 'prop-types';

const propTypes = {};

const defaultProps = {};

export default class Admin extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <React.Fragment>
        Admin
      </React.Fragment>
    );
  }
}

Admin.propTypes = propTypes;
Admin.defaultProps = defaultProps;