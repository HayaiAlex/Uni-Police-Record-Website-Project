<script>
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();

    import PersonCreateModal from "../lib/PersonCreateModal.svelte";
    import PersonSearch from "./PersonSearch.svelte";

    let searchingPerson = false;
    let creatingPerson = false;
    let addingPerson = false;
    let addPersonButtonText = "Add Person";
    export let personText = "None";
    export let buttonText = "Person";
    export let textColour = "text-white";

    $: updateButtonText(personText);
    const updateButtonText = (personText) => {
        if (personText == "None") {
            addPersonButtonText = `Add ${buttonText}`;
        } else {
            addPersonButtonText = `Change ${buttonText}`;
        }
    };
    const setPerson = (person) => {
        addingPerson = false;
        dispatch("personSet", person);
    };
</script>

{#if addingPerson == false}
    <button
        on:click={() => {
            addingPerson = true;
        }}
        class="button">{addPersonButtonText}</button
    >
{:else}
    <div>
        <input
            type="button"
            value="Search People"
            on:click={() => {
                searchingPerson = true;
                creatingPerson = false;
            }}
            class="button"
        />
        <input
            type="button"
            value="Create New Person"
            on:click={() => {
                searchingPerson = false;
                creatingPerson = true;
            }}
            class="button"
        />
    </div>{/if}

{#if searchingPerson}
    <PersonSearch
        on:personSelected={(event) => {
            searchingPerson = false;
            setPerson(event.detail);
        }}
        selectable={true}
        {textColour}
    />
{/if}

{#if creatingPerson}
    <PersonCreateModal
        on:close={(event) => {
            creatingPerson = false;
            let person = event.detail;
            if (person) {
                setPerson(person);
            }
        }}
    />
{/if}
