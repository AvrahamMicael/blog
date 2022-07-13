<template>
    <article>
        <Popup
            v-if="addContentPopup"
            title="Add More"
            @toggle="toggleAddContentPopup"
        >
            <Select
                v-model="new_content_type"
                name="type"
                :options="['text', 'image']"
            />
            <div class="text-center mt-4">
                <button
                    @click="
                        insertContentToPostBody(new_content_type),
                        toggleAddContentPopup()
                    "
                    class="btn btn-outline-primary"
                >
                    <i class="fa-solid fa-plus"/>
                </button>
            </div>
        </Popup>
        <form @submit.prevent="savePost">
            <Input
                v-model="post.title"
                label="title"
                :required="true"
            />
            <div
                v-for="(body_content, index) in post.body"
                :key="`body-${index}`"
                @mouseenter="showUpDownArrows(index)"
                @mouseleave="closeUpDownArrows(index)"
                class="row"
            >
                <TextArea
                    v-if="body_content.type == 'text'"
                    v-model="body_content.value"
                    :name="index == 0 ? 'content' : null"
                    :required="true"
                    :class="getRowColClassFormat(body_content)"
                />
                <div
                    v-else
                    class="row justify-content-center mt-3"
                >
                    <PostImg
                        :content_src="body_content.src"
                        :index="index"
                        :classes="getRowColClassFormat(body_content)"
                    />
                    <UpDownArrows
                        v-if="
                            body_content.src
                            && body_content.hover
                        "
                        @click-arrow="clickArrow(index, $event)"
                    />
                    <div class="col-md-6">
                        <InputImg
                            @change="onFileChange"
                            v-model="body_content.value"
                            :name="`images[${index}]`"
                            :required="!body_content.id"
                        />
                    </div>
                </div>
                <UpDownArrows
                    v-if="
                        body_content.type == 'text'
                        && body_content.hover
                    "
                    @click-arrow="clickArrow(index, $event)"
                />
                <div v-if="index != 0" class="text-end mt-2">
                    <a href="javascript:;" @click="removeBodyContent(index)" class="btn btn-outline-danger">
                        <i class="fa-solid fa-x"/>
                    </a>
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="javascript:;" @click="toggleAddContentPopup" class="btn btn-outline-primary">
                    <i class="fa-solid fa-plus"/>
                </a>
                &nbsp;
                <button class="btn btn-outline-success">
                    <i class="fa-solid fa-check"/>
                </button>
            </div>
        </form>
    </article>
</template>

<script>
import { getPostBySlug } from '../common-functions.js';
import Input from '../components/Input.vue';
import Select from '../components/Select.vue';
import TextArea from '../components/TextArea.vue';
import Popup from '../components/Popup.vue';
import PostImg from '../components/PostImg.vue';
import InputImg from '../components/InputImg.vue';
import UpDownArrows from '../components/UpDownArrows.vue';

const initialState = () => ({
    post: {
        title: '',
        body: [{
            type: 'text',
            value: '',
        }],
    },
    addContentPopup: false,
    new_content_type: 'text',
});

export default {
    data: () => initialState(),
    watch: {
        $route(to, from) {
            if(from.name == 'EditPost')
                Object.assign(this.$data, initialState());
        }
    },
    components: {
        Input,
        Popup,
        TextArea,
        Select,
        PostImg,
        InputImg,
        UpDownArrows
    },
    methods: {
        clickArrow(index, arrowValue) {
            const otherContentIndex = index + arrowValue;
            const canChange = ! [0, this.post.body.length].includes(otherContentIndex);
            if(canChange)
            {
                [
                    this.post.body[index],
                    this.post.body[otherContentIndex]
                ] = [
                    this.post.body[otherContentIndex],
                    this.post.body[index]
                ];
            }
        },
        getRowColClassFormat(body_content) {
            return body_content.hover
                ? 'col-11'
                : 'col-12';
        },
        showUpDownArrows(content_index) {
            if(content_index > 0) 
                this.post.body[content_index].hover = true;
        },
        closeUpDownArrows(content_index) {
            if(content_index > 0) 
                delete this.post.body[content_index].hover;
        },
        toggleAddContentPopup() {
            this.addContentPopup = !this.addContentPopup;
        },
        insertContentToPostBody(type) {
            this.post.body.push({
                type,
                value: ''
            });
        },
        removeBodyContent(index) {
            this.post.body.splice(index, 1);
        },
        onFileChange(ev) {
            const file = ev.target.files[0];
            const name = ev.target.attributes.name.value;
            const index = this.getIndexFromArrayString(name);
            this.post.body[index].src = URL.createObjectURL(file);
            URL.revokeObjectURL(file);
        },
        getIndexFromArrayString(string) {
            return string.slice(string.indexOf('[') + 1, string.indexOf(']'));
        },
        savePost() {
            this.$store
                .dispatch('savePost', this.post)
                .then(post => {
                    this.$router.push({
                        name: 'Post',
                        params: { slug: post.slug }
                    });
                });
        }
    },
    async created() {
        if(this.$route.name == 'EditPost')
        {
            this.post = await getPostBySlug(this.$route.params.slug);
            this.post.body.forEach(body_content => {
                if(body_content.type == 'image')
                {
                    body_content.src = body_content.value;
                    body_content.value = '';
                }
                return body_content;
            });
        }
    },
};
</script>
