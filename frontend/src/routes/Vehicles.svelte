<script>
    import VehicleCreate from "../lib/VehicleCreate.svelte";
    import VehicleSearch from "../lib/VehicleSearch.svelte";
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen">
    <main
        class="text-white flex flex-col justify-center items-center pt-2 gap-2"
    >
        <h2 class="text-xl">Vehicles</h2>

        <h3>Add a new vehicle.</h3>
        <VehicleCreate />

        <h3>Search for a vehicle by entering its number plate.</h3>
        <VehicleSearch />
    </main>
</div>
