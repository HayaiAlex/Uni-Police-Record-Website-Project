<script>
    import { root } from "../config";
    import ReportCreate from "../lib/ReportCreate.svelte";
    import ReportSearch from "../lib/ReportSearch.svelte";

    import { offences } from "../stores/offences";
    import { onMount } from "svelte";

    const getOffences = async () => {
        const url = `${root}/backend/report/get-offences.php`;
        let result = await fetch(url);
        result = await result.json();
        $offences = result.data;
        console.log(offences);
    };
    onMount(getOffences);

    let creatingReport = false;
    let searchingReport = false;
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen">
    <div class="flex justify-center gap-4 py-4">
        <button
            class="button"
            on:click={() => {
                searchingReport = true;
                creatingReport = false;
            }}>Search Reports</button
        >
        <button
            class="button"
            on:click={() => {
                creatingReport = true;
                searchingReport = false;
            }}>Create a Report</button
        >
    </div>
    <div class="grid place-content-center gap-1">
        {#if creatingReport}
            <ReportCreate />
        {:else if searchingReport}
            <ReportSearch />
        {/if}
    </div>
</div>
