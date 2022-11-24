<script>
    import { successMsg, failMsg } from "../lib/toast";
    import PersonCreateModal from "../lib/PersonCreateModal.svelte";
    import PersonSearch from "./PersonSearch.svelte";

    let vehicleMake = "";
    let vehicleModel = "";
    let vehicleColour = "";
    let numberPlate = "";
    let owner;
    let ownerText = "None";
    let setOwnerText = "Add Owner";
    let addingOwner = false;

    $: updateOwnerText(ownerText);
    const updateOwnerText = (ownerText) => {
        if (ownerText == "None") {
            setOwnerText = "Add Owner";
        } else {
            setOwnerText = "Change Owner";
        }
    };

    const createVehicle = async () => {
        let data = new FormData();
        data.append("make", vehicleMake);
        data.append("model", vehicleModel);
        data.append("colour", vehicleColour);
        data.append("licence", numberPlate);

        const url = "http://localhost/backend/vehicle/create-vehicle.php";
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        let vehicleId = result.data;
        if (vehicleId) {
            successMsg(`Added ${vehicleMake} ${vehicleModel} successfully!`);
            console.log(owner);
            if (owner.People_ID) {
                setVehicleOwnership(owner.People_ID, vehicleId);
            }
            clearForm();
        } else {
            failMsg("Error Occurred");
        }

        console.log(vehicleId);
    };

    const setVehicleOwnership = async (personID, vehicleID) => {
        let data = new FormData();
        data.append("person-id", personID);
        data.append("vehicle-id", vehicleID);

        const url =
            "http://localhost/backend/vehicle/set-vehicle-ownership.php";
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        data = result.data;
        console.log(data);
        if (data) {
            successMsg(`Set ownership successfully`);
        } else {
            failMsg("Error Occurred Setting Vehicle Onwership");
        }
    };

    const clearForm = () => {
        vehicleMake = "";
        vehicleModel = "";
        vehicleColour = "";
        numberPlate = "";
        owner = null;
        ownerText = "None";
    };

    let searchingPerson = false;
    let creatingPerson = false;

    const setPerson = (person) => {
        console.log(person);
        owner = person;
        ownerText = person.People_name;
        searchingPerson = false;
    };
</script>

<form
    class="flex flex-col justify-center items-center gap-2"
    on:submit|preventDefault={createVehicle}
>
    <div class="flex">
        <div class="flex flex-col gap-1 items-end mr-2">
            <label for="vehicleMake">Make</label>
            <label for="vehicleModel">Model</label>
            <label for="vehicleColour">Colour</label>
            <label for="numberPlate">Number Plate</label>
            <p>Owner</p>
        </div>
        <div class="flex flex-col gap-1">
            <input
                class="text-black"
                bind:value={vehicleMake}
                name="vehicleType"
                type="text"
            />
            <input
                class="text-black"
                bind:value={vehicleModel}
                name="vehicleType"
                type="text"
            />
            <input
                class="text-black"
                bind:value={vehicleColour}
                name="vehicleColour"
                type="text"
            />
            <input
                class="text-black"
                bind:value={numberPlate}
                name="numberPlate"
                type="text"
            />
            <p>{ownerText}</p>
        </div>
    </div>
    {#if addingOwner == false}
        <button
            on:click={() => {
                addingOwner = true;
            }}
            class="button">{setOwnerText}</button
        >
    {:else}
        <div>
            <input
                type="button"
                value="Search People"
                on:click={() => {
                    searchingPerson = true;
                }}
                class="button"
            />
            <input
                type="button"
                value="Create New Person"
                on:click={() => {
                    creatingPerson = true;
                }}
                class="button"
            />
        </div>{/if}

    <button
        disabled={vehicleMake == "" && vehicleModel == ""}
        type="submit"
        class="button"
    >
        Add new vehicle
    </button>
</form>

{#if searchingPerson}
    <PersonSearch
        on:personSelected={(event) => {
            setPerson(event.detail);
        }}
        selectable={true}
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
