<script>
    import ChangePassword from "../lib/AccountComponents/ChangePassword.svelte";
    import CreateAccount from "../lib/AccountComponents/CreateAccount.svelte";
    import { loginStatus } from "../stores/loginStatus";
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen">
    <main
        class="text-white flex flex-col gap-4 pt-4 justify-center items-center"
    >
        <ChangePassword />

        {#if $loginStatus.admin == "1"}
            <CreateAccount />
        {/if}
    </main>
</div>
