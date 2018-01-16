import React, { Component } from 'react';
// import { Switch, Route } from 'react-router-dom';

export default class Orders extends Component {
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
                    <tr>git
                      <th>#</th>
                      <th>Статус</th>
                      <th>Имя Заказчика</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr className={"table-success"}>
                      <td>1</td>
                      <td>Готово</td>
                      <td>Василий</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>Ожидается...</td>
                      <td>Олександр</td>
                    </tr>
                    <tr className={"table-success"}>
                      <td>3</td>
                      <td>Готово</td>
                      <td>Андрей</td>
                    </tr>
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