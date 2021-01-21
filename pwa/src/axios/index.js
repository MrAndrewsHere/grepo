import axios from "axios"
import router from "../router";

export const HTTP = axios.create({
    baseURL: process.env.VUE_APP_API + '/api/',

})
const requestHandler = (request) => {

    request.headers['Authorization'] = 'Bearer ' + localStorage.getItem('token')
    return request
}
HTTP.interceptors.request.use(request => requestHandler(request));


HTTP.interceptors.response.use(res => res, error => {


    if (error.response.status === 404) {
        router.replace('/404')
    }


    if (process.env.NODE_ENV === 'production') {
        if (error.response.status.toString().startsWith('5')) {
            router.push('/500')
        }
    }


    throw  error;
});


