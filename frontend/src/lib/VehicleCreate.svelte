<script>
    import { root } from "../config";
    import { successMsg, failMsg } from "../lib/toast";
    import AddPerson from "./AddPerson.svelte";

    let vehicleMake = "";
    let vehicleModel = "";
    let vehicleColour = "";
    let numberPlate = "";
    let owner;
    let ownerText = "None";

    const createVehicle = async () => {
        let data = new FormData();
        data.append("make", vehicleMake);
        data.append("model", vehicleModel);
        data.append("colour", vehicleColour);
        data.append("licence", numberPlate);

        const url = `${root}/backend/vehicle/create-vehicle.php`;
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

        const url = `${root}/backend/vehicle/set-vehicle-ownership.php`;
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

    const setPerson = (event) => {
        let person = event.detail;
        owner = person;
        ownerText = person.People_name;
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

    <AddPerson
        on:personSet={setPerson}
        personText={ownerText}
        buttonText={"Owner"}
    />

    <button
        disabled={vehicleMake == "" && vehicleModel == ""}
        type="submit"
        class="button"
    >
        Add new vehicle
    </button>
</form>
