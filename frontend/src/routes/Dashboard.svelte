<script>
    import SummaryCard from "../lib/SummaryCard.svelte";
    import { people } from "../stores/people";
    import { vehicles } from "../stores/vehicle";
    import { reports } from "../stores/report";
    import { onMount } from "svelte";

    onMount(async () => {
        // Get People
        // const peopleUrl = "../../../backend/person/get-people.php";
        const peopleUrl = "http://localhost/backend/person/get-people.php";
        let result = await fetch(peopleUrl);
        result = await result.json();
        $people = result.data;

        // Get Vehicles
        const vehicleUrl = "http://localhost/backend/vehicle/get-vehicles.php";
        result = await fetch(vehicleUrl);
        result = await result.json();
        $vehicles = result.data;

        // Get Incidents
        const incidentUrl = "http://localhost/backend/report/get-reports.php";
        result = await fetch(incidentUrl);
        result = await result.json();
        $reports = result.data;
    });
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen">
    <main class="flex flex-wrap justify-center">
        <SummaryCard title="People" data={$people} route={"./people"} />
        <SummaryCard title="Vehicles" data={$vehicles} route={"./vehicles"} />
        <SummaryCard title="Reports" data={$reports} route={"./reports"} />
    </main>
</div>
