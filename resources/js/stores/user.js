import { defineStore } from "pinia";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        async getUser() {
            await axios.get('/user').then(({ data }) => {
                this.user = data;
            }).catch(({ response }) => {
                // nothing happend
            })
        }
    }
})
