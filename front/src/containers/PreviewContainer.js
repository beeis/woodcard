import { connect } from 'react-redux';
import Preview from '../components/Preview';
import { togglePreview } from "../actions/index";
import { bindActionCreators } from 'redux';

function mapDispatchToProps(dispatch) {
  return bindActionCreators({ togglePreview }, dispatch);
}

export default connect(null, mapDispatchToProps)(Preview);