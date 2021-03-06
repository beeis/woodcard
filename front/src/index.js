import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
// eslint-disable-next-line
import registerServiceWorker from './registerServiceWorker';
import { Provider } from 'react-redux';
import { createStore } from 'redux';
import reducers from './reducers';
import {HashRouter as Router} from 'react-router-dom';

let store = createStore(reducers);

ReactDOM.render(
    <Provider store={store}>
        <Router>
            <App />
        </Router>
    </Provider>
    , document.getElementById('root'));
// registerServiceWorker();
