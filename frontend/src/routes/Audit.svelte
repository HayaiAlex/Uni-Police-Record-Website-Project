<script>
    import { root } from "../config";
    import { audits } from "../stores/audit";
    import Audit from "../lib/Audit.svelte";

    let searchTerm = "";

    const setAudits = (newAudits) => {
        $audits = newAudits;
        // Replace null with ""
        $audits = $audits.map((audit) => {
            let newAudit = {};
            for (const key in audit) {
                if (audit[key] == null) {
                    newAudit[key] = "";
                } else {
                    newAudit[key] = audit[key];
                }
            }
            return newAudit;
        });
    };

    const getResults = async () => {
        console.log(`searching for ${searchTerm}...`);
        const limit = 50;
        const url = `${root}/backend/audit/get-audits-by-keyword.php?keyword=${searchTerm}&limit=${limit}`;
        let result = await fetch(url);
        result = await result.json();
        if (result.data) {
            console.log(result.data);
            setAudits(result.data);
        }
        searchTerm = "";
    };

    import { onMount } from "svelte";

    const getRecentAudits = async () => {
        const limit = 50;
        const url = `${root}/backend/audit/get-recent-audits.php?limit=${limit}`;
        let result = await fetch(url);
        result = await result.json();
        if (result.data) {
            setAudits(result.data);
        }
        console.log($audits);
    };
    onMount(getRecentAudits);
</script>

<div
    class="bg-traffic bg-no-repeat bg-cover bg-center  text-white p-4 min-h-screen"
>
    <h2 class="text-2xl font-semibold">Audits</h2>

    <main class="w-max">
        <div class="flex justify-between">
            <form
                class="flex items-center gap-2 mt-2"
                on:submit|preventDefault={getResults}
            >
                <label for="criteria">Search</label>
                <input
                    class="text-black px-1 h-8"
                    bind:value={searchTerm}
                    type="text"
                    name="criteria"
                    placeholder="Enter search term..."
                />
                <button
                    disabled={searchTerm.length < 4}
                    class="button h-8 mt-0"
                    type="submit"
                >
                    <span class="relative -top-1">Search</span>
                </button>
            </form>
            <div class="flex flex-wrap gap-2 my-2">
                <button
                    on:click={getRecentAudits}
                    class="button bg-sky-300 rounded-full">Recent</button
                >
            </div>
        </div>
        <table class="bg-sky-100 rounded shadow text-black px-4">
            <tr>
                <th class="px-4 py-1">Time</th>
                <th class="px-4">Username</th>
                <th class="px-4">Action</th>
                <th class="px-4">Search Term/Item</th>
                <th class="px-4">Show Details</th>
            </tr>
            {#each $audits as audit}
                <Audit {audit} />
            {/each}
        </table>
    </main>
</div>
