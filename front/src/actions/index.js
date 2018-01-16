import { CHANGE_TEST } from '../constants/actions';

export function changeTestValue(value) {
    return {
        type: CHANGE_TEST,
        payload: {
            testValue: value
        }
    }
}

