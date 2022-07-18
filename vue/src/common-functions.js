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
    let date_string = `${addZeroIfOneDigit(date.getMonth() + 1)}/${addZeroIfOneDigit(date.getDate())}/${date.getFullYear()}`;
    return withHours
        ? `
            ${date_string}
            ${addZeroIfOneDigit(date.getHours())}:${addZeroIfOneDigit(date.getMinutes())}
        `
        : date_string;

    function addZeroIfOneDigit(number)
    {
        return number.toString().length == 1
            ? `0${number}`
            : number;
    }
}
