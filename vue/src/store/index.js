import { createStore } from 'vuex';
import { guest, user, admin } from '../constants/Roles.js';
import axiosClient from '../axios.js';


const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem('token'),
            role: guest,
        },
        popup: null
    },
    getters: {},
    actions: {
        login({ commit }, user) {
            return axiosClient.post('/login', user)
                .then(( { data } ) => {
                    commit('changeAuthPopup', null);
                    commit('setUser', data);
                    return data;
                });
        },
        register({ commit }, user) {
            return axiosClient.post('/register', user)
                .then(( { data } ) => {
                    commit('changeAuthPopup', null);
                    commit('setUser', data);
                    return data;
                });
        }
    },
    mutations: {
        setUser(state, response) {
            // sessionStorage.setItem('token', response.token);
            state.user.token = response.token;
            state.user.data = response.user;
        },
        logout(state) {
            state.user.data = {};
            state.user.token = null;
            state.user.role = guest;
        },
        changeAuthPopup(state, content) {
            if(state.user.role == guest)
            {
                state.popup = content;
            }
        },
        changeAuthMethod(state) {
            if(state.popup == 'register')
            {
                state.popup = 'login';
            }
            else
            {
                state.popup = 'register';
            }
        }
    },
    modules: {}
});

export default store;