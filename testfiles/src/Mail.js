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
  body: {
    textAlign: 'left',
    color: 'white',
  }
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

  componentDidMount() {
    new FetchData(
      { id: this.props.alt, scope: 'mail' },
    ).get()
      .then(data => {
        let newList = Mail.jsonToMailList(data);
        if (newList.length !== (this.state.mailList || []).length) {
          let updatedList = {};
          Object.keys(newList).map(idx => {
            updatedList[idx] = { ...newList[idx], collapsed: true };
          })
          this.setState({ mailList: updatedList })
        }
      });
  }

  badlyRemoveFontSizeColor(html){
    let small = html.replace(/<font size=['"]\d*['"]/g, '<font size="unset"');
    small.replace(/color=['"]#[0-9a-zA-Z]*['"]/g, 'color="white"');
    return small;
  }

  toggleMessage = (idx) => {
    let { mailList } = this.state;
    let thisMail = mailList[idx];
    console.log('toggle mail', thisMail)
    this.setState({ mailList: { ...mailList, [idx]: { ...thisMail, collapsed: !thisMail.collapsed } } });
    if (!thisMail.body){
    new FetchData(
      { id: this.props.alt, scope: 'mail', param1: thisMail.mail_id },
    ).get()
      .then((data) => {
        console.log('mail get body', idx, data)
        let body = this.badlyRemoveFontSizeColor(data);
        this.setState({ mailList: { ...mailList, [idx]: { ...thisMail, collapsed: false, body } } });
        console.log('got body')
      })
    }
  };


  mailItem(key, { timestamp, from, subject, is_read }) {
    let lineStyle, formattedDate;
    let readStyle = is_read ? styles.isRead : styles.isUnread;

    lineStyle = (key % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...readStyle, ...styles.cell };
    let newdate = new Date(timestamp);
    formattedDate = newdate.toLocaleDateString() + ' ' + newdate.toLocaleTimeString();
    return (
      <div style={styles.row} onClick={this.toggleMessage.bind(this, key)}>
        <div style={lineStyle}>{formattedDate}</div>
        <div style={lineStyle}>{from.name}</div>
        <div style={lineStyle}>{subject}</div>
      </div>
    );
  }


  render() {
    return (
      <div style={styles.table}>
        <div style={styles.header}>
          <div style={styles.cell}>DATE</div>
          <div style={styles.cell}>FROM</div>
          <div style={styles.cell}>SUBJECT</div>
        </div>
        {Object.keys(this.state.mailList).map((line, idx) => {
          return (
            <React.Fragment>
              {this.mailItem(idx, this.state.mailList[line])}
              {!this.state.mailList[line].collapsed &&
                  (<div style={styles.body} dangerouslySetInnerHTML={{__html:  this.state.mailList[line].body}}/>)}
            </React.Fragment>
          );
        })}
      </div>
    );
  }
}

Mail.propTypes = propTypes;
Mail.defaultProps = defaultProps;