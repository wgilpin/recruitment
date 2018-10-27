import React from 'react';
import PropTypes from 'prop-types';
import FetchData from './FetchData';
import TableStyles from './TableStyles';

const propTypes = {
  alt: PropTypes.string,
  ContactList: PropTypes.array,
};

const defaultProps = {
};

const styles = TableStyles.styles;

export default class Contacts extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'contacts',
      ContactList: [],
    };
  }

  componentDidMount() {
    new FetchData(
      { id: this.props.alt, scope: this.state.scope },
    ).get()
      .then(data => {
        debugger;
        if (data.length !== (this.state.ContactList || []).length) {
          this.setState({ ContactList: data })
        }
      });
  }

  static ContactLine(idx, contact) {
    let { name, creationDate, corporation_id, alliance_id} = contact.contact_id;
    let { Blacklist_standing } = contact;
    console.log('contact', name)
    debugger
    let lineStyle =
      (idx % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...styles.cell };
    let newdate = new Date(creationDate);
    let theDate = creationDate === "DATE" ? creationDate : newdate.toLocaleDateString() + ' ' + newdate.toLocaleTimeString();

    return (
      <div style={styles.row} key={idx}>
        <div style={lineStyle}>{name}</div>
        <div style={lineStyle}>{(corporation_id || {}).ticker}</div>
        <div style={lineStyle}>{(alliance_id || {}).ticker}</div>
        <div style={lineStyle}>{Blacklist_standing}</div>
        <div style={lineStyle}>{theDate}</div>
      </div>
    )
  }

  render() {
    console.log('contacts', this.state.ContactList.length)
    debugger
    return (
      <div style={styles.div}>
        <div style={styles.table}>
          <div style={styles.header} key='header'>
            <div style={styles.cell}>NAME</div>
            <div style={styles.cell}>CORP</div>
            <div style={styles.cell}>ALLIANCE</div>
            <div style={styles.cell}>STANDING</div>
            <div style={styles.cell}>CREATED</div>
          </div>
          {Object.keys(this.state.ContactList).map((key, idx) => {
            return Contacts.ContactLine(idx, this.state.ContactList[key])
          })}
        </div>
      </div>
    );
  }
}

Contacts.propTypes = propTypes;
Contacts.defaultProps = defaultProps;