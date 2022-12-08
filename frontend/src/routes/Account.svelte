<script>
    import ChangePassword from "../lib/ChangePassword.svelte";
    import CreateAccount from "../lib/CreateAccount.svelte";
</script>

<div class="bg-traffic bg-no-repeat bg-cover bg-center h-screen">
    <main
        class="text-white flex flex-col gap-4 pt-4 justify-center items-center"
    >
        <ChangePassword />

        <CreateAccount />
    </main>
</div>
