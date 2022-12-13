<script>
    import { root } from "../config";
    import { successMsg, failMsg } from "../lib/toast";
    import { loginStatus } from "../stores/loginStatus";
    import Report from "./Report.svelte";
    let reports = [];
    let name = "";

    const search = async () => {
        let data = `?name=${name}`;
        if ($loginStatus.username) {
            data += `&username=${$loginStatus.username}`;
        }
        const url = `${root}/backend/report/get-reports-by-name.php${data}`;
        let result = await fetch(url);
        result = await result.json();

        reports = result.data;
        if (reports) {
            successMsg(`Found ${reports.length} reports.`);
        } else {
            failMsg("No report found!");
        }

        console.log("Reports", reports);
    };
</script>

<form
    class="flex flex-col justify-center items-center gap-2"
    on:submit|preventDefault={search}
>
    <label class="text-white" for="name">Name</label>
    <input class="text-black" bind:value={name} name="name" type="text" />

    <button disabled={name == ""} type="submit" class="button"> Search </button>
</form>

{#each reports as report}
    <Report
        on:fineEditted={(event) => {
            const fine = event.detail;
            console.log(fine);
            reports = reports.map((report) => {
                if (fine.Incident_ID == report.Incident_ID) {
                    report.Fine_ID = fine.Fine_ID;
                    report.Fine_Amount = fine.Fine_Amount;
                    report.Fine_Points = fine.Fine_Points;
                    console.log(report);
                    return report;
                } else {
                    console.log(report);
                    return report;
                }
            });
        }}
        on:reportEditted={(event) => {
            const edittedReport = event.detail;
            console.log(edittedReport);
            reports = reports.map((report) => {
                if (edittedReport.Incident_ID == report.Incident_ID) {
                    console.log("Found");
                    return edittedReport;
                } else {
                    return report;
                }
            });
        }}
        on:reportDeleted={(event) => {
            const deletedID = event.detail;
            reports = reports.filter((report) => {
                return report.Incident_ID !== deletedID;
            });
            console.log(report);
        }}
        {report}
    />
{/each}
