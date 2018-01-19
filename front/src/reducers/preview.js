import { TOGGLE_PREVIEW } from "../constants/actions";

const initialState = {
  opened: false
};

export default function preview(state = initialState, action) {
  if(action.type === TOGGLE_PREVIEW) {
    return {
      ...state,
      opened: !state.opened
    }
  } else {
    return state;
  }
}