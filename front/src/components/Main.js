import React, { Component } from 'react';
import { Switch, Route } from 'react-router-dom';
import Orders from './Orders';
import NotFound from './NotFound';
import Order from './Order';
import { rootFolder } from '../constants/server';

export default class MainScreen extends Component {
    render () {
        return (
            <main>
                <Switch>
                    <Route exact path = {rootFolder} component={Orders} />
                    <Route path = {rootFolder+'/order/:id'} component={Order} />
                    <Route component={NotFound} />
                </Switch>
            </main>
        )
    }
}
