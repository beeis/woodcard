import { CHANGE_TEST, TOGGLE_PREVIEW } from '../constants/actions';

export function changeTestValue(value) {
    return {
        type: CHANGE_TEST,
        payload: {
            testValue: value
        }
    }
}

export function togglePreview(){
    return {
        type: TOGGLE_PREVIEW
    }
}

