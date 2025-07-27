import { toast } from 'vue-sonner';
interface ShowToastParams {
    isSuccess: boolean;
    description: string;
}
export default function showToast({ isSuccess, description }: ShowToastParams) {
    toast(isSuccess ? 'Success' : 'Error', {
        description: description,
        style: { backgroundColor: isSuccess ? 'green' : 'red', color: 'white', padding: '5px', border: '1px solid', borderRadius: '10px' },
    });
}
