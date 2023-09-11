<template>
    <div class="photo-list">
        <div class="grid" v-for="url in urls">
            <img :src="url" alt="" />
        </div>
    </div>
</template>

<script setup>
// refについて
// refはリアクティブな変数を定義するためのものです。
// 変数が変更された場合、即座にその変更を検知し、DOMに反映させることが可能です。
// template内ではurlsのように通常の変数として使用できますが、
// <script>内ではurls.valueとしてアクセスする必要があります。
// 定義の際はconst 定数名 = ref(初期値);の形式で行います。
import { ref } from "vue";
import PhotosRepository from "./../repository/photos.js";

const urls = ref([]);

const getPhoto = async () => {
    await PhotosRepository.getPhoto()
        .then((data) => {
            // map関数を使用して一つづつurl定数に格納していく
            urls.value = data.map((item) => item);
        })
        .catch((error) => {
            alert("画像の取得に失敗しました。");
            console.log(error);
        })
        .finally(() => {});
};

const init = () => {
    getPhoto();
};

init();
</script>

<style scoped>
.photo-list {
    display: flex;
}
.grid {
    width: 10rem;
    height: 10rem;
    margin: 1rem;
}
</style>
