<template>
    <div class="photo__list">
        <div class="grid" v-for="url in filteredPhotos">
            <img :src="url.url" alt="" />
            <button
                class="photo__list-like"
                @click="onClickAddLike(url.id)"
                v-if="user && Object.keys(url).length === 2"
            >
                いいね！
            </button>
            <button
                class="photo__list-like"
                @click="onClickDeleteLike(url.id)"
                v-if="user && Object.keys(url).length === 3"
            >
                いいね解除
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import PhotosRepository from "./../repository/photos.js";
import LikeRepository from "./../repository/like.js";

const store = useStore();

onMounted(() => {
    store.dispatch("fetchUser");
});
const user = computed(() => store.state.user);

const urls = ref([]);
const likes = ref([]);
const filteredPhotos = ref([]);

// イベント
const onClickAddLike = (id) => {
    console.log(filteredPhotos.value);
    console.log(id);
    const params = {
        image_id: id,
        user_id: user.value.id,
    };
    likePhoto(id, params);
};

const onClickDeleteLike = (id) => {
    console.log(id);
    const params = {
        image_id: id,
        user_id: user.value.id,
    };
    unLikePhoto(id, params);
    console.log(params);
};

// API
const likePhoto = async (id, params) => {
    await LikeRepository.likePhoto(id, params)
        .then((data) => {})
        .catch((error) => {
            alert("いいねがうまくいきませんでした");
            console.log(error);
        })
        .finally(() => {});
};

const unLikePhoto = async (id, params) => {
    await LikeRepository.unlikePhoto(id, params)
        .then((data) => {})
        .catch((error) => {
            alert("いいね解除がうまくいきませんでした");
            console.log(error);
        })
        .finally(() => {});
};
const getLike = async () => {
    await LikeRepository.getLike()
        .then((data) => {
            console.log(data);
            // map関数を使用して1つずつURL定数に格納する
            likes.value = data.map((item) => item);

            filteredPhotos.value = urls.value.map((photo) => {
                const foundLike = likes.value.find(
                    (like) => Number(like.photo_id) === Number(photo.id)
                );
                if (foundLike) {
                    return { ...photo, user_id: foundLike.user_id };
                }
                return photo;
            });
        })
        .catch((error) => {
            alert("写真の取得に失敗しました。");
            console.log(error);
        })
        .finally(() => {});
};

const getPhoto = async () => {
    await PhotosRepository.getPhoto()
        .then((data) => {
            console.log("Response from server:", data);
            // map関数を使用して1つずつURL定数に格納する
            urls.value = data.map((item) => item);
            getLike();
        })
        .catch((error) => {
            alert("写真の取得に失敗しました。");
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
.photo__list {
    display: flex;
}
.grid {
    margin: 3rem;
    border: 1px solid rgb(116, 147, 107);
    border-radius: 5px;
    width: 15rem;
    height: 15rem;
    margin: 1rem;
}
img {
    height: auto;
    width: auto;
    padding: 1rem;
}
button {
    border-radius: 8px;
    background-color: rgb(222, 79, 79);
    color: aliceblue;
    width: 50%;
    height: 2rem;
    margin: 1rem;
}
</style>
