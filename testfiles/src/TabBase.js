import React from 'react';
import PropTypes from 'prop-types';

const propTypes = {
  alt: PropTypes.string,
};

const defaultProps = {
};


export default class TabBase extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: null,
      isLoaded: false,
    };
  }

  get(params) {
    let data = { ...params, id: this.props.alt };
    fetch(
      "https://ascee.droeftoeters.com/testfiles/Pullpage.php",
      {
        method:'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        mode: 'no-cors',
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(
        (result) => {
          this.setState({
            isLoaded: true,
            data: result.data
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }

  render() {
    return (
      <React.Fragment>

      </React.Fragment>
    );
  }
}

 TabBase.propTypes = propTypes;
 TabBase.defaultProps = defaultProps;