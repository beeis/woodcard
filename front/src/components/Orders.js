import React, { Component } from 'react';
import axios from 'axios';
import {apiPoint} from '../constants/server';
import { Link } from 'react-router-dom';

export default class Orders extends Component {
  constructor() {
    super();
    this.state = {
      orders: {},
      goId: '',
      name: '',
      phone: ''
    }
  }

  _openOrder = (id) => {
    this.props.history.push('/order/'+id);
  };

  _createOrder = () => {
      let {name, phone} = this.state;
      let formData = new FormData();
      formData.append("name", name);
      formData.append("phone", phone);

      axios.post(`${apiPoint}/order`, formData, {
          headers: {
              'content-type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest'
          }
      }).then((response) => {
        this._openOrder(response.data.order_id)
      }).catch(() => {
          console.log('Something went wrong!');
      });
  }

  componentDidMount() {
    axios.get(`${apiPoint}/admin/orders/new`).then((data) => {
      this.setState({
        orders: data.data.data
      });
    })
  };

  render () {
    return (
      <div className="container-fluid orders-container">
          <div className="row add-new-order">
          <div className="col-md-8 orders-border">
          <div className="row orders-header">
          Создать Заказ
      </div>
          <div>Имя</div>
          <input type="text" className={"form-control"}
      value={this.state.name} onChange={(e) => {this.setState({name: e.target.value})}}/>
          <div>Телефон</div>
          <input type="text" className={"form-control"}
      value={this.state.phone} onChange={(e) => {this.setState({phone: e.target.value})}}/>
          <button type={"button"} className={"btn btn-primary"} onClick={this._createOrder}>Создать</button>
      </div>
        </div>
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
                      <th>Телефон</th>
                      <th>Добавлено</th>
                      <th>Фото</th>
                    </tr>
                  </thead>
                  <tbody>
                  {this.state.orders && Object.values(this.state.orders).map((order) =>
                    <tr key={order.order_id} onClick={() => {this._openOrder(order.order_id)}}>
                      <td>{order.order_id}</td>
                      <td>{order.ttn_status}</td>
                      <td>{order.bayer_name}</td>
                      <td>{order.phone}</td>
                      <td>{order.created_at}</td>
                      <td>{order.has_item}</td>
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