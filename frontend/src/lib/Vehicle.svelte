<script>
    import { root } from "../config";
    import { successMsg, failMsg } from "../lib/toast";
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();

    export let data;
    export let selectable;

    let make = data.Vehicle_make;
    let model = data.Vehicle_model;
    let colour = data.Vehicle_colour;
    let licence = data.Vehicle_licence;

    let editting = false;
    console.log(data);

    const editVehicle = async () => {
        let formData = new FormData();
        formData.append("id", data.Vehicle_ID);
        formData.append("make", make);
        formData.append("model", model);
        formData.append("colour", colour);
        formData.append("licence", licence);

        const url = `${root}/backend/vehicle/update-vehicle.php`;
        let result = await fetch(url, {
            method: "POST",
            body: formData,
        });
        result = await result.json();
        console.log(result);
        if (result.status == 1) {
            successMsg(`Record successfully editted!`);
            editting = false;
            dispatch("vehicleEditted", {
                Vehicle_ID: data.Vehicle_ID,
                Vehicle_make: make,
                Vehicle_model: model,
                Vehicle_colour: colour,
                Vehicle_licence: licence,
                People_ID: data.People_ID,
                People_name: data.People_name,
                People_address: data.People_address,
                People_licence: data.People_licence,
            });
        } else {
            failMsg("Something went wrong.");
        }
    };

    const deleteVehicle = async () => {
        const url = `${root}/backend/vehicle/remove-vehicle.php?id=${data.Vehicle_ID}`;
        let result = await fetch(url);
        result = await result.json();
        console.log(result);
        if (result.status == 1) {
            successMsg(`Deleted record!`);
            dispatch("vehicleDeleted", data.Vehicle_ID);
        } else {
            failMsg("Cannot delete a vehicle found in a report.");
        }
    };
</script>

<section
    class="text-black font-semibold m-2 rounded p-4 shadow w-96 border-4 border-sky-200 bg-sky-200 bg-opacity-90 relative"
>
    {#if editting}
        <form
            class="flex flex-col gap-1"
            on:submit|preventDefault={editVehicle}
        >
            <input
                required
                class="text-black font-bold pl-1"
                bind:value={make}
                name="make"
                type="text"
                placeholder="Make"
            />
            <input
                class="text-black pl-1"
                bind:value={model}
                name="model"
                type="text"
                placeholder="Model"
            />
            <input
                class="text-black pl-1"
                bind:value={colour}
                name="colour"
                type="text"
                placeholder="Colour"
            />
            <input
                class="text-black pl-1"
                bind:value={licence}
                name="licence"
                type="text"
                placeholder="Number Plate"
            />

            <button
                disabled={make == "" || model == ""}
                type="submit"
                class="button"
            >
                Edit {make}
                {model}
            </button>
        </form>
    {:else}
        <h3 class="font-bold">{data.Vehicle_make} {data.Vehicle_model}</h3>
        <p>{data.Vehicle_colour}</p>
        <p>{data.Vehicle_licence}</p>

        {#if data.People_ID}
            <h3 class="font-bold">{data.People_name}</h3>
            <p>{data.People_licence}</p>
        {:else}
            <h3 class="font-bold">This vehicle has no owner.</h3>
        {/if}
    {/if}
    <div class="flex gap-2">
        {#if selectable}
            <button
                on:click={() => {
                    dispatch("selected", data);
                }}
                class="button bg-green-600">Select</button
            >
        {/if}
        {#if editting}
            <button
                on:click={() => {
                    editting = !editting;
                }}
                class="button">Cancel</button
            >
        {:else}
            <button
                on:click={() => {
                    editting = !editting;
                }}
                class="button">Edit</button
            >
        {/if}
        <button
            on:click={deleteVehicle}
            class="button bg-red-400 hover:bg-red-300">Delete</button
        >
    </div>
</section>
