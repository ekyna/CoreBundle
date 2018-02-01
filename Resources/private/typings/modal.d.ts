declare module Ekyna {
    export interface Modal {
        new():Modal;
        load(settings:JQueryAjaxSettings): JQueryXHR
        handleResponse(xmlData:string): Modal
        close():Modal
        getDialog(): BootstrapDialog
    }

    export interface ModalResponseEvent extends JQueryEventObject {
        modal:Modal
        contentType:string
        content:any
    }
}

declare let Modal:Ekyna.Modal;

declare module 'ekyna-modal' {
    export = Modal;
}
