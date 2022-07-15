import { createStore } from 'vuex';
import axiosClient from '../axios.js';

const store = createStore({
    state: {
        user: {
            data: JSON.parse(sessionStorage.getItem('user.data')) ?? {},
            token: JSON.parse(sessionStorage.getItem('user.token'))
        },
        subscribers: null,
        popup: null,
        loader: false,
        posts: {
            links: [],
            data: [],
            showedPosts: sessionStorage.showedPosts
                ? JSON.parse(sessionStorage.showedPosts)
                : []
        },
    },
    getters: {},
    actions: {
        getSubscribersNumber({ commit }) {
            return axiosClient.get('/subscriber')
                .then(( { data } ) => commit('setSubscribers', data))
                .catch(() => commit('setSubscribers', ''));
        },
        showPost({ commit }, slug) {
            return axiosClient.get(`/post/${slug}`)
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
        async saveComment({ commit, state }, comment) {
            commit('toggleLoader');
            let promise;
            const fd = new FormData();
            fd.append('body', comment.body);
            fd.append('id_post', comment.id_post);
            if(!state.user.token)
            {
                fd.append('user_name', comment.user_name);
                fd.append('email', comment.email);
            }

            if(comment.id)
            {

            }
            else
            {
                promise = axiosClient
                    .post('/comment', fd);
            }

            return await promise.finally(() => commit('toggleLoader'));
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
        setSubscribers(state, qty) {
            state.subscribers = qty;
        },
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
            let showed_posts = state.posts.showed_posts.filter(post => post.id != updated_post.id);
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