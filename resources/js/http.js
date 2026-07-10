import axios from 'axios';

const defaultJsonHeaders = {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
};

function mergeHeaders(extra = {}) {
    return {
        ...defaultJsonHeaders,
        ...extra,
    };
}

export function get(url, config = {}) {
    return axios.get(url, {
        ...config,
        headers: mergeHeaders(config.headers),
    });
}

export function post(url, data, config = {}) {
    return axios.post(url, data, {
        ...config,
        headers: mergeHeaders(config.headers),
    });
}

export function postForm(url, data, config = {}) {
    return post(url, data, config);
}

export function postJson(url, data, config = {}) {
    return post(url, data, {
        ...config,
        headers: mergeHeaders({
            'Content-Type': 'application/json',
            ...(config.headers || {}),
        }),
    });
}

export function put(url, data, config = {}) {
    return axios.put(url, data, {
        ...config,
        headers: mergeHeaders(config.headers),
    });
}

export function del(url, config = {}) {
    return axios.delete(url, {
        ...config,
        headers: mergeHeaders(config.headers),
    });
}

export function postText(url, data, config = {}) {
    return post(url, data, {
        ...config,
        responseType: 'text',
    });
}

const http = {
    get,
    post,
    postForm,
    postJson,
    put,
    delete: del,
    postText,
};

export default http;
