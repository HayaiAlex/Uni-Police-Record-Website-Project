<script>
    import { root } from "../config";
    import { successMsg, failMsg } from "../lib/toast";
    import { createEventDispatcher } from "svelte";
    import { loginStatus } from "../stores/loginStatus";
    import { offences } from "../stores/offences";
    import AddPerson from "./AddPerson.svelte";
    import AddVehicle from "./AddVehicle.svelte";
    const dispatch = createEventDispatcher();

    export let report;
    console.log(report);

    let date = report.Incident_Date;
    let offence = report.Offence_ID;
    let personId = report.People_ID;
    let personName = report.People_name;
    let personLicence = report.People_licence;

    let vehicleId = report.Vehicle_ID;
    let vehicleColour = report.Vehicle_colour;
    let vehicleMake = report.Vehicle_make;
    let vehicleModel = report.Vehicle_model;
    let vehicleLicence = report.Vehicle_licence;

    let statement = report.Incident_Report;

    let fineId = report.Fine_ID;
    let fine = report.Fine_Amount;
    let points = report.Fine_Points;

    let editting = false;
    let edittingFine = false;
    const getOffenceById = (offence) => {
        for (const obj of $offences) {
            if (obj.Offence_ID == offence) {
                return obj;
            }
        }
    };
    let offenceObj = getOffenceById(offence);
    $: offenceObj = getOffenceById(offence);

    const editReport = async () => {
        let formData = new FormData();
        formData.append("incidentId", report.Incident_ID);
        formData.append("personId", personId);
        formData.append("vehicleId", vehicleId);
        formData.append("offenceId", offence);
        formData.append("date", date);
        formData.append("statement", statement);
        console.log(report.Incident_ID);

        const url = `${root}/backend/report/edit-report.php`;
        let result = await fetch(url, {
            method: "POST",
            body: formData,
        });
        result = await result.json();
        console.log(result);
        editting = false;
        if (result.status == 1) {
            successMsg(`Record successfully editted!`);
            dispatch("reportEditted", {
                Incident_ID: report.Incident_ID,
                Incident_Date: date,
                Offence_ID: offence,
                Offence_description: offenceObj.Offence_description,
                Offence_maxFine: offenceObj.Offence_maxFine,
                Offence_maxPoints: offenceObj.Offence_maxPoints,
                People_ID: personId,
                People_name: personName,
                People_licence: personLicence,

                Vehicle_ID: vehicleId,
                Vehicle_colour: vehicleColour,
                Vehicle_make: vehicleMake,
                Vehicle_model: vehicleModel,
                Vehicle_licence: vehicleLicence,

                Incident_Report: statement,

                Fine_Amount: fine,
                Fine_Points: points,
            });
        } else {
            failMsg("Report was unchanged or something went wrong.");
        }
    };

    const editFine = async () => {
        let formData = new FormData();
        formData.append("incidentId", report.Incident_ID);
        formData.append("amount", fine);
        formData.append("points", points);

        let url = "";
        if (!fineId) {
            // Create a new fine
            url = `${root}/backend/report/add-fine.php`;
        } else {
            // Edit existing fine
            formData.append("fineId", fineId);
            url = `${root}/backend/report/edit-fine.php`;
        }
        let result = await fetch(url, {
            method: "POST",
            body: formData,
        });
        result = await result.json();
        console.log(result);
        edittingFine = false;
        if (result.status == 1) {
            successMsg(`Fine successfully editted!`);
            fineId = result.id;
            dispatch("fineEditted", {
                Incident_ID: report.Incident_ID,
                Fine_ID: result.id,
                Fine_Amount: fine,
                Fine_Points: points,
            });
        } else {
            failMsg("Fine failed to update!");
        }
    };
</script>

<section
    class="text-black font-semibold m-2 rounded p-4 shadow w-96 border-4 border-sky-200 bg-sky-200 bg-opacity-90 relative"
>
    {#if editting}
        <form class="flex flex-col gap-1" on:submit|preventDefault={editReport}>
            <input
                required
                class="text-black font-bold pl-1"
                bind:value={date}
                name="date"
                type="date"
            />

            <label for="offences">Select an offence</label>
            <select class="text-black" name="offences" bind:value={offence}>
                {#each $offences as item}
                    {#if item.Offence_ID === offence}
                        <option value={item.Offence_ID}
                            >{item.Offence_description}</option
                        >
                    {:else}
                        <option value={item.Offence_ID}
                            >{item.Offence_description}</option
                        >
                    {/if}
                {/each}
            </select>

            <p>Person: {personName} {personLicence}</p>

            <AddPerson
                on:personSet={(event) => {
                    const newPerson = event.detail;
                    personId = newPerson.People_ID;
                    personName = newPerson.People_name;
                    personLicence = newPerson.People_licence;
                }}
                personText={" "}
                textColour={"text-black"}
            />

            <p>
                Vehicle: {vehicleColour}
                {vehicleMake}
                {vehicleModel} ({vehicleLicence})
            </p>

            <AddVehicle
                on:vehicleSet={(event) => {
                    const newVehicle = event.detail;
                    vehicleId = newVehicle.Vehicle_ID;
                    vehicleColour = newVehicle.Vehicle_colour;
                    vehicleMake = newVehicle.Vehicle_make;
                    vehicleModel = newVehicle.Vehicle_model;
                    vehicleLicence = newVehicle.Vehicle_licence;
                }}
                vehicleText={" "}
            />

            <label for="statement">Report</label>
            <textarea
                name="statement"
                bind:value={statement}
                cols="30"
                rows="2"
            />

            <button type="submit" class="button"> Edit Report </button>
        </form>
    {:else}
        <h3 class="font-bold">
            {report.Incident_Date} - {report.Offence_description}
        </h3>
        {#if personId}
            <p>{report.People_name} {report.People_licence}</p>
        {:else}
            <p>No person associated</p>
        {/if}
        {#if vehicleId}
            <p>
                {report.Vehicle_colour}
                {report.Vehicle_make}
                {report.Vehicle_model}
                ({report.Vehicle_licence})
            </p>
        {:else}
            <p>No vehicle associated</p>
        {/if}
        <p class="border-t-gray-500 border-t-2 mt-1">
            Report: {report.Incident_Report}
        </p>

        {#if edittingFine}
            <form
                class="flex flex-col gap-1"
                on:submit|preventDefault={editFine}
            >
                <div
                    class="grid grid-cols-[max-content_max-content_1fr]  gap-1 items-center whitespace-nowrap"
                >
                    <label for="fine">Fine</label>
                    <input
                        class="w-16 px-1"
                        type="number"
                        name="fine"
                        bind:value={fine}
                    />
                    <p class="text-xs">
                        (Maximum of £{$offences[offence].Offence_maxFine})
                    </p>

                    <label for="points">Points</label>
                    <input
                        class="w-16 px-1"
                        type="number"
                        name="points"
                        bind:value={points}
                    />
                    <p class="text-xs">
                        (Maximum of {$offences[offence].Offence_maxPoints} points)
                    </p>
                </div>
                <button type="submit" class="button"> Edit Fine </button>
            </form>
        {:else if fineId}
            <p>Fine of £{report.Fine_Amount}, {report.Fine_Points} Points</p>
        {:else}
            <p>No fine set</p>
        {/if}
    {/if}
    <div class="flex gap-2">
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
        {#if $loginStatus.admin && $loginStatus.admin == 1}
            {#if fineId}
                <button
                    on:click={() => {
                        edittingFine = !edittingFine;
                    }}
                    class="button">Edit Fine</button
                >
            {:else}
                <button
                    on:click={() => {
                        edittingFine = !edittingFine;
                    }}
                    class="button">Add Fine</button
                >
            {/if}
        {/if}
    </div>
</section>
