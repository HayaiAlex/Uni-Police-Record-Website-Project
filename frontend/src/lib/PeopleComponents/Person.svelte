<script>
    import { root } from "../../config";
    import { successMsg, failMsg } from "../toast";
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();
    import { loginStatus } from "../../stores/loginStatus";
    export let person;
    export let selectable;
    let name = person.People_name;
    let address = person.People_address;
    let licence = person.People_licence;
    let editting = false;

    const editPerson = async () => {
        let data = new FormData();
        if ($loginStatus.username) {
            data.append("username", $loginStatus.username);
        }
        data.append("id", person.People_ID);
        data.append("name", name);
        data.append("address", address);
        data.append("licence", licence);

        const url = `${root}/backend/person/update-person.php`;
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        console.log(result);
        if (result.status == 1) {
            successMsg(`Record successfully editted!`);
            editting = false;
            dispatch("personEditted", {
                People_ID: person.People_ID,
                People_name: name,
                People_address: address,
                People_licence: licence,
                Vehicles: person.Vehicles,
            });
        } else {
            failMsg("Something went wrong.");
        }
    };

    const deletePerson = async () => {
        let data = `?id=${person.People_ID}`;
        if ($loginStatus.username) {
            data += `&username=${$loginStatus.username}`;
        }
        const url = `${root}/backend/person/remove-person.php${data}`;
        let result = await fetch(url);
        result = await result.json();
        console.log(result);
        if (result.status == 1) {
            successMsg(`Deleted record!`);
            dispatch("personDeleted", person.People_ID);
        } else {
            failMsg("Cannot delete someone found in a report.");
        }
    };
</script>

<div
    class="text-black font-semibold m-2 rounded p-4 shadow w-96 border-4 border-sky-200 bg-sky-200 bg-opacity-90 relative"
>
    {#if editting}
        <form class="flex flex-col gap-1" on:submit|preventDefault={editPerson}>
            <input
                required
                class="text-black font-bold pl-1"
                bind:value={name}
                name="name"
                type="text"
            />
            <input
                class="text-black pl-1"
                bind:value={licence}
                name="licence"
                type="text"
            />
            <input
                class="text-black pl-1"
                bind:value={address}
                name="address"
                type="text"
            />

            <button disabled={name == ""} type="submit" class="button">
                Edit {name}
            </button>
        </form>
    {:else}
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
    {/if}
    <div class="flex gap-2">
        {#if selectable}
            <button
                on:click={() => {
                    dispatch("selected", person);
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
            on:click={deletePerson}
            class="button bg-red-400 hover:bg-red-300">Delete</button
        >
    </div>
</div>
