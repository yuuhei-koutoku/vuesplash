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
                    reject(error);
                });
        });
    },
};
