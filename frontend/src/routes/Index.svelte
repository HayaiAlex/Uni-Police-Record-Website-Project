<script>
    import { root } from "../config";
    import { loginStatus } from "../stores/loginStatus";
    import router from "page";

    let username;
    let password;
    const login = async () => {
        let data = new FormData();
        data.append("username", username);
        data.append("password", password);

        const url = `${root}/backend/user/login.php`;
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        data = result.data;

        // If login status is something like {username: 'daniels', admin: '1'} then send to dashboard page
        if (typeof data === "object" && data != null) {
            $loginStatus.loggedIn = true;
            $loginStatus.username = data.username;
            $loginStatus.admin = data.admin;
            $loginStatus.message = "";
            // window.location.href = "/dashboard";
            router("/dashboard");
        } else {
            $loginStatus.message = data;
        }
        console.log($loginStatus);
    };
</script>

<main
    class="bg-traffic bg-no-repeat bg-cover bg-center h-[40rem] grid items-center"
>
    <div class="flex justify-center items-center gap-4 p-10 h-max">
        <div class="self-start">
            <h2 class="text-4xl text-white font-semibold max-w-xs">
                Managing traffic incidents made
                <span class="text-sky-300 font-bold italic"> easy! </span>
            </h2>
        </div>

        <div
            class="flex flex-col items-center gap-6 text-center bg-opacity-90  bg-sky-200 rounded-lg w-96 px-5 pt-10 pb-16 min-w-min shadow"
        >
            <h2 class="text-xl font-bold">Login</h2>

            <form on:submit|preventDefault={login}>
                <div class="flex gap-2 font-semibold mb-5">
                    <div class="flex flex-col gap-2 items-end">
                        <label for="username">Username:</label>
                        <label for="password">Password:</label>
                    </div>
                    <div class="flex flex-col gap-2">
                        <input
                            bind:value={username}
                            type="text"
                            name="username"
                        />
                        {#if $loginStatus.message == "password was incorrect"}
                            <input
                                class="border-red-700 border-2 -m-0.5 "
                                bind:value={password}
                                type="password"
                                name="password"
                            />
                        {:else}
                            <input
                                bind:value={password}
                                type="password"
                                name="password"
                            />
                        {/if}
                    </div>
                </div>

                <button
                    disabled={!username && !password}
                    type="submit"
                    class="bg-sky-500 hover:bg-sky-400 rounded w-max px-6 py-2 font-semibold shadow hover:mb-0.5 hover:-mt-0.5 duration-75 transition-all"
                >
                    Sign in
                </button>
            </form>
        </div>
    </div>
</main>
