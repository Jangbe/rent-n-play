import { defineStore } from "pinia";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        getUser() {
            return new Promise((res, rej) => {
                if (this.user != null) return res(this.user);
                axios.get('/user').then(({ data }) => {
                    this.user = data;
                }).catch(({ response }) => {
                    // nothing happend
                }).finally(() => res(this.user))
            })
        }
    }
})
