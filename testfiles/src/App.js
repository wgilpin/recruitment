import React, { Component } from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';
import './App.css';
import Evidence from './Evidence';
import Admin from './Admin';
import Home from './Home';
import Apply from './Apply';

class App extends Component {
  handleCick(id) {
    console.log('hello' + id)
  }

  render() {
    return (
      <Router basename="testfiles">
      <div className="App">
        <Route exact path="/" component={Home}/>
        <Route path="/evidence" component={Evidence}/>
        <Route path="/apply" component={Apply}/>
        <Route path="/admin" component={Admin}/>
      </div>
        {/* <div className="App">
          <Evidence>
          </Evidence>
        </div > */}
      </Router>
    );
  }
}

export default App;
