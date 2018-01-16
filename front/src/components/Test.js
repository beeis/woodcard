import React, { Component } from 'react';

export default class TestScreen extends Component {
    render () {
        return (
            <div>Test Value is: {this.props.testValue}</div>
        );
    }
}