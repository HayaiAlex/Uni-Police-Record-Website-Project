<script>
    import { route } from "../src/config";
    // Routes
    import router from "page";
    import Index from "./routes/Index.svelte";
    import Dashboard from "./routes/Dashboard.svelte";
    import People from "./routes/People.svelte";
    import Vehicles from "./routes/Vehicles.svelte";
    import Reports from "./routes/Reports.svelte";
    import Account from "./routes/Account.svelte";
    import References from "./routes/References.svelte";
    import NotFound from "./routes/NotFound.svelte";

    // Components
    import { SvelteToast } from "@zerodevx/svelte-toast";
    import Header from "./lib/Header.svelte";

    let page;
    let params;

    router(`${route}/`, () => (page = Index));
    router(`${route}/dashboard`, () => (page = Dashboard));
    router(`${route}/people`, () => (page = People));
    router(`${route}/vehicles`, () => (page = Vehicles));
    router(`${route}/reports`, () => (page = Reports));
    router(`${route}/account`, () => (page = Account));
    router(`${route}/references`, () => (page = References));
    router(`${route}/*`, () => (page = NotFound));

    router.start();
    console.log(router.current);
</script>

<!-- <svelte:component this={page} {params} /> -->
<SvelteToast />

<Header />
<svelte:component this={page} />
