import React, { Component } from 'react';
import { Switch, Route } from 'react-router-dom';
import Orders from './Orders';
import NotFound from './NotFound';

export default class MainScreen extends Component {
    render () {
        return (
            <main>
                <Switch>
                    <Route exact path = '/' component={Orders} />
                    <Route component={NotFound} />
                </Switch>
            </main>
        )
    }
}
