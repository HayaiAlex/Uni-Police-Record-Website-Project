<script>
    import { root } from "../../config";
    import { loginStatus } from "../../stores/loginStatus";
    import { successMsg, failMsg } from "../toast";

    let currentPassword = "";
    let newPassword = "";
    const setPassword = async () => {
        if ($loginStatus.username === undefined) {
            failMsg("You are not logged in.");
            return 0;
        }

        let data = new FormData();
        data.append("username", $loginStatus.username);
        data.append("currentPassword", currentPassword);
        data.append("newPassword", newPassword);

        const url = `${root}/backend/user/change-password.php`;
        let result = await fetch(url, {
            method: "POST",
            body: data,
        });
        result = await result.json();
        data = result.data;
        currentPassword = "";
        newPassword = "";

        if (data == 1) {
            console.log("Password updated");
            successMsg("Password updated!");
        } else if (data == 0) {
            console.log(
                "No rows updated. Password incorrect or new password is the same as old passowrd."
            );
            failMsg("Password incorrect!");
        }
    };
</script>

<h2 class="text-xl">Change password</h2>

<form
    class="flex flex-col justify-center items-center gap-2"
    on:submit|preventDefault={setPassword}
>
    <label for="currentPassword">Current Password</label>
    <input
        class="text-black"
        name="currentPassword"
        bind:value={currentPassword}
        type="password"
    />

    <label for="newPassword">New Password</label>
    <input
        class="text-black"
        name="newPassword"
        type="password"
        bind:value={newPassword}
    />

    <button
        disabled={currentPassword == "" || newPassword == ""}
        type="submit"
        class="button"
    >
        Submit
    </button>
</form>
