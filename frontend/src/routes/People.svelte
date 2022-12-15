<script>
    import PersonCreateModal from "../lib/PeopleComponents/PersonCreateModal.svelte";
    import PersonSearch from "../lib/PeopleComponents/PersonSearch.svelte";

    let creatingPerson = false;
</script>

<div
    class="bg-traffic bg-no-repeat bg-cover bg-center min-h-screen flex flex-col items-center"
>
    <h2 class="mt-2 text-xl text-center text-white">People</h2>
    <button
        on:click={() => {
            creatingPerson = true;
        }}
        class="button">Create Person</button
    >
    {#if creatingPerson}
        <PersonCreateModal
            on:close={() => {
                creatingPerson = false;
            }}
        />
    {/if}
    <PersonSearch textColour={"text-white"} />
</div>
