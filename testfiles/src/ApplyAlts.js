import React from 'react';
import PropTypes from 'prop-types';
import Alt from './Alt';
import RoundImage from './RoundImage';
import plusImg from './images/plus-circle.svg';
import MockAlts from './mocks/MockAlts';

const propTypes = {};

const defaultProps = {};

const styles = {
  addButton: {
    marginRight: "48px",
    textAlign: "right",
  },
  alt: {
    marginLeft: "48px",
    marginTop: "12px",
  }
}
export default class ApplyAlts extends React.Component {
  constructor(props) {
    super(props);
    this.state = { alts: [] };
  }

  componentDidMount = () => {
    let mockAlts = MockAlts.mock;
    this.setState({ alts: mockAlts });
  }

  render() {
    return (
      <>
        {this.state.alts.map((alt) => {
          return (
          <div style={styles.alt} key={alt.name}>
            <Alt size="40px"  name={alt.name} src={alt.src} corp={alt.corp}/>
          </div>)
      })}
        <div style={styles.addButton}>
          <img width="60" height="60" src={plusImg} alt="Add alt" />
        </div>
        <hr />
      </>
    );
  }
}

ApplyAlts.propTypes = propTypes;
ApplyAlts.defaultProps = defaultProps;