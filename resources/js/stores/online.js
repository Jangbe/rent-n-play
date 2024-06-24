import { defineStore } from "pinia";

export const useOnlineStore = defineStore('online', {
    state: () => ({
        users: []
    }),
    actions: {
        join() {
            Echo.join('online').here((users) => {
                this.users.push(...users);
            }).joining((user) => {
                this.add(user)
            }).leaving((user) => {
                this.remove(user);
            })
        },
        add(user) {
            this.users.push(user)
        },
        remove(user) {
            this.users.splice(this.users.findIndex((u) => u.id == user.id), 1);
        }
    }
})