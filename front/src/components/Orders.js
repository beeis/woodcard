import React, { Component } from 'react';
import axios from 'axios';
import {apiPoint} from '../constants/server';
import { Link } from 'react-router-dom';

export default class Orders extends Component {
  constructor() {
    super();
    this.state = {
      orders: {},
      goId: ''
    }
  }

  _openOrder = (id) => {
    this.props.history.push('/order/'+id);
  };

  componentDidMount() {
    axios.get(`${apiPoint}/admin/orders`).then((data) => {
      this.setState({
        orders: data.data.data
      });
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
                <input type="text" className={"form-control"}
                       value={this.state.goId} onChange={(e) => {this.setState({goId: e.target.value})}}/>
              </div>
              <div className={"col-md"}>
                <Link to={`order/${this.state.goId}`}>
                  <button type={"button"} className={"btn btn-primary"}>Перейти</button>
                </Link>
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
                  {this.state.orders && Object.values(this.state.orders).map((order) =>
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