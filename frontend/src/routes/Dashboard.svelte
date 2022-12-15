<script>
    import { root } from "../config";
    import SummaryCard from "../lib/SummaryCard.svelte";
    import { people } from "../stores/people";
    import { vehicles } from "../stores/vehicle";
    import { reports } from "../stores/report";
    import { numAudits } from "../stores/audit";
    import { onMount } from "svelte";
    import { loginStatus } from "../stores/loginStatus";

    onMount(async () => {
        // Get People
        const peopleUrl = `${root}/backend/person/get-people.php`;
        let result = await fetch(peopleUrl);
        result = await result.json();
        $people = result.data;

        // Get Vehicles
        const vehicleUrl = `${root}/backend/vehicle/get-vehicles.php`;
        result = await fetch(vehicleUrl);
        result = await result.json();
        $vehicles = result.data;

        // Get Incidents
        const incidentUrl = `${root}/backend/report/get-reports.php`;
        result = await fetch(incidentUrl);
        result = await result.json();
        $reports = result.data;

        // Get Audits
        const auditUrl = `${root}/backend/audit/count-audits.php`;
        result = await fetch(auditUrl);
        result = await result.json();
        $numAudits = result.data;
    });
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen">
    <main class="flex flex-wrap justify-center">
        <SummaryCard title="People" data={$people} route={"./people"} />
        <SummaryCard title="Vehicles" data={$vehicles} route={"./vehicles"} />
        <SummaryCard title="Reports" data={$reports} route={"./reports"} />
        {#if $loginStatus.admin == "1"}
            <SummaryCard title="Audits" data={$numAudits} route={"./audits"} />
        {/if}
    </main>
</div>
