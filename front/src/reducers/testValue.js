import { CHANGE_TEST } from "../constants/actions";

const initialState = {
    testValue: ''
};

export default function testValue(state = initialState, action) {
    if(action.type === CHANGE_TEST) {
        const { testValue } = action.payload;
        return {
            ...state,
            testValue: testValue
        }
    } else {
        return state;
    }
}