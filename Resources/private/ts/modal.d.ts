declare module Ekyna {
    export interface Modal {
        new():Modal;
        load(settings:JQueryAjaxSettings): JQueryXHR
        handleResponse(xmlData:string): Modal
        close():Modal
    }

    export interface ModalResponseEvent extends JQueryEventObject {
        modal:Modal
        contentType:string
        content:any
    }
}

declare var Modal:Ekyna.Modal;

declare module "ekyna-modal" {
    export = Modal;
}
