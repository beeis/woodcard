import React, { Component } from 'react';
import { Link } from 'react-router-dom';

export default class Orders extends Component {
  constructor() {
    super();
  }

  render () {
    return (
      <div className="container-fluid orders-container">
        <div className="row">
          <Link to={'/'} className={'btn btn-primary orders-link'}>Главная</Link>
        </div>
        <div className="row">
          <div className={"order-id"}>
            id: {this.props.match.params.id}
          </div>
        </div>
        <div className="row">
          <div className={"order-name"}>
            Имя Заказчика: Tim Cook
          </div>
        </div>
        <div className="row">
          <div className="col-md-8 orders-border">
            <div className={"row"}>
              <div className={"col-md"}>
                <table className={"table orders-table table-striped"}>
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Фото</th>
                    <th>Макет</th>
                    <th>PSD</th>
                    <th>Комментарий</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1</td>
                    <td><button className={"btn btn-primary btn-sm btn-add-sm"}>+</button></td>
                    <td><button className={"btn btn-primary btn-sm btn-add-sm"}>+</button></td>
                    <td><a href="#">Скачать</a></td>
                    <td>Lorem ipsum</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td><button className={"btn btn-primary btn-sm btn-add-sm"}>+</button></td>
                    <td><button className={"btn btn-primary btn-sm btn-add-sm"}>+</button></td>
                    <td><a href="#">Скачать</a></td>
                    <td>Lorem ipsum</td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td><button className={"btn btn-primary btn-sm btn-add-sm"}>+</button></td>
                    <td><button className={"btn btn-primary btn-sm btn-add-sm"}>+</button></td>
                    <td><a href="#">Скачать</a></td>
                    <td>Lorem ipsum</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div className={'row order-add-row'}>
          <form>
            <input type='file' name="выбрать фото" />
            <div className={'form-group o-comment-add'}>
              <label for="o-comment">Комментарий</label>
              <textarea className="form-control" id="o-comment"/>
            </div>
            <button type={"submit"} className={'btn btn-primary'}>Добавить</button>
          </form>
        </div>
      </div>
    )
  }
}