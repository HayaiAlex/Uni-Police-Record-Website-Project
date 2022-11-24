<script>
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();
    export let person;
    export let selectable;
</script>

<div
    class="text-black font-semibold m-2 rounded p-4 shadow w-96 border-4 border-sky-200 bg-sky-200 bg-opacity-90 relative"
>
    <h3 class="font-bold">{person.People_name}</h3>
    <p>{person.People_licence}</p>
    <p>{person.People_address}</p>

    {#if person.Vehicles.length > 0}
        <h3 class="font-bold mt-2">Vehicles</h3>
        <div class="flex flex-col gap-2">
            {#each person.Vehicles as vehicle}
                <div class="border-b-2 border-sky-900">
                    <p>{vehicle.Vehicle_make} {vehicle.Vehicle_model}</p>
                    <p>{vehicle.Vehicle_colour}</p>
                    <p>{vehicle.Vehicle_licence}</p>
                </div>
            {/each}
        </div>
    {:else}
        <h3>No Vehicles Owned</h3>
    {/if}
    {#if selectable}
        <button
            on:click={() => {
                dispatch("selected", person);
            }}
            class="button">Select</button
        >
    {/if}
</div>
