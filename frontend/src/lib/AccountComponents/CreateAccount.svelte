<script>
    import { root } from "../../config";
    import { loginStatus } from "../../stores/loginStatus";
    import { successMsg, failMsg } from "../toast";

    let username = "";
    let password = "";
    const createAccount = async () => {
        if ($loginStatus.username === undefined) {
            failMsg("You are not logged in.");
            return 0;
        }

        let data = new FormData();
        data.append("username", username);
        data.append("password", password);
        data.append("loggedInUsername", $loginStatus.username);

        const url = `${root}/backend/user/create-account.php`;
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();

        if (result.status == 1) {
            username = "";
            password = "";
            successMsg("Account created!");
        } else {
            failMsg("This user already exists!");
        }
    };
</script>

<h2 class="text-xl">Create New Police Account</h2>

<form
    class="flex flex-col justify-center items-center gap-2"
    on:submit|preventDefault={createAccount}
>
    <label for="username">Username</label>
    <input
        class="text-black"
        name="username"
        bind:value={username}
        type="text"
    />

    <label for="password">Password</label>
    <input
        class="text-black"
        name="password"
        type="password"
        bind:value={password}
    />

    <button
        disabled={username == "" || password == ""}
        type="submit"
        class="button"
    >
        Submit
    </button>
</form>
