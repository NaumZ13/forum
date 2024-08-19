import { reactive, readonly } from "vue"

const globalState = reactive({
    title: '',
    message: '',
    show: false,
    resolver: null
});


export function useConfirm() {

    const resetModal = () => {
        globalState.title = '';
        globalState.message = '';
        globalState.show = false;
        globalState.resolver = null;
    }

    return {
        state: readonly(globalState),
        confirmation: (message, title = 'Please confirm!') => {
            globalState.title = title;
            globalState.message = message;
            globalState.show = true;


            return new Promise((resolver)=> {
                globalState.resolver = resolver;
            });
        },
        confirm: () => {
            if (globalState.resolver) {
                globalState.resolver(true)
            }

            resetModal();
        },
        cancel: () => {
            if(globalState.resolver) {
                globalState.resolver(false)
            }

            resetModal();
        }
    };
}