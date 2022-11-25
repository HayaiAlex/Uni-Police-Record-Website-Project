<script>
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();
    import { successMsg, failMsg } from "../lib/toast";
    let data;
    let numberPlate = "";
    export let selectable;

    const search = async () => {
        if (numberPlate == "") {
            return;
        }

        const url =
            "http://localhost/backend/vehicle/get-vehicle.php?plateNumber=" +
            numberPlate;
        let result = await fetch(url);
        result = await result.json();
        data = result.data;
        if (data) {
            successMsg("Found your vehicle.");
        } else {
            failMsg("No vehicle found!");
        }

        console.log(data);
    };
</script>

<form
    class="flex flex-col justify-center items-center gap-2"
    on:submit|preventDefault={search}
>
    <label for="numberPlate">Number Plate</label>
    <input
        class="text-black"
        bind:value={numberPlate}
        name="numberPlate"
        type="text"
    />

    <button
        disabled={numberPlate == ""}
        type="submit"
        class="text-black bg-sky-500 hover:bg-sky-400 rounded w-max mt-2 px-6 py-2 font-semibold shadow hover:mb-0.5 hover:mt-1.5 duration-75 transition-all"
    >
        Search
    </button>
</form>

{#if data}
    <section
        class="text-black font-semibold m-2 rounded p-4 shadow w-96 border-4 border-sky-200 bg-sky-200 bg-opacity-90 relative"
    >
        <h3 class="font-bold">{data.Vehicle_make} {data.Vehicle_model}</h3>
        <p>{data.Vehicle_colour}</p>
        <p>{data.Vehicle_licence}</p>

        {#if data.People_ID}
            <h3 class="font-bold">{data.People_name}</h3>
            <p>{data.People_licence}</p>
        {:else}
            <h3 class="font-bold">This vehicle has no owner.</h3>
        {/if}

        {#if selectable}
            <button
                on:click={() => {
                    dispatch("selected", data);
                }}
                class="button">Select</button
            >
        {/if}
    </section>
{/if}
