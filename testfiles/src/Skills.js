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
import train1 from './images/train1.png';
import train2 from './images/train2.png';
import train3 from './images/train3.png';
import train4 from './images/train4.png';
import train5 from './images/train5.png';

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
  }
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
    let queue = [];
    if (json && json.queue) {
      for (let idx in json.queue) {
        queue.push(json.queue[idx]);
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
    return { queue, groupedList };
  }

  onLoaded = data => {
    let { queue, groupedList } = Skill.jsonToskillList(data);
    if (queue.length !== (this.state.skillQueue || []).length) {
      this.setState({ skillQueue: queue });
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

  static train2image = {
    1: train1,
    2: train2,
    3: train3,
    4: train4,
    5: train5,
  };

  static skillQLine(key, { finish_date, start_date, finished_level, skill_id }) {
    let lineStyle =
      (key % 2 === 0 ? styles.isOdd : {});
    lineStyle = { ...lineStyle, ...styles.cell };
    let startDate = new Date(start_date),
      endDate = new Date(finish_date),
      today = new Date(),
      fullRange = endDate - startDate,
      soFar = today - startDate;

    return (
      <div style={styles.row} key={key}>
        <div style={lineStyle}>{skill_id.name}</div>
        <div style={lineStyle}>
          <img
            src={Skill.train2image[finished_level]}
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

  static skillLine(key, name, active_skill_level) {
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
    return (
      <div style={styles.div}>
        <div style={styles.table}>
          <div style={styles.header} key='header'>
            <div style={styles.cell}>SKILL QUEUE</div>
            <div style={styles.cell}>LVL</div>
            <div style={styles.cell}>PROGRESS</div>
          </div>
          {this.state.skillQueue.map((line, idx) => {
            return Skill.skillQLine(idx, line)
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
                <div style={styles.groupHeader} key={group}>
                  <div style={styles.cell}>{group.toUpperCase()}</div>
                </div>
                {Object.keys(this.state.skillList[group]).map((line, idx) => {
                  return Skill.skillLine(idx,line, this.state.skillList[group][line])
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