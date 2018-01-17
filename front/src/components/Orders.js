import React, { Component } from 'react';

export default class Orders extends Component {
  constructor() {
    super();
    this.state = {
      orders: [
        {id: 1, status: 1, name: 'Василий'},
        {id: 2, status: 0, name: 'Олександр'},
        {id: 3, status: 1, name: 'Андрей'}
      ]
    }
  }

  _openOrder = (id) => {
    this.props.history.push('/order/'+id);
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
                <table className={"table orders-table table-hover"}>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Статус</th>
                      <th>Имя Заказчика</th>
                    </tr>
                  </thead>
                  <tbody>
                  {this.state.orders.map((order) =>
                    <tr className={ order.status && 'table-success'} onClick={() => {this._openOrder(order.id)}}>
                      <td>{order.id}</td>
                      <td>{order.status ? 'Готово' : 'Ожидается'}</td>
                      <td>{order.name}</td>
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