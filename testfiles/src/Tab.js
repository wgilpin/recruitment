import React from 'react';

const styles = {
  outer: {
    overflow: "hidden",
    borderBottomWidth: "1px",
    borderBottomStyle: "solid",
    borderBottomColour: "#ccc",
  },
  inner: {
    margin: "0 auto",
    width: "95%",
  },
}

export default class Tab extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <div style={styles.outer}>
        <div style={styles.inner}>
          {this.props.children}
        </div>
      </div>
    );
  }
}
