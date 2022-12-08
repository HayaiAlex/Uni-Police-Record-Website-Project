<script>
    import { root } from "../config";
    import { successMsg, failMsg } from "../lib/toast";
    let date;
    let statement;
    let offence;
    let person;
    let personText = "None";
    let vehicle;
    let vehicleText = "None";
    import { offences } from "../stores/offences";
    import AddPerson from "../lib/AddPerson.svelte";
    import AddVehicle from "../lib/AddVehicle.svelte";

    const createReport = async () => {
        let data = new FormData();
        data.append("date", date);
        data.append("statement", statement);
        if (person) {
            data.append("person-id", person.People_ID);
            console.log("added person");
        }
        if (vehicle) {
            data.append("vehicle-id", vehicle.Vehicle_ID);
            console.log("added vehicle");
        }
        if (offence) {
            data.append("offence-id", offence);
            console.log("added offence");
        }

        const url = `${root}/backend/report/create-report.php`;
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        let reportId = result.data;
        if (reportId) {
            successMsg(`Created your report!`);
            // clearForm();
        } else {
            failMsg("Error Occurred");
        }

        console.log(reportId);
    };

    const setPerson = (event) => {
        person = event.detail;
        personText = person.People_name;
    };
    const setVehicle = (event) => {
        vehicle = event.detail;
        vehicleText = `${vehicle.Vehicle_make} ${vehicle.Vehicle_model}`;
    };

    $: console.log(date);
</script>

<main class="text-white flex flex-col gap-4 pt-4 justify-center items-center">
    <h2 class="text-xl">Create Report</h2>

    <form
        class="flex flex-col justify-center items-center gap-2"
        on:submit|preventDefault={createReport}
    >
        <label for="currentPassword">Date</label>
        <input
            class="text-black"
            name="currentPassword"
            bind:value={date}
            type="date"
        />

        <label for="offences">Select an offence</label>
        <select class="text-black" name="offences" bind:value={offence}>
            {#each $offences as offence}
                <option value={offence.Offence_ID}
                    >{offence.Offence_description}</option
                >
            {/each}
        </select>

        <label for="statement">Statement</label>
        <textarea
            class="text-black"
            name="statement"
            rows="3"
            cols="40"
            bind:value={statement}
        />

        <p>Person: {personText}</p>
        <AddPerson
            on:personSet={setPerson}
            {personText}
            buttonText={"Person"}
        />

        <p>Vehicle: {vehicleText}</p>
        <AddVehicle on:vehicleSet={setVehicle} />

        <button type="submit" class="button"> Submit </button>
    </form>
</main>
