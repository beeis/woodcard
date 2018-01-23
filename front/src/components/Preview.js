import React, { Component } from 'react';
import download from '../images/download.png';

export default class Preview extends Component {
  render() {
    return (
      <div className={"img-preview-popup"}>
        <div className={"img-preview-content"}>
          <img src={this.props.url} />
          <a className={"preview-download"} href={this.props.url}><img src={download} /></a>
          <button className={"preview-close"} onClick = {() => {this.props.togglePreview();}}>x</button>
        </div>
      </div>
    )
  }
}