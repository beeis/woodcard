import React, { Component } from 'react';
import { Switch, Route } from 'react-router';
import Orders from './Orders';
import NotFound from './NotFound';
import Order from '../containers/OrderContainer';

export default class MainScreen extends Component {
    render () {
        return (
            <main>
                <Switch>
                    <Route exact path = {'/'} component={Orders} />
                    <Route path = {'/order/:id'} component={Order} />
                    <Route component={NotFound} />
                </Switch>
            </main>
        )
    }
}
