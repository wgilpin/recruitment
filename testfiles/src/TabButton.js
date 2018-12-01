import React from 'react';
import PropTypes from 'prop-types';

const propTypes = {
  label: PropTypes.string,
  selected: PropTypes.bool,
};

const defaultProps = {
  label: '',
  selected: false,
};

const styles = {
  button: {
    float: "left",
    border: "none",
    outline: "none",
    cursor: "pointer",
    padding: "14px 16px",
    transition:" 0.3s",
    marginLeft: "4px",
    marginRight: "4px",
    width: "48%",
    color: "white",
    backgroundColor: "#222"
  },
  selected: {
    backgroundColor: "#444",
    fontStyle: "bold",
  }
}

export default class TabButton extends React.Component {

  constructor(props) {
    super(props);
    this.state = {  };
  }

  onClick = (e) => {
    if (this.props.onClick){
      e.preventDefault();
      this.props.onClick(this.props.label);
    }
  }

  render() {
    let style = this.props.selected ? {...styles.button, ...styles.selected} : styles.button;
    return (
      <>
        <button type="button" style={style} onClick={this.onClick}>{this.props.label}</button>
      </>
    );
  }
}

TabButton.propTypes = propTypes;
TabButton.defaultProps = defaultProps;