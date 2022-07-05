<template>
    <main class="container">
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
        <div class="row justify-content-center">
            <div class="card col-md-10 mb-4">
                <form @submit.prevent="savePost" class="card-body">
                    <Input
                        v-model="post.title"
                        name="title"
                        label="title"
                        :required="true"
                    />
                    <!-- <Input v-model="post.main_img" name="Main Image" type="file"/> -->
                    <div
                        v-for="(body_content, index) in post.body"
                        :key="`body-${index}`"
                    >
                        <TextArea
                            v-if="body_content.type == 'text'"
                            v-model="body_content.value"
                            :name="index == 0 ? 'content' : null"
                            :required="true"
                        />
                        <div
                            v-else
                            class="row justify-content-center mt-3"
                        >
                            <PostImg
                                :content_src="body_content.src"
                                :index="index"
                            />
                            <div class="col-md-6">
                                <InputImg
                                    @change="onFileChange"
                                    type="file"
                                    v-model="body_content.value"
                                    :name="`images[${index}]`"
                                    :required="true"
                                />
                            </div>
                        </div>
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
            </div>
        </div>
    </main>
</template>

<script>
import Input from '../components/Input.vue';
import Select from '../components/Select.vue';
import TextArea from '../components/TextArea.vue';
import Popup from '../components/Popup.vue';
import PostImg from '../components/PostImg.vue';
import InputImg from '../components/InputImg.vue';

export default {
    data() {
        return {
            post: {
                title: '',
                body: [{
                    type: 'text',
                    value: ''
                }],
                // main_img: 
            },
            addContentPopup: false,
            new_content_type: 'text'
        };
    },
    components: {
        Input,
        Popup,
        TextArea,
        Select,
        PostImg,
        InputImg
    },
    methods: {
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
                .then(res => {
                    this.$router.push({
                        name: 'Post',
                        params: { id: res.data.id }
                    });
                });
        }
    }
};
</script>
