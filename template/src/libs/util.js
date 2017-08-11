import axios from 'axios';
import env from '../config/env';

let util = {

};
util.title = function(title) {
    title = title ? title + ' - Home' : 'iView project';
    window.document.title = title;
};

const ajaxUrl = env === 'development' ?
    'http://127.0.0.3' :
    env === 'production' ?
    'http://127.0.0.3' :
    'http://127.0.0.3';

util.ajax = axios.create({
    baseURL: ajaxUrl,
    timeout: 30000
});

export default util;