<script>
    import { root } from "../config";
    import { successMsg, failMsg } from "../lib/toast";
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();

    let name = "";
    let address;
    let licence;

    const addPerson = async () => {
        let data = new FormData();
        data.append("name", name);
        data.append("address", address);
        data.append("licence", licence);

        const url = `${root}/backend/person/create-person.php`;
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        data = result.data;
        if (data) {
            successMsg(`Added ${name} successfully!`);
            dispatch("close", {
                People_ID: data,
                People_name: name,
            });
        } else {
            failMsg("Please add a name");
        }

        console.log(data);
    };
</script>

<div
    class="absolute left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2
            bg-sky-300 rounded shadow-screen p-2 text-black w-60 h-72 grid place-content-center"
>
    <button
        on:click={() => {
            dispatch("close", false);
        }}
        class="aboslute top-1 rigth-1">Close</button
    >
    <form
        class="flex flex-col justify-center items-center gap-2"
        on:submit|preventDefault={addPerson}
    >
        <label for="name"
            >Name <span class="italic text-sm text-gray-600">required</span
            ></label
        >
        <input
            required
            class="text-black"
            bind:value={name}
            name="name"
            type="text"
        />
        <label for="address">Address</label>
        <input
            class="text-black"
            bind:value={address}
            name="address"
            type="text"
        />
        <label for="licence">Licence Number</label>
        <input
            class="text-black"
            bind:value={licence}
            name="licence"
            type="text"
        />

        <button disabled={name == ""} type="submit" class="button">
            Add {name}
        </button>
    </form>
</div>
