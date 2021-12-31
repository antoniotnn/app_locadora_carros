const { default: axios } = require('axios');

window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/* interceptar os requests da aplicação */
axios.interceptors.request.use(
    config => {

        //definir para todas as requisições os parâmtros de Accept e Authorization ( headers )
        config.headers['Accept'] = 'application/json'; //sintaxe de uma forma  OU (ler como foi passado no Authorization)

        //recuperando o token de Autorização dos cookies
        let token = document.cookie.split(';').find(indice => {
            return indice.includes('token=');
        });
        
        if (token) {
            token = token.split('=')[1];
            token = 'Bearer ' + token;
        } else {
            token = null;
        }

        config.headers.Authorization = token; //sintaxe de outra forma (exemplo)
    
        //console.log('Interceptando o request antes do envio', config);
        return config;
    },
    error => {
        console.log('Erro na requisição', error);
        return Promise.reject(error);
    }
);

/* interceptar responses da aplicação */

axios.interceptors.response.use(
    response => {
        console.log('Interceptando o Response antes da aplicação', response);
        return response;
    },
    error => {
        console.log('Erro na resposta', error);
        return Promise.reject(error);
    }
);
