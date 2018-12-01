import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

const propTypes = {};

const defaultProps = {};

export default class Home extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <React.Fragment>
        Home
        <br/>
        <Link to='/admin'>
          <button>Admin</button>
        </Link>
        <Link to='/evidence'>
          <button>Recruiter</button>
        </Link>
        <Link to='/apply'>
          <button>Applicant</button>
        </Link>
      </React.Fragment>
    );
  }
}

Home.propTypes = propTypes;
Home.defaultProps = defaultProps;