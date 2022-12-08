<script>
    import { root } from "../config";
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();
    export let selectable;
    export let textColour = "text-white";
    import { successMsg, failMsg } from "../lib/toast";
    import Person from "../lib/Person.svelte";
    let people = [];

    let name = "";
    let licence = "";

    const search = async () => {
        let data = "";
        if (name != "" && licence != "") {
            data = "?name=" + name + "&" + "licence=" + licence;
        } else if (name != "") {
            data = "?name=" + name;
        } else if (licence != "") {
            data = "?licence=" + licence;
        } else {
            return;
        }
        const url = `${root}/backend/person/get-person.php${data}`;
        let result = await fetch(url);
        result = await result.json();
        data = result.data;
        if (data) {
            successMsg(`Found ${data.length} people!`);
        } else {
            failMsg("Found no records");
        }
        console.log(data);
        people = data;
    };
</script>

<main class="{textColour} flex flex-col justify-center items-center pt-2 gap-2">
    <p>Search for a person by name or licence number.</p>
    <form
        class="flex flex-col justify-center items-center gap-2"
        on:submit|preventDefault={search}
    >
        <label for="name">Name</label>
        <input class="text-black" bind:value={name} name="name" type="text" />

        <label for="licence">Licence</label>
        <input
            class="text-black"
            bind:value={licence}
            name="licence"
            type="text"
        />

        <button
            disabled={name == "" && licence == ""}
            type="submit"
            class="button"
        >
            Submit
        </button>
    </form>

    {#if people}
        <section>
            {#if people.length == 1}
                <p class="text-center">{people.length} record found.</p>
            {:else if people.length > 1}
                <p class="text-center">{people.length} records found.</p>
            {/if}
            {#each people as person}
                <Person
                    on:personEditted={(event) => {
                        const data = event.detail;
                        console.log(data);
                        people = people.map((person) => {
                            if (data.People_ID == person.People_ID) {
                                console.log("Found");
                                return data;
                            } else {
                                return person;
                            }
                        });
                        console.log(people);
                    }}
                    on:personDeleted={(event) => {
                        const deletedID = event.detail;
                        people = people.filter((person) => {
                            return person.People_ID !== deletedID;
                        });
                        console.log(people);
                    }}
                    on:selected={() => {
                        dispatch("personSelected", person);
                    }}
                    {person}
                    {selectable}
                />
            {/each}
        </section>
    {/if}
</main>
