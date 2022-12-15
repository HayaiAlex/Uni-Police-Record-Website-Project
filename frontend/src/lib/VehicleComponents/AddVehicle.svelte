<script>
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();

    import VehicleCreate from "./VehicleCreate.svelte";
    import VehicleSearch from "./VehicleSearch.svelte";

    let searchingVehicle = false;
    let creatingVehicle = false;
    let addingVehicle = false;
    let addVehicleButtonText = "Add Vehicle";
    export let vehicleText = "None";
    export let buttonText = "Vehicle";

    $: updateButtonText(vehicleText);
    const updateButtonText = (vehicleText) => {
        if (vehicleText == "None") {
            addVehicleButtonText = `Add ${buttonText}`;
        } else {
            addVehicleButtonText = `Change ${buttonText}`;
        }
    };
    const setVehicle = (vehicle) => {
        addingVehicle = false;
        dispatch("vehicleSet", vehicle);
    };
</script>

{#if addingVehicle == false}
    <button
        on:click={() => {
            addingVehicle = true;
        }}
        class="button">{addVehicleButtonText}</button
    >
{:else}
    <div>
        <input
            type="button"
            value="Search Vehicles"
            on:click={() => {
                searchingVehicle = true;
                creatingVehicle = false;
            }}
            class="button"
        />
        <input
            type="button"
            value="Create New Vehicle"
            on:click={() => {
                creatingVehicle = true;
                searchingVehicle = false;
            }}
            class="button"
        />
    </div>{/if}

{#if searchingVehicle}
    <VehicleSearch
        on:selected={(event) => {
            searchingVehicle = false;
            setVehicle(event.detail);
        }}
        selectable={true}
    />
{/if}

{#if creatingVehicle}
    <VehicleCreate
        on:close={(event) => {
            creatingVehicle = false;
            let vehicle = event.detail;
            if (vehicle) {
                setVehicle(vehicle);
            }
        }}
    />
{/if}
