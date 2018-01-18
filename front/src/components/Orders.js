import React, { Component } from 'react';
import axios from 'axios';
import {apiPoint, rootFolder} from '../constants/server';

export default class Orders extends Component {
  constructor() {
    super();
    this.state = {
      orders: {}
    }
  }

  _openOrder = (id) => {
    this.props.history.push(rootFolder+'/order/'+id);
  };

  componentDidMount() {
    axios.get(`${apiPoint}/admin/orders`).then((data) => {
      this.setState({
        orders: data.data.data
      });
      console.log(data.data.data);
    })
  };

  render () {
    return (
      <div className="container-fluid orders-container">
        <div className="row orders-header">
          Список Заказов
        </div>
        <div className="row">
          <div className="col-md-8 orders-border">
            <div className="row">
              <div className={"col-md-3"}>
                <input type="text" className={"form-control"} />
              </div>
              <div className={"col-md"}>
                <button type={"button"} className={"btn btn-primary"}>Перейти</button>
              </div>
            </div>
            <div className={"row"}>
              <div className={"col-md"}>
                <table className={"table orders-table table-hover table-striped"}>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Статус</th>
                      <th>Имя Заказчика</th>
                    </tr>
                  </thead>
                  <tbody>
                  {Object.values(this.state.orders).map((order) =>
                    <tr key={order.order_id} onClick={() => {this._openOrder(order.order_id)}}>
                      <td>{order.order_id}</td>
                      <td>{order.ttn_status}</td>
                      <td>{order.bayer_name}</td>
                    </tr>
                  )}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }
}