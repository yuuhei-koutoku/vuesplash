<template>
    <div class="photo-form">
        <h2 class="title">Submit a photo</h2>
        <form class="form" enctype="multipart/form-data">
            <input class="form__item" type="file" ref="fileInput" />
            <div class="form__button">
                <button
                    type="submit"
                    class="button button--inverse"
                    @click.prevent="onClickAddImage"
                >
                    submit
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import PhotosRepository from "./../repository/photos.js";

const fileInput = ref(null);

const onClickAddImage = () => {
    postPhoto(fileInput.value.files[0]);
};

const postPhoto = async (file) => {
    await PhotosRepository.postPhoto(file)
        .then((data) => {
            console.log(data);
            alert("写真の投稿が完了しました");
        })
        .catch((error) => {
            if (error.response && error.response.status === 401) {
                alert("ログインしてください。");
            } else {
                alert("写真の投稿に失敗しました。");
            }
            console.log(error);
        })
        .finally(() => {});
};
</script>
