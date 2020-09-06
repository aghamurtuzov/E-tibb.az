import axios from 'axios';

export const base_url = '/api/general';
export const delete_response = 0;

export function axiosGet (url) {
    return axios.get(url)
        .then(function (response) {
            return response.data.data;
        })
        .catch(function (error) {
            return 'An error occured..' + error;
        })
};

export function axiosPost(url, id) {
    return axios.post(base_url+url, {id})
        .then(function (response) {
            return response.data.data;
        })
        .catch(function (error) {
            return 'An error occured..' + error;
        })
};

export function sendMessage(url, id, message) {
    return axios.post(base_url+url, {id, message})
        .then(function (response) {
            return 'success';
        })
        .catch(function (error) {
            return 'error';
        })
};