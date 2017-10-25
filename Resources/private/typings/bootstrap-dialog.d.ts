interface BootstrapDialogButtonOptions {
    id?: string
    icon?: string
    label?: string
    title?: string
    cssClass?: string
    data?: object
    hotKey?: string
    autospin?: boolean
    enabled?: boolean
    action?: Function
}

interface BootstrapDialog {
    /**
     * Opens the dialog.
     * @returns {BootstrapDialog}
     */
    open():BootstrapDialog

    /**
     * Closes the dialog.
     * @returns {BootstrapDialog}
     */
    close():BootstrapDialog

    /**
     * Return the raw modal, equivalent to $('<div class='modal fade'...></div>')
     * @returns {JQuery}
     */
    getModal():JQuery

    /**
     * Return the raw modal dialog.
     * @returns {JQuery}
     */
    getModalDialog():JQuery

    /**
     * Return the raw modal content.
     * @returns {JQuery}
     */
    getModalContent():JQuery

    /**
     * Return the raw modal header.
     * @returns {JQuery}
     */
    getModalHeader():JQuery

    /**
     * Return the raw modal body.
     * @returns {JQuery}
     */
    getModalBody():JQuery

    /**
     * Return the raw modal footer.
     * @returns {JQuery}
     */
    getModalFooter():JQuery

    /**
     * Get data entry according to the given key, returns null if no data entry found.
     * @param {string} key
     * @returns {any}
     */
    getData(key:string):any

    /**
     * Bind data entry to dialog instance, value can be any types that javascript supports.
     * @param {string} key
     * @param value
     * @returns {BootstrapDialog}
     */
    setData(key:string, value:any):BootstrapDialog

    /**
     * Sets the dialog's footer buttons.
     * @param {Array<BootstrapDialogButtonOptions>} buttons
     * @returns {BootstrapDialog}
     */
    setButtons(buttons: Array<BootstrapDialogButtonOptions>):BootstrapDialog

    /**
     * Returns the configured buttons.
     * @returns {Array<BootstrapDialogButtonOptions>}
     */
    getButtons():Array<BootstrapDialogButtonOptions>

    /**
     * Disable all buttons in dialog footer when it's false, enable all when it's true.
     * @param {boolean} enable
     * @returns {BootstrapDialog}
     */
    enableButtons(enable: boolean):BootstrapDialog

    /**
     * When set to true (default), dialog can be closed by clicking close icon in dialog header, or by clicking outside the dialog, or, ESC key is pressed.
     * @param {boolean} closable
     * @returns {BootstrapDialog}
     */
    setClosable(closable: boolean):BootstrapDialog

    /**
     * Calling dialog.open() will automatically get this method called first, but if you want to do something on your dialog before it's shown, you can manually call dialog.realize() before calling dialog.open().
     * @returns {BootstrapDialog}
     */
    realize():BootstrapDialog
}

declare let BSD:BootstrapDialog;

declare module "bootstrap/dialog" {
    export = BSD;
}
