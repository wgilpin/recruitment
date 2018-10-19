import React from 'react';
import PropTypes from 'prop-types';
import FetchData from './FetchData';
import TableStyles from './TableStyles';

const propTypes = {
  alt: PropTypes.string,
  mailList: PropTypes.array,
};

const defaultProps = {
};

const styles = {
  ...TableStyles.styles,
  isRead: {
    fontWeight: 'normal',
  },
  isUnread: {
    fontWeight: 'bold',
  },
}

export default class Mail extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'mail',
      mailList: [],
    };
  }

  static jsonToMailList(json) {
    let list = [];
    if (json && json.info) {
      for (let we in json.info) {
        list.push(json.info[we]);
      }
    }
    return list;
  }

  onLoaded = data => {
    let newList = Mail.jsonToMailList(data);
    if (newList.length !== (this.state.mailList || []).length) {
      this.setState({ mailList: newList })
    }
  }

  componentDidMount() {
    let fetch = new FetchData(
      { id: this.props.alt, scope: 'mail' },
      this.onLoaded,
      this.onError
    );
    fetch.get();
  }

  static mailItem(key, { timestamp, from, subject, is_read }) {
    let lineStyle, formattedDate;
    let readStyle = is_read ? styles.isRead : styles.isUnread;

    lineStyle = (key % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...readStyle, ...styles.cell };
    let newdate = new Date(timestamp);
    formattedDate = newdate.toLocaleDateString() + ' ' + newdate.toLocaleTimeString();
    return (
      <div style={styles.row}>
        <div style={lineStyle}>{formattedDate}</div>
        <div style={lineStyle}>{from.name}</div>
        <div style={lineStyle}>{subject}</div>
      </div>
    );
  }


  render() {
    console.log(this.state.mailList.length ? Mail.mailItem(1, this.state.mailList[0]) : null)
    return (
      <div style={styles.table}>
        <div style={styles.header}>
          <div style={styles.cell}>DATE</div>
          <div style={styles.cell}>FROM</div>
          <div style={styles.cell}>SUBJECT</div>
        </div>
        {this.state.mailList.map((line, idx) => {
          return Mail.mailItem(idx, line)
        })}
      </div>
    );
  }
}

Mail.propTypes = propTypes;
Mail.defaultProps = defaultProps;