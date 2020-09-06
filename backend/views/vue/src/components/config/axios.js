import axios_main from 'axios';
export const axios = axios_main.create({
    baseURL: `/api/general/`
});
