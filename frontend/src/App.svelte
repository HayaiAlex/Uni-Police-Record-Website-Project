<script>
    // Routes
    import router from "page";
    import Index from "./routes/Index.svelte";
    import References from "./routes/References.svelte";
    import NotFound from "./routes/NotFound.svelte";
    // Components
    import Header from "./lib/Header.svelte";

    let page;
    let params;

    router("/", () => (page = Index));
    // router(
    //     "/svelte-countries/country/:countryName",
    //     (ctx, next) => {
    //         params = ctx.params;
    //         next();
    //     },
    //     () => (page = Country)
    // );
    router("/references", () => (page = References));
    router("/*", () => (page = NotFound));

    router.start();
</script>

<svelte:component this={page} {params} />
