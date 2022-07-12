import { createStore } from 'vuex';
import { user, admin } from '../constants/Roles.js';
import axiosClient from '../axios.js';
import axios from 'axios';

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
        posts: {
            links: [],
            data: [],
            showedPosts: sessionStorage.showedPosts
                ? [...JSON.parse(sessionStorage.showedPosts)]
                : []
        },
    },
    getters: {},
    actions: {
        showPost({ commit }, id_post) {
            return axiosClient.get(`/post/${id_post}`)
                .then(( { data } ) => {
                    commit('addPostToShowedPosts', data);
                    return data;
                })
                .catch(() => null);
        },
        getHomePosts({ commit, state }, url = null) {
            url = url || '/post';
            state.posts.data = [];
            return axiosClient.get(url)
                .then(( { data } ) => {
                    commit('setHomePosts', data.data);
                    commit('setPostsLinks', data.links);
                    return null;
                })
                .catch(( { response } ) => response.data ?? 'Connection Error.');
        },
        deletePost({ commit }, post) {
            commit('toggleLoader');
            return axiosClient.delete(`/post/${post.id}`)
                .then(() => commit('deletePost', post))
                .finally(() => commit('toggleLoader'));
        },
        async submitAuthForm({ commit }, user) {
            let auth_errors = {};
            commit('toggleLoader');

            await axiosClient.post(`/${this.state.popup}`, user)
                .then(( { data } ) => {
                    commit('changeAuthPopup', null);
                    commit('setUser', data);
                    return data;
                })
                .catch(error => auth_errors = error.response.data.errors)
                .finally(() => commit('toggleLoader'));
            return auth_errors;
        },
        async savePost({ commit }, post) {
            commit('toggleLoader');
            let response;
            const fd = new FormData();
            fd.append('title', post.title);
            post.body.forEach(( body_content, index ) => {
                fd.append(`body[${index}][type]`, body_content.type);
                fd.append(`body[${index}][value]`, body_content.value);
                if(post.id && body_content.id)
                {
                    fd.append(`body[${index}][id]`, body_content.id);
                }
            });
            
            if(post.id)
            {
                fd.append('id', post.id);
                fd.append('_method', 'PUT');

                response = await axiosClient
                    .post(`/post/${post.id}`, fd)
                    .then(( { data } ) => {
                        commit('updatePost', data);
                        return data;
                    })
                    .catch(response => console.log('error', response));
            }
            else
            {
                response = await axiosClient
                    .post('/post', fd)
                    .then(( { data } ) => {
                        commit('addPost', data);
                        return data;
                    });
            }
            commit('toggleLoader');
            return response;
        },
        logout({ commit }) {
            commit('toggleLoader');
            return axiosClient.post('/logout')
                .then(res => {
                    commit('logout');
                    return res;
                })
                .finally(() => commit('toggleLoader'));
        }
    },
    mutations: {
        setHomePosts(state, posts) {
            state.posts.data = [...posts];
        },
        addPostToShowedPosts(state, showed_post) {
            state.posts.showedPosts = [
                ...state.posts.showedPosts,
                showed_post
            ];
            sessionStorage.showedPosts = JSON.stringify(state.posts.showedPosts);
        },
        setPostsLinks(state, posts_links) {
            state.posts.links = posts_links;
        },
        deletePost(state, deleted_post) {
            state.posts.data = state.posts.data.filter(post => {
                return deleted_post.id != post.id;
            });
            state.posts.showedPosts = state.posts.showedPosts.filter(post => {
                return deleted_post.id != post.id;
            });
        },
        toggleLoader(state) {
            state.loader = !state.loader;
        },
        addPost(state, added_post) {
            state.posts.data = [...state.posts.data, added_post];
        },
        addPosts(state, added_posts) {
            state.posts.data = [...state.posts.data, ...added_posts];
        },
        updatePost(state, updated_post) {
            let showed_posts = sessionStorage.showedPosts
                ? JSON.parse(sessionStorage.showedPosts).filter(post => post.id != updated_post.id)
                : [];
            state.posts.showedPosts = [...showed_posts, updated_post];
            sessionStorage.showedPosts = JSON.stringify(state.posts.showedPosts);
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