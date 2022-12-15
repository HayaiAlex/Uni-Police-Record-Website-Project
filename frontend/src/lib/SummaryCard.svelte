<script>
    export let title;
    export let data;
    export let route;
</script>

<a href={route}>
    <div
        class="m-2 rounded p-4 shadow w-44 h-44 border-4 border-sky-200 bg-sky-200 bg-opacity-70 relative"
    >
        <h2 class="font-bold text-xl">{title}</h2>
        {#if Array.isArray(data)}
            <p class="text-lg">{data.length} Records</p>
        {:else}
            <p class="text-lg">{data} Records</p>
        {/if}
    </div>
</a>
