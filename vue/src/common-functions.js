import store from './store';
import router from './router';

export async function getPostBySlug(slug)
{
    return store.state.posts.data.find(post => post.slug == slug)
        ?? store.state.posts.showedPosts.find(post => post.slug == slug)
        ?? await store.dispatch('showPost', slug)
        ?? router.push({
            name: 'NotFound'
        });
}
