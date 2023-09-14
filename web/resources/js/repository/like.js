export default {
    // いいね取得
    getLike() {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/photos/like")
                .then((res) => {
                    const resData = res.data;
                    resolve(resData);
                })
                .catch((error) => {
                    console.error(
                        "Error in getLike:",
                        error.response ? error.response.data : error.message
                    );
                    reject(error);
                });
        });
    },
    // いいね追加
    likePhoto(id, params) {
        return new Promise((resolve, reject) => {
            axios
                .put(`/api/photos/${id}/like`, params)
                .then((response) => {
                    // レスポンスに基づいた何かしらの処理（状態の更新など）
                    resolve(response);
                })
                .catch((error) => {
                    console.error(
                        "Error in getLike:",
                        error.response ? error.response.data : error.message
                    );
                    reject(error);
                });
        });
    },
    // いいね解除
    unlikePhoto(id, params) {
        return new Promise((resolve, reject) => {
            axios
                .delete(`/api/photos/${id}/like`, { data: params })
                .then((response) => {
                    // レスポンスに基づいた何かしらの処理（状態の更新など）
                    resolve(response);
                })
                .catch((error) => {
                    console.error(
                        "Error in getLike:",
                        error.response ? error.response.data : error.message
                    );
                    reject(error);
                });
        });
    },
};
