import React, { Component } from 'react';
// import { Switch, Route } from 'react-router-dom';

export default class Orders extends Component {
  render () {
    return (
      <div class="alert alert-primary not-found" role="alert">
        Order id is: {this.props.match.params.id}
      </div>
    )
  }
}