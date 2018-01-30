import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { apiPoint } from "../constants/server";
import Dropzone from 'react-dropzone';
import { amazon } from '../constants/server';
import Preview from '../containers/PreviewContainer';
import Comment from './OrderItem/Comment';
import Inscription from "./OrderItem/Inscription";

export default class Orders extends Component {
  constructor() {
    super();
    this.state = {
      orderInfo: {},
      items: [],
      newItemComment: '',
      newItemFile: null,
      loadedImage: null,
      preview: ''
    };
    this._refreshItems = this._refreshItems.bind(this);
    this.onDropImage = this.onDropImage.bind(this);
  };

  componentDidMount() {
    this._refreshItems();
  }

  _refreshItems = () => {
    axios.get(`${apiPoint}/admin/orders/${this.props.match.params.id}`).then((data) => {
      this.setState({
        orderInfo: data.data.order.data,
        items: data.data.items
      });
    });
  };

  onDropMock = (files, id) => {
    let formData = new FormData();
    formData.append("file", files[0]);
    axios.post(`${apiPoint}/admin/order_item/${id}/upload/model`, formData).then((response) => {
      console.log(response);
      this._refreshItems();
    }).catch(() => {
      console.log('Возникла проблема при закачке файла.');
    });
  };

  onDropImage = (files) => {
    const reader = new FileReader();

    reader.onload = (e) => {
      this.setState({loadedImage: e.target.result});
    };

    reader.readAsDataURL(files[0]);
    this.setState({
      newItemFile: files[0]
    });
  };

  onDropPSD = (files, id) => {
    let formData = new FormData();
    formData.append("file", files[0]);
    axios.post(`${apiPoint}/admin/order_item/${id}/upload/psd`, formData).then((response) => {
      this._refreshItems();
    }).catch(() => {
      alert('Возникла проблема при закачке файла.');
    });
  };

  newItemCommentChange = (e) => {
    this.setState({newItemComment: e.target.value});
  };

  createNewItem = () => {
    if (this.state.newItemFile) {
      let formData = new FormData();
      formData.append("file", this.state.newItemFile);
      axios.post(`${apiPoint}/admin/orders/${this.props.match.params.id}`, formData).then((response) => {
        if(this.state.newItemComment) {
          axios.post(`${apiPoint}/admin/order_item/${response.data.id}/comment`, {
            "comment": this.state.newItemComment
          }, {
            headers: {
              'content-type': 'multipart/form-data'
            }
          });
        }
      }).then(() => {
        this._refreshItems();
      }).catch(() => {
        console.log('Something went wrong!');
      });
    } else {
      alert('Пожалуйста загрузите фото');
    }
  };

  _showPreviewWith = (image) => {
    this.props.togglePreview();
    this.setState({preview: image});
  };

  toggleActive = (e, id) => {
    let passData = false;
    if(e.target.checked) {
      passData = true;
      e.target.checked = false;
    } else {
      e.target.checked = "checked";
    }
    axios.post(`${apiPoint}/admin/order_item/${id}/active`, {
      "active": passData
    }, {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }).then(() => {
      this._refreshItems();
    }).catch(() => {
      alert('Возникла проблема при закачке файла.');
    });
  };

  _duplicate = (id) => {
    axios.post(`${apiPoint}/admin/order_item/${id}/duplicate`).then(() => {
      this._refreshItems();
    }).catch(() => {
      console.log('Something went wrong!');
    });
  };

  render () {
    return (
      <div className="container-fluid orders-container">
        {this.props.preview && <Preview url={this.state.preview}/>}
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
            Имя Заказчика: {this.state.orderInfo.bayer_name}
          </div>
        </div>
        <div className="row order-items-row">
          <div className="col-md-12 orders-border">
            <div className={"row"}>
              <div className={"col-md"}>
                <table className={"table orders-table order-items-table"}>
                  <thead>
                  <tr style={{textAlign: 'center'}}>
                    <th>#</th>
                    <th>Фото</th>
                    <th>Макет</th>
                    <th>PSD</th>
                    <th>Комментарий</th>
                    <th>Надпись</th>
                    <th>Активный</th>
                    <th>Print</th>
                    <th>Создано</th>
                    <th>Обновлено</th>
                    <th>Дублировать</th>
                    <th>Activity</th>
                  </tr>
                  </thead>
                  <tbody>
                  {this.state.items.map((item, index) =>
                    <tr className={!item.active ? "item-no-active" : ""} style={{textAlign: 'center'}} key={index}>
                      <td>{index+1}</td>
                      <td>{item.photo ? <img alt='макет' className = "img-preview" src={amazon+item.photo}
                         onClick={(e) => this._showPreviewWith(e.target.src)}/> : 'no image found'}</td>
                      <td>
                        {item.model ?
                          <img alt='psd' className = "img-preview" src={amazon+item.model}
                          onClick={(e) => this._showPreviewWith(e.target.src)} /> :
                          <Dropzone accept=".jpeg,.jpg,.png,.svg" style={styles.dropZoneStyle} onDrop={(files) => this.onDropMock(files, item.id)}>
                            <button className={"btn btn-primary btn-sm btn-add-sm"}>+</button>
                          </Dropzone>
                        }
                      </td>
                      <td>
                        {item.psd ? <a href={amazon+item.psd} download>Скачать</a> :
                          <Dropzone style={styles.dropZoneStyle} onDrop={(files) => this.onDropPSD(files, item.id)}>
                            <button className={"btn btn-primary btn-sm btn-add-sm"}>+</button>
                          </Dropzone>
                        }
                      </td>
                      <Comment comment={item.comment} id={item.id} />
                      <Inscription inscription={item.inscription} id={item.id} />
                      <td align={"center"}>
                          <input type={"checkbox"} checked={item.active ? "checked" : false} onChange={(e) => this.toggleActive(e, item.id)} />
                      </td>
                      <td>{item.print ? <a href={amazon+item.print} download>Print</a> : '-'}</td>
                      <td>
                        {item.created_at}
                      </td>
                      <td>
                        {item.updated_at}
                      </td>
                      <td>
                        <button className={"btn btn-warning btn-sm btn-add-sm"} onClick={() => {this._duplicate(item.id)}}>C</button>
                      </td>
                      <td>
                        <Link to={"/activity/"+item.id}><button className={"btn btn-warning btn-sm btn-add-sm"}>A</button></Link>
                      </td>
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
            <Dropzone style={this.state.loadedImage ? {width: '50px', height: '50px'} : {width: '27px', height: '27px'}} onDrop={(files) => this.onDropImage(files)}>
              {this.state.loadedImage ? <img alt="фото" src={this.state.loadedImage} className={"img-preview"} /> :<button className={"btn btn-primary btn-sm btn-add-sm"}>+</button>}
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

const styles = {
  dropZoneStyle: {
    margin: 'auto', width: '27px', height: '27px'
  }
};
