import React, { Component } from 'react';
import axios from 'axios';
import { apiPoint }from '../../constants/server';

export default class Inscription extends Component {
  constructor(props){
    super(props);
    this.state = {
      show: false,
      inscription: props.inscription
    };
    this._handleKeyPress = this._handleKeyPress.bind(this);
  }

  _handleKeyPress = (e) => {
    if(e.key === 'Enter') {
      this.setState({show: false});
      if(this.props.inscription !== this.state.inscription) {
        axios.post(`${apiPoint}/admin/order_item/${this.props.id}/inscription`, {
          inscription: this.state.inscription
        }, {
          headers: {
            'content-type': 'multipart/form-data'
          }
        }).then((response) => {
          console.log(response);
        }).catch((e) => {
          console.log(e);
        });
      }
    }
  };

  render(){
    return (
      <td>
        {!this.state.show && <span onClick={() => this.setState({show: true})}>{this.state.inscription ? this.state.inscription : '-'}</span>}
        {
          this.state.show &&
          <input type={"text"} value={this.state.inscription || ''} onChange={(e) => this.setState({inscription: e.target.value})}
                 onKeyPress={this._handleKeyPress} />
        }
      </td>
    );
  }
}