<script>
    import { route } from "../config";
    import router from "page";
    import Car from "svelte-icons/fa/FaCar.svelte";
    import { loginStatus } from "../stores/loginStatus";

    // Redirect to dashboard if logged in
    let homepage = `${route}/`;
    const updateHomepage = () => {
        if (typeof $loginStatus === "object" && $loginStatus.username) {
            homepage = `${route}/dashboard`;
        }
    };
    $: updateHomepage($loginStatus);
</script>

<div class="w-full bg-sky-200 bg-opacity-90 p-2 grid grid-cols-3">
    {#if $loginStatus.username}
        <a
            class="place-self-start font-semibold mr-2 hover:text-gray-500"
            href={`${route}/account`}
        >
            Logged in as <span class="font-bold">{$loginStatus.username}</span>
        </a>
    {:else if router.current != `${route}/`}
        <a
            class="place-self-start font-semibold mr-2 hover:text-gray-500"
            href={`${route}/`}
        >
            Login
        </a>
    {/if}
    <a
        class="flex gap-2 justify-center items-center hover:text-gray-500 w-max col-start-2 place-self-center"
        href={homepage}
    >
        <div class="w-5"><Car /></div>
        <header class="font-semibold">Traffic Records</header>
    </a>
    <a
        class="place-self-end font-semibold mr-2 hover:text-gray-500"
        href={`${route}/references`}
    >
        References
    </a>
</div>
