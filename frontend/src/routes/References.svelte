<script>
    import Header from "../lib/Header.svelte";
    import PoliceBadge from "../assets/badge.png";
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center min-h-screen">
    <Header />

    <main class="bg-sky-100 opacity-90 p-6">
        <h2 class="font-semibold text-xl mb-4">References</h2>

        <p>
            <a
                class="flex gap-1 underline"
                href="https://www.flaticon.com/free-icons/police"
                title="police icons"
                ><img alt="Blue Police Badge Icon" src={PoliceBadge} /> Police favicon
                icon created by Freepik - Flaticon</a
            >
        </p>

        <p>
            Background Image -
            https://www.pexels.com/photo/aerial-photography-of-cars-on-the-road-during-night-time-12445652/
        </p>
    </main>
</div>
