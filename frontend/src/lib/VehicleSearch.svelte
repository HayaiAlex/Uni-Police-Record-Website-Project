<script>
    import { root } from "../config";
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();
    import { successMsg, failMsg } from "../lib/toast";
    import { loginStatus } from "../stores/loginStatus";
    import Vehicle from "./Vehicle.svelte";
    let vehicles = [];
    let numberPlate = "";
    export let selectable;

    const search = async () => {
        if (numberPlate == "") {
            return;
        }
        let data = `?plateNumber=${numberPlate}`;
        if ($loginStatus.username) {
            data += `&username=${$loginStatus.username}`;
        }
        const url = `${root}/backend/vehicle/get-vehicle.php${data}`;
        let result = await fetch(url);
        result = await result.json();
        vehicles = result.data;
        if (vehicles) {
            successMsg("Found your vehicle.");
        } else {
            failMsg("No vehicle found!");
        }

        console.log(vehicles);
    };
</script>

<form
    class="flex flex-col justify-center items-center gap-2"
    on:submit|preventDefault={search}
>
    <label for="numberPlate">Number Plate</label>
    <input
        class="text-black"
        bind:value={numberPlate}
        name="numberPlate"
        type="text"
    />

    <button
        disabled={numberPlate == ""}
        type="submit"
        class="text-black bg-sky-500 hover:bg-sky-400 rounded w-max mt-2 px-6 py-2 font-semibold shadow hover:mb-0.5 hover:mt-1.5 duration-75 transition-all"
    >
        Search
    </button>
</form>

{#each vehicles as data}
    <Vehicle
        on:vehicleEditted={(event) => {
            const data = event.detail;
            console.log(data);
            vehicles = vehicles.map((vehicle) => {
                if (data.Vehicle_ID == vehicle.Vehicle_ID) {
                    console.log("Found");
                    return data;
                } else {
                    return vehicle;
                }
            });
        }}
        on:vehicleDeleted={(event) => {
            const deletedID = event.detail;
            vehicles = vehicles.filter((vehicle) => {
                return vehicle.Vehicle_ID !== deletedID;
            });
            console.log(data);
        }}
        on:selected={() => {
            dispatch("selected", data);
        }}
        {data}
        {selectable}
    />
{/each}
