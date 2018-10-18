
export default class TableStyles {
  static styles = {
  table: {
    marginLeft: 12,
    display: 'table',
    width: '100%',
    padding: '16px',
  },
  header: {
    display: 'table-header-group',
    fontWeight: 'bold',
    paddingBottom: '19px',
    color: 'darkgoldenrod',
  },
  body: {
    display: 'table-row-group',
  },
  row: {
    display: 'table-row',
    padding: '6px',
  },
  cell: {
    textAlign: 'left',
    display: 'table-cell',
    padding: '6px',
  },
  isOdd: {
    backgroundColor: '#222',
  },
}
}
