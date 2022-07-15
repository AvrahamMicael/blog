import store from './store';
import router from './router';
import axiosClient from './axios.js';

export async function getPostBySlug(slug)
{
    return store.state.posts.data.find(post => post.slug == slug)
        ?? await axiosClient.get(`/post/${slug}`)
            .then(( { data } ) => data)
            .catch(() => null)
        ?? router.push({
            name: 'NotFound'
        });
}

export function formatDate(date_from_db, withHours = false) {
    const date = new Date(date_from_db);
    let date_string = `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
    return withHours
        ? `${date_string} ${date.getHours()}:${date.getMinutes()}`
        : date_string;
}
