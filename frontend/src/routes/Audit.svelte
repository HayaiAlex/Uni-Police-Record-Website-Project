<script>
    import { root } from "../config";
    import { audits } from "../stores/audit";
    import Audit from "../lib/Audit.svelte";

    let searchTerm = "";
    const getResults = async () => {
        console.log(`searching for ${searchTerm}...`);
    };
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen text-white p-4">
    <h2 class="text-2xl font-semibold">Audits</h2>

    <label for="criteria">Search Term</label>
    <input
        class="text-black"
        bind:value={searchTerm}
        type="text"
        name="criteria"
        placeholder="Enter search term..."
    />
    <button on:click={getResults} class="button">Search</button>

    <div class="flex flex-wrap gap-2 my-2">
        <button class="button bg-sky-300 rounded-full">People</button>
        <button class="button bg-sky-300 rounded-full">Vehicles</button>
        <button class="button bg-sky-300 rounded-full">Vehicle Ownership</button
        >
        <button class="button bg-sky-300 rounded-full">Reports</button>
        <button class="button bg-sky-300 rounded-full">Fines</button>
        <button class="button bg-sky-300 rounded-full">Users</button>
    </div>

    {#each $audits as audit}
        <Audit {audit} />
    {/each}
</div>
