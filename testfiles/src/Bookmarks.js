import React from 'react';
import PropTypes from 'prop-types';
import FetchData from './FetchData';
import TableStyles from './TableStyles';

const propTypes = {
  alt: PropTypes.string,
};

const defaultProps = {
};

const styles = {
  ...TableStyles.styles,
  progress: {
    backgroundColor: '#444',
    color: 'cyan',
    height: '7px',
  },
}

export default class Bookmarks extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'bookmarks',
      bookmarkList: {},
    };
  }


  onLoaded = data => {
    let { info } = data;
    if (Object.keys(info).length !== Object.keys(this.state.bookmarkList || {}).length) {
      this.setState({ bookmarkList: info });
    };
  }

  componentDidMount() {
    let fetch = new FetchData(
      { id: this.props.alt, scope: 'bookmarks' },
      this.onLoaded,
      this.onError
    );
    fetch.get();
  }

  static bookmarkQLine(key, { finish_date, start_date, finished_level, bookmark_id }) {
    let lineStyle =
      (key % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...styles.cell };
    debugger;
    let startDate = new Date(start_date),
      endDate = new Date(finish_date),
      today = new Date(),
      fullRange = endDate - startDate,
      soFar = today - startDate;

    return (
      <div style={styles.row} key={key}>
        <div style={lineStyle}>{bookmark_id.name}</div>
        <div style={lineStyle}>{finished_level}</div>
        <div style={lineStyle}>{
          soFar > 0.0 ?
            <progress style={styles.progress} value={soFar} max={fullRange}/> :
            null
          }
        </div>
      </div>
    )
  }

  static bookmarkLine(key, idx, { item, location_id }) {
    let lineStyle =
      (idx % 2 === 0 ? styles.isOdd : {});
    debugger;
    lineStyle = { ...lineStyle, ...styles.cell };
    let location = `${location_id.regionName}/${location_id.solarSystemName}`;
    return (
      <div style={styles.row} key={key}>
        <div style={lineStyle}></div>
        <div style={lineStyle}>{(item || {}).typeName}</div>
        <div style={lineStyle}>{location}</div>
      </div>
    )
  }

  bookmarkFolder(id) {
    let folder = this.state.bookmarkList[id];
    if (folder.inside){
      return (
      <React.Fragment>
        <div style={styles.row} key={folder.folder_id}>
          <div style={styles.folder}>{folder.name}</div>
          <div style={styles.folder}> </div>
          <div style={styles.folder}> </div>
        </div>
        <div style={styles.hr}/>
        {Object.keys(folder.inside).map((key, idx) => {
          return Bookmarks.bookmarkLine(key, idx, folder.inside[key]);
        })}
      </React.Fragment>
    )} else {
      return null;
    }
  }

  render() {
    return (
      <div style={styles.div}>
        <div style={styles.table}>
          <div style={styles.header} key='header'>
            <div style={styles.cell}>FOLDER</div>
            <div style={styles.cell}>TYPE</div>
            <div style={styles.cell}>LOCATION</div>
          </div>
          {Object.keys(this.state.bookmarkList).map((line) => {
            return this.bookmarkFolder(line)
          })}
        </div>
      </div>
    );
  }
}

Bookmarks.propTypes = propTypes;
Bookmarks.defaultProps = defaultProps;