import React from 'react';
import PropTypes from 'prop-types';
import mockQs from './mocks/MockQuestions';

const propTypes = {};

const defaultProps = {};

const styles = {
  outer: {
    textAlign: "left",
    marginLeft: "48px",
    marginTop: "16px",
    marginBottom: "24px",
  },
  text: {
    paddingBottom: "16px",
    fontSize: "18px",
  },
  textarea: {
    width: "500px",
    marginLeft: "24px",
    backgroundColor: "#B5D8E2",
    fontSize: 14,
  }
}
export default class ApplyQs extends React.Component {
  constructor(props) {
    super(props);
    this.state = { questions: [] };
  }

  componentDidMount = () => {
    this.setState({ questions: mockQs.mock })
  }

  render() {
    console.log(this.state.questions)
    return (
      <>
        {this.state.questions.map( (q) => {
          return (
            <form style={styles.outer} key={q.id}>
              <div style={styles.text}>{q.q}</div>
              <textarea style={styles.textarea} rows="4" ></textarea>
            </form>
          );
        })}
        <hr/>
      </>
    );
  }
}

ApplyQs.propTypes = propTypes;
ApplyQs.defaultProps = defaultProps;