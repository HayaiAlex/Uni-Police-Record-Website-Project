<script>
    export let audit;
    import Eye from "svelte-icons/fa/FaEye.svelte";
    import EyeCancel from "svelte-icons/fa/FaEyeSlash.svelte";

    import { offences } from "../stores/offences";
    import { root } from "../config";

    import { onMount } from "svelte";

    const getOffences = async () => {
        const url = `${root}/backend/report/get-offences.php`;
        let result = await fetch(url);
        result = await result.json();
        $offences = result.data;
        console.log($offences);
    };

    const getPerson = async (id) => {
        const url = `${root}/backend/person/get-person-by-id.php?id=${id}`;
        let result = await fetch(url);
        result = await result.json();
        if (result.data) {
            console.log(result.data);
            return `${result.data.People_name} (${result.data.People_licence})`;
        }
        return "";
    };

    const getVehicle = async (id) => {
        const url = `${root}/backend/vehicle/get-vehicle-by-id.php?id=${id}`;
        let result = await fetch(url);
        result = await result.json();
        if (result.data) {
            console.log(result.data);
            return `${result.data.Vehicle_make} ${result.data.Vehicle_model} (${result.data.Vehicle_licence})`;
        }
        return "";
    };

    let showingDetails = false;
    let keyDetail = "";
    let oldPersonName = "";
    let newPersonName = "";
    let oldVehicle = "";
    let newVehicle = "";
    const update = async () => {
        if (audit.Audit_search_term) {
            keyDetail = audit.Audit_search_term;
        } else if (audit.Audit_People_new_name) {
            keyDetail = audit.Audit_People_new_name;
        } else if (audit.Audit_Vehicle_new_make) {
            keyDetail = `${audit.Audit_Vehicle_new_make} ${audit.Audit_Vehicle_new_model}`;
        } else if (audit.Audit_Incident_incident_ID) {
            keyDetail = `${audit.Audit_Incident_new_report}`;
            oldPersonName = await getPerson(audit.Audit_Incident_old_people_ID);
            newPersonName = await getPerson(audit.Audit_Incident_new_people_ID);
            oldVehicle = await getVehicle(audit.Audit_Incident_old_vehicle_ID);
            newVehicle = await getVehicle(audit.Audit_Incident_new_vehicle_ID);
            getOffences();
        } else if (audit.Audit_Users_new_username) {
            keyDetail = audit.Audit_Users_new_username;
        } else if (audit.Audit_Vehicle_old_make) {
            keyDetail = `${audit.Audit_Vehicle_old_make} ${audit.Audit_Vehicle_old_model}`;
        } else if (
            audit.Audit_Ownership_new_people_ID ||
            audit.Audit_Ownership_old_people_ID
        ) {
            console.log("test");
            oldPersonName = await getPerson(
                audit.Audit_Ownership_old_people_ID
            );
            newPersonName = await getPerson(
                audit.Audit_Ownership_new_people_ID
            );
            oldVehicle = await getVehicle(audit.Audit_Ownership_old_vehicle_ID);
            newVehicle = await getVehicle(audit.Audit_Ownership_new_vehicle_ID);
            if (newVehicle != "") {
                keyDetail = newVehicle;
            } else {
                keyDetail = oldVehicle;
            }
        } else {
            keyDetail = "";
        }
    };
    onMount(update);
    $: update(audit.Audit_ID);

    const showDetails = () => {
        console.log(`Show details for`, audit);
        showingDetails = !showingDetails;
    };
</script>

<tr class="border-t border-gray-400 relative">
    <td class="px-4">{audit.Audit_timestamp}</td>
    <td class="px-4">{audit.Audit_username}</td>
    <td class="px-4 text-center">{audit.Audit_action}</td>
    <td class="px-4 text-center">{keyDetail}</td>
    {#if Object.keys(audit).length > 5}
        <td class="text-center relative">
            <button
                on:click={showDetails}
                class="h-5 absolute top-1/2 -translate-y-1/2 opacity-50 hover:opacity-100 hover:cursor-pointer"
            >
                {#if !showingDetails}
                    <Eye />
                {:else}
                    <EyeCancel />
                {/if}
            </button>
        </td>
    {/if}

    <!-- People details card -->
    {#if showingDetails && audit.Audit_People_people_ID}
        <table class="absolute bg-sky-100 -right-1 translate-x-full rounded">
            <tr>
                <td />
                <th>Previous</th>
                <th>New</th>
            </tr>
            <tr>
                <th>Name</th>
                <td>{audit.Audit_People_old_name}</td>
                <td>{audit.Audit_People_new_name}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{audit.Audit_People_old_address}</td>
                <td>{audit.Audit_People_new_address}</td>
            </tr>
            <tr>
                <th>Licence</th>
                <td>{audit.Audit_People_old_licence}</td>
                <td>{audit.Audit_People_new_licence}</td>
            </tr>
        </table>
    {/if}

    <!-- Vehicle details card -->
    {#if showingDetails && audit.Audit_Vehicle_vehicle_ID}
        <table class="absolute bg-sky-100 -right-1 translate-x-full rounded">
            <tr>
                <td />
                <th>Previous</th>
                <th>New</th>
            </tr>
            <tr>
                <th class="px-2">Make</th>
                <td>{audit.Audit_Vehicle_old_make}</td>
                <td>{audit.Audit_Vehicle_new_make}</td>
            </tr>
            <tr>
                <th class="px-2">Model</th>
                <td>{audit.Audit_Vehicle_old_model}</td>
                <td>{audit.Audit_Vehicle_new_model}</td>
            </tr>
            <tr>
                <th class="px-2">Colour</th>
                <td>{audit.Audit_Vehicle_old_colour}</td>
                <td>{audit.Audit_Vehicle_new_colour}</td>
            </tr>
            <tr>
                <th class="px-2 pb-2">Licence</th>
                <td>{audit.Audit_Vehicle_old_licence}</td>
                <td>{audit.Audit_Vehicle_new_licence}</td>
            </tr>
        </table>
    {/if}

    <!-- Report details card -->
    {#if showingDetails && audit.Audit_Incident_incident_ID}
        <table class="absolute bg-sky-100 -right-1 translate-x-full rounded">
            <tr>
                <td />
                <th>Previous</th>
                <th>New</th>
            </tr>
            <tr>
                <th class="px-2">Date</th>
                <td>{audit.Audit_Incident_old_date}</td>
                <td>{audit.Audit_Incident_new_date}</td>
            </tr>
            <tr>
                <th class="px-2">Offence</th>
                <td
                    >{$offences.filter((offence) => {
                        return (
                            offence.Offence_ID ==
                            audit.Audit_Incident_old_offence_ID
                        );
                    })[0].Offence_description}</td
                >
                <td
                    >{$offences.filter((offence) => {
                        return (
                            offence.Offence_ID ==
                            audit.Audit_Incident_new_offence_ID
                        );
                    })[0].Offence_description}</td
                >
            </tr>
            <tr>
                <th class="px-2">Report</th>
                <td>{audit.Audit_Incident_old_report}</td>
                <td>{audit.Audit_Incident_new_report}</td>
            </tr>
            <tr>
                <th class="px-2">Person</th>
                <td>{oldPersonName}</td>
                <td>{newPersonName}</td>
            </tr>
            <tr>
                <th class="px-2">Vehicle</th>
                <td>{oldVehicle}</td>
                <td>{newVehicle}</td>
            </tr>
        </table>
    {/if}

    <!-- Fine details card -->
    {#if showingDetails && audit.Audit_Fines_fine_ID}
        <div
            class="absolute bg-sky-100 -right-1 translate-x-full rounded py-1 px-2"
        >
            <table>
                <tr>
                    <td />
                    <th>Previous</th>
                    <th>New</th>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>£{audit.Audit_Fines_old_amount}</td>
                    <td>£{audit.Audit_Fines_new_amount}</td>
                </tr>
                <tr>
                    <th>Points</th>
                    <td>{audit.Audit_Fines_old_points}</td>
                    <td>{audit.Audit_Fines_new_points}</td>
                </tr>
            </table>
            <p class="text-center w-full">
                Report ID: {audit.Audit_Fines_incident_ID}
            </p>
        </div>
    {/if}

    <!-- Users details card -->
    {#if showingDetails && audit.Audit_Users_new_username}
        <div
            class="absolute bg-sky-100 -right-1 translate-x-full rounded py-1 px-2"
        >
            <table>
                <tr>
                    <td />
                    <th>Previous</th>
                    <th>New</th>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{audit.Audit_Users_old_username}</td>
                    <td>{audit.Audit_Users_new_username}</td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>
                        {#if audit.Audit_Users_old_password}
                            *********
                        {/if}
                    </td>
                    <td>
                        {#if audit.Audit_Users_new_password}
                            *********
                        {/if}
                    </td>
                </tr>
                <tr>
                    <th>Admin</th>
                    <td>
                        {#if audit.Audit_Users_old_admin === "1"}
                            Yes
                        {:else if audit.Audit_Users_old_admin === "0"}
                            No
                        {/if}
                    </td>
                    <td>
                        {#if audit.Audit_Users_new_admin === "1"}
                            Yes
                        {:else if audit.Audit_Users_new_admin === "0"}
                            No
                        {/if}
                    </td>
                </tr>
            </table>
        </div>
    {/if}

    <!-- Ownership details card -->
    {#if showingDetails && (audit.Audit_Ownership_new_people_ID || audit.Audit_Ownership_old_people_ID)}
        <div
            class="absolute bg-sky-100 -right-1 translate-x-full rounded py-1 px-2"
        >
            <table>
                <tr>
                    <td />
                    <th>Previous</th>
                    <th>New</th>
                </tr>
                <tr>
                    <th>Person</th>
                    <td>{oldPersonName}</td>
                    <td>{newPersonName}</td>
                </tr>
                <tr>
                    <th>Vehicle</th>
                    <td>{oldVehicle}</td>
                    <td>{newVehicle}</td>
                </tr>
            </table>
        </div>
    {/if}
</tr>

<!-- TODOs
Add div + padding to person, vehicle, report audits
Extra: Add quick button searches -->
