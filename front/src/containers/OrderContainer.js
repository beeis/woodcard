import { connect } from 'react-redux';
import Order from '../components/Order';
import { togglePreview } from "../actions/index";
import { bindActionCreators } from 'redux';

function mapDispatchToProps(dispatch) {
  return bindActionCreators({ togglePreview }, dispatch);
}

function mapStateToProps(state){
  return {
    preview: state.preview.opened
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(Order);