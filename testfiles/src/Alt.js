import React from 'react';
import PropTypes from 'prop-types';
import RoundImage from './RoundImage';

const propTypes = {
  name: PropTypes.string,
  src: PropTypes.string,
  id: PropTypes.string,
  corp: PropTypes.string,
  onClick: PropTypes.func,
  selected: PropTypes.bool,
};

const defaultProps = {};

export default class Alt extends React.Component {

  handleClick = () => {
    console.log('alt click');
    if (this.props.onClick) {
      console.log('alt click handled');
      this.props.onClick(this.props.id);
    }
  }

  render() {
    const { size, src, name, corp, selected } = this.props;

    const styles = {
      div: {
        padding: 8,
        display: 'grid',
        gridTemplateColumns: `${size || '32px'} auto`,
        gridTemplateRows: '50',
        gridColumnGap: 18,
        // gridRowGap: 18,
      },
      textName: {
        size: 14,
        textAlign: 'left',
        gridColumn: 2,
        gridRow: 1,
        fontWeight: 600,
      },
      textCorp: {
        size: 14,
        textAlign: 'left',
        gridColumn: 2,
        gridRow: 2,
        color: "#01799a"
      },
      image: {
        gridColumn: 1,
        gridRowStart: 1,
        gridRowEnd: 3,
      },
      selected: {
        backgroundColor: '#222',
      }
    };

    let style = styles.div;
    if (selected) {
      style = { ...style, ...styles.selected };
    }

    return (
      <div style={style} onClick={this.handleClick}>
        <div style={styles.image}>
          <RoundImage size={size} src={src}></RoundImage>
        </div>
        <span style={styles.textName}>{name}</span>
        {corp && <span style={styles.textCorp}>[{corp}]</span>}
      </div>
    );
  }
}

Alt.propTypes = propTypes;
Alt.defaultProps = defaultProps;