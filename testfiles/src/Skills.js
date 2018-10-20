import React from 'react';
import PropTypes from 'prop-types';
import FetchData from './FetchData';
import TableStyles from './TableStyles';
import skill0 from './images/skill0.png';
import skill1 from './images/skill1.png';
import skill2 from './images/skill2.png';
import skill3 from './images/skill3.png';
import skill4 from './images/skill4.png';
import skill5 from './images/skill5.png';
import train01 from './images/train02.png';
import train02 from './images/train02.png';
import train03 from './images/train03.png';
import train04 from './images/train04.png';
import train05 from './images/train05.png';
import train12 from './images/train12.png';
import train13 from './images/train13.png';
import train14 from './images/train14.png';
import train15 from './images/train15.png';
import train23 from './images/train23.png';
import train24 from './images/train24.png';
import train25 from './images/train25.png';
import train34 from './images/train34.png';
import train35 from './images/train35.png';
import train45 from './images/train45.png';

const propTypes = {
  alt: PropTypes.string,
};

const defaultProps = {
};

const styles = {
  ...TableStyles.styles,
  progress: {
    backgroundColor: '#444',
    color: '#0084A8',
    height: '7px',
  },
  skillImage:{
    verticalAlign: 'bottom',
  },
}

export default class Skill extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      scope: 'skill',
      skillQueue: [],
      skillList: {},
    };
  }

  static jsonToskillList(json) {
    let trainLevels = {};
    let queue = [];
    if (json && json.queue) {
      for (let idx in json.queue) {
        queue.push(json.queue[idx]);
        let { finished_level, skill_id: { name }} = json.queue[idx];
        console.log('added', idx, name, finished_level)
        // store the level being trained to for later
        if (trainLevels[name]){
          console.log('update', name, finished_level)
          trainLevels[name].finish = finished_level;
        } else {
          // it only doesn't have this prop the first time
          console.log('start is ', finished_level-1)
          trainLevels[name] = {start: finished_level-1, finish: finished_level};
        }
        console.log('end iter', trainLevels[name])
      }
    }
    let groupedList = {};
    if (json && json.skills){
      for (let idx in json.skills) {
        let sk = json.skills[idx];
        let group = sk.skill_id.groupName;
        if (!(group in groupedList)){
          groupedList[group] = {};
        };
        groupedList[group][sk.skill_id.name] = sk.active_skill_level;
      }
    }
    return { queue, groupedList, trainLevels };
  }

  onLoaded = data => {
    let { queue, groupedList, trainLevels } = Skill.jsonToskillList(data);
    if (queue.length !== (this.state.skillQueue || []).length) {
      this.setState({ skillQueue: queue, trainLevels });
    };
    if (Object.keys(groupedList).length !== Object.keys(this.state.skillList || {}).length) {
      this.setState({ skillList: groupedList });
    };
  }

  componentDidMount() {
    let fetch = new FetchData(
      { id: this.props.alt, scope: 'skill' },
      this.onLoaded,
      this.onError
    );
    fetch.get();
  }

  static skill2image = {
    0: skill0,
    1: skill1,
    2: skill2,
    3: skill3,
    4: skill4,
    5: skill5,
  };

  static train2Image = {
    0: { 1: train01, 2: train02, 3: train03, 4: train04, 5: train05 },
    1: { 2: train12, 3: train13, 4: train14, 5: train15 },
    2: { 3: train23, 4: train24, 5: train25 },
    3: { 4: train34, 5: train35 },
    4: { 5: train45 },
  }

  skillQueueLinesShown = 0;

  skillQLine(key, { finish_date, start_date, finished_level, skill_id }) {
    let lineStyle =
      (this.skillQueueLinesShown % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...styles.cell };
    let startDate = new Date(start_date),
      endDate = new Date(finish_date),
      today = new Date(),
      fullRange = endDate - startDate,
      soFar = today - startDate;
    let { start, finish } = this.state.trainLevels[skill_id.name];
    let image = Skill.train2Image[start][finish];
    if (finished_level !== finish){
      return null;
    }
    this.skillQueueLinesShown += 1;
    return (
      <div style={styles.row} key={key}>
        <div style={lineStyle}>{skill_id.name}</div>
        <div style={lineStyle}>
          <img
            src={image}
            alt={finished_level}
            style={styles.skillImage}
          />
        </div>
        <div style={lineStyle}>{
          soFar > 0.0 ?
            <progress style={styles.progress} value={soFar} max={fullRange}/> :
            null
          }
        </div>
      </div>
    )
  }

  skillLine(key, name, active_skill_level) {
    let lineStyle =
      (key % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...styles.cell };

    return (
      <div style={styles.row} key={key}>
        <div style={lineStyle}></div>
        <div style={lineStyle}>{name}</div>
        <div style={lineStyle}>
          <img src={Skill.skill2image[active_skill_level]} alt={active_skill_level}/>
        </div>
      </div>
    )
  }

  render() {
    this.skillQueueLinesShown = 0;
    return (
      <div style={styles.div}></div>
        <div style={styles.table}>
          <div style={styles.header} key='header'>
            <div style={styles.cell}>SKILL QUEUE (ROLLED UP)</div>
            <div style={styles.cell}>LEVEL</div>
            <div style={styles.cell}>PROGRESS</div>
          </div>
          {this.state.skillQueue.map((line, idx) => {
            return this.skillQLine(idx, line)
          })}
        </div>
        <hr/>
        <div style={styles.table}>
          <div style={styles.header} key='header'>
            <div style={styles.cell}>GROUP</div>
            <div style={styles.cell}>SKILL</div>
            <div style={styles.cell}>LVL</div>
          </div>
          {Object.keys(this.state.skillList).map((group) => {
            console.log('group',this.state.skillList[group]);
            return (
              <React.Fragment>
                <div style={{...styles.row, ...styles.folderHeader}} key={group}>
                  <div style={styles.cell}>{group.toUpperCase()}</div>
                  <div style={styles.cell}></div>
                  <div style={styles.cell}></div>
                </div>
                {Object.keys(this.state.skillList[group]).map((line, idx) => {
                  return this.skillLine(idx,line, this.state.skillList[group][line])
                })}
              </React.Fragment>
            )
          })}
        </div>
      </div>
    );
  }
}

Skill.propTypes = propTypes;
Skill.defaultProps = defaultProps;