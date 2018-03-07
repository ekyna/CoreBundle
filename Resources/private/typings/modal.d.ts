declare module Ekyna {
    export interface Modal {
        new():Modal;
        load(settings:JQueryAjaxSettings): JQueryXHR
        getContentType(jqXHR:JQueryXHR): string
        handleResponse(data:any, textStatus:string, jqXHR:JQueryXHR): Modal
        close():Modal
        getDialog(): BootstrapDialog
    }

    export interface ModalResponseEvent extends JQueryEventObject {
        modal:Modal
        contentType:string
        content:any
        jqXHR: JQueryXHR
    }
}

declare let Modal:Ekyna.Modal;

declare module 'ekyna-modal' {
    export = Modal;
}
