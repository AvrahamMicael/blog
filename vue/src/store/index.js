import { createStore } from 'vuex';
import { guest, user, admin } from '../constants/Roles.js';


const store = createStore({
    state: {
        user: {
            data: {
                name: 'Micael',
                email: "micael@test.com"
            },
            token: 123,
            role: admin,
        },
        popup: null
    },
    getters: {},
    actions: {},
    mutations: {
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
                state.popup == 'login';
            }
            else
            {
                state.popup == 'register';
            }
        }
    },
    modules: {}
});

export default store;