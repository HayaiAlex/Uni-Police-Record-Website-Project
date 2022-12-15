import { writable } from "svelte/store";
export let audits = writable([])
export let numAudits = writable(0)