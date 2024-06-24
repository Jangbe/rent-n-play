import { defineStore } from "pinia";

export const useCoorStore = defineStore('coor', {
    state: () => ({
        lat: -6.931743099999999,
        lng: 107.7202073
    })
})