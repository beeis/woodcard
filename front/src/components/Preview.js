import React, { Component } from 'react';

export default class Preview extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    console.log(this.props.url);
  }

  render() {
    return (
      <div className={"img-preview-popup"}>
        <div className={"img-preview-content"}>
          <img src={this.props.url} />
          <button className={"preview-close"} onClick = {() => {this.props.togglePreview();}}>x</button>
        </div>
      </div>
    )
  }
}