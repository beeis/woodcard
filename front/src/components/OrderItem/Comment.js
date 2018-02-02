import React, { Component } from 'react';
import axios from 'axios';
import { apiPoint }from '../../constants/server';

export default class Comment extends Component {
  constructor(props){
    super(props);
    this.state = {
      show: false,
      comment: props.comment
    };
    this._handleKeyPress = this._handleKeyPress.bind(this);
  }

  _handleKeyPress = (e) => {
    if(e.key === 'Enter') {
      this.setState({show: false});
      if(this.props.comment !== this.state.comment) {
        axios.post(`${apiPoint}/admin/order_item/${this.props.id}/comment`, {
          comment: this.state.comment
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
      <td className={"c-pointer"} onClick={() => this.setState({show: true})}>
        {!this.state.show && <span>{this.state.comment ? this.state.comment : '-'}</span>}
        {
          this.state.show &&
          <input type={"text"} value={this.state.comment || ''} onChange={(e) => this.setState({comment: e.target.value})}
                 onKeyPress={this._handleKeyPress} />
        }
      </td>
    );
  }
}