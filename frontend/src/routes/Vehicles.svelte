<script>
    import VehicleCreate from "../lib/VehicleComponents/VehicleCreate.svelte";
    import VehicleSearch from "../lib/VehicleComponents/VehicleSearch.svelte";

    let creatingVehicle = false;
    let searchingVehicles = false;
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center min-h-screen">
    <main
        class="text-white flex flex-col justify-center items-center pt-2 gap-2"
    >
        <h2 class="text-xl">Vehicles</h2>

        <div class="flex justify-center gap-4">
            <button
                class="button"
                on:click={() => {
                    searchingVehicles = true;
                    creatingVehicle = false;
                }}>Search Vehicles</button
            >
            <button
                class="button"
                on:click={() => {
                    creatingVehicle = true;
                    searchingVehicles = false;
                }}>Create a Vehicle</button
            >
        </div>

        <div class="grid place-content-center gap-1">
            {#if creatingVehicle}
                <h3 class="text-center">Add a new vehicle</h3>
                <VehicleCreate />
            {:else if searchingVehicles}
                <h3>Search for a vehicle by entering its number plate.</h3>
                <VehicleSearch />
            {/if}
        </div>
    </main>
</div>
