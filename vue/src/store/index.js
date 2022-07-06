import { createStore } from 'vuex';
import { user, admin } from '../constants/Roles.js';
import axiosClient from '../axios.js';

const tempPosts = [
    {
        id: 100,
        title: "About Something",
        slug: "about-something",
        body: [
            {type: "text", value: "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Incidunt, molestias. Esse aut fuga expedita? Molestiae eveniet fugiat commodi sequi nisi aperiam ducimus dolores, ex reiciendis iusto dolor, maxime distinctio numquam. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Incidunt, molestias. Esse aut fuga expedita? Molestiae eveniet fugiat commodi sequi nisi aperiam ducimus dolores, ex reiciendis iusto dolor, maxime distinctio numquam."},
            {type: "text", value: "Just testing."},
            {type: "text", value: "Just testing more."},
        ],
        created_at: "2022-06-28 10:00:00",
        updated_at: "2022-06-28 10:00:00",
        comments: [
            {
                id: 423,
                id_user: 1,
                user_name: "Karl",
                body: "Nice information",
                created_at: "2022-06-28 11:00:00",
                updated_at: "2022-06-28 11:00:00",
            },
            {
                id: 424,
                id_user: 2,
                user_name: "May",
                body: "Nice information2",
                created_at: "2022-06-28 12:00:00",
                updated_at: "2022-06-28 12:00:00",
            }
        ]
    }
];

const store = createStore({
    state: {
        user: {
            data: JSON.parse(sessionStorage.getItem('user.data')) ?? {},
            token: JSON.parse(sessionStorage.getItem('user.token'))
        },
        popup: null,
        loader: false,
        posts: [...tempPosts]
    },
    getters: {},
    actions: {
        async submitAuthForm({ commit }, user) {
            let auth_errors = {};
            this.commit('toggleLoader');

            await axiosClient.post(`/${this.state.popup}`, user)
                .then(( { data } ) => {
                    commit('changeAuthPopup', null);
                    commit('setUser', data);
                    return data;
                })
                .catch(error => auth_errors = error.response.data.errors)
                .finally(() => this.commit('toggleLoader'));
            return auth_errors;
        },
        async savePost({ commit }, post) {
            this.commit('toggleLoader');
            let response;
            const fd = new FormData();
            fd.append('title', post.title);
            post.body.forEach(( body_content, index ) => {
                fd.append(`body[${index}][type]`, body_content.type);
                fd.append(`body[${index}][value]`, body_content.value);
            });

            if(post.id)
            {
                response = await axiosClient
                    .put(`/post/${post.id}`, fd)
                    .then(res => {
                        commit('updatePost', res.data);
                        return res;
                    });
            }
            else
            {
                response = await axiosClient
                    .post('/post', fd)
                    .then(res => {
                        commit('savePost', res.data);
                        return res;
                    });
            }
            this.commit('toggleLoader');
            return response;
        },
        logout({ commit }) {
            this.commit('toggleLoader');
            return axiosClient.post('/logout')
                .then(res => {
                    commit('logout');
                    return res;
                })
                .finally(() => this.commit('toggleLoader'));
        }
    },
    mutations: {
        toggleLoader(state) {
            state.loader = !state.loader;
        },
        savePost(state, post) {
            state.posts = [...state.posts, post];
        },
        updatePost(state, updated_post) {
            state.posts = state.posts.map(post => {
                if(post.id == updated_post.id)
                {
                    return updated_post;
                }

                return post;
            });
        },
        setUser(state, res) {
            sessionStorage.setItem('user.token', JSON.stringify(res.token));
            state.user.token = res.token;

            sessionStorage.setItem('user.data', JSON.stringify(res.user));
            state.user.data = res.user;
        },
        logout(state) {
            sessionStorage.clear();
            state.user.data = {};
            state.user.token = null;
        },
        changeAuthPopup(state, content) {
            if(! state.user.data.role)
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