import React, { Component } from 'react';
import MainScreen from "./components/Main";
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';

class App extends Component {

    // render() {
    //     return (
    //         <div>
    //             <header>
    //                 <nav>
    //                     <ul>
    //                         <li><Link to='/show'>Show Test Value</Link></li>
    //                         <li><Link to='/change/test'>Change Test Value</Link></li>
    //                     </ul>
    //                 </nav>
    //             </header>
    //             <MainScreen />
    //         </div>
    //     );
    // }
    render () {
        return (
          <MainScreen />
        )
    }
}

export default App;
