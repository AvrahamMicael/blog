import store from './store';
import router from './router';

export async function checkIfIdIsNumberAndTryToGetThePost(route_param_id)
{
    const id_post = parseInt(route_param_id);
    return isNaN(id_post)
        ? router.push({ name: 'NotFound' })
        : null
        ?? store.state.posts.data.find(post => post.id == id_post)
        ?? store.state.posts.showedPosts.find(post => id_post == post.id)
        ?? await store.dispatch('showPost', id_post)
        ?? router.push({
            name: 'NotFound'
        });
}
