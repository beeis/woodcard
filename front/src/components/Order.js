import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { rootFolder } from "../constants/server";
import axios from 'axios';
import { apiPoint } from "../constants/server";
import Dropzone from 'react-dropzone';
import { amazon } from '../constants/server';

const axiosFileUpload = require('axios-fileupload');

export default class Orders extends Component {
  constructor() {
    super();
    this.state = {
      orderInfo: {},
      items: [],
      newItemComment: '',
      newItemFile: null
    };
    this._refreshItems = this._refreshItems.bind(this);
    this.onDropImage = this.onDropImage.bind(this);
  };

  componentDidMount() {
    this._refreshItems()
  }

  _refreshItems = () => {
    axios.get(`${apiPoint}/admin/orders/${this.props.match.params.id}`).then((data) => {
      console.log(data.data);
      this.setState({
        orderInfo: data.data.order.data,
        items: data.data.items
      });
    });
  };

  onDropMock = (files, id) => {
    axiosFileUpload(`${apiPoint}/admin/order_item/${id}/upload/model`, files[0]).then((response) => {
      console.log(response);
      this._refreshItems();
    }).catch(() => {
      console.log('Возникла проблема при закачке файла.');
    });
  };

  onDropImage = (files) => {
    this.setState({
      newItemFile: files[0]
    });
  };

  onDropPSD = (files, id) => {
    axiosFileUpload(`${apiPoint}/admin/order_item/${id}/upload/psd`, files[0]).then((response) => {
      console.log(response);
      this._refreshItems();
    }).catch(() => {
      console.log('Возникла проблема при закачке файла.');
    });
  };

  newItemCommentChange = (e) => {
    this.setState({newItemComment: e.target.value});
  };

  //TODO: FIX ISSUE WITH NOT UPLOADING COMMENT
  createNewItem = () => {
    if (this.state.newItemFile) {
      axiosFileUpload(`${apiPoint}/admin/orders/${this.props.match.params.id}`, this.state.newItemFile).then((response) => {
        if(this.state.newItemComment) {
          axios.post(`${apiPoint}/admin/order_item/${response.data.id}/comment`, {
            comment: this.state.newItemComment
          }, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
        }
      });
    } else {
      alert('Пожалуйста загрузите фото');
    }
  };

  render () {
    return (
      <div className="container-fluid orders-container">
        <div className="row">
          <Link to={rootFolder} className={'btn btn-primary orders-link'}>Главная</Link>
        </div>
        <div className="row">
          <div className={"order-id"}>
            id: {this.props.match.params.id}
          </div>
        </div>
        <div className="row">
          <div className={"order-name"}>
            Имя Заказчика: {this.state.orderInfo.bayer_name}
          </div>
        </div>
        <div className="row order-items-row">
          <div className="col-md-8 orders-border">
            <div className={"row"}>
              <div className={"col-md"}>
                <table className={"table orders-table table-striped order-items-table"}>
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
                  {this.state.items.map((item, index) =>
                    <tr key={index}>
                      <td>{index+1}</td>
                      <td>{item.photo ? <img alt='макет' className = "img-preview" src={amazon+item.photo} /> : 'no image found'}</td>
                      <td>{item.model ? <img alt='psd' className = "img-preview" src={amazon+item.photo} /> : <Dropzone accept=".jpeg,.jpg,.png,.svg" style={{width: '27px', height: '27px'}} onDrop={(files) => this.onDropMock(files, item.id)}>
                        <button className={"btn btn-primary btn-sm btn-add-sm"}>+</button>
                      </Dropzone>}</td>
                      <td>
                        {item.psd ? <a href={amazon+item.psd} download>Скачать</a> :
                          <Dropzone style={{width: '27px', height: '27px'}} onDrop={(files) => this.onDropPSD(files, item.id)}>
                            <button className={"btn btn-primary btn-sm btn-add-sm"}>+</button>
                          </Dropzone>
                        }
                      </td>
                      <td>{item.comment ? item.comment : '-'}</td>
                    </tr>
                  )}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div className={'row order-add-row'}>
          <div>
            <p className="add-order-item-label">Добавить Item:</p>
            <Dropzone style={{width: '27px', height: '27px'}} onDrop={(files) => this.onDropImage(files)}>
              <button className={"btn btn-primary btn-sm btn-add-sm"}>+</button>
            </Dropzone>
            <div className={'form-group o-comment-add'}>
              <p className={"add-order-item-label"}>Комментарий:</p>
              <textarea className="form-control" id="o-comment" value={this.state.newItemComment} onChange={this.newItemCommentChange}/>
            </div>
            <button type={"submit"} className={'btn btn-primary'} onClick={this.createNewItem}>Добавить</button>
          </div>
        </div>
      </div>
    )
  }
}