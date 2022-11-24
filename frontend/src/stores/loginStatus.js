import { writable } from "svelte/store";
export let loginStatus = writable({ loggedIn: false })

// Add local storage handler here so refreshing the page doesn't log you out immediately. Could store time of last page visit to timeout login