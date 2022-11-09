<script>
    import Header from "../lib/Header.svelte";
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center min-h-screen">
    <Header />

    <main
        class="flex flex-col items-center gap-6 text-center bg-opacity-90  bg-sky-200 rounded-lg w-96 px-5 pt-10 pb-16 min-w-min  absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 shadow"
    >
        <h2 class="text-xl font-bold">Login</h2>

        <div class="flex gap-2 justify-center font-semibold">
            <div class="flex flex-col gap-2 items-end">
                <label for="username">Username:</label>
                <label for="password">Password:</label>
            </div>
            <div class="flex flex-col gap-2">
                <input type="text" name="username" />
                <input type="password" name="password" />
            </div>
        </div>

        <button
            class="bg-sky-500 hover:bg-sky-400 rounded w-max px-6 py-2 font-semibold shadow"
        >
            Sign in
        </button>
    </main>
</div>
