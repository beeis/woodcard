import React, { Component } from 'react';
import axios from 'axios';
import { apiPoint }from '../../constants/server';

export default class Activity extends Component {
  constructor() {
    super();
    this.state = {
      activities: []
    };
  }

  componentDidMount() {
    axios.get(`${apiPoint}/admin/activity/${this.props.match.params.id}`).then((response) => {
      if(response.data.length) {
        this.setState({
          activities: response.data
        });
      }
    }).catch(() => {
      alert('The server is temporarily unavailable');
    });
  }

  render() {
    return (
      <div className="container-fluid orders-container">
        <div className="row">
          <div className="col-md-12 orders-border">
            <button className={"btn btn-primary"} onClick={() => this.props.history.goBack()}>&#60;- Вернуться</button>
            <table className={"table orders-table order-items-table"}>
              <thead>
              <tr style={{textAlign: 'left'}}>
                <th>Дата создания</th>
                <th>Изменения</th>
              </tr>
              </thead>
              <tbody>
                {this.state.activities.map((item, index) =>
                  <tr key={index}>
                    <td>{item.created_at}</td>
                    <td dangerouslySetInnerHTML={{__html: item.changed_comment}} />
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    )
  }
}