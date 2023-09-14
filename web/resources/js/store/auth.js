import { createStore } from "vuex";

export default createStore({
    state: {
        user: null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
    },
    actions: {
        async fetchUser({ commit }) {
            const response = await axios.get("/user");
            commit("setUser", response.data);
        },
    },
});
