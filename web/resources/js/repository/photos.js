export default {
    getPhoto() {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/photos")
                .then((res) => {
                    const resData = res.data;
                    resolve(resData);
                })
                .catch((error) => {
                    console.error(
                        "Error in getPhoto:",
                        error.response ? error.response.data : error.message
                    );
                    reject(error);
                });
        });
    },
    postPhoto(file) {
        return new Promise((resolve, reject) => {
            // 写真を送るにあたってformDataオブジェクトを使用します。
            let formData = new FormData();
            formData.append("photo", file);

            axios
                .post("/photos/add", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((res) => {
                    resolve(res);
                })
                .catch((error) => {
                    console.error(
                        "Error in getPhoto:",
                        error.response ? error.response.data : error.message
                    );
                    reject(error);
                });
        });
    },
};
