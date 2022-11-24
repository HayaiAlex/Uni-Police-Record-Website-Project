
import { toast } from "@zerodevx/svelte-toast";

const successMsg = (msg) => {
    toast.push(msg, {
        theme: {
            "--toastColor": "mintcream",
            "--toastBackground": "rgba(72,187,120,0.9)",
            "--toastBarBackground": "#2F855A",
        },
    })
}
const failMsg = (msg) => {
    toast.push(msg, {
        theme: {
            "--toastBackground": "rgba(186, 60, 60, 0.9)",
            "--toastBarBackground": "#D11E1E",
        },
    })
}

export { successMsg, failMsg }