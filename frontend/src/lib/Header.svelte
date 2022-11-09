<script>
    import Car from "svelte-icons/fa/FaCar.svelte";
</script>

<div class="w-full bg-sky-200 bg-opacity-90 p-2 grid grid-cols-3">
    <a
        class="flex gap-2 justify-center items-center hover:text-gray-500 w-max col-start-2 place-self-center"
        href="/"
    >
        <div class="w-5"><Car /></div>
        <header class="font-semibold">Traffic Records</header>
    </a>
    <a
        class="place-self-end font-semibold mr-2 hover:text-gray-500"
        href="/references"
    >
        References
    </a>
</div>
